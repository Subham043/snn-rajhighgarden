<?php

namespace App\Http\Services;

use App\Models\Counter;
use App\Http\Resources\CounterCollection;
use App\Http\Requests\CounterPostRequest;
use App\Http\Services\DecryptService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Request;

class CounterService
{
    private $counterModel;

    public function __construct(Counter $counterModel)
    {
        $this->counterModel = $counterModel;
    }

    public function all(): Collection
    {
        return $this->counterModel->all();
    }

    public function random(): Collection
    {
        return $this->counterModel->inRandomOrder()->limit(4)->get();
    }

    public function pagination(Request $request): LengthAwarePaginator
    {
        return $this->counterModel->orderBy('id', 'DESC')->paginate(10);
    }

    public function getById(Int $id): Counter
    {
        return $this->counterModel->findOrFail($id);
    }

    public function getCounterResource(Counter $counter): CounterCollection
    {
        return CounterCollection::make($counter);
    }

    public function getCounterCollection($counter): AnonymousResourceCollection
    {
        return CounterCollection::collection($counter);
    }

    public function create(CounterPostRequest $request) : Counter
    {
        return $this->counterModel->create($request->all());
    }

    public function delete(String $id): Counter
    {
        $counter = $this->getById((new DecryptService)->decryptId($id));
        $counter->delete();
        return $counter;
    }

    public function update(CounterPostRequest $request, String $id) : Counter
    {
        $counter = $this->getById((new DecryptService)->decryptId($id));
        $counter->update($request->all());
        return $counter;
    }

}
