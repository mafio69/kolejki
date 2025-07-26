<?php

namespace App\Models;

use Redis;

class CoasterRepository
{
    private Redis $redis;

    public function __construct()
    {
        $this->redis = new Redis();
        $this->redis->connect(getenv('REDIS_HOST'), getenv('REDIS_PORT'));
    }

    /**
     * Tworzy nową kolejkę górską w Redis.
     *
     * @param array $data Dane kolejki.
     * @return string ID nowo utworzonej kolejki.
     */
    public function create(array $data): string
    {
        $coasterId = uniqid('coaster:');

        $this->redis->hMSet($coasterId, [
            'liczba_personelu' => $data['liczba_personelu'],
            'liczba_klientow' => $data['liczba_klientow'],
            'dl_trasy' => $data['dl_trasy'],
            'godziny_od' => $data['godziny_od'],
            'godziny_do' => $data['godziny_do'],
        ]);

        return $coasterId;
    }
}
