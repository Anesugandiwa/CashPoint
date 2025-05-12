<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetDocument extends Model
{
    protected $fillable = [
        'asset_id',
        'document_path',
        'document_type',
        'name',
    ];
    
    public function asset(){
        return $this->belongsTo(Asset::class);
    }

}
