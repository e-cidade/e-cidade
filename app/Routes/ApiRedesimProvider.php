<?php

namespace App\Routes;

use App\Http\Controllers\Api\RedesimController;
use Silex\Application;
use Silex\ControllerProviderInterface;

class ApiRedesimProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        $controllers->post('/companies', function () use ($app) {
            return (new RedesimController($app["request"]))->index();
        });

        $controllers->post('/companiesReport', function () use ($app) {
            return (new RedesimController($app["request"]))->companiesReport();
        });

        return $controllers;
    }
}
