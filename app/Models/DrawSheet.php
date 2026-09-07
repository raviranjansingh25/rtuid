<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DrawSheet extends Model
{
    use HasFactory;
    
    protected $appends = ['district', 'code'];
    
    public function getDistrictAttribute()
    {
        $district = ApplyTournament::find($this->user_id);
        if (!empty($district)) {
            return $district->district;
        } else {
            return null;
        }
        
    }
    
    public function getCodeAttribute()
    {
        $data = ApplyTournament::find($this->user_id);
        if (!empty($data)) {
            return last(explode('/', $data->code));
            
        } else {
            return null;
        }
        
    }

    protected $fillable = [
        'tournament_id',
        'user_id',
        'user_name',
        'sheet',
        'match',
        'match_group',
        'group_set',
        'status'
    ];
}
