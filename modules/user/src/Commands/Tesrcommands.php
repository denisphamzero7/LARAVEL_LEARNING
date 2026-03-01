<?php

namespace Modules\user\src\Commands;

use Illuminate\Console\Command;

class Tesrcommands extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'codedime';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
       $this->info('success');
       return 0;
    }
}
