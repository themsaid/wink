<?php

namespace Wink\Console;

use Wink\WinkAuthor;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class MigrateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wink:migrate';

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
            $this->comment(PHP_EOL.'<info>Admin user account</info>');
            $email      = $this->ask('Email');
            $password   = $this->secret('Password');
            $firstName  = $this->ask('First name');
            $lastName   = $this->ask('Last name');
            $fullName   = $firstName . '' . $lastName;

            WinkAuthor::create([
                'id'        => (string) Str::uuid(),
                'name'      => $fullName,
                'slug'      => str_slug($fullName),
                'bio'       => 'Hello there :) ',
                'email'     => $email,
                'password'  => bcrypt($password),
            ]);

            $this->line('');
            $this->line('');
            $this->line('Wink is ready for use. Enjoy!');
            $this->line('You may log in using <info>'. $email .'</info> and your password');
        }
    }
}
