<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Validator;

class WishlistController extends Controller
{
    public function add_to_wish(Request $request)
    {

        $user = Auth::user();
        $user_id = $user->id;
        $validator = Validator::make($request->all(), [
            'file_id'      => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => $validator->errors()->first()], 200);
        }
        try {
            $save = new Wishlist;
            $save->user_id = $user_id;
            $save->doctor_id = $request['file_id'];
            $save->save();

            // $remove_button = '<div class="like-ser" onclick="remove_to_wishlist(' . $request['file_id'] . ')">
            // 						<img src="' . url('/public/website//img/like-1.svg') . '" alt="">
            // 					</div>';
            $remove_button = '<div class="like-dr" onclick="remove_to_wishlist(' . $request['file_id'] . ')"><img src="' . url('/public/frontend/assets/like.svg') . '" alt=""></div>';
            if ($save) {
                return response()->json([
                    'message' => 'Wishlist add successfully',
                    'status' => 1,
                    'remove_button'  => $remove_button,
                    'id'  => $request['file_id'],
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Somthing went wrong',
                    'status' => 0,
                    'data'  => '',
                ], 200);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
            return response()->json(['message' => 'Something went wrong.', 'status' => 500, 'data' => ''], 200);
            exit;
        }
    }

    public function view_to_wish(Request $request)
    {

        try {
            $user = Auth::user();
            $user_id = $user->id;
            $view = Wishlist::with('get_doctor')->where('user_id', $user_id)->get();

            $data = array(
                'title' => 'Home',
                'view_wishlist' => $view,

            );
            return view('website.cart')->with($data);
        } catch (\Exception $e) {
            return $e->getMessage();
            return response()->json(['message' => 'Something went wrong.', 'status' => 500, 'data' => ''], 200);
            exit;
        }
    }

    public function delete_wish(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'file_id'   => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => $validator->errors()->first()], 200);
        }
        try {

            $data = Wishlist::where(['doctor_id' => $request['file_id'], 'user_id' => $user->id])->first();
            if (!empty($data)) {
                $data->delete();
                $remove_button = '<div class="like-dr" onclick="add_to_wishlist(' . $request['file_id'] . ')">
									<img src="' . url('/public/frontend/assets/images/like-heart.svg') . '" alt="">
								</div>';


                return response()->json([
                    'message' => 'Cart delete successfully',
                    'status' => 1,
                    'remove_button'  => $remove_button,
                    'id'  => $request['file_id'],
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Cart not found',
                    'status' => 0,
                ], 200);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
            return response()->json(['message' => 'Something went wrong.', 'status' => 0, 'data' => ''], 200);
            exit;
        }
    }
}
