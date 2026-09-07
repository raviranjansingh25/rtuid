<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VenderConsultPrice extends Model
{
    use HasFactory;

    protected $appends = ['consult_name2'];

    public function getConsultNameAttribute($value)
    {
        $name = Consult::find($value);
        if (!empty($name)) {
            $user_p = auth()->guard('web')->user();
            if (!empty($user_p)) {
                $const = SessionBooking::where('user_id', $user_p->id)->where('status','active')->where('vender_id', $this->vender_id)->count();
                if ($const > 0) {
                    return $name->title . ' follow up price';
                } else {
                    return $name->title . ' initial price';
                }
            } else {
                return $name->title . ' initial price';
            }
        } else {
            return 'N/A';
        }
    }

    public function getConsultName2Attribute()
    {
        $name = Consult::find($this->consult_name_id);
        if (!empty($name)) {
                if ($this->type == 1) {
                    return $name->title . ' initial price';
                } else {
                    return $name->title . ' follow up price';
                }
            
        } else {
            return 'N/A';
        }
    }

    function get_consult()
    {
        return $this->belongsTo('App\Models\Consult', 'consult_name_id');
    }
}
