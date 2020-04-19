<?php

namespace Wink\Console;

use Wink\WinkAuthor;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class MigrateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wink:migrate {email?} {password?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run database migrations for Wink';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $shouldCreateNewAuthor =
            ! Schema::connection(config('wink.database_connection'))->hasTable('wink_authors') ||
            ! WinkAuthor::count();

        $this->call('migrate', [
            '--database' => config('wink.database_connection'),
            '--path' => 'vendor/writingink/wink/src/Migrations',
        ]);

        if ($shouldCreateNewAuthor) {

            $email = !$this->argument('email') ? 'admin@mail.com' : $this->argument('email');
            $password =  !$this->argument('password') ? Str::random() : $this->argument('password');

            $this->call('wink:admin', [ 'name' => 'Regina Phalange', 'email' => $email, 'password' => $password ]);
        }
    }
}
