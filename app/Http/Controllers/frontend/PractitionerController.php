<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;
use App\Models\UserDetail;
use App\Models\Course;
use App\Models\SessionBooking;
use App\Models\Wishlist;
use App\Models\Qualification; 
use App\Models\UserQualification;
use App\Models\VenderConsultPrice;
use App\Models\AriaIntrest;
use App\Models\Timezone;
use App\Models\Language;
use App\Models\Consult;
use App\Models\Specialities;
use App\Models\DoctorView;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Exception;
use Cache;
use Session;
use Illuminate\Support\Facades\Auth;
use Validator;
use Carbon\Carbon;

class PractitionerController extends Controller
{
    public function index(Request $request)
    {
        
        $category = Category::where('status', 1)->orderBy('title', 'ASC')->get();
        $language = Language::where('status', 1)->orderBy('language', 'ASC')->get();
        $specialities = Specialities::where('status', 1)->orderBy('title', 'ASC')->get();
        $aria = AriaIntrest::where('status', 1)->orderBy('title', 'ASC')->get();
        $experience = Qualification::where('status', 1)->orderBy('title', 'ASC')->get();
        $user_time_zone = User::whereNotNull('timezone')->where('role', 2)->groupBy('timezone')->pluck('timezone')->toArray();

        $follow_price = VenderConsultPrice::where('type', 2)->get()->toArray();
        $init_price = VenderConsultPrice::where('type', 1)->get()->toArray();

        // Get the maximum consult_price
        if (!empty($follow_price)) {
            $consult_prices = array_column($follow_price, 'consult_price');
            $follow_price_min = min($consult_prices);
            $follow_price_max = max($consult_prices);
        } else {
            $follow_price_min = 0;
            $follow_price_max = 0;
        }

        if (!empty($init_price)) {
            $initconsult_prices = array_column($init_price, 'consult_price');
            $init_price_min = min($initconsult_prices);
            $init_price_max = max($initconsult_prices);
        } else {
            $init_price_min = 0;
            $init_price_max = 0;
        }
        // p($max_consult_price);
        $timezone = Timezone::whereIn('timezone', $user_time_zone)->get();
        $apport = Consult::get();

        $user = User::with('get_consult', 'get_package', 'get_detail')
            ->where('status', 1)
            ->where('role', 2)
            ->where(function ($query) use ($request) {

                if (!empty($request['name'])) {
                    $query->where('name', 'LIKE', '%' . $request['name'] . '%');
                }
                if (!empty($request['name_tag'])) {
                    $aria_data = AriaIntrest::where('title', 'LIKE', '%' . $request['name_tag'] . '%')->pluck('id')->toArray();
                    $aria_data_string = implode(',', $aria_data);

                    // Use the string in the query
                    $user_ids = User::orWhereRaw("FIND_IN_SET('$aria_data_string', areas_of_intresres) > 0")->pluck('id')->toArray();
                    $spec_data = Specialities::where('title', 'LIKE', '%' . $request['name_tag'] . '%')->pluck('id')->toArray();
                    $aria_data_string1 = implode(',', $user_ids);
                    $spec_user_ids = User::orWhereRaw("FIND_IN_SET('$aria_data_string1',specialities ) > 0")->pluck('id')->toArray();


                    // p($aria_data);
                    $query->whereIn('id', $user_ids)->orWhere('id', $spec_user_ids);
                }
                if (!empty($request['tag'])) {

                    $tag = AriaIntrest::where('slug', $request['tag'])->first();

                    $query->orWhereRaw("FIND_IN_SET('$tag->id',areas_of_intresres ) > 0");
                }
                if (!empty($request['specialities'])) {

                    $tag = Specialities::where('slug', $request['specialities'])->first();

                    $query->orWhereRaw("FIND_IN_SET('$tag->id',specialities ) > 0");
                }
                if (!empty($request['aria_id'])) {

                    $query->orWhereRaw("FIND_IN_SET('$request->aria_id',areas_of_intresres) > 0");
                }
                if (!empty($request['language_id'])) {
                    $query->orWhereRaw("FIND_IN_SET('$request->language_id',language) > 0");
                }
                if (!empty($request['init_price'])) {

                    $vender = VenderConsultPrice::where('type', 1)->where('consult_price', '<=', $request['init_price'])->groupBy('vender_id')->pluck('vender_id')->toArray();

                    $query->whereIn('id', $vender);
                }

                if (!empty($request['follow_price'])) {

                    $vender = VenderConsultPrice::where('type', 2)->where('consult_price', '<=', $request['follow_price'])->groupBy('vender_id')->pluck('vender_id')->toArray();

                    $query->whereIn('id', $vender);
                }
            })
            ->where(function ($query) use ($request) {

                if (!empty($request['search'])) {

                    $query->whereHas('get_detail', function ($query2) use ($request) {
                        if (!empty($request['search'])) {
                            $query2->whereHas('get_category', function ($query21) use ($request) {
                                $query21->where('slug', 'LIKE', '%' . $request['search'] . '%');
                            });
                        }
                    })->orWhere('name', 'LIKE', '%' . $request['search'] . '%')
                    ->orWhere(function ($query3) use ($request) {
                        $aria = AriaIntrest::where('title', 'LIKE', '%' . $request['search'] . '%')->pluck('id')->toArray();
                        
                        $query3->where(function ($query11) use ($aria) {
                            foreach ($aria as $ariaid) {
                                $query11->orWhereRaw("FIND_IN_SET('$ariaid', areas_of_intresres) > 0");
                            }
                        });
                    })->orWhere(function ($query4) use ($request) {
                        $Specialities = Specialities::where('title', 'LIKE', '%' . $request['search'] . '%')->pluck('id')->toArray();
                        
                        $query4->where(function ($query22) use ($Specialities) {
                            foreach ($Specialities as $ariaid) {
                                $query22->orWhereRaw("FIND_IN_SET('$ariaid', areas_of_intresres) > 0");
                            }
                        });
                    });
                }
            })

            ->orderBy('id', 'DESC')
            ->get();

        $cat_name = Category::where('slug', $request['search'])->first();

        if (!empty($request['name'] || $request['search'] || $request['tag']) || $request['name_tag']) {
            $button = 'Clear';
            $search = isset($cat_name->search_title) ? $cat_name->search_title : $request['search'];
            $search = isset($search) ? $search : $request['name'];
            $search = isset($search) ? $search : $request['name_tag'];
        } else {
            $button = 'Search';
            $search = '';
        }
        // p($cat_name->search_title);
        $data = array(
            'title' => 'Practitioners',
            'user' => $user,
            'category' => $category,
            'specialities' => $specialities,
            'aria' => $aria,
            'button' => $button,
            'search' => $search,
            'cat_search' => isset($cat_name->search_title) ? $cat_name->search_title : '',
            'tag_search' => $request['tag'],
            'experience' => $experience,
            'timezone' => $timezone,
            'language' => $language,
            'init_price_min' => $init_price_min,
            'init_price_max' => $init_price_max,
            'follow_price_min' => $follow_price_min,
            'follow_price_max' => $follow_price_max,
            'apport' => $apport,
        );
        return view('frontend.practitioner_list')->with($data);
    }

