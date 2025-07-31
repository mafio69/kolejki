<?php

namespace App\Services;

class PersonnelService
{
    /**
     * Oblicza wymagany personel dla kolejki i jej wagonów.
     *
     * @param int $wagonCount Liczba wagonów w kolejce.
     * @return int Całkowita liczba wymaganego personelu.
     */
    public function calculateRequiredPersonnel(int $wagonCount): int
    {
        // 1 pracownik na kolejkę + 2 na każdy wagon.
        return 1 + ($wagonCount * 2);
    }

    /**
     * Porównuje wymagany personel z dostępnym personelem.
     *
     * @param int $requiredPersonnel Wymagany personel.
     * @param int $availablePersonnel Dostępny personel.
     * @return array Informacje o brakach lub nadmiarze personelu.
     */
    public function checkPersonnel(int $requiredPersonnel, int $availablePersonnel): array
    {
        $difference = $availablePersonnel - $requiredPersonnel;

        if ($difference < 0) {
            return [
                'status' => 'error',
                'message' => 'Brakuje ' . abs($difference) . ' pracowników.',
                'missing' => abs($difference)
            ];
        } elseif ($difference > 0) {
            return [
                'status' => 'warning',
                'message' => 'Nadmiar ' . $difference . ' pracowników.',
                'surplus' => $difference
            ];
        } else {
            return [
                'status' => 'ok',
                'message' => 'Wystarczająca liczba personelu.'
            ];
        }
    }
}