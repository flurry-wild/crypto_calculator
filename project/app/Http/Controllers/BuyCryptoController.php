<?php

namespace App\Http\Controllers;

use App\Http\Requests\BuyCryptoRequest;
use App\Http\Requests\SellCryptoRequest;
use App\Services\BuyCryptoService;
use Illuminate\Http\JsonResponse;

class BuyCryptoController extends Controller
{
    /** @var BuyCryptoService $service */
    private $service;

    public function __construct()
    {
        $this->service = app(BuyCryptoService::class);
    }

    public function index()
    {
        return view('buy_crypto.index');
    }

    public function getSum()
    {
        $sumRub = $this->service->getSumRub();
        $currentSumUsdt = $this->service->getCurrentSumPortfolioUsdt();
        $currentSumRub = round($currentSumUsdt * $this->service->getCourseFromCache(BuyCryptoService::DEFAULT_CURRENCY), 2);

        return new JsonResponse([
            'sum' => $sumRub,
            'sumUsdt' => $this->service->getSumUsdt(),
            'currentSumUsdt' => $currentSumUsdt,
            'currentSumRub' => $currentSumRub,
            'profitability' => $this->service->getProfitability($currentSumRub, $sumRub)
        ]);
    }

    public function create(BuyCryptoRequest $request)
    {
        $this->service->create($request->validated());
    }

    public function update(int $id, SellCryptoRequest $request)
    {
        $this->service->sellCrypto($id, $request->validated());
    }

    public function delete(int $id)
    {
        return new JsonResponse($this->service->delete($id));
    }

    public function courses()
    {
        return new JsonResponse(['data' => $this->service->courses()]);
    }

    public function purchases(string $coin)
    {
        return new JsonResponse(['purchases' => $this->service->getCoinPurchases($coin)]);
    }

    public function chart(string $coin)
    {
        return new JsonResponse($this->service->chart($coin));
    }
}
