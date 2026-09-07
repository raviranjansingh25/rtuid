<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Convenien extends Model
{
    use HasFactory;


    protected $table = 'conveniencs';
    protected $fillable = [
        'ticket_id',
        'ticket_type',
        'last_message',
    ];

    public function getUpdatedAtAttribute($value)
    {
        return timeAgo($value);
    }

    public function chatuser()
    {
        return $this->hasMany(ChatUser::class, 'convenience_id', 'id');
    }

    public function chat()
    {
        return $this->hasMany(Chat::class, 'convenience_id', 'id')->where('is_read', 0);
    }

    public function getGroupImageAttribute($value)
    {
        return '';
    }
}
