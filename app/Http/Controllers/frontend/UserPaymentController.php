<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CourseBooking;
use App\Models\SessionBooking;
use App\Models\PaymentHistory;
use App\Models\DoctorAvailability;
use App\Models\VenderConsultPrice;
use App\Models\Course;
use App\Models\Coupon;
use App\Models\ConsultForm;
use App\Models\VenderPackagePrice;
use App\Models\EmailPage;
use App\Models\PackageBooking;
use Carbon\Carbon;
use Session;
use Illuminate\Support\Facades\Auth;
use App\Mail\BookingMail;
use Mail;

class UserPaymentController extends Controller
{
    public function chek_discount(Request $request){
        try{
            if(empty($request['discount_code'])){
                return response()->json([
                    'message' => 'Coupon code avalable!',
                    'status' => 1
                ]);
            }
            $coupondata = Coupon::where('coupon_code',$request['discount_code'])->first();
            // p($coupondata);
            if (!empty($coupondata)) {
                $currentDate = Carbon::now();
                $startDate = Carbon::parse($coupondata->start_date);
                $endDate = Carbon::parse($coupondata->end_date);
                if ($currentDate >= $startDate && $currentDate <= $endDate) {
                    return response()->json([
                        'message' => 'Coupon code avalable!',
                        'status' => 1
                    ]);
                }else{
                    return response()->json([
                        'message' => 'Coupon code not avalable!',
                        'status' => 0
                    ]);
                }
            }else{
                return response()->json([
                    'message' => 'Invalid Coupon Code',
                    'status' => 0
                ]);
            }
        }catch (\Exception $e) {
            $error_message = $e->getMessage();
            // p($error_message);
            return response()->json([
                'message' => $error_message,
                'status' => '0'
            ]);
            exit;
        }
    }
    public function paylink(Request $request)
    {
        
        $product = $this->CreateProduct('course');
        $price = $this->CreatePrice($product['id'], number_format($request->final_amount,2));
        $link = $this->CrearteLink($price['id'], $request['course_id']);
        return redirect($link['url']);
    }

    private function CreateProduct($productname)
    {

        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $product = $stripe->products->create([
            'name' => 'Course'
        ]);
        return $product;
    }
    private function CreatePrice($productId, $productPrice)
    {

        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $price = $stripe->prices->create([
            'unit_amount' => 100 * $productPrice,
            'currency' => 'AUD',
            'product' => $productId,
        ]);

        return $price;
    }

    private function CrearteLink($price, $product)
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $link = $stripe->paymentLinks->create([

            'line_items' => [
                [
                    'price' => $price,
                    'quantity' => 1,
                ],
            ],
            'after_completion' => [
                'type' => 'redirect',
                'redirect' => ['url' => route('buy_now', ['product_id' => $product])],
            ],

        ]);



