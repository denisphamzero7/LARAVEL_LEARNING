<?php
namespace Modules;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;
use Modules\user\src\http\Middlewares\DemoMiddleware;
class ModuleServiceProvider extends ServiceProvider{
    public function boot()
    {
         $directories = array_map('basename', File::directories(__DIR__));

         if(!empty( $directories)) {
             foreach ($directories as $directory) {
                $this->registerModule($directory);
             }
         }

    }
    public function registerModule($module){
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
    }
 public function register()
    {          // dăng kí config
               $directories = array_map('basename', File::directories(__DIR__));

         if(!empty( $directories)) {
            //configs
             foreach ($directories as $directory) {
                 $configPath= __DIR__.'/'. $directory.'/config';
                 if(File::exists($configPath)) {
                     $configFiles=array_map('basename', File::files($configPath));
                     foreach($configFiles as $config){
                        $alias = basename($config, '.php');
                        $this->mergeConfigFrom($configPath.'/'.$config, $alias);
                     }
                 }
             }
         }
         //middleware
         $middlewarePath = [
            'demo'=>DemoMiddleware::class
         ];

         if(!empty($middlewarePath)){
            foreach($middlewarePath as $key=>$middleware){
                $this->app['router']->aliasMiddleware($key, $middleware);
            }
         }
    }
}
