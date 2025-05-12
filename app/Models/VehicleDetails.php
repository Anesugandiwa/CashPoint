<?php

namespace App\Models;
use App\Models\Asset;
use Illuminate\Database\Eloquent\Model;

class VehicleDetails extends Model
{
    protected $fillable = [
        'asset_id',
        'make',
        'model',
        'vehicle_identification_number',
        'mileage',
        'registration_number',
        'color',
        'engine_number',
        'chassis_number',
        'verified',
        'verification_notes',
    ];

    public function asset(){
        return $this->belongsTo(Asset::class);
    }

        public function verifyWithVehicleTheftSquad()
    {
        // Implementation for integration with Vehicle Theft Squad API
        // This would validate the vehicle's authenticity
        // Return true if vehicle is verified, false otherwise
    }
}
