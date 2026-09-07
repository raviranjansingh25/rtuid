<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;
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

    protected $appends = ['category_name', 'waight_category', 'waight_category_name', 'language_name', 'total_review', 'user_id','user_timezone','district_name','user_gender'];


    public function getUserIdAttribute(){
        // p($this->id);
        return $this->id;
        
    }
    
    public function getUserGenderAttribute()
    {
        

        if ($this->gender == 1) {
            return 'Male';
        }else if($this->gender == 2) {
            return 'Female';
        } else {
            return 'Other';
        }
    } 
    
     public function getCategoryAttribute()
    {
        if ($this->dob) {
            
            // $age = Carbon::parse($this->dob)->age;
            $age = Carbon::now()->year - Carbon::parse($this->dob)->year;
            $category = Category::where('min','<=',$age)->where('max','>=',$age)->where('status',1)->first();
            return $category ? $category->id : null;
        }
        return null;
    }

    public function getWaightCategoryAttribute()
    {
        if ($this->waight) {
            $category = WeightCategory::where('min', '<=', $this->weight)
                ->where('max', '>', $this->weight)
                ->where('gender',$this->gender)
                ->where('category',$this->category)
                ->where('status', 1)
                ->first();

            return $category ? $category->id : null;
        }

        return null;
    }

    public function getWaightCategoryNameAttribute()
    {
        if ($this->weight) {
            $category = WeightCategory::where('min', '<=', $this->weight)
                ->where('max', '>', $this->weight)
                ->where('gender',$this->gender)
                ->where('category',$this->category)
                ->where('status', 1)
                ->first();

            // p($category);

            return $category ? $category->title : null;
        }

        return null;
    }
    

    public function getDistrictNameAttribute()
    {
        $timezone = Tags::find($this->district);
        if (!empty($timezone)) {
            return $timezone->title;
        } else {
            return $this->district;
        }
        
    }
    
    public function getUserTimezoneAttribute()
    {
        $timezone = Timezone::find($this->timezone);
        if (!empty($timezone)) {
            return $timezone->timezone;
        } else {
            return $this->timezone;
        }
        
    }

    public function getTotalReviewAttribute()
    {
        $totalVotes = DoctorReview::where('doctor_id', $this->id)->count();
        
        $likes = DoctorReview::where('doctor_id', $this->id)->where('ratting', 1)->count();
        $total_dis = DoctorReview::where('doctor_id', $this->id)->where('ratting', 2)->count();
        $totalVotes = $totalVotes+5;
        $likes =  $likes+5;
        if ($totalVotes > 0) {
            $averageRating = ($likes / $totalVotes) * 100; // Assuming the rating is on a scale of 5
            // $formattedAverage = number_format($averageRating, 1); // Format to one decimal place
            return $averageRating;
        } else {
            return 0;
        }
    }

    public function getCategoryNameAttribute()
    {
        if ($this->dob) {
            
            // $age = Carbon::parse($this->dob)->age;
            $age = Carbon::now()->year - Carbon::parse($this->dob)->year;
            
            $category = Category::where('min','<=',$age)->where('max','>=',$age)->where('status',1)->first();
            return $category ? $category->title : null;
        }
        return null;
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

   
    

    function get_dist()
    {
        return $this->belongsTo('App\Models\Tags', 'district');
    }





    protected $fillable = [
        'name',
        'middle_name',
        'last_name',
        'email',
        'role',
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
