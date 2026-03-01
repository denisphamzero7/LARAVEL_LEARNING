<?php
namespace Modules;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;

class ModuleServiceProvider extends ServiceProvider{
    public function boot()
    {
         $dir = array_map('basename', File::directories(__DIR__));
         dd($dir);
         if(!empty($dir)) {
             foreach ($dir as $module) {
                 $routePath = __DIR__ . '/' . $module . '/routes.php';
                 if (file_exists($routePath)) {
                     include_once $routePath;
                 }
             }
         }

    }
 public function register()
    {

    }
}
