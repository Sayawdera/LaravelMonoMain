<?php

namespace App\Http\Controllers;

use App\Http\Resources\AddressesResource;
use Illuminate\Http\Request;
use Throwable;
use App\Models\Addresses;
use App\Services\AddressesService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Requests\StoreRequest\StoreAddressesRequest;
use App\Http\Requests\UpdateRequest\UpdateAddressesRequest;


class AddressesController extends Controller
{
    /**
     * @var AddressesService
     */
    private AddressesService $service;

    /**
     * @param AddressesService $service
     */
    public function __construct(AddressesService $service)
    {
        $this->service = $service;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws Throwable
     */
    public function index(Request $request)
    {
        return AddressesResource::collection( $this->service->paginatedList( $request->all() ) );
    }

    /**
     * @param StoreAddressesRequest $request
     * @return array|Builder|Collection|Addresses
     * @throws Throwable
     */
    public function store(StoreAddressesRequest $request): array |Builder|Collection|Addresses
    {
        return $this->service->createModel($request->validated());

    }

    /**
     * @param $usersId
     * @return AddressesResource
     * @throws Throwable
     */
    public function show(int $usersId)
    {
        return new AddressesResource( $this->service->getModelById( $usersId ));
    }

    /**
     * @param UpdateAddressesRequest $request
     * @param int $usersId
     * @return array|Addresses|Collection|Builder
     * @throws Throwable
     */
    public function update(UpdateAddressesRequest $request, int $usersId): array |Addresses|Collection|Builder
    {
        return $this->service->updateModel($request->validated(), $usersId);

    }

    /**
     * @param int $usersId
     * @return array|Builder|Collection|Addresses
     * @throws Throwable
     */
    public function destroy(int $usersId): array |Builder|Collection|Addresses
    {
        return $this->service->deleteModel($usersId);
    }
}
