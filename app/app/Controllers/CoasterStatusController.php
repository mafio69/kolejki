<?php

namespace App\Controllers;

use App\Services\CoasterStatusService;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;

class CoasterStatusController extends BaseController
{
    private CoasterStatusService $coasterStatusService;

    public function __construct()
    {
        $this->coasterStatusService = service('coasterStatusService');
    }

    public function show(string $coasterId): ResponseInterface
    {
        try {
            $coasterDetails = $this->coasterStatusService->getCoasterStatus($coasterId);

            return $this->response->setJSON([
                'status' => 'success',
                'data' => $coasterDetails,
            ])->setStatusCode(ResponseInterface::HTTP_OK);
        } catch (Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage(),
            ])->setStatusCode($e->getCode());
        }
    }
}
