<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntecSdrDeveloper extends Model
{
    use HasFactory;

    protected $fillable = ['dev_ic', 'dev_name', 'dev_email'];

    protected $table = "intec_sdr_developers";

    protected $primaryKey = 'dev_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();

        // Listen to the creating event
        static::creating(function ($developer) {
            if (empty($developer->dev_id)) {
                // Generate the custom ID format (e.g., dev-001)
                $lastId = self::orderBy('dev_id', 'desc')->first();

                // Extract the numeric part from the last ID and increment it
                $newIdNumber = $lastId ? intval(substr($lastId->dev_id, 5)) + 1 : 1; // Default to 1 if no records exist

                // Generate the new ID
                $developer->dev_id = 'DEV' . str_pad($newIdNumber, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
