<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\CurrencyRate;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Carbon;

class CurrencyController extends BaseController
{
    const ITEMS_PER_PAGE = 8;

    public function index()
    {
        $currencies = Currency::paginate(self::ITEMS_PER_PAGE);
        $currencies->append('latest_rate');

        return view('index', [
            'currencies' => $currencies
        ]);
    }

    public function show(Currency $currency)
    {
        $currencyHistory = CurrencyRate::where('currency_id', $currency->id)->orderBy('datetime');
        $currencyChart = $currencyHistory->get()->map(function ($item) {
            return [
                'x' => Carbon::parse($item->datetime)->format('Y-m-d'),
                'y' => $item->rate
            ];
        })->toJson();

        return view('show', [
            'currencyRates' => $currencyHistory->paginate(self::ITEMS_PER_PAGE),
            'currencyChart' => $currencyChart,
            'currencyName' => $currency->name,
        ]);
    }
}
