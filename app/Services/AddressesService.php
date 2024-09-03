<?php

namespace App\Services;

use App\Repositories\AddressesRepository;

class AddressesService extends BaseService
{
    public function __construct(AddressesRepository $repository)
    {
        $this->repository = $repository;
    }
}