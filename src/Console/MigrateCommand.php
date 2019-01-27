<?php

namespace Wink\Console;

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
        $authorModel = config('wink.models.author');

        $shouldCreateNewAuthor =
            ! Schema::connection(config('wink.database_connection'))->hasTable('wink_authors') ||
            ! $authorModel::count();

        $this->call('migrate', [
            '--database' => config('wink.database_connection'),
            '--path' => 'vendor/writingink/wink/src/Migrations',
        ]);

        if ($shouldCreateNewAuthor) {
            $authorModel::create([
                'id' => (string) Str::uuid(),
                'name' => 'Regina Phalange',
                'slug' => 'regina-phalange',
                'bio' => 'This is me.',
                'email' => 'admin@mail.com',
                'password' => Hash::make($password = str_random()),
            ]);

            $this->line('');
            $this->line('');
            $this->line('Wink is ready for use. Enjoy!');
            $this->line('You may log in using <info>admin@mail.com</info> and password: <info>'.$password.'</info>');
        }
    }
}
