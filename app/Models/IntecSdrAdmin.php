<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntecSdrAdmin extends Model
{
    use HasFactory;

    protected $fillable = ['admin_ic', 'admin_name' , 'admin_email'];

    protected $table = "intec_sdr_admins";

    protected $primaryKey = 'admin_id';
    
    // Indicate if the primary key is not an incrementing integer
    public $incrementing = false;
        
    // Specify the type of the primary key
    protected $keyType = 'int'; // Change this if your primary key is not a string
    protected static function boot()
    {
        parent::boot();
    
        // Listen to the creating event
        static::creating(function ($admin) {
            if (empty($admin->admin_id)) {
                
                // Get the last inserted admin ID (order by the numeric part of the ID)
                $lastId = self::orderByRaw('CAST(SUBSTRING(admin_id, 2) AS UNSIGNED) DESC')->first();
                
                // Extract the numeric part from the last ID and increment it
                $newIdNumber = $lastId ? intval(substr($lastId->admin_id, 1)) + 1 : 1; // Default to 1 if no records exist
    
                // Generate the new ID with 'A' prefix and padded number
                $admin->admin_id = 'A' . str_pad($newIdNumber, 4, '0', STR_PAD_LEFT);
            }
        });
    }
    
}
