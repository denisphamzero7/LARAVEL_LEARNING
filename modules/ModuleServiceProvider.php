<?php
namespace Modules;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;
use Modules\user\src\http\Middlewares\DemoMiddleware;

class ModuleServiceProvider extends ServiceProvider{
    private $middlewares = [
        'demo' => DemoMiddleware::class
    ];

    public function boot()
    {
        $module = $this->getDirectoriesModules();
         if(!empty( $module)) {
             foreach ($module as $directory) {
                $this->registerModule($directory);
             }
         }
    }
   
    public function register()
    {
         $modules = $this->getDirectoriesModules();
         if(!empty( $modules)) {
             foreach ($modules as $module) {
                $this->registerConfig($module);
             }
         }
         //middleware
         $this->registerMiddleware();
    }   

    //get modules
    private function getDirectoriesModules(){
        return array_map('basename', File::directories(__DIR__));
    }

    private function registerConfig($module){
          $configPath= __DIR__.'/'. $module.'/config';
          if(File::exists($configPath)) {
              $configFiles=array_map('basename', File::files($configPath));
              foreach($configFiles as $config){
                 $fileName = basename($config, '.php');
                 $alias = $module . '.' . $fileName; // Tránh trùng lặp config giữa các module
                 $this->mergeConfigFrom($configPath.'/'.$config, $alias);
              }
          }
    }

    private function registerMiddleware(){
        if(!empty($this->middlewares)){
            foreach($this->middlewares as $key=>$middleware){
                $this->app['router']->aliasMiddleware($key, $middleware);
            }
        }
    }

    //register module
    private function registerModule($module){
        $modulePath = __DIR__."/{$module}";
        // Khai báo routes
        if(File::exists($modulePath.'/routes/routes.php')) {
            $this->loadRoutesFrom($modulePath.'/routes/routes.php');
        }
        // khai báo migrations
        if(File::exists($modulePath.'/database/migrations')) {
            $this->loadMigrationsFrom($modulePath.'/database/migrations');
        }
        // Khai báo langs
        if(File::exists($modulePath.'/resources/lang')) {
            $this->loadTranslationsFrom($modulePath.'/resources/lang', $module);
        }
        // Khai báo views
        if(File::exists($modulePath.'/resources/views')) {
            $this->loadViewsFrom($modulePath.'/resources/views', $module);
            $this->loadJsonTranslationsFrom($modulePath.'/resources/lang');
        }
        // Khai báo helper
        if (File::exists($modulePath . '/helpers')) {
            $helperlist = File::allFiles($modulePath . '/helpers');
            if(!empty($helperlist)) {
                foreach ($helperlist as $helper) {
                    require_once $helper->getPathname();
                }
            }
        }

        // Tự động đăng ký Commands từ thư mục src/Commands
        $commandPath = $modulePath . '/src/Commands';
        if (File::exists($commandPath)) {
            $commandFiles = File::allFiles($commandPath);
            foreach ($commandFiles as $file) {
                $commandClass = 'Modules\\' . $module . '\\src\\Commands\\' . $file->getFilenameWithoutExtension();
                if (class_exists($commandClass)) {
                    $this->commands([$commandClass]);
                }
            }
        }
    }
}