        return $link;
    }
    public function buy_now(Request $request)
    {
        $user = Auth::user();
        $file = Course::find($request['product_id']);


        if (!empty($request['product_id'])) {
            $tax = ($file->price*2.5)/100;
            $rand = rand(1111, 9999);
            $purchage                    = new CourseBooking;
            $purchage->user_id           = $user->id;
            $purchage->course_id           = $file->id;
            $purchage->course_price             = number_format($file->price+$tax,2);
            $purchage->course_name             = $file->title;
            $purchage->tran_id     = '';
            $purchage->status = 1;
            $purchage->save();
            $purchage->booking_id       = 'TIMED' . $purchage->id . $rand;
            $purchage->save();


            // $firebaseTokens  = User::where('id', $file->user_id)->whereNotNull('users.device_token')->pluck('users.device_token')->all();
            // if ($firebaseTokens) {
            //     $not_user = User::find($file->user_id);
            //     $notification_msg = isset($file->file_type) ? $file->file_type == 1 ? $not_user->name . 'purchase your video' : $not_user->name . 'purchase your music' : '';
            //     $send_token = apiNotificationForApp($firebaseTokens, $notification_msg, null, 'Purchage');
            // }

            // $not = new Notifications;
            // $not->message = isset($file->file_type) ? $file->file_type == 1 ? 'purchase your video' : 'purchase your music' : '';
            // $not->reciver_userId = $file->user_id;
            // $not->sender_userId = $user->id;
            // $not->save();



            $transfer_payment                = new PaymentHistory;
            $transfer_payment->vender_id     = $file->doctor_id;
            $transfer_payment->course_booking_id     = $purchage->id;
            $transfer_payment->user_id       = $user->id;
            $transfer_payment->course_id     = $purchage->course_id;
            $transfer_payment->course_name   = $purchage->course_name;
            $transfer_payment->price         = $purchage->course_price;
            $transfer_payment->tran_id       = '';
            $transfer_payment->status        = 'success';
            $transfer_payment->save();

            // createThread(['ticket_type' => 'single_chat', 'ticket_id' => 0, 'from_id' => $user->id, 'to_id' => $file->doctor_id ?? 0, 'type' => 'SINGLE', 'group_name' => '']);

            // $docs = SessionBooking::where('user_id', $user->id)->where('vender_id', '!=', $file->doctor_id)->where('status', 'active')->groupBy('vender_id')->get();
            // foreach ($docs as  $docs_data) {
            //     createThread(['ticket_type' => 'single_chat', 'ticket_id' => 0, 'from_id' => $file->doctor_id, 'to_id' => $docs_data->vender_id ?? 0, 'type' => 'SINGLE', 'group_name' => '']);
            // }

            // $resp = $this->hostHandAmountTransfer($purchage->id);


            // $firebaseTokens  = User::where('id', $user->id)->whereNotNull('users.device_token')->pluck('users.device_token')->all();
            // if ($firebaseTokens) {
            //     $notification_msg = 'Purchage Successfully';
            //     $send_token = apiNotificationForApp($firebaseTokens, $notification_msg, null, 'Purchage');
            // }


            $page = EmailPage::find(14);
            $mailData = [
                'user' => $user->name,
                'message' => $page->description,
                'subject' => 'Course Purchase Invoice'
            ];
            Mail::to($user->email)->send(new BookingMail($mailData));

            $pre = User::find($file->doctor_id);
            $page = EmailPage::find(13);
            $mailData = [
                'user' => $pre->name,
                'message' => $page->description,
                'subject' => 'Course/Program Purchase'
            ];
            Mail::to($pre->email)->send(new BookingMail($mailData));

            $success_msg = 'Purchage Successfully.';
            return redirect('/programs-detail/' . $file->slug)->withSuccess($success_msg);
            exit;
        } else {
            return response()->json(['status' => 2, 'message' => 'Payment failed'], 400);
        }
    }

    public function paylinksession(Request $request)
    {

        $user = auth()->guard('web')->user();
        $book_date = $request['date'] . ' ' . $request['month'];
        $carbonDate = Carbon::createFromFormat('d F Y', $book_date);

        $dateTime = Carbon::parse($request->start_time);
        $start_timeFormat = $dateTime->format('H:i');
        // Format the date in Y-m-d format
        $formattedDate = $carbonDate->format('Y-m-d');

        // p($request->all());
        $session = DoctorAvailability::find($request['session_id']);
        // p($session);
        $vender_price = VenderConsultPrice::where('vender_id', $session->vender_id)->pluck('time')->toArray();


        // Create Carbon objects for each time
        $carbonTime1 = Carbon::createFromFormat('H:i', $start_timeFormat);
        $carbonTime2 = Carbon::createFromFormat('H:i', $request->end_time);

        // Calculate the difference in minutes
        $minutesDifference = $carbonTime2->diffInMinutes($carbonTime1);

        $closestValue = null;
        $closestDifference = PHP_INT_MAX;

        // Iterate through the array
        foreach ($vender_price as $time) {
            // Calculate the absolute difference between the target and the current value
            $difference = abs($minutesDifference - $time);

            // Check if the current value is closer than the previous closest value
            if ($difference < $closestDifference) {
                $closestValue = $time;
                $closestDifference = $difference;
            }
        }

        $lan = SessionBooking::where('vender_id', $session->vender_id)->where('status','active')->where('user_id', $user->id)->count();
        if ($lan > 0) {
            $sessionprice = VenderConsultPrice::where('type', 2)->where('vender_id', $session->vender_id)->where('time', $closestValue)->first();
            if (empty($sessionprice)) {
                $sessionprice = VenderConsultPrice::where('type', 1)->where('vender_id', $session->vender_id)->where('time', $closestValue)->first();
            }
        } else {
            $sessionprice = VenderConsultPrice::where('type', 1)->where('vender_id', $session->vender_id)->where('time', $closestValue)->first();
        }

        if($request['discount_code']){
            $coupondata = Coupon::where('coupon_code',$request['discount_code'])->first();
            $remain_amt = $coupondata->discount*(int)$sessionprice->consult_price/100;
            $cutprice = number_format($remain_amt, 2);
            $sessionprice->consult_price = $sessionprice->consult_price-$cutprice;
        }

        $tax = 1.8*$sessionprice->consult_price/100;
        $sessionprice->consult_price = $sessionprice->consult_price+$tax;
        // p($sessionprice->consult_price);

        $product = $this->CreateProductsession('course');
        $price = $this->CreatePricesession($product['id'], $sessionprice->consult_price);
        $link = $this->CrearteLinksession($price['id'], $session->vender_id, $session->id, $formattedDate, $sessionprice->id, $start_timeFormat, $request->end_time);
        return redirect($link['url']);
    }

    private function CreateProductsession($productname)
    {

        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $product = $stripe->products->create([
            'name' => 'Session'
        ]);
        return $product;
    }
    private function CreatePricesession($productId, $productPrice)
    {
        $unitAmount = round($productPrice * 100);
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $price = $stripe->prices->create([
            'unit_amount' => $unitAmount,
            'currency' => 'AUD',
            'product' => $productId,
        ]);

        return $price;
    }

    private function CrearteLinksession($price, $vender, $session_id, $booking_date, $const_price, $start_time, $end_time)
    {
        
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
    
        $session = $stripe->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price' => $price,
                    'quantity' => 1,
                ],
            ],
            
            'mode' => 'payment',
            
            'success_url' => route('buy_nowsession', [
            'vender_id' => $vender,
            'session_id' => $session_id,
            'booking_date' => $booking_date,
            'const_price' => $const_price,
            'start_time' => $start_time,
            'end_time' => $end_time,
        ]) . '&check_session_id={CHECKOUT_SESSION_ID}',
            
        ]);

        return $session;
    }
    public function buy_nowsession(Request $request)
    {

        $sessionId = $request->check_session_id;
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $session = $stripe->checkout->sessions->retrieve($sessionId, [
            'expand' => ['payment_intent', 'customer'],
        ]);
        
        $paymentIntentId = $session->payment_intent->id;
        $user = Auth::user();

        $vender = User::find($request['vender_id']);
        $ava = DoctorAvailability::find($request['session_id']);
        $const_price = VenderConsultPrice::find($request['const_price']);
        // p($request['const_price']);



        if (!empty($request['vender_id'])) {

            $purchage                    = new SessionBooking;
            $purchage->user_id           = $user->id;
            $purchage->vender_id         = $vender->id;
            $purchage->session_id        = $ava->id;
            $purchage->booking_date      = $request['booking_date'];
            $purchage->session_name      = $const_price->consult_name;
            $purchage->duration          = $const_price->time;
            $purchage->session_price     = $const_price->consult_price;
            $purchage->doctor_name       = $vender->name;
            $purchage->start_time        = $request->start_time;
            $purchage->end_time          = $request->end_time;
            $purchage->status            = 'active';
            $purchage->save();

            // $firebaseTokens  = User::where('id', $file->user_id)->whereNotNull('users.device_token')->pluck('users.device_token')->all();
            // if ($firebaseTokens) {
            //     $not_user = User::find($file->user_id);
            //     $notification_msg = isset($file->file_type) ? $file->file_type == 1 ? $not_user->name . 'purchase your video' : $not_user->name . 'purchase your music' : '';
            //     $send_token = apiNotificationForApp($firebaseTokens, $notification_msg, null, 'Purchage');
            // }

            // $not = new Notifications;
            // $not->message = isset($file->file_type) ? $file->file_type == 1 ? 'purchase your video' : 'purchase your music' : '';
            // $not->reciver_userId = $file->user_id;
            // $not->sender_userId = $user->id;
            // $not->save();



            $transfer_payment                = new PaymentHistory;
            $transfer_payment->vender_id     = $purchage->vender_id;
            $transfer_payment->user_id       = $user->id;
            $transfer_payment->session_booking_id       = $purchage->id;
            $transfer_payment->session_id     = $purchage->session_id;
            $transfer_payment->course_name   = $purchage->session_name;
            $transfer_payment->price         = $purchage->session_price;
            $transfer_payment->tran_id       = $paymentIntentId;
            $transfer_payment->status        = 'success';
            $transfer_payment->save();

            createThread(['ticket_type' => 'single_chat', 'ticket_id' => 0, 'from_id' => $user->id, 'to_id' => $purchage->vender_id ?? 0, 'type' => 'SINGLE', 'group_name' => '','booking_date_time' => $request['booking_date'].' '.$request->start_time]);


            $docs = SessionBooking::where('user_id', $user->id)->where('vender_id', '!=', $request['vender_id'])->where('status', 'active')->groupBy('vender_id')->get();
            foreach ($docs as  $docs_data) {
                createThread(['ticket_type' => 'single_chat', 'ticket_id' => 0, 'from_id' => $request['vender_id'], 'to_id' => $docs_data->vender_id ?? 0, 'type' => 'SINGLE', 'group_name' => '','booking_date_time' => $request['booking_date'].' '.$request->start_time]);
            }

            // $resp = $this->hostHandAmountTransfer($purchage->id);


            // $firebaseTokens  = User::where('id', $user->id)->whereNotNull('users.device_token')->pluck('users.device_token')->all();
            // if ($firebaseTokens) {
            //     $notification_msg = 'Purchage Successfully';
            //     $send_token = apiNotificationForApp($firebaseTokens, $notification_msg, null, 'Purchage');
            // }
            $lan = SessionBooking::where('vender_id', $purchage->vender_id)->where('status','active')->where('user_id', $user->id)->count();

            $page = EmailPage::find(15);
            $mailData = [
                'user' => $user->name,
                'message' => str_replace('[BOOKING_DATE]', $request->start_time . ' ' . $request['booking_date'], $page->description),
                'subject' => 'Booking confirmation with Invoice receipt'
            ];
            // Mail::to($user->email)->send(new BookingMail($mailData));

            $pre = User::find($vender->id);
            $page = EmailPage::find(16);
            $mailData = [
                'user' => $pre->name,
                'message' => str_replace('[BOOKING_DATE]', $request->start_time . ' ' . $request['booking_date'], $page->description),
                'subject' => 'Booking Confirmation'
            ];
            // Mail::to($pre->email)->send(new BookingMail($mailData));

            $success_msg = 'Purchage Successfully.';
            if($lan>1){
                return redirect()->route('user_dashboard')->withSuccess($success_msg);
            }else{
                return redirect()->route('preconsult_form', ['sec_id' => $purchage->id, 'vender_id' => $purchage->vender_id])->withSuccess($success_msg);
            }
            
            exit;
        } else {
            return response()->json(['status' => 2, 'message' => 'Payment failed'], 400);
        }
    }


    public function buy_package_session(Request $request)
    {
        // p($request->all());
        $user = auth()->guard('web')->user();

        $book_date = $request['date'] . ' ' . $request['month'];
        $carbonDate = Carbon::createFromFormat('d F Y', $book_date);

        $dateTime = Carbon::parse($request->start_time);
        $start_timeFormat = $dateTime->format('H:i');
        // Format the date in Y-m-d format
        $formattedDate = $carbonDate->format('Y-m-d');

        // p($request->all());
        $session = DoctorAvailability::find($request['session_id']);
        $vender_price = VenderConsultPrice::where('vender_id', $session->vender_id)->pluck('time')->toArray();
        // p($vender_price);
        $vender = User::find($session->vender_id);

        // Create Carbon objects for each time
        $carbonTime1 = Carbon::createFromFormat('H:i', $start_timeFormat);
        $carbonTime2 = Carbon::createFromFormat('H:i', $request->end_time);
        // p($carbonTime1);
        // Calculate the difference in minutes
        $minutesDifference = $carbonTime2->diffInMinutes($carbonTime1);

        $closestValue = null;
        $closestDifference = PHP_INT_MAX;

        // Iterate through the array
        foreach ($vender_price as $time) {
            // Calculate the absolute difference between the target and the current value
            $difference = abs($minutesDifference - $time);

            // Check if the current value is closer than the previous closest value
            if ($difference < $closestDifference) {
                $closestValue = $time;
                $closestDifference = $difference;
            }
        }

        $lan = SessionBooking::where('vender_id', $session->vender_id)->where('user_id', $user->id)->count();

        if ($lan > 0) {
            $sessionprice = VenderConsultPrice::where('type', 2)->where('vender_id', $session->vender_id)->where('time', $closestValue)->first();
            if (empty($sessionprice)) {
                $sessionprice = VenderConsultPrice::where('type', 1)->where('vender_id', $session->vender_id)->where('time', $closestValue)->first();
            }
        } else {
            $sessionprice = VenderConsultPrice::where('type', 1)->where('vender_id', $session->vender_id)->where('time', $closestValue)->first();
        }

        if (!empty($sessionprice)) {

            $purchage                    = new SessionBooking;
            $purchage->user_id           = $user->id;
            $purchage->vender_id         = $session->vender_id;
            $purchage->session_id        = $request['session_id'];
            $purchage->booking_date      = $formattedDate;
            $purchage->session_name      = $sessionprice->consult_name;
            $purchage->duration          = $sessionprice->time;
            $purchage->package          = 1;
            $purchage->package_id          = $request->package_id;
            $purchage->session_price     = $sessionprice->consult_price;
            $purchage->doctor_name       = $vender->name;
            $purchage->start_time        = $request->start_time;
            $purchage->end_time          = $request->end_time;
            $purchage->status            = 'active';
            $purchage->save();


            $package                    = PackageBooking::find($request->package_id);
            $package->remaining_package = $package->remaining_package - 1;
            $package->save();




            // $firebaseTokens  = User::where('id', $file->user_id)->whereNotNull('users.device_token')->pluck('users.device_token')->all();
            // if ($firebaseTokens) {
            //     $not_user = User::find($file->user_id);
            //     $notification_msg = isset($file->file_type) ? $file->file_type == 1 ? $not_user->name . 'purchase your video' : $not_user->name . 'purchase your music' : '';
            //     $send_token = apiNotificationForApp($firebaseTokens, $notification_msg, null, 'Purchage');
            // }

            // $not = new Notifications;
            // $not->message = isset($file->file_type) ? $file->file_type == 1 ? 'purchase your video' : 'purchase your music' : '';
            // $not->reciver_userId = $file->user_id;
            // $not->sender_userId = $user->id;
            // $not->save();


            // p($purchage);
            $transfer_payment                = new PaymentHistory;
            $transfer_payment->vender_id     = $purchage->vender_id;
            $transfer_payment->user_id       = $user->id;
            $transfer_payment->session_id     = $purchage->session_id;
            $transfer_payment->session_name   = $purchage->session_name;
            $transfer_payment->price         = $purchage->session_price;
            $transfer_payment->tran_id       = '';
            $transfer_payment->status        = 'success';
            $transfer_payment->save();

            createThread(['ticket_type' => 'single_chat', 'ticket_id' => 0, 'from_id' => $user->id, 'to_id' => $purchage->vender_id ?? 0, 'type' => 'SINGLE', 'group_name' => '','booking_date_time' => $formattedDate.' '.$request->start_time]);

            $docs = SessionBooking::where('user_id', $user->id)->where('vender_id', '!=', $purchage->vender_id)->where('status', 'active')->groupBy('vender_id')->get();
            foreach ($docs as  $docs_data) {
                createThread(['ticket_type' => 'single_chat', 'ticket_id' => 0, 'from_id' => $purchage->vender_id, 'to_id' => $docs_data->vender_id ?? 0, 'type' => 'SINGLE', 'group_name' => '' ,'booking_date_time' => $formattedDate.' '.$request->start_time]);
            }

            // $resp = $this->hostHandAmountTransfer($purchage->id);


            // $firebaseTokens  = User::where('id', $user->id)->whereNotNull('users.device_token')->pluck('users.device_token')->all();
            // if ($firebaseTokens) {
            //     $notification_msg = 'Purchage Successfully';
            //     $send_token = apiNotificationForApp($firebaseTokens, $notification_msg, null, 'Purchage');
            // }

            $success_msg = 'Purchage Successfully.';
            return redirect()->route('preconsult_form', ['sec_id' => $purchage->id, 'vender_id' => $purchage->vender_id])->withSuccess($success_msg);
            exit;
        } else {
            return response()->json(['status' => 2, 'message' => 'Payment failed'], 400);
        }
    }


    //package booking
    public function paylinkpackage(Request $request)
    {
        $session = VenderPackagePrice::find($request['package_id']);
        $product = $this->CreateProductpackage('course');
        $price = $this->CreatePricepackage($product['id'], (int)$session->price);
        $link = $this->CrearteLinkpackage($price['id'], $request['package_id']);
        return redirect($link['url']);
    }

    private function CreateProductpackage($productname)
    {

        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $product = $stripe->products->create([
            'name' => 'Course'
        ]);
        return $product;
    }
    private function CreatePricepackage($productId, $productPrice)
    {

        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $price = $stripe->prices->create([
            'unit_amount' => 100 * $productPrice,
            'currency' => 'AUD',
            'product' => $productId,
        ]);

        return $price;
    }

    private function CrearteLinkpackage($price, $product)
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $link = $stripe->paymentLinks->create([

            'line_items' => [
                [
                    'price' => $price,
                    'quantity' => 1,
                ],
            ],
            'after_completion' => [
                'type' => 'redirect',
                'redirect' => ['url' => route('buy_nowpackage', ['product_id' => $product])],
            ],

        ]);



        return $link;
    }
    public function buy_nowpackage(Request $request)
    {
        $user = Auth::user();
        $file = VenderPackagePrice::find($request['product_id']);
        $doc = User::find($file->vender_id);

        if (!empty($request['product_id'])) {

            $rand = rand(1111, 9999);
            $purchage                    = new PackageBooking;
            $purchage->user_id           = $user->id;
            $purchage->vender_id         = $file->vender_id;
            $purchage->package_id        = $file->id;
            $purchage->package_name      = $file->title;
            $purchage->package_price     = $file->price;
            $purchage->no_of_conslt      = $file->time;
            $purchage->remaining_package = $file->time;
            $purchage->doctor_name       = $doc->name;
            $purchage->save();
            $purchage->booking_id        = 'TIMEDP' . $purchage->id . $rand;
            $purchage->save();


            // $firebaseTokens  = User::where('id', $file->user_id)->whereNotNull('users.device_token')->pluck('users.device_token')->all();
            // if ($firebaseTokens) {
            //     $not_user = User::find($file->user_id);
            //     $notification_msg = isset($file->file_type) ? $file->file_type == 1 ? $not_user->name . 'purchase your video' : $not_user->name . 'purchase your music' : '';
            //     $send_token = apiNotificationForApp($firebaseTokens, $notification_msg, null, 'Purchage');
            // }

            // $not = new Notifications;
            // $not->message = isset($file->file_type) ? $file->file_type == 1 ? 'purchase your video' : 'purchase your music' : '';
            // $not->reciver_userId = $file->user_id;
            // $not->sender_userId = $user->id;
            // $not->save();



            $transfer_payment                = new PaymentHistory;
            $transfer_payment->vender_id     = $file->vender_id;
            $transfer_payment->user_id       = $user->id;
            $transfer_payment->course_id     = $purchage->package_id;
            $transfer_payment->course_name   = $purchage->package_name;
            $transfer_payment->price         = $purchage->package_price;
            $transfer_payment->tran_id       = '';
            $transfer_payment->status        = 'success';
            $transfer_payment->save();

            // createThread(['ticket_type' => 'single_chat', 'ticket_id' => 0, 'from_id' => $user->id, 'to_id' => $file->vender_id ?? 0, 'type' => 'SINGLE', 'group_name' => '']);

            // $docs = SessionBooking::where('user_id', $user->id)->where('vender_id', '!=', $file->vender_id)->where('status', 'active')->groupBy('vender_id')->get();
            // foreach ($docs as  $docs_data) {
            //     createThread(['ticket_type' => 'single_chat', 'ticket_id' => 0, 'from_id' => $file->vender_id, 'to_id' => $docs_data->vender_id ?? 0, 'type' => 'SINGLE', 'group_name' => '']);
            // }

            // $resp = $this->hostHandAmountTransfer($purchage->id);


            // $firebaseTokens  = User::where('id', $user->id)->whereNotNull('users.device_token')->pluck('users.device_token')->all();
            // if ($firebaseTokens) {
            //     $notification_msg = 'Purchage Successfully';
            //     $send_token = apiNotificationForApp($firebaseTokens, $notification_msg, null, 'Purchage');
            // }

            $success_msg = 'Purchage Successfully.';
            return redirect()->route('my_sessions')->withSuccess($success_msg);
            exit;
        } else {
            return response()->json(['status' => 2, 'message' => 'Payment failed'], 400);
        }
    }
}
