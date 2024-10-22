<?php

namespace App\Providers\Database;

use Illuminate\Database\Connection;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use PDO;

class DatabaseServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        Connection::resolverFor('pgsql', function ($connection, $database, $prefix, $config) {
            $connection = new PostgresConnection($connection, $database, $prefix, $config);

            $connection->getPdo()->exec('select fc_startsession();');

            return $connection;
        });

        Event::listen('Illuminate\Database\Events\QueryExecuted', function ($query) {
            $query->connection->setFetchMode(PDO::FETCH_OBJ);
        });
    }
}
