<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Laravel\Sanctum\HasApiTokens;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $appends = ['category_name', 'language_name', 'total_review', 'qualification_name'];
    public function getTotalReviewAttribute()
    {
        $totalVotes = DoctorReview::where('doctor_id', $this->id)->count();
        $likes = DoctorReview::where('doctor_id', $this->id)->where('ratting', 1)->count();
        $total_dis = DoctorReview::where('doctor_id', $this->id)->where('ratting', 2)->count();
        if ($totalVotes > 0) {
            $averageRating = ($likes / $totalVotes) * 5; // Assuming the rating is on a scale of 5
            $formattedAverage = number_format($averageRating, 1); // Format to one decimal place
            return $formattedAverage;
        } else {
            return 0;
        }
    }

    public function getCategoryNameAttribute()
    {
        $name = UserDetail::where('doctor_id', $this->id)->first();
        if (!empty($name)) {
            $cat = Category::find($name->category);
            if (!empty($cat)) {
                return $cat->name;
            }
            return 'N/A';
        } else {
            return 'N/A';
        }
    }

    public function getQualificationNameAttribute()
    {

        $name = UserDetail::where('doctor_id', $this->id)->first();
        if (!empty($name)) {
            $cat = Qualification::find($name->qualification);

            if (!empty($cat)) {
                return $cat->title;
            }
            return 'N/A';
        } else {
            return 'N/A';
        }
    }

    public function getLanguageNameAttribute()
    {

        $lan = Language::find($this->language);
        if (!empty($lan)) {
            return $lan->language;
        } else {
            return 'N/A';
        }
    }


    // public function getCountryCode1Attribute()
    // {
    //     $name = Country::find($this->country_code);
    //     if (!empty($name)) {
    //         return '+' . $name->phonecode;
    //     } else {
    //         return 'N/A';
    //     }
    // }



    function get_consult()
    {
        return $this->hasMany('App\Models\VenderConsultPrice', 'vender_id');
    }

    function get_package()
    {
        return $this->hasMany('App\Models\VenderPackagePrice', 'vender_id');
    }

    function get_detail()
    {
        return $this->hasOne('App\Models\UserDetail', 'doctor_id', 'id')->with('get_category');
    }



    protected $fillable = [
        'name',
        'email',
        'password',
        'mobile',
        'category',
        'collection',
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
    ];
}
