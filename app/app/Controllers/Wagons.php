<?php

namespace App\Controllers;

use App\Models\CoasterRepository;
use App\Models\WagonRepository;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class Wagons extends BaseController
{
    private WagonRepository $wagonRepository;
    private CoasterRepository $coasterRepository;

    public function __construct()
    {
        $this->wagonRepository = service('wagonRepository');
        $this->coasterRepository = service('coasterRepository');
    }

    /**
     * Dodaje nowy wagon do kolejki.
     *
     * @param string $coasterId ID kolejki, do której dodajemy wagon.
     * @return ResponseInterface
     */
    public function add(string $coasterId): ResponseInterface
    {
        // Sprawdzenie, czy kolejka istnieje
        if (!$this->coasterRepository->exists($coasterId)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Kolejka o podanym ID nie istnieje.',
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        $data = $this->request->getJSON(true);

        // Walidacja danych
        $validation = Services::validation();
        $validation->setRules([
            'ilosc_miejsc' => 'required|integer',
            'predkosc_wagonu' => 'required|numeric',
        ]);

        if (!$validation->run($data)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $validation->getErrors(),
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        $wagonId = $this->wagonRepository->add($coasterId, $data);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Wagon został pomyślnie dodany do kolejki.',
            'wagon_id' => $wagonId,
        ])->setStatusCode(ResponseInterface::HTTP_CREATED);
    }

    /**
     * Usuwa wagon z kolejki.
     *
     * @param string $coasterId ID kolejki.
     * @param string $wagonId ID wagonu do usunięcia.
     * @return ResponseInterface
     */
    public function remove(string $coasterId, string $wagonId): ResponseInterface
    {
        // Sprawdzenie, czy kolejka i wagon istnieją
        if (!$this->coasterRepository->exists($coasterId) || !$this->wagonRepository->exists($coasterId, $wagonId)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Kolejka lub wagon o podanym ID nie istnieje.',
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        $this->wagonRepository->remove($coasterId, $wagonId);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Wagon został pomyślnie usunięty.',
        ])->setStatusCode(ResponseInterface::HTTP_OK);
    }
}
