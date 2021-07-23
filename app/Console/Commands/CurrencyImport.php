<?php

namespace App\Console\Commands;

use App\Models\Currency;
use App\Models\CurrencyRate;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class CurrencyImport extends Command
{
    const BANK_URL = 'https://www.bank.lv/vk/ecb_rss.xml';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import currency rates';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $xml_string = file_get_contents(self::BANK_URL);
        $xml = simplexml_load_string($xml_string);
        $channel = (array)$xml->channel;

        $this->singleRate($channel);
    }

    private function singleRate(array $channel)
    {
        foreach ((array)$channel['item'] as $channelItem) {
            $item = (array)$channelItem;
            $currencyString = (string)$item['description'];

            $data = collect(array_chunk(explode(' ', $currencyString), 2));
            $filter = $data->filter(function ($item) {
                return !empty($item[0]);
            });
            $currencyList = $filter->map(function ($item) {
                return [$item[0] => $item[1]];
            })->collapse();
            $dateString = (string)$item['pubDate'];
            $ratePublishDate = Carbon::parse($dateString)->format('Y-m-d H:i:s');

            foreach ($currencyList as $currency => $rate) {
                $currencyModel = Currency::firstOrCreate([
                    'name' => $currency,
                ]);
                $currencyRateModel = CurrencyRate::firstOrNew([
                    'currency_id' => $currencyModel->id,
                    'datetime' => $ratePublishDate,
                ]);
                if (!$currencyRateModel->id) {
                    $currencyRateModel->currency_id = $currencyModel->id;
                    $currencyRateModel->rate = $rate;
                    $currencyRateModel->save();
                }
            }
        }
    }
}
