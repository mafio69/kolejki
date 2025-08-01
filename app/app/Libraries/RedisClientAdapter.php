<?php

namespace App\Libraries;

use Clue\React\Redis\RedisClient;
use React\Promise\PromiseInterface;

class RedisClientAdapter implements RedisClientInterface
{
    private RedisClient $client;

    public function __construct(RedisClient $client)
    {
        $this->client = $client;
    }

    public function ping(): PromiseInterface
    {
        return $this->client->ping();
    }

    public function keys(string $pattern): PromiseInterface
    {
        return $this->client->keys($pattern);
    }

    public function hgetall(string $key): PromiseInterface
    {
        return $this->client->hgetall($key);
    }

    public function smembers(string $key): PromiseInterface
    {
        return $this->client->smembers($key);
    }

    public function on(string $event, callable $listener)
    {
        $this->client->on($event, $listener);
    }
}