<?php

namespace App\Services;

use App\Models\CoasterRepository;

class CoasterService
{
    public function __construct(private readonly CoasterRepository $coasterRepository)
    {
    }

    public function getCoasterDetails(string $coasterId): ?array
    {
        return $this->coasterRepository->find($coasterId);
    }

    /**
     * Oblicza dostępny personel na podstawie danych kolejki.
     *
     * @param array $coasterData Dane kolejki.
     * @return int Dostępny personel.
     */
    public function calculateAvailablePersonnel(array $coasterData): int
    {
        $godzinyOd = \DateTime::createFromFormat('H:i', $coasterData['godziny_od']);
        $godzinyDo = \DateTime::createFromFormat('H:i', $coasterData['godziny_do']);

        if (!$godzinyOd || !$godzinyDo) {
            // Obsługa błędu, jeśli format czasu jest nieprawidłowy
            // Na razie zwrócimy 0, ale w przyszłości można logować lub rzucać wyjątek
            return 0;
        }

        $interval = $godzinyOd->diff($godzinyDo);
        $hours = $interval->h + ($interval->i / 60);

        // Prosta logika: dostępny personel to liczba personelu * liczba godzin pracy
        // Można to rozbudować o bardziej złożone zasady
        return (int)($coasterData['liczba_personelu'] * $hours);
    }
}
