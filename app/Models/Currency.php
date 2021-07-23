<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    public function getLatestRateAttribute()
    {
        return $this->rates()->orderBy('datetime', 'desc')->first()->toArray();
    }

    public function rates()
    {
        return $this->hasMany(CurrencyRate::class);
    }
}
