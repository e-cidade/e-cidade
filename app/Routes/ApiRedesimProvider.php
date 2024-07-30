<?php

namespace App\Routes;

use App\Http\Controllers\Api\RedesimController;
use ECidade\V3\Extension\Registry;
use Exception;
use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;

class ApiRedesimProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        $controllers->post('/companies', function () use ($app) {
            return (new RedesimController($app["request"]))->index();
        })->before(function (Request $request) {

            $env = Registry::get('app.env');

            if (!$request->headers->has('redesim-token') || empty($env['API_REDESIM_TOKEN'])) {
                throw new Exception('Invalid credentials.');
            }

            $token = $request->headers->get('redesim-token');

            if ($token !== $env['API_REDESIM_TOKEN']) {
                throw new Exception('Invalid credentials.');
            }
        });

        $controllers->post('/companiesReport', function () use ($app) {
            return (new RedesimController($app["request"]))->companiesReport();
        });

        return $controllers;
    }
}
