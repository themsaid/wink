<?php

namespace Wink\Console;

use Illuminate\Console\Command;
use Wink\WinkAuthor;
use Illuminate\Support\Str;

class AuthorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wink:author {name} {email} {password?} {--bio=} {--avatar=} {--slug=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Wink Author';

    /**
     * Check if the generated Author is valid
     *
     * @var boolean
     */
    public function validate($author) {
        $email_unique = WinkAuthor::where('email', $author['email'])->count() == 0;
        $slug_unique = WinkAuthor::where('slug', $author['slug'])->count() == 0;

        if($email_unique && $slug_unique) {
            return true;
        } else {
            if(!$slug_unique) {
                $this->error('The slug "' . $author['slug'] .'" is already in use. Please choose another one.');
            }
            if(!$email_unique) {
                $this->error('The email "' . $author['email'] .'" is already in use. Please choose another one.');
            }
            return false;
        }
    }

    /**
     * Print the generated author to the console.
     *
     * @var void
     */
    public function showResult($author) {
        $headers = ['Property', 'Value'];

        $values = [];
        $values[] = [ 'ID', $author['id'] ];
        $values[] = [ 'Slug', $author['slug'] ];
        $values[] = [ 'Name', $author['name'] ];
        $values[] = [ 'Email', $author['email'] ];
        $values[] = [ 'Password', $this->unhashed_password ];
        $values[] = [ 'Bio', $author['bio'] ];
        $values[] = [ 'Avatar', $author['avatar'] ];

        $this->table($headers, $values);
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $author = [
            'id' => (string) Str::uuid(),
            'name' => $this->argument('name'),
            'email' => $this->argument('email'),
        ];

        // Add Slug
        $author['slug'] = $this->option('slug') != null ? $this->option('slug') : str_slug($this->argument('name'));

        // Add Bio
        $author['bio'] = $this->option('bio') != null ? $this->option('bio') : '';

        // Add Avatar
        $author['avatar'] = $this->option('avatar');

        // Add Password
        if($this->argument('password') == null) {
            $this->unhashed_password = str_random();
            $this->random_password_generated = true;
        } else {
            $this->unhashed_password = $this->argument('password');
        }
        $author['password'] = \Hash::make($this->unhashed_password);

        // Validate author. If it is invalid, don't save it.
        if($this->validate($author)) {            
            WinkAuthor::create($author);

            $this->showResult($author);

            if(isset($this->random_password_generated) && $this->random_password_generated == true) {
                $this->comment('No password was given. A random password was generated for your author.');
            }
            $this->info('Author created!');
        }
    }
}
