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
    protected $description = 'Create a new module structure';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = ucfirst($this->argument('name'));
        $path = base_path('modules/' . $name);

        if (File::exists($path)) {
            $this->error('Module ' . $name . ' already exists!');
            return 0;
        }

        // 1. Create Directories
        $directories = [
            $path . '/config',
            $path . '/helpers',
            $path . '/migrations',
            $path . '/resources/lang/en',
            $path . '/resources/views',
            $path . '/routes',
            $path . '/src/Commands',
            $path . '/src/http/Controllers',
            $path . '/src/http/Models',
        ];

        foreach ($directories as $directory) {
            File::makeDirectory($directory, 0755, true);
        }

        // 2. Create Boilerplate Files

        // Config
        File::put($path . '/config/config.php', "<?php\n\nreturn [\n    'name' => '{$name}'\n];\n");

        // Routes
        $routeContent = "<?php\n\nuse Illuminate\Support\Facades\Route;\n\nRoute::group(['namespace' => 'Modules\\{$name}\\src\\http\\Controllers'], function () {\n    Route::prefix('" . strtolower($name) . "')->group(function () {\n        Route::get('/', '" . $name . "Controller@index');\n    });\n});\n";
        File::put($path . '/routes/routes.php', $routeContent);

        // Controller
        $controllerContent = "<?php\n\nnamespace Modules\\{$name}\\src\\http\\Controllers;\n\nuse App\\Http\\Controllers\\Controller;\nuse Illuminate\\Http\\Request;\n\nclass {$name}Controller extends Controller\n{\n    public function index()\n    {\n        return view('" . strtolower($name) . "::index');\n    }\n}\n";
        File::put($path . '/src/http/Controllers/' . $name . 'Controller.php', $controllerContent);

        // Model
        $modelContent = "<?php\n\nnamespace Modules\\{$name}\\src\\http\\Models;\n\nuse Illuminate\\Database\\Eloquent\\Model;\n\nclass {$name} extends Model\n{\n    protected \$table = '" . strtolower($name) . "s';\n    protected \$fillable = [];\n}\n";
        File::put($path . '/src/http/Models/' . $name . '.php', $modelContent);

        // View
        File::put($path . '/resources/views/index.blade.php', "<h1>Welcome to Module {$name}</h1>\n");

        // Lang
        File::put($path . '/resources/lang/en/custom.php', "<?php\n\nreturn [\n    'welcome' => 'Welcome to module {$name}',\n];\n");

        $this->info("Module {$name} created successfully!");
        return 0;
    }
}
