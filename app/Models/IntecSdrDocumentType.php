<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntecSdrDocumentType extends Model
{
    use HasFactory;
    // protected $table = 'intec_sdr_document_type';
    protected $fillable = ['doc_type','doc_name'];

    protected $table = "intec_sdr_document_types";

    // Specify the primary key column if it's not 'id'
    protected $primaryKey = 'type_id';

    // Indicate if the primary key is not an incrementing integer
    public $incrementing = false;
    
    // Specify the type of the primary key
    protected $keyType = 'int'; // Change this if your primary key is not a string
    
}
