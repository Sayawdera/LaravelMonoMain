<?php

namespace App\Traits;

use App\Repositories\BaseRepository;
use Throwable;

trait ServiceHelper
{
    protected ?BaseRepository $repository;
    protected function __construct(){}

    /**
     * @return BaseRepository
     * @throws Throwable
     */
    protected function getRepository(): BaseRepository
    {
        throw_if(! $this->repository, get_class($this) . "[ ERROR ]: Repository Property Not Implemented!");
        return $this->repository;
    }
}