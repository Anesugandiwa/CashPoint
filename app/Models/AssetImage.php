<?php

namespace App\Models;
use App\Models\Asset;
use Illuminate\Database\Eloquent\Model;

class AssetImage extends Model
{
    protected $fillable =[
        'asset_id',
        'image_path',
        'caption',
    ];
    public function asset(){
        return $this->belongsTo(Asset::class);
    }
}
