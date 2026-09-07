<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultNote extends Model
{
    use HasFactory;

    protected $appends = ['vender_name'];

    public function getVenderNameAttribute()
    {

        $lan = User::find($this->vender_id);
        if (!empty($lan)) {
            return $lan->name.' '.$lan->middle_name.' '.$lan->last_name;
        } else {
            return 'N/A';
        }
    }
}
