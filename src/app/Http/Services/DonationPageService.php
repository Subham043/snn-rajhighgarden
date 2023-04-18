<?php

namespace App\Http\Services;

use App\Models\DonationPage;
use App\Http\Resources\DonationPageCollection;
use App\Http\Requests\DonationPageCreatePostRequest;
use App\Http\Requests\DonationPageUpdatePostRequest;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Services\FileService;
use App\Http\Services\DecryptService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Request;

class DonationPageService
{
    private $donationPageModel;

    private $path = 'public/upload/donation_pages';

    public function __construct(DonationPage $donationPageModel)
    {
        $this->donationPageModel = $donationPageModel;
    }

    public function all(): Collection
    {
        return $this->donationPageModel->all();
    }

    public function random(): Collection
    {
        return $this->donationPageModel->inRandomOrder()->limit(4)->get();
    }

    public function pagination(Request $request): LengthAwarePaginator
    {
        return $this->donationPageModel->orderBy('id', 'DESC')->paginate(10);
    }

    public function getById(Int $id): DonationPage
    {
        return $this->donationPageModel->findOrFail($id);
    }

    public function getBySlug(String $slug): DonationPage
    {
        return $this->donationPageModel->where('slug', $slug)->firstOrFail();
    }

    public function getDonationPageResource(DonationPage $donationPage): DonationPageCollection
    {
        return DonationPageCollection::make($donationPage);
    }

    public function getDonationPageCollection($donationPage): AnonymousResourceCollection
    {
        return DonationPageCollection::collection($donationPage);
    }

    public function create(DonationPageCreatePostRequest $request) : DonationPage
    {
        $image = (new FileService)->save_file($request, 'image', $this->path);
        return $this->donationPageModel->create([
            ...$request->except('image'),
            'image' => $image,
        ]);
    }

    public function delete(String $id): DonationPage
    {
        $donationPage = $this->getById((new DecryptService)->decryptId($id));
        (new FileService)->delete_file('app/'.$this->path.'/'.$donationPage->image);
        $donationPage->delete();
        return $donationPage;
    }

    public function update(DonationPageUpdatePostRequest $request, String $id) : DonationPage
    {
        $donationPage = $this->getById((new DecryptService)->decryptId($id));
        if($request->hasFile('image')){
            $image = (new FileService)->save_file($request, 'image', $this->path);
            (new FileService)->delete_file('app/'.$this->path.'/'.$donationPage->image);
            $donationPage->update([
                'image' => $image,
            ]);
        }
        $donationPage->update([
            ...$request->except('image'),
        ]);
        return $donationPage;
    }

}
