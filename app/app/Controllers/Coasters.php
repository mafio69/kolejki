<?php

namespace App\Controllers;

use App\Models\CoasterRepository;
use CodeIgniter\HTTP\ResponseInterface;

class Coasters extends BaseController
{
    private CoasterRepository $coasterRepository;

    public function __construct()
    {
        $this->coasterRepository = new CoasterRepository();
    }

    /**
     * Tworzy nową kolejkę górską.
     *
     * @return ResponseInterface
     */
    public function create(): ResponseInterface
    {
        $data = $this->request->getJSON(true);

        // TODO: Dodać walidację danych

        $coasterId = $this->coasterRepository->create($data);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Kolejka została pomyślnie utworzona.',
            'coaster_id' => $coasterId
        ])->setStatusCode(ResponseInterface::HTTP_CREATED);
    }
}
