<?php

namespace App\Models;

use App\Services\PorfolioManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Holding extends Model
{
    protected $guarded = [];
    protected $appends = ['name', 'last_price'];

    protected function lastPrice(): Attribute
    {
        return Attribute::make(
            get: function () {
                return PorfolioManager::all()[$this->ticker][1];
            },
        );
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            get: function () {
                return PorfolioManager::all()[$this->ticker][2];
            },
        );
    }
}
