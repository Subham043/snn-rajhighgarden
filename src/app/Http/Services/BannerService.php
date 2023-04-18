<?php

namespace App\Http\Services;

use App\Models\Banner;
use App\Http\Resources\BannerCollection;
use App\Http\Requests\BannerCreatePostRequest;
use App\Http\Requests\BannerUpdatePostRequest;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Services\FileService;
use App\Http\Services\DecryptService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Request;

class BannerService
{
    private $bannerModel;

    private $path = 'public/upload/banner';

    public function __construct(Banner $bannerModel)
    {
        $this->bannerModel = $bannerModel;
    }

    public function all(): Collection
    {
        return $this->bannerModel->all();
    }

    public function random(): Collection
    {
        return $this->bannerModel->inRandomOrder()->limit(4)->get();
    }

    public function pagination(Request $request): LengthAwarePaginator
    {
        return $this->bannerModel->orderBy('id', 'DESC')->paginate(10);
    }

    public function getById(Int $id): Banner
    {
        return $this->bannerModel->findOrFail($id);
    }

    public function getBannerResource(Banner $banner): BannerCollection
    {
        return BannerCollection::make($banner);
    }

    public function getBannerCollection($banner): AnonymousResourceCollection
    {
        return BannerCollection::collection($banner);
    }

    public function create(BannerCreatePostRequest $request) : Banner
    {
        $image = (new FileService)->save_file($request, 'image', $this->path);
        return $this->bannerModel->create([
            ...$request->except('image'),
            'image' => $image,
        ]);
    }

    public function delete(String $id): Banner
    {
        $banner = $this->getById((new DecryptService)->decryptId($id));
        (new FileService)->delete_file('app/'.$this->path.'/'.$banner->image);
        $banner->delete();
        return $banner;
    }

    public function update(BannerUpdatePostRequest $request, String $id) : Banner
    {
        $banner = $this->getById((new DecryptService)->decryptId($id));
        if($request->hasFile('image')){
            $image = (new FileService)->save_file($request, 'image', $this->path);
            (new FileService)->delete_file('app/'.$this->path.'/'.$banner->image);
            $banner->update([
                'image' => $image,
            ]);
        }
        $banner->update([
            ...$request->except('image'),
        ]);
        return $banner;
    }

}
