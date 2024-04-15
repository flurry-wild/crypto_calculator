<?php

namespace App\Services;

use App\Courses\CryptoCourse;
use App\Courses\FiatCourse;
use App\Http\Clients\OkxClient;
use App\Models\CryptoPayment;
use App\Models\FiatPayment;
use Illuminate\Support\Facades\Cache;

class BuyCryptoService
{
    const DEFAULT_CURRENCY = 'USDT';

    const WBTC = 'WBTC';
    const BNB = 'BNB';
    const MATIC = 'MATIC';
    const USDC = 'USDC';

    const CRYPTO_COINS = [self::WBTC, self::BNB, self::MATIC, self::USDC];

    public function courses()
    {
        return [
            'cryptoCoins' => [
                static::WBTC => $this->getCourseFromCache(static::WBTC),
                static::BNB => $this->getCourseFromCache(static::BNB),
                static::MATIC => $this->getCourseFromCache(static::MATIC),
                static::USDC => $this->getCourseFromCache(static::USDC),
            ],

            static::DEFAULT_CURRENCY => $this->getCourseFromCache(static::DEFAULT_CURRENCY)
        ];
    }

    public function getCourseFromCache(string $coin)
    {
        $course = Cache::get($coin);

        if ($course) {
            return floatval($course);
        } else {
            Cache::put($coin, $this->getCourse($coin), 60*10);

            return floatval(Cache::get($coin));
        }
    }

    public function create(array $params)
    {
        $params['sum_in_currency'] = $params['sum'] / $params['course'];

        CryptoPayment::create($params);
    }

    public function update(int $id, array $params)
    {
        $deal = CryptoPayment::find($id);

        $deal->update($params);
    }

    public function getSumRub()
    {
        return FiatPayment::query()->where('currency', static::DEFAULT_CURRENCY)
            ->sum('sum');
    }

    public function getSumUsdt()
    {
        return round(FiatPayment::query()->where('currency', static::DEFAULT_CURRENCY)
            ->sum('sum_in_currency'), 2);
    }

    public function getPurchases()
    {
        return CryptoPayment::query()->get();
    }

    public function getCoinPurchases(string $coin)
    {
        return CryptoPayment::query()->where('currency', $coin)->get();
    }

    public function getCourse(string $coin)
    {
        if ($coin === static::DEFAULT_CURRENCY) {
            $course = new FiatCourse();
        } else {
            $course = new CryptoCourse();
        }

        return $course->getCourse($coin);
    }

    public function delete(int $id)
    {
        return CryptoPayment::find($id)->delete();
    }

    public function getCurrentSumPortfolioUsdt()
    {
        $purchases = $this->getPurchases();

        /** @var OkxClient $client */
        $client = app(OkxClient::class);

        $purchaseAssetSumUsdt = 0;
        foreach ($purchases as $purchase) {
            $purchaseAssetSumUsdt += $purchase->course * $purchase->sum_in_currency;
        }

        $currentAssetSumUsdt = 0;
        foreach ($purchases as $purchase) {
            $currentAssetSumUsdt += $client->getCourse($purchase->currency) * $purchase->sum_in_currency;
        }

        $freeBalance = $this->getSumUsdt() - $purchaseAssetSumUsdt;

        return round($freeBalance + $currentAssetSumUsdt, 2);
    }

    public function getProfitability($currentSumRub, $sumRub)
    {
        return round($currentSumRub / $sumRub * 100 - 100, 2);
    }

    public function chart(string $coin)
    {
        $coin = strtolower($coin);
        $file = file_get_contents(resource_path("chart/year_chart_$coin.json"));
        $data = json_decode($file);

        $deals = CryptoPayment::query()->where('currency', $coin)->get();
        $candles = $data->candles;

        $points = [];
        foreach ($deals as $deal) {
            $purchaseTimestamp = strtotime($deal->purchase_date . ' 00:00:00');
            $points[] = [
                'timestamp' => $purchaseTimestamp,
                'value' => $deal->course,
                'type' => 'buy',
                'time' => date('Y-m-d', $purchaseTimestamp)
            ];
        }

        foreach ($deals as $deal) {
            if (!empty($deal->sell_date)) {
                $saleTimestamp = strtotime($deal->sell_date . ' 00:00:00');
                $points[] = [
                    'timestamp' => $saleTimestamp,
                    'value' => $deal->sell_course,
                    'type' => 'sell',
                    'time' => date('Y-m-d', $saleTimestamp)
                ];
            }
        }

        foreach ($candles as $item) {
            $time = $item[0]/1000;

            $points[] = [
                'timestamp' => $time,
                'value' => $item[1],
                'type' => 'default',
                'time' => date('Y-m-d', $time)
            ];
        }

        uasort($points, function ($a, $b) {
            if ($a['timestamp'] == $b['timestamp']) {
                return 0;
            }
            return ($a['timestamp'] < $b['timestamp']) ? -1 : 1;
        });
        $pointsSort = [];
        foreach ($points as $point) {
            $pointsSort[] = $point;
        }

        $times = array_column($pointsSort, 'time');
        $values = array_column($pointsSort, 'value');
        $types = array_column($pointsSort, 'type');

        return ['times' => $times, 'values' => $values, 'types' => $types];
    }
}
