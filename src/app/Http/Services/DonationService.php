<?php

namespace App\Http\Services;

use App\Enums\PaymentStatusEnum;
use App\Models\Donation;
use App\Http\Resources\DonationCollection;
use App\Http\Requests\DonationCreatePostRequest;
use App\Http\Requests\DonationUpdatePostRequest;
use App\Http\Requests\DonationVerifyPostRequest;
use App\Http\Services\DecryptService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Request;

class DonationService
{
    private $donationModel;

    public function __construct(Donation $donationModel)
    {
        $this->donationModel = $donationModel;
    }

    public function all(): Collection
    {
        return $this->donationModel->all();
    }

    public function random(): Collection
    {
        return $this->donationModel->inRandomOrder()->limit(5)->get();
    }

    public function pagination(Request $request): LengthAwarePaginator
    {
        return $this->donationModel->orderBy('id', 'DESC')->paginate(10);
    }

    public function getById(Int $id): Donation
    {
        return $this->donationModel->findOrFail($id);
    }

    public function getDonationResource(Donation $donation): DonationCollection
    {
        return DonationCollection::make($donation);
    }

    public function getDonationCollection($donation): AnonymousResourceCollection
    {
        return DonationCollection::collection($donation);
    }

    public function create(DonationCreatePostRequest $request) : Donation
    {
        return $this->donationModel->create($request->all());
    }

    public function delete(String $id): Donation
    {
        $donation = $this->getById((new DecryptService)->decryptId($id));
        $donation->delete();
        return $donation;
    }

    public function verify(DonationVerifyPostRequest $request) : Donation
    {
        $donation = $this->donationModel->where('order_id', $request['order_id'])->firstOrFail();
        $donation->update($request->except('order_id'));
        return $donation;
    }

    public function verify_webhook(String $order_id, String $payment_id) : void
    {
        $donation = $this->donationModel->where('order_id', $order_id)->firstOrFail();
        $donation->update([
            "payment_id" => $payment_id,
            "status" => PaymentStatusEnum::COMPLETED->label(),
        ]);
    }

}
