<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IntecSdrDocument extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['applications_id', 'doc_type_id', 'doc_name' , 'doc_web_path' ,'doc_size'];

    protected $table = "intec_sdr_documents";

    protected $primaryKey = 'doc_id';
    
    // Indicate if the primary key is not an incrementing integer
    public $incrementing = false;
        
    // Specify the type of the primary key
    protected $keyType = 'int'; // Change this if your primary key is not a string

}
