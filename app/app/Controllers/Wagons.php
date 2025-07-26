<?php

namespace App\Controllers;

use App\Models\WagonRepository;
use CodeIgniter\HTTP\ResponseInterface;

class Wagons extends BaseController
{
    private WagonRepository $wagonRepository;

    public function __construct()
    {
        $this->wagonRepository = new WagonRepository();
    }

    /**
     * Dodaje nowy wagon do kolejki.
     *
     * @param string $coasterId ID kolejki, do której dodajemy wagon.
     * @return ResponseInterface
     */
    public function add(string $coasterId): ResponseInterface
    {
        $data = $this->request->getJSON(true);

        // TODO: Dodać walidację danych
        // TODO: Sprawdzić, czy kolejka o podanym ID istnieje

        $wagonId = $this->wagonRepository->add($coasterId, $data);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Wagon został pomyślnie dodany do kolejki.',
            'wagon_id' => $wagonId
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
        // TODO: Sprawdzić, czy kolejka i wagon istnieją przed usunięciem

        $this->wagonRepository->remove($coasterId, $wagonId);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Wagon został pomyślnie usunięty.'
        ])->setStatusCode(ResponseInterface::HTTP_OK);
    }
}
