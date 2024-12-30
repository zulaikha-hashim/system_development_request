<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class IntecSdrStatusApplication extends Model
{
    use HasFactory;
    // use SoftDeletes;
    protected $fillable = ['status_name'];

    // protected $table = "intec_sdr_status_applications";

     // Specify the primary key column if it's not 'id'
     protected $primaryKey = 'status_id';

     // Indicate if the primary key is not an incrementing integer
     public $incrementing = false;
     
     // Specify the type of the primary key
     protected $keyType = 'int'; 
     

    public function applications()
    {
        return $this->hasMany(Application::class, 'status_id');
    }
}

