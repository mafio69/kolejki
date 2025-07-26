<?php

namespace App\Models;

use Redis;

class WagonRepository
{
    private Redis $redis;

    public function __construct()
    {
        $this->redis = new Redis();
        $this->redis->connect(getenv('REDIS_HOST'), getenv('REDIS_PORT'));
    }

    /**
     * Dodaje nowy wagon do kolejki.
     *
     * @param string $coasterId ID kolejki.
     * @param array $data Dane wagonu.
     * @return string ID nowo utworzonego wagonu.
     */
    public function add(string $coasterId, array $data): string
    {
        $wagonId = uniqid('wagon:');

        $this->redis->hMSet($wagonId, [
            'coaster_id' => $coasterId,
            'ilosc_miejsc' => $data['ilosc_miejsc'],
            'predkosc_wagonu' => $data['predkosc_wagonu'],
        ]);

        // Dodajemy ID wagonu do listy wagonów przypisanych do kolejki
        $this->redis->sAdd($coasterId . ':wagons', $wagonId);

        return $wagonId;
    }

    /**
     * Usuwa wagon.
     *
     * @param string $coasterId ID kolejki.
     * @param string $wagonId ID wagonu.
     * @return bool
     */
    public function remove(string $coasterId, string $wagonId): bool
    {
        // Usuwamy wagon z listy wagonów przypisanych do kolejki
        $this->redis->sRem($coasterId . ':wagons', $wagonId);

        // Usuwamy dane wagonu
        return (bool)$this->redis->del($wagonId);
    }
}
