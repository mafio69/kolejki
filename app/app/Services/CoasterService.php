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
}
