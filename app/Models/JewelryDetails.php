<?php

namespace App\Models;
use App\Models\Asset;
use Illuminate\Database\Eloquent\Model;

class JewelryDetails extends Model
{
    protected $fillable = [
        'asset_id',
        'material_type',
        'carat_weight',
        'gemstone_type',
        'gemstone_weight',
    ];

    public function asset(){
        return $this->belongsTo(Asset::class);
    }
}
