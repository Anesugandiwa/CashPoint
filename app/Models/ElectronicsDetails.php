<?php

namespace App\Models;
use App\Models\Asset;
use Illuminate\Database\Eloquent\Model;

class ElectronicsDetails extends Model
{
    protected $fillable = [
        'asset_id',
        'brand',
        'model_number',
        'serial_number',
        'specifications',
    ];
    public function asset(){
        return $this->belongsTo(Asset::class);
    }
}
