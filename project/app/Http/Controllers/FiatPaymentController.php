<?php

namespace App\Http\Controllers;

use App\Services\FiatPaymentService;
use Illuminate\Http\JsonResponse;

class FiatPaymentController extends Controller
{
    /** @var FiatPaymentService $service */
    private $service;

    public function __construct()
    {
        $this->service = app(FiatPaymentService::class);
    }

    public function index()
    {
        return new JsonResponse(['data' => $this->service->index()]);
    }

    public function delete(int $id)
    {
        return new JsonResponse($this->service->delete($id));
    }
}
