<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReplySupport;
use App\Http\Requests\StoreSupport;
use App\Http\Resources\ReplySupportResource;
use App\Http\Resources\SupportResource;
use App\Repositories\SupportRepository;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    protected $repository;

    public function __construct(SupportRepository $SupportRepository)
    {
        $this->repository = $SupportRepository;
    }

    public function index(Request $request)
    {
        $supports = $this->repository->getSupports($request->all());

        return SupportResource::collection($supports);
    }

    public function store(StoreSupport $request)
    {
        $support = $this->repository->storeSupport($request->validated());

        return new SupportResource($support);
    }

    public function storeReply(StoreReplySupport $request, $supportId)
    {
        $reply = $this->repository->storeReply($supportId, $request->validated());

        return new ReplySupportResource($reply);
    }
}
