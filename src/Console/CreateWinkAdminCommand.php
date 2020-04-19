<?php

namespace Wink\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Wink\WinkAuthor;

class CreateWinkAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wink:admin {name?} {email?} {password?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create or Update the Wink Authors';
    
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = ! $this->argument('name') ? 'Regina Phalange' : $this->argument('name');
        $email = ! $this->argument('email') ? 'admin@mail.com' : $this->argument('email');
        $password =  ! $this->argument('password') ? Str::random() : $this->argument('password');

        WinkAuthor::updateOrCreate(['email' => $email ], [
                'id' => (string) Str::uuid(),
                'name' => $name,
                'slug' => Str::slug(strtolower($name)),
                'bio' => 'This is me.',
                'email' => $email,
                'password' => Hash::make($password)]
        );

        $this->line('Wink is ready for use. Enjoy!');
        $this->line('You may log in using <info>'.$email.'</info> and password: <info>'.$password.'</info>');
    }
}
