<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Chat extends Model
{
    use HasFactory;
    protected $table = 'chats';
    protected $fillable = [
        'convenience_id',
        'chat_user_id',
        'from_id',
        'to_id',
        'replay_id',
        'message',
        'file',
        'file_type',
        'is_read'
    ];

    protected $appends =  ['date', 'time'];

    public function sender()
    {
        return $this->hasOne(User::class, 'id', 'from_id');
    }


    /*public function booking()
    {
        return $this->hasOne(Booking::class, 'id', 'tipe_id');
    }*/

    public function receiver()
    {
        return $this->hasOne(User::class, 'id', 'to_id');
    }

    public function getFileAttribute($value)
    {
        return  imageUrl($value, 'user');
    }

    public function getDateAttribute($value)
    {
        return date('Y-m-d', strtotime($this->created_at));
    }
    public function getTimeAttribute($value)
    {
        return $this->created_at;
    }

    public function getCreatedAtAttribute($value)
    {
        return date('Y-m-d H:i:s', strtotime($value));
        //return Helper::timeAgo($value);
    }

    public function getMessageAttribute($value)
    {
        return nl2br($value);
        //return date('Y-m-d H:i:s',strtotime($value));
        //return Helper::timeAgo($value);
    }
}
