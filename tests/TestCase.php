<?php
namespace AlexHnydiuk\Laracent\Test;
use Orchestra\Testbench\TestCase as Orchestra;
use AlexHnydiuk\Laracent\LaracentServiceProvider;
class TestCase extends Orchestra
{
    /**
     * @var AlexHnydiuk\Laracent\Laracent
     */
    protected $centrifugo;
    public function setUp()
    {
        parent::setUp();
        $this->centrifugo = $this->app->make('centrifugo');
    }
    protected function getPackageProviders($app)
    {
        return [
            LaracentServiceProvider::class,
        ];
    }
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('broadcasting.default', 'centrifugo');
        $app['config']->set('broadcasting.connections.centrifugo', [
            'driver' => 'centrifugo',
            'secret' => 'f5dd4b9a-98aa-42c2-9a89-be4345d49580',
            'apikey' => '4c977fba-5d05-47b5-8e8d-404b29bd824e',
            // 'secret' => 'd7bcb1a8-a535-4115-b0cc-21ee53318842',
            // 'apikey' => '7519cb23-f48c-4bfd-afbe-d99424bed6be',
            'url' => 'http://localhost:8000',
        ]);
    }
}