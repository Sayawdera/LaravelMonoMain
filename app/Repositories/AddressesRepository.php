<?php

namespace App\Repositories;

use App\Models\Addresses;

class AddressesRepository extends BaseRepository
{
    public function __construct(Addresses $model)
    {
        parent::__construct($model);
    }
}