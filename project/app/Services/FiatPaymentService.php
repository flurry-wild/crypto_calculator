<?php

namespace App\Services;

use App\Exceptions\FundsSpentException;
use App\Models\FiatPayment;

class FiatPaymentService
{
    const DEFAULT_CURRENCY = 'USDT';

    public function getSumUsdt()
    {
        return round(FiatPayment::query()->where('currency', static::DEFAULT_CURRENCY)
            ->sum('sum_in_currency'), 2);
    }

    public function create(array $params)
    {
        $params['currency'] = static::DEFAULT_CURRENCY;
        $params['sum_in_currency'] = $params['sum'] / $params['course'];

        FiatPayment::create($params);
    }

    public function index()
    {
        return FiatPayment::get();
    }

    public function delete(int $id)
    {
        $fiatPayment = FiatPayment::find($id);

        $sumWithoutThisRecord = $this->getSumUsdt() - $fiatPayment->sum_in_currency;
        $cryptoService = app(BuyCryptoService::class);
        if ($sumWithoutThisRecord < $cryptoService->getSumPurchases()) {
            throw new FundsSpentException();
        }

        return $fiatPayment->delete();
    }
}
