<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VenderPackagePrice extends Model
{
    use HasFactory;

    protected $appends = ['package_min_name'];

    public function getPackageMinNameAttribute()
    {
        $pac_min = Consult::select('id', 'title')->find($this->time_duration);

        if (!empty($pac_min)) {
            return $pac_min->title;
        } else {
            return 0;
        }
    }
}
