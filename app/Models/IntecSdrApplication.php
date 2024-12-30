<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IntecSdrApplication extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'applicant_id', 'status_id', 'admin_id', 'dev_id',
        'applications_system_name', 'applications_system_desc', 
        'applications_urgency', 'applications_date1', 
        'applications_date2', 'applications_date3', 
        'applications_time', 'applications_remark', 
        'date_comfirm', 'applications_deadline'
    ];

    protected $table = "intec_sdr_applications";

    protected $primaryKey = 'applications_id';

    public function applicant()
    {
        return $this->hasOne(IntecSdrApplicant::class, 'applicant_id', 'applicant_id');
    }

    public function status()
    {
        return $this->hasOne(IntecSdrStatusApplication::class, 'status_id', 'status_id');
    }

    public function developer()
    {
        return $this->hasOne(IntecSdrDeveloper::class, 'dev_id', 'dev_id');
    }
}
