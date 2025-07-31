<?php

namespace App\Services;

class PerformanceService
{
    private const BREAK_TIME_SECONDS = 300; // 5 minut przerwy

    /**
     * Oblicza dzienną przepustowość kolejki.
     *
     * @param array $coasterData Dane kolejki (godziny_od, godziny_do, dl_trasy)
     * @param array $wagons Tablica z danymi wagonów (ilosc_miejsc, predkosc_wagonu)
     * @return float Całkowita dzienna przepustowość
     */
    public function calculateDailyCapacity(array $coasterData, array $wagons): float
    {
        $operatingTime = strtotime($coasterData['godziny_do']) - strtotime($coasterData['godziny_od']);
        if ($operatingTime <= 0) {
            return 0;
        }

        $totalCapacity = 0;

        foreach ($wagons as $wagon) {
            $rideTime = $coasterData['dl_trasy'] / $wagon['predkosc_wagonu'];
            $cycleTime = $rideTime + self::BREAK_TIME_SECONDS;

            if ($cycleTime > 0) {
                $ridesPerWagon = floor($operatingTime / $cycleTime);
                $totalCapacity += $ridesPerWagon * $wagon['ilosc_miejsc'];
            }
        }

        return $totalCapacity;
    }

    /**
     * Sprawdza wydajność kolejki w stosunku do planowanej liczby klientów.
     *
     * @param float $dailyCapacity Dzienna przepustowość
     * @param int $plannedClients Planowana liczba klientów
     * @return array Informacje o wydajności
     */
    public function checkPerformance(float $dailyCapacity, int $plannedClients): array
    {
        if ($dailyCapacity < $plannedClients) {
            return [
                'status' => 'error',
                'message' => 'Kolejka nie jest w stanie obsłużyć wszystkich klientów.',
                'missing_capacity' => $plannedClients - $dailyCapacity
            ];
        } elseif ($dailyCapacity > ($plannedClients * 2)) {
            return [
                'status' => 'warning',
                'message' => 'Kolejka ma znacznie większą przepustowość niż wymagana.',
                'surplus_capacity' => $dailyCapacity - $plannedClients
            ];
        } else {
            return [
                'status' => 'ok',
                'message' => 'Wydajność kolejki jest wystarczająca.'
            ];
        }
    }
}
