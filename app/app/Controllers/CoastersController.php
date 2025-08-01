<?php

namespace App\Controllers;

use App\Models\CoasterRepository;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class CoastersController extends BaseController
{
    private CoasterRepository $coasterRepository;

    public function __construct()
    {
        $this->coasterRepository = service('coasterRepository');
    }

    /**
     * Tworzy nową kolejkę górską.
     *
     * @return ResponseInterface
     * @throws \RedisException
     */
    public function create(): ResponseInterface
    {
        $data = $this->request->getJSON(true);
        
        $validation = Services::validation();
        if (!$validation->run($data, 'coaster')) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $validation->getErrors(),
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }
        
        $coasterId = $this->coasterRepository->create($data);
        
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Kolejka została pomyślnie utworzona.',
            'coaster_id' => $coasterId,
        ])->setStatusCode(ResponseInterface::HTTP_CREATED);
    }
}