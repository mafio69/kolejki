<?php

namespace App\Libraries;

use React\Promise\PromiseInterface;

interface RedisClientInterface
{
    public function ping(): PromiseInterface;
    public function keys(string $pattern): PromiseInterface;
    public function hgetall(string $key): PromiseInterface;
    public function smembers(string $key): PromiseInterface;
    public function on(string $event, callable $listener);
}