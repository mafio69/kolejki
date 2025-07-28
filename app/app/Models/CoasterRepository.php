<?php

namespace App\Models;

use Redis;

class CoasterRepository
{
    public function __construct(private readonly Redis $redis)
    {
    }

    /**
     * Tworzy nową kolejkę górską w Redis.
     *
     * @param array $data Dane kolejki.
     * @return string ID nowo utworzonej kolejki.
     * @throws \RedisException
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

    /**
     * Sprawdza, czy kolejka o podanym ID istnieje.
     *
     * @param string $coasterId
     * @return bool
     * @throws \RedisException
     */
    public function exists(string $coasterId): bool
    {
        return (bool)$this->redis->exists($coasterId);
    }

    /**
     * Pobiera dane kolejki o podanym ID.
     *
     * @param string $coasterId
     * @return array|null
     * @throws \RedisException
     */
    public function find(string $coasterId): ?array
    {
        $data = $this->redis->hGetAll($coasterId);

        return $data ?: null;
    }
}
