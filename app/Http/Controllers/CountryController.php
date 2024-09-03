<?php

namespace App\Http\Controllers;

use App\Http\Resources\CountryResource;
use Illuminate\Http\Request;
use Throwable;
use App\Models\Country;
use App\Services\CountryService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Requests\StoreRequest\StoreCountryRequest;
use App\Http\Requests\UpdateRequest\UpdateCountryRequest;


class CountryController extends Controller
{
    /**
     * @var CountryService
     */
    private CountryService $service;

    /**
     * @param CountryService $service
     */
    public function __construct(CountryService $service)
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
        return CountryResource::collection( $this->service->paginatedList( $request->all() ) );
    }

    /**
     * @param StoreCountryRequest $request
     * @return array|Builder|Collection|Country
     * @throws Throwable
     */
    public function store(StoreCountryRequest $request): array |Builder|Collection|Country
    {
        return $this->service->createModel($request->validated());

    }

    /**
     * @param $
     * @return CountryResource
     * @throws Throwable
     */
    public function show(int $CountryId)
    {
        return new CountryResource( $this->service->getModelById( $CountryId ));
    }

    /**
     * @param UpdateCountryRequest $request
     * @param int $
     * @return array|Country|Collection|Builder
     * @throws Throwable
     */
    public function update(UpdateCountryRequest $request, int $CountryId): array |Country|Collection|Builder
    {
        return $this->service->updateModel($request->validated(), $CountryId);

    }

    /**
     * @param int $
     * @return array|Builder|Collection|Country
     * @throws Throwable
     */
    public function destroy(int $CountryId): array |Builder|Collection|Country
    {
        return $this->service->deleteModel($CountryId);
    }
}
