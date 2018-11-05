<?php

namespace Wink\Console;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wink:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install all of the Wink resources';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->comment('Publishing Wink Assets...');
        $this->callSilent('vendor:publish', ['--tag' => 'wink-assets']);

        $this->comment('Publishing Wink Configuration...');
        $this->callSilent('vendor:publish', ['--tag' => 'wink-config']);

        $this->info('Wink was installed successfully.');
    }
}
