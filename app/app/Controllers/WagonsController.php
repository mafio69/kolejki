<?php

namespace App\Controllers;

use App\Services\WagonService;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;

class WagonsController extends BaseController
{
    private WagonService $wagonService;

    public function __construct()
    {
        $this->wagonService = service('wagonService');
    }

    public function add(string $coasterId): ResponseInterface
    {
        try {
            $data = $this->request->getJSON(true);
            $result = $this->wagonService->addWagon($coasterId, $data);

            return $this->response->setJSON($result)->setStatusCode(ResponseInterface::HTTP_CREATED);
        } catch (Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage(),
            ])->setStatusCode($e->getCode());
        }
    }

    public function remove(string $coasterId, string $wagonId): ResponseInterface
    {
        try {
            $result = $this->wagonService->removeWagon($coasterId, $wagonId);

            return $this->response->setJSON($result)->setStatusCode(ResponseInterface::HTTP_OK);
        } catch (Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage(),
            ])->setStatusCode($e->getCode());
        }
    }
}
