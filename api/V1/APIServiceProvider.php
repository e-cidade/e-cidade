<?php

namespace ECidade\Api\V1;

use App\Routes\ApiPixProvider;
use App\Routes\ApiRedesimProvider;
use ECidade\Api\V1\Providers\ProtocoloControllerProvider;
use ECidade\Api\V1\Providers\ConfiguracaoControllerProvider;
use Silex\Application;
use Silex\ServiceProviderInterface;

class APIServiceProvider implements ServiceProviderInterface
{

    public function register(Application $app)
    {
    }

    public function boot(Application $app)
    {
        $prefix = $app['ecidade_api.mount_prefix'];
        $app->mount($prefix . "/protocolo", new ProtocoloControllerProvider());
        $app->mount($prefix . "/configuracao", new ConfiguracaoControllerProvider());
        $app->mount($prefix . '/pix', new ApiPixProvider());
    }
}
