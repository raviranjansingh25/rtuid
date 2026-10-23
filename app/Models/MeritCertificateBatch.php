<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeritCertificateBatch extends Model
{
    protected $table = 'merit_certificate_batches';

    protected $fillable = [
        'created_by',
        'recipient_type',
        'template',
        'course_title',
        'place',
        'date_from',
        'date_on',
        'issue_date',
        'status',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'template' => 'integer',
    ];

    public function certificates()
    {
        return $this->hasMany(MeritCertificate::class, 'batch_id');
    }

    public function creator()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }
}
