<?php
namespace Modules;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;
use Modules\user\src\http\Middlewares\DemoMiddleware;

<<<<<<< HEAD
class ModuleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $directionary = array_map('basename', File::directories(__DIR__));

        if (!empty($directionary)) {
            foreach ($directionary as $module) {
                $this->registerModule($module);
            }
        }
    }

    public function registerModule($module)
    {
        $modulePath = __DIR__ . '/' . $module;

        // 1. Đăng ký Route (Thay vì include, dùng loadRoutesFrom để Laravel quản lý tốt hơn)
        if (file_exists($modulePath . '/routes/routes.php')) {
            $this->loadRoutesFrom($modulePath . '/routes/routes.php');
        }

        // 2. Đăng ký Migration (Dùng loadMigrationsFrom để chạy thẳng từ module mà không cần publish)
        if (file_exists($modulePath . '/migrations')) {
            $this->loadMigrationsFrom($modulePath . '/migrations');
        }

        // 3. Đăng ký View (Dùng loadViewsFrom để gọi view theo cấu trúc: view("TênModule::tên_view"))
        if (file_exists($modulePath . '/views')) {
            $this->loadViewsFrom($modulePath . '/views', $module);
        }

        // 4. (Tùy chọn) Đăng ký Translation/Lang
        if (file_exists($modulePath . '/lang')) {
            $this->loadTranslationsFrom($modulePath . '/lang', $module);
            $this->loadJsonTranslationsFrom($modulePath . '/lang', $module);
        }

    // LƯU Ý: Không nên publish Models và Controllers ra thư mục app. 
    // Mục đích của Module là đóng gói code bên trong thư mục modules/.
    // Bạn chỉ cần thiết lập autoload chuẩn PSR-4 cho thư mục modules/ trong composer.json là lớp sẽ tự chạy được.
    }
=======
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
   
>>>>>>> 1f1dfbf341ff76ab67d7fb4c93e5148d8176f7b9
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
        if(File::exists($modulePath.'/migrations')) {
            $this->loadMigrationsFrom($modulePath.'/migrations');
        }
        // Khai báo langs
        if(File::exists($modulePath.'/resources/lang')) {
            $this->loadTranslationsFrom($modulePath.'/resources/lang', strtolower($module));
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
