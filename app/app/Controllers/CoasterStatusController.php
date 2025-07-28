<?php

namespace App\Controllers;

use App\Services\CoasterService;
use CodeIgniter\HTTP\ResponseInterface;

class CoasterStatusController extends BaseController
{
    private CoasterService $coasterService;

    public function __construct()
    {
        $this->coasterService = service('coasterService');
    }

    /**
     * Zwraca status i szczegóły kolejki górskiej.
     *
     * @param string $coasterId ID kolejki.
     * @return ResponseInterface
     */
    public function show(string $coasterId): ResponseInterface
    {
        $coasterDetails = $this->coasterService->getCoasterDetails($coasterId);

        if (empty($coasterDetails)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Kolejka o podanym ID nie istnieje.',
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $coasterDetails,
        ])->setStatusCode(ResponseInterface::HTTP_OK);
    }
}
