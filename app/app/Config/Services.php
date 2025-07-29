<?php

namespace Config;

use App\Models\CoasterRepository;
use App\Models\WagonRepository;
use App\Services\CoasterService;
use CodeIgniter\Config\BaseService;
use Redis;

/**
 * Services Configuration file.
 *
 * Services are simply other classes/libraries that the system uses
 * to do its job. This is used by CodeIgniter to allow the core of the
 * framework to be swapped out easily without affecting the usage within
 * the rest of your application.
 *
 * This file holds any application-specific services, or service overrides
 * that you might need. An example has been included with the general
 * method format you should use for your service methods. For more examples,
 * see the core Services file at system/Config/Services.php.
 */
class Services extends BaseService
{
    public static function redis($getShared = true): object
    {
        if ($getShared) {
            return static::getSharedInstance('redis');
        }
        if (!extension_loaded('redis')) {
            throw new \RuntimeException('Redis extension is not loaded.');
        }

        if (!getenv('REDIS_HOST') || !getenv('REDIS_PORT')) {
            throw new \RuntimeException('Redis host and port must be set in environment variables.');
        }
        $redis = new Redis();
        $redis->connect(getenv('REDIS_HOST'), getenv('REDIS_PORT'));

        return $redis;
    }

    public static function coasterRepository($getShared = true): object
    {
        if ($getShared) {
            return static::getSharedInstance('coasterRepository');
        }

        return new CoasterRepository(self::redis());
    }

    public static function wagonRepository($getShared = true): object
    {
        if ($getShared) {
            return static::getSharedInstance('wagonRepository');
        }

        return new WagonRepository(self::redis());
    }

    public static function coasterService($getShared = true): object
    {
        if ($getShared) {
            return static::getSharedInstance('coasterService');
        }

        return new CoasterService(self::coasterRepository());
    }
}