    public function detail($id)
    {
        $user_p = auth()->guard('web')->user();
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $decrypted_id  = get_decrypted_value($id, true);
        $doctorview = DoctorView::where('vender_id', $decrypted_id)->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)->first();
        if (!empty($doctorview)) {
            $doctorview->count = $doctorview->count + 1;
            $doctorview->save();
        } else {
            $doctorview = new DoctorView;
            $doctorview->vender_id = $decrypted_id;
            $doctorview->count = 1;
            $doctorview->save();
        }

        $user = User::with('get_consult', 'get_availabilities', 'get_package')->find($decrypted_id);
        // p($user);
        
        $const_data = VenderConsultPrice::where('vender_id',$user->id)->get();
        


        $user_detail = UserDetail::with('get_quali')->where('doctor_id', $user->id)->first();
        $courses = Course::where('status', 1)->where('doctor_id', $decrypted_id)->get();
        $const = SessionBooking::where('vender_id', $user->id)->where('status', 'active')->count();
        $data = array(
            'title' => 'Practitioners',
            'user' => $user,
            'user_detail' => $user_detail,
            'courses' => $courses,
            'const' => $const,
            'const_data' => $const_data,
        );
        return view('frontend.practitioner_profle')->with($data);
    }

    public function filter(Request $request)
    {
        try {
            
            $user = User::where('status', 1)->where('role', 2)
                ->where(function ($query) use ($request) {

                    if (!empty($request['category_id'])) {
                        $user_detail = UserDetail::whereIn('category', $request['category_id'])->pluck('doctor_id')->toArray();
                        $query->whereIn('id', $user_detail);
                    }

                    if (!empty($request['qualifications_id'])) {
                        $user_detail = UserQualification::whereIn('qualification_id', $request['qualifications_id'])->pluck('vender_id')->toArray();
                        // p($user_detail);
                        $query->whereIn('id', $user_detail);
                    }

                    if (!empty($request['ratting_id'])) {
                        $query->where('ratting', '<=', $request['ratting_id']);
                    }

                    if (!empty($request['aria_id'])) {

                        
                        $aria_id = $request['aria_id'];
                        $query->where(function ($query) use ($aria_id) {
                            foreach ($aria_id as $ariaid) {
                                $query->orWhereRaw("FIND_IN_SET('$ariaid', areas_of_intresres) > 0");
                            }
                        });
                    }

                    if (!empty($request['language_id'])) {
                        
                        
                        $languageIds = $request['language_id'];
                        $query->where(function ($query) use ($languageIds) {
                            foreach ($languageIds as $languageId) {
                                $query->orWhereRaw("FIND_IN_SET('$languageId', language) > 0");
                            }
                        });
                    }

                    if (!empty($request['appointment_id'])) {
                        $user = VenderConsultPrice::whereIn('time', $request['appointment_id'])->pluck('vender_id')->toArray();
                        $query->whereIn('id', $user);
                    }

                    if (!empty($request['timezone_id'])) {
                        $query->whereIn('timezone', $request['timezone_id']);
                    }
                    if (!empty($request['specialities_id'])) {

                        foreach ($request['specialities_id'] as  $val) {
                            $query->whereRaw("FIND_IN_SET('$val',specialities ) > 0");
                        }
                    }
                    if (isset($request['experience_id']) && !empty($request['experience_id'])) {
                        $ranges = $request['experience_id'];
                        
                        // Initialize arrays to store min and max values
                        $minValues = [];
                        $maxValues = [];
                    
                        foreach ($ranges as $range) {
                            // Explode each range and get min value
                            $minMax = explode('-', $range);
                            $minValues[] = $minMax[0];
                            $maxValues[] = $minMax[1];
                        }
                    
                        // Check if arrays are not empty
                        if (!empty($minValues) && !empty($maxValues)) {
                            // Get overall min and max values
                            $overallMin = min($minValues);
                            $overallMax = max($maxValues);
                    
                            // Apply whereBetween clause
                            $query->whereBetween('experience', [$overallMin, $overallMax]);
                        }
                    }
                    
                    if (!empty($request['init_price'])) {
                        // p($request['init_price']);

                        $vender = VenderConsultPrice::where('type', 1)->where('consult_price', '<=', $request['init_price'])->groupBy('vender_id')->pluck('vender_id')->toArray();
                        
    
                        $query->whereIn('id', $vender);
                    }
    
                    if (!empty($request['follow_price'])) {
    
                        $vender = VenderConsultPrice::where('type', 2)->where('consult_price', '<=', $request['follow_price'])->groupBy('vender_id')->pluck('vender_id')->toArray();
    
                        $query->whereIn('id', $vender);
                    }
                    if (!empty($request['search'])) {
                        $query->whereHas('get_detail', function ($query) use ($request) {
                            if (!empty($request['search'])) {
                                $query->whereHas('get_category', function ($query2) use ($request) {

                                    $query2->where('slug', 'LIKE', '%' . $request['search'] . '%');
                                });
                            }
                        })->orWhere('name', 'LIKE', '%' . $request['search'] . '%');
                    }
                })
                ->orderBy('id', 'DESC')
                ->get();

            $data = "";
            if (count($user) > 0) {
                foreach ($user as $key => $user_data) {
                    $conslt = VenderConsultPrice::where('vender_id', $user_data->id)->count();
                    $price = VenderConsultPrice::where('vender_id', $user_data->id)->get()->toArray();
                    $initconsult_prices = array_column($price, 'consult_price');
                    if (count($price) > 0) {
                        $min = min($initconsult_prices);
                        $max = max($initconsult_prices);
                    } else {
                        $min = 0;
                        $max = 0;
                    }
                    $data .= '<div class="list-box">
                    <div class="profile-d1">
                        <div class="profile-di">
                        <a href="' . url('/practitioners-detail/' . get_encrypted_value($user_data->id, true)) . '">
                            <img src="' . (isset($user_data->profile) ? url($user_data->profile) : url('/public/noimage.png')) . '" alt="">
                            <h3>' . $user_data->name . ' ' . $user_data->last_name . ' <span>' . $user_data->category_name . '</span></h3>
                        </a>
                        </div>
                    </div>
                    <div class="price-limit-p1">
                        <div class="price-limit">';

                    if ($conslt > 1) {
                        $data .= '<p>from $' . (isset($min) ? $min : 0) . ' to $' . (isset($max) ? $max : 0) . ' </p>';
                    } else {
                        $data .= '<p>$' . (isset($min) ? $min : 0) . '</p>';
                    }
                    $data .= '</div>
                    </div>

                    <div class="heart-icon">
                        <span class="show_file_' . $user_data->id . '">';
                    if (Auth::user()) {
                        $wish_product = Wishlist::where(['user_id' => Auth::user()->id, 'doctor_id' => $user_data->id])->first();

                        if (empty($wish_product)) {
                            $data .= '<div class="like-dr" onclick="add_to_wishlist(' . $user_data->id . ')">
                            <img src="' . url('/public/frontend/assets/images/like-heart.svg') . '" alt="">
                        </div>';
                        } else {
                            $data .= '<div class="like-dr" onclick="remove_to_wishlist(' . $user_data->id . ')">
                            <img src="' . url('/public/frontend/assets/like.svg') . '" alt="">
                        </div>';
                        }
                    } else {
                        $data .= '<div class="like-dr" onclick="login_model()">
                            <img src= "' . url('/public/frontend/assets/images/like-heart.svg') . '" alt="">
                        </div>';
                    }
                    $data .= '</span>
                    </div>

                    <div class="like-icon">
                        <span>' . $user_data->total_review . '%<img src="' . url('/public/frontend/assets/images/like.svg') . '" class="ms-2" alt=""></span>
                    </div>
                    </div>';
                }
            } else {
                $data = 'Sorry, no practitioners match your selected filters. Please try reducing your filters.<br><a href="'.route('practitioners').'" class="btn-primary">Clear</a>';
            }

            $categ_data = "";
            if (!empty($request['category_id'])) {
                $cat = Category::whereIn('id', $request['category_id'])->where('status', 1)->get();

                if (!empty($cat) > 0) {
                    $categ_data .= '<div class="search-tags"><h4>Search Results:</h4>';
                    foreach ($cat as $key => $cat_data) {

                        $categ_data .= '<a class="tag_cat" onclick="toggle_Checkbox(this)" data-category="' . $cat_data->id . '" href="#" >' . $cat_data->search_title . ' <img class="ms-2" src="' . url('/public/frontend/') . '/assets/images/close-sr.svg" alt=""></a>';
                    }
                    $categ_data .= '<div>';
                }
            }



            return response()->json([
                'message' => 'data get successfully',
                'status' => 1,
                'data'  => $data,
                'categ_data'  => $categ_data,

            ], 200);
        } catch (\Exception $e) {
            return $e->getMessage();
            return response()->json(['message' => 'Something went wrong.', 'status' => 500, 'data' => ''], 200);
            exit;
        }
    }
}
