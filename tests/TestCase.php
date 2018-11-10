<?php

namespace Wink\Tests;

use Wink\WinkAuthor;
use Wink\WinkServiceProvider;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TestCase extends \Orchestra\Testbench\TestCase
{
    use DatabaseTransactions;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->withFactories(__DIR__.'/Factories');
        
        $this->artisan('migrate');
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [WinkServiceProvider::class];
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'wink');
        $app['config']->set('database.connections.wink', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    /**
     * @param $user
     * @return $this
     */
    protected function signIn($user = null)
    {
        if(! $user) {
            $user = factory(WinkAuthor::class)->create();
        }
        
        $this->actingAs($user, 'wink');
        
        return $user;
    }
}
