<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntecSdrApplicant extends Model
{
    use HasFactory;

    protected $fillable = ['applicant_ic', 'applicant_name', 'applicant_phone', 'applicant_email', 'applicant_depart'];

    protected $table = "intec_sdr_applicants";

    protected $primaryKey = 'applicant_id';

    public $incrementing = false;

    protected $keyType = 'string'; // Use 'string' since you want a string-based ID like 'S0001'

    protected static function boot()
    {
        parent::boot();

        // Listen to the creating event
        static::creating(function ($applicant) {
            if (empty($applicant->applicant_id)) {
                // Generate the custom ID format (e.g., dev-001)
                $lastId = self::orderBy('applicant_id', 'desc')->first();

                // Extract the numeric part from the last ID and increment it
                $newIdNumber = $lastId ? intval(substr($lastId->applicant_id, 1)) + 1 : 1; // Default to 1 if no records exist

                // Generate the new ID
                $newApplicantId = 'S' . str_pad($newIdNumber, 3, '0', STR_PAD_LEFT);

                // Ensure the new applicant ID is unique
                while (self::where('applicant_id', $newApplicantId)->exists()) {
                    // If the ID already exists, increment the number and generate a new ID
                    $newIdNumber++;
                    $newApplicantId = 'S' . str_pad($newIdNumber, 3, '0', STR_PAD_LEFT);
                }

                $applicant->applicant_id = $newApplicantId;
            }
        });
    }
// In Applicant model
public function user()
{
    return $this->belongsTo(User::class);
}

    public function applications()
    {
        return $this->hasMany(IntecSdrApplication::class, 'applicant_id'); // Adjust the foreign key as needed
    }


}
