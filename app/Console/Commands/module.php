<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class module extends Command
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
    protected $description = 'Tạo cấu trúc thư mục và file cho Module mới';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');
        // Chỉ gọi base_path() 1 lần duy nhất để lấy đường dẫn gốc của module
        $modulePath = base_path('modules/' . $name);

        // 1. Kiểm tra module đã tồn tại chưa
        if (File::exists($modulePath)) {
            $this->error('Module này đã tồn tại!');
            return Command::FAILURE;
        }

        // 2. Tạo thư mục gốc cho module
        File::makeDirectory($modulePath, 0755, true);

        // 3. Tạo các THƯ MỤC con
        $folders = [
            'configs', 'routes', 'migrations', 'views', 
            'helpers', 'commands', 'resources', 'src'
        ];

        foreach ($folders as $folder) {
            File::makeDirectory($modulePath . '/' . $folder, 0755, true);
        }
        $this->info('Đã tạo xong các thư mục con!');

        // 4. Tạo các FILE (Dùng File::put thay vì File::makeDirectory)
        // Cấu trúc: 'đường_dẫn_file' => 'Nội_dung_mặc_định_bên_trong'
        $files = [
            'configs/config.php' => "<?php\n\nreturn [];\n",
            'routes/routes.php' => "<?php\n\nuse Illuminate\Support\Facades\Route;\n\n",
            'migrations/migrations.php' => "<?php\n\n// Migration file\n",
            'views/views.php' => "\n",
            'helpers/helpers.php' => "<?php\n\n// Helper functions\n",
            'commands/commands.php' => "<?php\n\n// Command file\n",
            'resources/resources.php' => "<?php\n\n// Resource file\n",
            'src/src.php' => "<?php\n\n// Source file\n",
            'composer.json' => "{\n    \"name\": \"module/" . strtolower($name) . "\"\n}\n",
            'ModuleServiceProvider.php' => "<?php\n\nnamespace Modules\\{$name};\n\nuse Illuminate\\Support\\ServiceProvider;\n\nclass ModuleServiceProvider extends ServiceProvider\n{\n    // \n}\n",
        ];

        foreach ($files as $filePath => $content) {
            File::put($modulePath . '/' . $filePath, $content);
        }
        $this->info('Đã tạo xong các file cơ bản!');

        $this->info("Tạo module [{$name}] thành công rực rỡ!");
        return Command::SUCCESS;
    }
}