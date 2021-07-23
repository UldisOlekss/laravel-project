<?php

namespace App\Console\Commands;

use App\Models\Currency;
use App\Models\CurrencyRate;
use Carbon\CarbonPeriod;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class CurrencyDateImport extends Command
{
    const BANK_HISTORY_URL = 'https://www.bank.lv/vk/ecb.xml?date=%s';
    const SLEEP_TIME = 1;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:import:date {inputDays}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import currency rate for number of past days';

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
        $argument = $this->argument('inputDays');

        $validator = Validator::make(['argument' => $argument], ['argument' => 'required|integer|min:1|max:20']);
        if ($validator->fails()) {
            $this->warn($validator->errors()->first());

            return false;
        }

        $currentDate = Carbon::now()->format('Y-m-d');
        $targetDate = Carbon::now()->subDays($this->argument('inputDays'));
        $period = CarbonPeriod::create($targetDate, $currentDate);

        foreach ($period as $date) {
            $this->getRate($date);
            sleep(self::SLEEP_TIME);
        }

        return true;
    }

    private function getRate(Carbon $carbonDate)
    {
        $date = $carbonDate->format('Ymd');

        $xml_string = file_get_contents(sprintf(self::BANK_HISTORY_URL, $date));
        $xml = simplexml_load_string($xml_string);

        $currencies = (array)$xml->Currencies;
        $date = (string)$xml->Date;
        $ratePublishDate = Carbon::parse($date)->setTime(3, 0, 0)->format('Y-m-d H:i:s');

        $currencyList = (array)$currencies["Currency"];

        foreach ((array)$currencyList as $currency) {
            $item = (array)$currency;

            $currencyModel = Currency::firstOrCreate([
                'name' => $item['ID'],
            ]);
            $currencyRateModel = CurrencyRate::firstOrNew([
                'currency_id' => $currencyModel->id,
                'datetime' => $ratePublishDate,
            ]);
            if (!$currencyRateModel->id) {
                $currencyRateModel->currency_id = $currencyModel->id;
                $currencyRateModel->rate = $item['Rate'];
                $currencyRateModel->save();
            }
        }
    }
}
