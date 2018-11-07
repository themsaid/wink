<?php

namespace Wink\Tests;

use Wink\WinkServiceProvider;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TestCase extends \Orchestra\Testbench\TestCase
{
    use DatabaseTransactions;

    /**
     * The user the request is currently authenticated as.
     *
     * @var mixed
     */
    protected $authenticatedAs;

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
}
