<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageBooking extends Model
{
    use HasFactory;

    protected $appends = ['package_min', 'package_min_name'];

    public function getPackageMinAttribute()
    {
        $venderpackage = VenderPackagePrice::find($this->package_id);
        $pac_min = Consult::select('id', 'min')->find($venderpackage->time_duration);

        if (!empty($pac_min)) {
            return $pac_min->min;
        } else {
            return 0;
        }
    }

    public function getPackageMinNameAttribute()
    {
        $venderpackage = VenderPackagePrice::find($this->package_id);
        $pac_min = Consult::select('id', 'title')->find($venderpackage->time_duration);

        if (!empty($pac_min)) {
            return $pac_min->title;
        } else {
            return 0;
        }
    }


    function get_doctor()
    {
        return $this->belongsTo('App\Models\User', 'vender_id');
    }
}
