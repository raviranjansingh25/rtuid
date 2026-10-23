<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeritCertificate extends Model
{
    protected $table = 'merit_certificates';

    protected $fillable = [
        'batch_id',
        'recipient_id',
        'serial_no',
    ];

    public function batch()
    {
        return $this->belongsTo(MeritCertificateBatch::class, 'batch_id');
    }

    public function coach()
    {
        return $this->belongsTo(Coach::class, 'recipient_id');
    }

    public function referee()
    {
        return $this->belongsTo(Referee::class, 'recipient_id');
    }

    public function recipient()
    {
        $type = $this->batch->recipient_type ?? null;
        if ($type === 'referee') {
            return $this->referee;
        }

        return $this->coach;
    }
}
