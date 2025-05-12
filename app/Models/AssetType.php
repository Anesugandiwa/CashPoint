<?php

namespace App\Models;
use App\Models\Asset;
use Illuminate\Database\Eloquent\Model;

class AssetType extends Model
{
    protected $fillable = [
        'name',
        'description',
        'loan_percentage',
        'interest_rate',
        'interest_period_days',
        'storage_charge_percentage',
    ];

    public function assets(){
        return $this->hasMany(Asset::class);
    }
}
