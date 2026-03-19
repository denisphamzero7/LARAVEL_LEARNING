<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File; // 🔴 SỬA LỖI: Thiếu khai báo Facade File
use Illuminate\Support\Str;          // ✨ THÊM MỚI: Dùng để chuẩn hóa tên Class (vd: user -> User)

class CreateModule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:module {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tạo module tùy chỉnh';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Lấy tên module user nhập vào (ví dụ như auth, user, product)
        $name = $this->argument('name');
        $moduleName = strtolower($name);
        
        // 🔴 SỬA LỖI: Thống nhất dùng $modulePath (chữ P viết hoa)
        $modulePath = base_path("modules/{$moduleName}");
        
        // Kiểm tra xem module tồn tại chưa
        if (File::exists($modulePath)) {
            $this->error("Module '{$moduleName}' đã tồn tại");
            return Command::FAILURE;
        }
        
        // Khai báo các thư mục cần tạo
        $directories = [
            'config',
            'Actions',
            'Enums',
            'console',
            'Models',
            'Repositories',
            'Routes',
            'migrations', // 🔴 SỬA LỖI: Đổi thành số nhiều 'migrations' cho chuẩn Laravel
            'provider',
            'Services',   // 🔴 SỬA LỖI: Đổi 'Service' thành 'Services' cho chuẩn
            'Events',
            'Listeners',  // 🔴 SỬA LỖI: Sửa sai chính tả (Listerners -> Listeners)
            'Exceptions',
            'Observers',
            'Policies',
            'src/Commands',
            'src/Http/Controllers',
            'src/Http/middlewares',
            'src/Http/requests',
            'src/models',
        ];
        
        // Tạo thư mục
        foreach ($directories as $dir) {
            // 🔴 SỬA LỖI: Dùng đúng biến $modulePath (trước đó bạn viết $modulePath nhưng khai báo là $modulepath)
            File::makeDirectory("{$modulePath}/{$dir}", 0755, true, true);
        }

        // ✨ THÊM MỚI: Hoàn thiện phần tạo các file cơ bản đang bị bỏ dở
        
        // Chuẩn hóa tên Class (VD: nhập 'user_profile' -> 'UserProfile')
        $className = Str::studly($name);

        // 1. Tạo File config/config.php
        File::put("{$modulePath}/config/config.php", "<?php\n\nreturn [\n    'name' => '{$className}'\n];\n");

        // 2. Tạo File Routes (api.php)
        // Lưu ý: Ở mảng $directories trên bạn viết là 'Routes' (R hoa), nên đường dẫn ở đây cũng phải là Routes/api.php
        File::put("{$modulePath}/Routes/api.php", "<?php\n\nuse Illuminate\\Support\\Facades\\Route;\n\nRoute::prefix('{$moduleName}')->group(function() {\n    // Khai báo API routes tại đây\n});\n");

        // 3. Tạo File composer.json (Rất quan trọng để load namespace src/)
        $composerJson = <<<JSON
{
    "name": "modules/{$moduleName}",
    "autoload": {
        "psr-4": {
            "Modules\\\\{$className}\\\\": "src/"
        }
    }
}
JSON;
        File::put("{$modulePath}/composer.json", $composerJson);

        // 4. Tạo File ModuleServiceProvider.php
        $providerContent = <<<PHP
<?php

namespace Modules\\{$className};

use Illuminate\\Support\\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    public function register()
    {
        \$this->mergeConfigFrom(__DIR__.'/config/config.php', '{$moduleName}');
    }

    public function boot()
    {
        \$this->loadRoutesFrom(__DIR__.'/Routes/api.php');
        // \$this->loadMigrationsFrom(__DIR__.'/migrations');
    }
}
PHP;
        File::put("{$modulePath}/ModuleServiceProvider.php", $providerContent);

        // Thông báo thành công
        $this->info("Module '{$className}' đã được tạo thành công tại: modules/{$moduleName}");
        
        return Command::SUCCESS;
    }
}