<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BestTestBatch extends Model
{
    protected $table = 'best_test_batches';

    protected $fillable = [
        'created_by',
        'district',
        'exam_date',
        'place',
        'status',
    ];

    protected $casts = [
        'exam_date' => 'date',
    ];

    public function athletes()
    {
        return $this->hasMany(BestTestAthlete::class, 'batch_id');
    }

    public function creator()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function districtTag()
    {
        return $this->belongsTo(Tags::class, 'district');
    }
}
