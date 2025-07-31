<?php

namespace App\Services;

use Exception;

class CoasterStatusService
{
    public function __construct(
        private readonly CoasterService $coasterService,
        private readonly PersonnelService $personnelService
    ) {
    }

    /**
     * @throws Exception
     */
    public function getCoasterStatus(string $coasterId): array
    {
        $coasterDetails = $this->coasterService->getCoasterDetails($coasterId);

        if (empty($coasterDetails)) {
            throw new Exception('Kolejka o podanym ID nie istnieje.', 404);
        }

        $requiredPersonnel = $this->personnelService->calculateRequiredPersonnel(count($coasterDetails['wagons'] ?? []));
        $personnelStatus = $this->personnelService->checkPersonnel($requiredPersonnel, $coasterDetails['liczba_personelu']);

        $coasterDetails['personnel_status'] = $personnelStatus;

        return $coasterDetails;
    }
}
