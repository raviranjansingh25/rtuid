<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    
    protected $table = 'admins';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'select_all_district' => 'boolean',
        'add_weight' => 'boolean',
        'can_apply' => 'boolean',
        'athlete_detail' => 'boolean',
        'athlete_edit' => 'boolean',
        'apply_tournament' => 'boolean',
        'can_apply_tournament' => 'boolean',
        'can_coach' => 'boolean',
        'can_referee' => 'boolean',
        'can_draw_sheet' => 'boolean',
        'can_create_tournament' => 'boolean',
    ];

    function admin_data(){
        return $this->belongsTo('App\Models\Admintype','admin_type');
    }
}
