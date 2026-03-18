<?php
namespace Modules;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;

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
    public function register()
    {

    }
}
