<?php

namespace App\Services;

use App\Models\CoasterRepository;
use App\Models\WagonRepository;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Exception;

class WagonService
{
    private WagonRepository $wagonRepository;
    private CoasterRepository $coasterRepository;

    public function __construct()
    {
        $this->wagonRepository = service('wagonRepository');
        $this->coasterRepository = service('coasterRepository');
    }

    /**
     * @throws Exception
     */
    public function addWagon(string $coasterId, array $data): array
    {
        if (!$this->coasterRepository->exists($coasterId)) {
            throw new Exception('Kolejka o podanym ID nie istnieje.', ResponseInterface::HTTP_NOT_FOUND);
        }

        $validation = Services::validation();
        $validation->setRules([
            'ilosc_miejsc' => 'required|integer',
            'predkosc_wagonu' => 'required|numeric',
        ]);

        if (!$validation->run($data)) {
            $errors = $validation->getErrors();
            throw new Exception(implode(', ', $errors), ResponseInterface::HTTP_BAD_REQUEST);
        }

        $wagonId = $this->wagonRepository->add($coasterId, $data);

        return [
            'status' => 'success',
            'message' => 'Wagon został pomyślnie dodany do kolejki.',
            'wagon_id' => $wagonId,
        ];
    }

    /**
     * @throws Exception
     */
    public function removeWagon(string $coasterId, string $wagonId): array
    {
        if (!$this->coasterRepository->exists($coasterId) || !$this->wagonRepository->exists($coasterId, $wagonId)) {
            throw new Exception('Kolejka lub wagon o podanym ID nie istnieje.', ResponseInterface::HTTP_NOT_FOUND);
        }

        $this->wagonRepository->remove($coasterId, $wagonId);

        return [
            'status' => 'success',
            'message' => 'Wagon został pomyślnie usunięty.',
        ];
    }
}
