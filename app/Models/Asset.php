<?php

namespace App\Models;
use App\Models\User;
use App\Models\AssetType;
use App\Models\Loan;
use App\Models\VehicleDetails;
use App\Models\JewelryDetails;
use App\Models\ElectronicsDetails;
use App\Models\AuctionItem;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $fillable = [
        'user_id',
        'asset_type_id',
        'title',
        'description',
        'user_estimated_value',
        'year_of_purchase',
        'condition',
        'market_value',
        'estimated_loan_value',
        'final_evaluation_value',
        'final_loan_value',
        'evaluator_id',
        'approver_id',
        'evaluation_notes',
        'evaluation_date',
        'approval_date',
        'status',
        'storage_location',
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function assetType(){
        return $this->belongsTo(AssetType::class);
    }
     public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
    public function images()
    {
        return $this->hasMany(AssetImage::class);
    }

    public function documents()
    {
        return $this->hasMany(AssetDocument::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function activeLoan()
    {
        return $this->loans()->whereIn('status', ['Pending', 'Approved', 'Active'])->first();
    }

    public function vehicleDetails()
    {
        return $this->hasOne(VehicleDetails::class);
    }

    public function jewelryDetails()
    {
        return $this->hasOne(JewelryDetails::class);
    }

    public function electronicsDetails()
    {
        return $this->hasOne(ElectronicsDetails::class);
    }

    public function auctionItems()
    {
        return $this->hasMany(AuctionItem::class);
    }


}
