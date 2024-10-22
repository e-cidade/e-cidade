<?php

namespace App\Support;

class EventManager
{
    protected static $listeners = [];

    public static function listen($event, $listener)
    {
        self::$listeners[$event][] = $listener;
    }

    public static function dispatch($event, $payload = [])
    {
        $responses = [];
        if (isset(self::$listeners[get_class($event)])) {
            foreach (self::$listeners[get_class($event)] as $listener) {
                $response = (new $listener())->handle($event, $payload);
                $responses[] = ['listener' => $listener, 'response' => $response];
            }
        }
        return $responses;
    }
}
