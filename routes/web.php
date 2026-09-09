<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;


use App\Http\Controllers\admin\auth\LoginController;
use App\Http\Controllers\admin\dashboardController;
use App\Http\Controllers\admin\SettingController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\WeightCategoryController;
use App\Http\Controllers\admin\ReviewController;

use App\Http\Controllers\admin\EnqueryController;
use App\Http\Controllers\admin\FaqController;
use App\Http\Controllers\admin\TestimonialController;
use App\Http\Controllers\admin\GalleryController;
use App\Http\Controllers\admin\SubadminController;
use App\Http\Controllers\admin\PermissionController;
use App\Http\Controllers\admin\ShoperController;
use App\Http\Controllers\admin\CoachController;

use App\Http\Controllers\admin\RefereeController;
use App\Http\Controllers\admin\EventController;
use App\Http\Controllers\admin\EventCategoryController;
use App\Http\Controllers\admin\TournamentController;

use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\PageController;
use App\Http\Controllers\admin\BannerController;
use App\Http\Controllers\admin\DrawSheetController;
use App\Http\Controllers\admin\TagController;
use App\Http\Controllers\admin\SpecialitiesController;
use App\Http\Controllers\MPESAController;
use App\Http\Controllers\admin\RevenueController;
use App\Http\Controllers\admin\ApplyTournamentController;
use App\Http\Controllers\admin\OtherCardEventController as AdminCardController;

use App\Http\Controllers\coach\auth\LoginController as CoachLoginController;
use App\Http\Controllers\coach\dashboardController as CoachDashboardController;
use App\Http\Controllers\coach\UserController as CoachUserController;
use App\Http\Controllers\coach\ApplyUserController;
use App\Http\Controllers\coach\ApplyTournamentController as CoachApplyTournamentController;
use App\Http\Controllers\coach\OtherCardEventController as CoachCardController;

use App\Http\Controllers\referee\auth\LoginController as RefereeLoginController;
use App\Http\Controllers\referee\dashboardController as RefereeDashboardController;
use App\Http\Controllers\referee\UserController as RefereeUserController;
use App\Http\Controllers\referee\ApplyUserController as RefereeApplyUserController;
use App\Http\Controllers\referee\ApplyTournamentController as RefereeApplyTournamentController;
use App\Http\Controllers\referee\OtherCardEventController as RefereeCardController;

use App\Http\Controllers\subadmin\auth\LoginController as SubadminLoginController;
use App\Http\Controllers\subadmin\dashboardController as SubadminDashboardController;
use App\Http\Controllers\subadmin\UserController as SubadminUserController;
use App\Http\Controllers\subadmin\ApplyUserController as SubadminApplyUserController;
use App\Http\Controllers\subadmin\ApplyTournamentController as SubadminApplyTournamentController;
use App\Http\Controllers\subadmin\CoachController as SubadminCoachController;
use App\Http\Controllers\subadmin\RefereeController as SubadminRefereeController;
use App\Http\Controllers\subadmin\DrawSheetController as SubadminDrawSheetController;
use App\Http\Controllers\subadmin\TournamentController as SubadminTournamentController;
use App\Http\Controllers\subadmin\OtherCardEventController as SubadminCardController;

use App\Http\Controllers\frontend\HomeController;
use App\Http\Controllers\frontend\VenderController;
use App\Http\Controllers\frontend\WebPageController;
use App\Http\Controllers\frontend\UserController as WebUserController;

use App\Http\Controllers\other\OtherEventController;
use App\Http\Controllers\other\OtherCardEventController;
use App\Http\Controllers\other\auth\LoginController as ShoperLoginController;
use App\Http\Controllers\other\dashboardController as ShoperDashboardController;
use App\Http\Controllers\other\ApplyTournamentController as ShoperApplyTournamentController;


// use App\Http\Controllers\frontend\BookingController;
// use App\Http\Controllers\frontend\PackageController as FrontPackageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/modalities', [HomeController::class, 'category_detail'])->name('category_detail');
Route::any('/gallery', [HomeController::class, 'program'])->name('program');
// Route::get('/programs-detail/{id}', [HomeController::class, 'program_detail']);
Route::any('/newsletter', [HomeController::class, 'newsletter'])->name('newsletter');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::any('/contact-enquery', [HomeController::class, 'contact_enquery'])->name('contact_enquery');

Route::get('/about-us', [WebPageController::class, 'about'])->name('about');
Route::get('/faq', [WebPageController::class, 'web_faq'])->name('web_faq');
Route::get('/register', [HomeController::class, 'register'])->name('register');

Route::get('/privacy-policy', [WebPageController::class, 'privacy_policy'])->name('privacy_policy');
Route::get('/terms-and-condition', [WebPageController::class, 'terms_condition'])->name('terms_condition');
Route::get('/why-join-telimed/{id?}', [WebPageController::class, 'why_join'])->name('why_join');
Route::get('/imagecroper', [HomeController::class, 'imageCroper'])->name('imageCroper');

Route::get('/callback/gmail', [WebUserController::class, 'handleGoogleCallback'])->name('call_back_gmail');
Route::get('auth/google/{id}', [WebUserController::class, 'redirectToGoogle'])->name('auth.google');

Route::get('/callback/facebook', [WebUserController::class, 'handleFacebookCallback']);
Route::get('auth/facebook/{id}', [WebUserController::class, 'redirectToFacebook'])->name('auth.facebook');
Route::get('/autocomplete', [WebUserController::class, 'autocomplete'])->name('autocomplete');
Route::get('/get-coaches/{district_id}', [WebUserController::class, 'getCoaches']);
Route::get('/resend_otp', [WebUserController::class, 'resend_otp'])->name('resend_otp');

Route::get('/get-tournaments-by-weight', [WebUserController::class, 'getByWeight'])->name('get.tournaments.by.weight');


//user module
Route::group(['middleware' => 'user'], function () {
    Route::get('/', [WebUserController::class, 'login_page'])->name('login_page');
    Route::get('/login', [WebUserController::class, 'login_page'])->name('login_page');
    Route::get('/forgot_passwprd', [WebUserController::class, 'forgot_passwprd'])->name('forgot_passwprd');
    Route::get('/otp', [WebUserController::class, 'otp_page'])->name('otp_page');
    Route::get('/new_password', [WebUserController::class, 'new_password'])->name('new_password');
    Route::any('/login-user', [WebUserController::class, 'login'])->name('login_save');
    Route::any('/send_otp', [WebUserController::class, 'send_otp'])->name('send_otp');
    Route::any('/resend_otp', [WebUserController::class, 'send_otp'])->name('resend_otp');
    Route::any('/user_match_otp', [WebUserController::class, 'match_otp'])->name('user_match_otp');
    Route::any('/reg_match_otp', [WebUserController::class, 'reg_match_otp'])->name('reg_user_match_otp');
    Route::any('/reset_password', [WebUserController::class, 'reset_password'])->name('user_reset_password');
    Route::any('/client-register', [WebUserController::class, 'register_user'])->name(name: 'user_register');
    Route::any('/register_save', [WebUserController::class, 'register'])->name('register_save');
    Route::get('/reset_profile/{id?}', [WebUserController::class, 'resetprofile'])->name('reset_profile');
	Route::post('/reset_profile_save', [WebUserController::class, 'reset_profile_save'])->name('reset_profile_save');
});

Route::group(['middleware' => ['usernot']], function () {
    Route::get('/dashboard', [WebUserController::class, 'dashboard'])->name('user_dashboard');
    Route::get('/id_card', [WebUserController::class, 'id_card'])->name('id_card');
    Route::get('/profile', [WebUserController::class, 'profile'])->name('user_profile');
    Route::any('/edit_profile', [WebUserController::class, 'edit_profile'])->name('user_edit_profile');
    Route::any('/profile_edit', [WebUserController::class, 'profile_edit'])->name('user_profile_edit');
    Route::any('/user_logout', [WebUserController::class, 'logout'])->name('user_logout');
    Route::get('/tracker', [WebUserController::class, 'tracker'])->name('tracker');
    Route::get('/set_reminder', [WebUserController::class, 'set_reminder'])->name('set_reminder');
    Route::any('/reminder_status', [WebUserController::class, 'reminderStatus'])->name('reminder.status');
    Route::any('/delete_reminder', [WebUserController::class, 'deleteReminder'])->name('delete.reminder');
    Route::get('/user_mood_save', [WebUserController::class, 'user_mood_save'])->name('user_mood_save');
    Route::get('/graph', [WebUserController::class, 'graph'])->name('graph');
    Route::any('/payment/success', [WebUserController::class, 'payment'])->name('payment');



    Route::post('/submit-rating', [WebUserController::class, 'submit_rating'])->name('submit_rating');
    Route::get('/user_notification', [WebUserController::class, 'user_notification'])->name('user_notification');
    Route::any('/moods/chart-data', [WebUserController::class, 'getChartData']);

    Route::any('/user_chek_booking', [WebUserController::class, 'user_chek_booking'])->name('user_chek_booking');

    
    Route::get('/my-sessions', [WebUserController::class, 'my_sessions'])->name('my_sessions');
    Route::post('/athlete/update-weight', [WebUserController::class, 'updateAthleteWeight']);
    Route::post('/athlete/apply-tournament', [WebUserController::class, 'applyAthleteTournament']);
    Route::get('/athlete/get-tournaments', [WebUserController::class, 'getAthleteTournaments']);
    Route::get('/sessions-detail/{id}', [WebUserController::class, 'sessions_detail'])->name('sessions_detail');
    Route::get('/my-favourites', [WebUserController::class, 'my_wishlist'])->name('my_wishlist');




    Route::any('/TreatmentPlanUser_data', [WebUserController::class, 'TreatmentPlan_data'])->name('TreatmentPlanUser_data');


    Route::any('/consult_form/{id?}', [WebUserController::class, 'consult_form_save'])->name('consult_form_save');


    Route::get('/rebook/{id?}', [WebUserController::class, 'rebook'])->name('rebook');
    Route::get('/cancle_session', [WebUserController::class, 'cancle_session'])->name('cancle_session');

    Route::get('/review_save', [WebUserController::class, 'review_save'])->name('review_save');
    Route::get('/video_call/{id?}', [WebUserController::class, 'user_video'])->name('user_video');
    Route::get('/user_invoice/{id?}', [WebUserController::class, 'user_invoice'])->name('user_invoice');

    Route::get('/financials-history', [WebUserController::class, 'financials'])->name('user_financials');

});

Route::group(['middleware' => 'vender'], function () {
    Route::get('/coach', [CoachLoginController::class, 'index'])->name('coachlogin');
    Route::post('/coach/login-save', [CoachLoginController::class, 'save'])->name('coachloginsave');
    Route::post('/coach/login-save_image', [CoachLoginController::class, 'loginsaveimage'])->name('coachloginsaveimage');
});

Route::group(['prefix' => 'coach', 'middleware' => 'vendernot'], function () {
    Route::get('/dashboard', [CoachDashboardController::class, 'index'])->name('coach_dashboard');
    Route::get('/idcard', [CoachDashboardController::class, 'coachidcard'])->name('coach_idcard');
    Route::get('/change-password', [CoachLoginController::class, 'change_password'])->name('coach_change_password');
    Route::post('/change-password/save', [CoachLoginController::class, 'change_password_save'])->name('coach_change_password_save');
    Route::get('/view-profile', [CoachLoginController::class, 'view_profile'])->name('coach_view_profile');
    Route::post('/update-profile', [CoachLoginController::class, 'update_profile'])->name('coach_update_profile');
    Route::get('/logout', [CoachLoginController::class, 'logout'])->name('coachadminlogout');
    
 
    /*---------------------Admin User routes Start---------------------*/
    Route::get('/user/add/{id?}', [CoachUserController::class, 'add'])->name('coach_user_add');
    Route::post('/user/save/{id?}', [CoachUserController::class, 'save'])->name('coach_user_save');
    Route::get('/user', [CoachUserController::class, 'index'])->name('coach_user');
    Route::get('/user-data', [CoachUserController::class, 'anydata'])->name('coach_user_data');
    Route::get('/user/delete', [CoachUserController::class, 'delete'])->name('coach_user_delete');
    Route::get('/user/status', [CoachUserController::class, 'changeStatus'])->name('coach_user_status');
    Route::get('/user/detail/{id}', [CoachUserController::class, 'detail'])->name('coach_user_detail');
    Route::get('/user_delete_image', [CoachUserController::class, 'user_delete_image'])->name('coach_user_delete_image');

    Route::post('/update-weight', [CoachUserController::class, 'updateWeight']);


     /*---------------------Admin User routes Start---------------------*/
     Route::get('/applyuser/add/{id?}', [ApplyUserController::class, 'add'])->name('coach_applyuser_add');
     Route::post('/applyuser/save/{id?}', [ApplyUserController::class, 'save'])->name('coach_applyuser_save');
     Route::get('/applyuser', [ApplyUserController::class, 'index'])->name('coach_applyuser');
     Route::get('/applyuser-data', [ApplyUserController::class, 'anydata'])->name('coach_applyuser_data');
     Route::get('/applyuser/delete', [ApplyUserController::class, 'delete'])->name('coach_applyuser_delete');
     Route::get('/applyuser/status', [ApplyUserController::class, 'changeStatus'])->name('coach_applyuser_status');
     Route::get('/applyuser/detail/{id}', [ApplyUserController::class, 'detail'])->name('coach_applyuser_detail');
     Route::get('/applyuser_delete_image', [ApplyUserController::class, 'applyuser_delete_image'])->name('coach_applyuser_delete_image');
 
     Route::post('/apply_update-weight', [CoachUserController::class, 'updateWeight']);
     Route::post('/user/update-it-uid', [CoachUserController::class, 'updateItUid']);

    
    Route::get('/get-tournaments',  [CoachApplyTournamentController::class, 'get_tournament']);
    /*---------------------apply tournament routes Start---------------------*/
    Route::get('/apply-tournament', [CoachApplyTournamentController::class, 'index'])->name('coach_apply_tournament');
    Route::any('/apply-tournament-data', [CoachApplyTournamentController::class, 'anydata'])->name('coach_apply_tournament_data');
    Route::get('/district-apply-tournament', [CoachApplyTournamentController::class, 'district_index'])->name('coach_district_apply_tournament');
    Route::any('/district-apply-tournament-data', [CoachApplyTournamentController::class, 'district_anydata'])->name('coach_district_apply_tournament_data');
    Route::get('/apply-tournament/status', [CoachApplyTournamentController::class, 'changeStatus'])->name('coach_apply_tournament_status');
    Route::get('/apply-tournament-name/{id}', [CoachApplyTournamentController::class, 'indexname'])->name('coach_apply_tournament_name');
    Route::get('/apply-tournament-name-data/{id}', [CoachApplyTournamentController::class, 'anydataname'])->name('coach_apply_tournament_name_data');
    Route::get('/apply-tournament-name_delete', [CoachApplyTournamentController::class, 'delete'])->name('coach_apply_tournament_name_delete');
    Route::get('/apply-tournament-name_edit/{id}', [CoachApplyTournamentController::class, 'edit'])->name('coach_apply_tournament_name_edit');
    Route::post('/apply-tournament-name_edit_save/{id}', [CoachApplyTournamentController::class, 'edit_save'])->name('coach_apply_tournament_name_edit_save');
    /*-----------------------Admin apply tournament routes End---------------------*/

    Route::post('/change-visible-status', [CoachApplyTournamentController::class, 'changeVisibleStatus'])->name('coach.change.visible.status');

    
    Route::any('/data/apply-tournament', [CoachApplyTournamentController::class, 'store']);

    Route::any('reg_form_single/{id}',[CoachCardController::class,'reg_form_single'])->name('coach.group_reg_form');
    Route::any('single_card/{id}',[CoachCardController::class,'single_card'])->name('coach.single_card');
    Route::any('all_single_reg_form/{id}',[CoachCardController::class,'all_single_reg_form'])->name('coach.all_single_reg_form');
    	Route::any('all_single_card/{id}',[CoachCardController::class,'all_single_card'])->name('coach.all_single_card');

    /*-----------------------Admin User routes End---------------------*/ 
    
});

Route::group(['middleware' => ['usernot']], function () {
    Route::get('/dashboard', [WebUserController::class, 'dashboard'])->name('user_dashboard');
    Route::get('/id_card', [WebUserController::class, 'id_card'])->name('id_card');
    Route::get('/profile', [WebUserController::class, 'profile'])->name('user_profile');
    Route::any('/edit_profile', [WebUserController::class, 'edit_profile'])->name('user_edit_profile');
    Route::any('/profile_edit', [WebUserController::class, 'profile_edit'])->name('user_profile_edit');
    Route::any('/user_logout', [WebUserController::class, 'logout'])->name('user_logout');
    Route::get('/tracker', [WebUserController::class, 'tracker'])->name('tracker');
    Route::get('/set_reminder', [WebUserController::class, 'set_reminder'])->name('set_reminder');
    Route::any('/reminder_status', [WebUserController::class, 'reminderStatus'])->name('reminder.status');
    Route::any('/delete_reminder', [WebUserController::class, 'deleteReminder'])->name('delete.reminder');
    Route::get('/user_mood_save', [WebUserController::class, 'user_mood_save'])->name('user_mood_save');
    Route::get('/graph', [WebUserController::class, 'graph'])->name('graph');
    Route::any('/payment/success', [WebUserController::class, 'payment'])->name('payment');



    Route::post('/submit-rating', [WebUserController::class, 'submit_rating'])->name('submit_rating');
    Route::get('/user_notification', [WebUserController::class, 'user_notification'])->name('user_notification');
    Route::any('/moods/chart-data', [WebUserController::class, 'getChartData']);

    Route::any('/user_chek_booking', [WebUserController::class, 'user_chek_booking'])->name('user_chek_booking');

    
    Route::get('/my-sessions', [WebUserController::class, 'my_sessions'])->name('my_sessions');
    Route::post('/athlete/update-weight', [WebUserController::class, 'updateAthleteWeight']);
    Route::post('/athlete/apply-tournament', [WebUserController::class, 'applyAthleteTournament']);
    Route::get('/athlete/get-tournaments', [WebUserController::class, 'getAthleteTournaments']);
    Route::get('/sessions-detail/{id}', [WebUserController::class, 'sessions_detail'])->name('sessions_detail');
    Route::get('/my-favourites', [WebUserController::class, 'my_wishlist'])->name('my_wishlist');




    Route::any('/TreatmentPlanUser_data', [WebUserController::class, 'TreatmentPlan_data'])->name('TreatmentPlanUser_data');


    Route::any('/consult_form/{id?}', [WebUserController::class, 'consult_form_save'])->name('consult_form_save');


    Route::get('/rebook/{id?}', [WebUserController::class, 'rebook'])->name('rebook');
    Route::get('/cancle_session', [WebUserController::class, 'cancle_session'])->name('cancle_session');

    Route::get('/review_save', [WebUserController::class, 'review_save'])->name('review_save');
    Route::get('/video_call/{id?}', [WebUserController::class, 'user_video'])->name('user_video');
    Route::get('/user_invoice/{id?}', [WebUserController::class, 'user_invoice'])->name('user_invoice');

    Route::get('/financials-history', [WebUserController::class, 'financials'])->name('user_financials');

});

Route::group(['middleware' => 'ifnotSubadmin'], function () {
    Route::get('/subadmin', [SubadminLoginController::class, 'index'])->name('subadminlogin');
    Route::post('/subadmin/login-save', [SubadminLoginController::class, 'save'])->name('subadminloginsave');
    Route::post('/subadmin/login-save_image', [SubadminLoginController::class, 'loginsaveimage'])->name('subadminloginsaveimage');
});

Route::group(['prefix' => 'subadmin', 'middleware' => 'ifSubadmin'], function () {
    Route::get('/dashboard', [SubadminDashboardController::class, 'index'])->name('subadmin_dashboard');
    Route::get('/idcard', [SubadminDashboardController::class, 'subadminidcard'])->name('subadmin_idcard');
    Route::get('/change-password', [SubadminLoginController::class, 'change_password'])->name('subadmin_change_password');
    Route::post('/change-password/save', [SubadminLoginController::class, 'change_password_save'])->name('subadmin_change_password_save');
    Route::get('/view-profile', [SubadminLoginController::class, 'view_profile'])->name('subadmin_view_profile');
    Route::post('/update-profile', [SubadminLoginController::class, 'update_profile'])->name('subadmin_update_profile');
    Route::get('/logout', [SubadminLoginController::class, 'logout'])->name('subadminadminlogout');
    
 
    /*---------------------Admin User routes Start---------------------*/
    Route::get('/user/add/{id?}', [SubadminUserController::class, 'add'])->name('subadmin_user_add');
    Route::post('/user/save/{id?}', [SubadminUserController::class, 'save'])->name('subadmin_user_save');
    Route::get('/user', [SubadminUserController::class, 'index'])->name('subadmin_user');
    Route::get('/user-data', [SubadminUserController::class, 'anydata'])->name('subadmin_user_data');
    Route::get('/user/delete', [SubadminUserController::class, 'delete'])->name('subadmin_user_delete');
    Route::get('/user/status', [SubadminUserController::class, 'changeStatus'])->name('subadmin_user_status');
    Route::get('/user/detail/{id}', [SubadminUserController::class, 'detail'])->name('subadmin_user_detail');
    Route::post('/user/verify', [SubadminUserController::class, 'verifyAthlete'])->name('subadmin_user_verify');
    Route::get('/user_delete_image', [SubadminUserController::class, 'user_delete_image'])->name('subadmin_user_delete_image');

    Route::post('/update-weight', [SubadminUserController::class, 'updateWeight']);


     /*---------------------Admin User routes Start---------------------*/
     Route::get('/applyuser/add/{id?}', [SubadminApplyUserController::class, 'add'])->name('subadmin_applyuser_add');
     Route::post('/applyuser/save/{id?}', [SubadminApplyUserController::class, 'save'])->name('subadmin_applyuser_save');
     Route::get('/applyuser', [SubadminApplyUserController::class, 'index'])->name('subadmin_applyuser');
     Route::get('/applyuser-data', [SubadminApplyUserController::class, 'anydata'])->name('subadmin_applyuser_data');
     Route::get('/applyuser/delete', [SubadminApplyUserController::class, 'delete'])->name('subadmin_applyuser_delete');
     Route::get('/applyuser/status', [SubadminApplyUserController::class, 'changeStatus'])->name('subadmin_applyuser_status');
     Route::get('/applyuser/detail/{id}', [SubadminApplyUserController::class, 'detail'])->name('subadmin_applyuser_detail');
     Route::get('/applyuser_delete_image', [SubadminApplyUserController::class, 'applyuser_delete_image'])->name('subadmin_applyuser_delete_image');
 
     Route::post('/apply_update-weight', [SubadminApplyUserController::class, 'updateWeight']);

    
    Route::get('/get-tournaments',  [SubadminApplyTournamentController::class, 'get_tournament']);
    /*---------------------apply tournament routes Start---------------------*/
    Route::get('/apply-tournament', [SubadminApplyTournamentController::class, 'index'])->name('subadmin_apply_tournament');
    Route::any('/apply-tournament-data', [SubadminApplyTournamentController::class, 'anydata'])->name('subadmin_apply_tournament_data');
    Route::get('/apply-tournament/status', [SubadminApplyTournamentController::class, 'changeStatus'])->name('subadmin_apply_tournament_status');
    Route::get('/apply-tournament-name/{id}', [SubadminApplyTournamentController::class, 'indexname'])->name('subadmin_apply_tournament_name');
    Route::get('/apply-tournament-name-data/{id}', [SubadminApplyTournamentController::class, 'anydataname'])->name('subadmin_apply_tournament_name_data');
    Route::get('/apply-tournament-name_delete', [SubadminApplyTournamentController::class, 'delete'])->name('subadmin_apply_tournament_name_delete');
    Route::get('/apply-tournament-name_edit/{id}', [SubadminApplyTournamentController::class, 'edit'])->name('subadmin_apply_tournament_name_edit');
    Route::post('/apply-tournament-name_edit_save/{id}', [SubadminApplyTournamentController::class, 'edit_save'])->name('subadmin_apply_tournament_name_edit_save');
    /*-----------------------Admin apply tournament routes End---------------------*/

    Route::post('/change-visible-status', [SubadminApplyTournamentController::class, 'changeVisibleStatus'])->name('subadmin.change.visible.status');

    
    Route::any('/data/apply-tournament', [SubadminApplyTournamentController::class, 'store']);

    Route::get('/coach', [SubadminCoachController::class, 'index'])->name('subadmin_coach');
    Route::get('/coach-data', [SubadminCoachController::class, 'anydata'])->name('subadmin_coach_data');
    Route::post('/coach/toggle-stop-apply', [SubadminCoachController::class, 'toggleStopApply'])->name('subadmin_coach_toggle_stop_apply');

    Route::get('/referee', [SubadminRefereeController::class, 'index'])->name('subadmin_referee');
    Route::get('/referee-data', [SubadminRefereeController::class, 'anydata'])->name('subadmin_referee_data');

    Route::get('/tournament/add/{id?}', [SubadminTournamentController::class, 'add'])->name('subadmin_tournament_add');
    Route::get('/tournament/edit/{id?}', [SubadminTournamentController::class, 'edit'])->name('subadmin_tournament_edit');
    Route::post('/tournament/save/{id?}', [SubadminTournamentController::class, 'save'])->name('subadmin_tournament_save');
    Route::post('/tournament_edit/save/{id?}', [SubadminTournamentController::class, 'editsave'])->name('subadmin_tournament_editsave');
    Route::get('/tournament', [SubadminTournamentController::class, 'index'])->name('subadmin_tournament');
    Route::any('/tournament-data', [SubadminTournamentController::class, 'anydata'])->name('subadmin_tournament_data');

    Route::get('/draw-sheet', [SubadminDrawSheetController::class, 'index'])->name('subadmin_draw_sheet');
    Route::any('/draw-sheet-data', [SubadminDrawSheetController::class, 'anydata'])->name('subadmin_draw_sheet_data');
    Route::get('/draw-sheet/view/{id}', [SubadminDrawSheetController::class, 'draw_sheet'])->name('subadmin_draw_sheet_view');
    Route::get('/draw-sheet-name/{id}', [SubadminDrawSheetController::class, 'indexname'])->name('subadmin_draw_sheet_name');
    Route::get('/draw-sheet-name-data/{id}', [SubadminDrawSheetController::class, 'anydataname'])->name('subadmin_draw_sheet_name_data');

    Route::any('reg_form_single/{id}',[SubadminCardController::class,'reg_form_single'])->name('subadmin.group_reg_form');
    Route::any('single_card/{id}',[SubadminCardController::class,'single_card'])->name('subadmin.single_card');
    Route::any('all_single_reg_form/{id}',[SubadminCardController::class,'all_single_reg_form'])->name('subadmin.all_single_reg_form');
    Route::any('all_single_card/{id}',[SubadminCardController::class,'all_single_card'])->name('subadmin.all_single_card');

    /*-----------------------Admin User routes End---------------------*/ 
    
});

Route::group(['middleware' => 'ifnotReferee'], function () {
    Route::get('/referee', [RefereeLoginController::class, 'index'])->name('refereelogin');
    Route::post('/referee/login-save', [RefereeLoginController::class, 'save'])->name('refereeloginsave');
    Route::post('/referee/login-save_image', [RefereeLoginController::class, 'loginsaveimage'])->name('refereeloginsaveimage');
});

Route::group(['prefix' => 'referee', 'middleware' => 'ifReferee'], function () {
    Route::get('/dashboard', [RefereeDashboardController::class, 'index'])->name('referee_dashboard');
    Route::get('/idcard', [RefereeDashboardController::class, 'refereeidcard'])->name('referee_idcard');
    Route::get('/change-password', [RefereeLoginController::class, 'change_password'])->name('referee_change_password');
    Route::post('/change-password/save', [RefereeLoginController::class, 'change_password_save'])->name('referee_change_password_save');
    Route::get('/view-profile', [RefereeLoginController::class, 'view_profile'])->name('referee_view_profile');
    Route::post('/update-profile', [RefereeLoginController::class, 'update_profile'])->name('referee_update_profile');
    Route::get('/logout', [RefereeLoginController::class, 'logout'])->name('refereeadminlogout');
    
 
    /*---------------------Admin User routes Start---------------------*/
    Route::get('/user/add/{id?}', [RefereeUserController::class, 'add'])->name('referee_user_add');
    Route::post('/user/save/{id?}', [RefereeUserController::class, 'save'])->name('referee_user_save');
    Route::get('/user', [RefereeUserController::class, 'index'])->name('referee_user');
    Route::get('/user-data', [RefereeUserController::class, 'anydata'])->name('referee_user_data');
    Route::get('/user/delete', [RefereeUserController::class, 'delete'])->name('referee_user_delete');
    Route::get('/user/status', [RefereeUserController::class, 'changeStatus'])->name('referee_user_status');
    Route::get('/user/detail/{id}', [RefereeUserController::class, 'detail'])->name('referee_user_detail');
    Route::get('/user_delete_image', [RefereeUserController::class, 'user_delete_image'])->name('referee_user_delete_image');

    Route::post('/update-weight', [RefereeUserController::class, 'updateWeight']);


     /*---------------------Admin User routes Start---------------------*/
     Route::get('/applyuser/add/{id?}', [RefereeApplyUserController::class, 'add'])->name('referee_applyuser_add');
     Route::post('/applyuser/save/{id?}', [RefereeApplyUserController::class, 'save'])->name('referee_applyuser_save');
     Route::get('/applyuser', [RefereeApplyUserController::class, 'index'])->name('referee_applyuser');
     Route::get('/applyuser-data', [RefereeApplyUserController::class, 'anydata'])->name('referee_applyuser_data');
     Route::get('/applyuser/delete', [RefereeApplyUserController::class, 'delete'])->name('referee_applyuser_delete');
     Route::get('/applyuser/status', [RefereeApplyUserController::class, 'changeStatus'])->name('referee_applyuser_status');
     Route::get('/applyuser/detail/{id}', [RefereeApplyUserController::class, 'detail'])->name('referee_applyuser_detail');
     Route::get('/applyuser_delete_image', [RefereeApplyUserController::class, 'applyuser_delete_image'])->name('referee_applyuser_delete_image');
 
     Route::post('/apply_update-weight', [RefereeUserController::class, 'updateWeight']);

    
    Route::get('/get-tournaments',  [RefereeApplyTournamentController::class, 'get_tournament']);
    /*---------------------apply tournament routes Start---------------------*/
    Route::get('/apply-tournament', [RefereeApplyTournamentController::class, 'index'])->name('referee_apply_tournament');
    Route::any('/apply-tournament-data', [RefereeApplyTournamentController::class, 'anydata'])->name('referee_apply_tournament_data');
    Route::get('/apply-tournament/status', [RefereeApplyTournamentController::class, 'changeStatus'])->name('referee_apply_tournament_status');
    Route::get('/apply-tournament-name/{id}', [RefereeApplyTournamentController::class, 'indexname'])->name('referee_apply_tournament_name');
    Route::get('/apply-tournament-name-data/{id}', [RefereeApplyTournamentController::class, 'anydataname'])->name('referee_apply_tournament_name_data');
    Route::get('/apply-tournament-name_delete', [RefereeApplyTournamentController::class, 'delete'])->name('referee_apply_tournament_name_delete');
    Route::get('/apply-tournament-name_edit/{id}', [RefereeApplyTournamentController::class, 'edit'])->name('referee_apply_tournament_name_edit');
    Route::post('/apply-tournament-name_edit_save/{id}', [RefereeApplyTournamentController::class, 'edit_save'])->name('referee_apply_tournament_name_edit_save');
    /*-----------------------Admin apply tournament routes End---------------------*/

    Route::post('/change-visible-status', [refereeApplyTournamentController::class, 'changeVisibleStatus'])->name('change.visible.status');

    
    Route::any('/data/apply-tournament', [refereeApplyTournamentController::class, 'store']);

    Route::any('reg_form_single/{id}',[refereeCardController::class,'reg_form_single'])->name('referee.group_reg_form');
    Route::any('single_card/{id}',[refereeCardController::class,'single_card'])->name('referee.single_card');
    Route::any('all_single_reg_form/{id}',[refereeCardController::class,'all_single_reg_form'])->name('referee.all_single_reg_form');
    	Route::any('all_single_card/{id}',[refereeCardController::class,'all_single_card'])->name('referee.all_single_card');

    /*-----------------------Admin User routes End---------------------*/ 
    
});

//shoper card register
Route::group(['middleware' => 'ifnotShoper'], function () {
    Route::get('/shoper/', [ShoperLoginController::class, 'index'])->name('shoperlogin');
    Route::post('/shoper/login-save', [ShoperLoginController::class, 'save'])->name('shoperloginsave');
    Route::post('/shoper/login-save_image', [ShoperLoginController::class, 'loginsaveimage'])->name('shoperloginsaveimage');
});

Route::group(['prefix' => 'shoper','middleware'=>'ifShoper'],function()
{
	Route::get('/dashboard', [ShoperDashboardController::class, 'index'])->name('shoper_dashboard');
    Route::get('/change-password', [ShoperLoginController::class, 'change_password'])->name('shoper_change_password');
    Route::post('/change-password/save', [ShoperLoginController::class, 'change_password_save'])->name('shoper_change_password_save');
    Route::get('/view-profile', [ShoperLoginController::class, 'view_profile'])->name('shoper_view_profile');
    Route::post('/update-profile', [ShoperLoginController::class, 'update_profile'])->name('shoper_update_profile');
    Route::get('/logout', [ShoperLoginController::class, 'logout'])->name('shoperadminlogout');

	

    /*---------------------apply tournament routes Start---------------------*/
    Route::get('/apply-tournament', [ShoperApplyTournamentController::class, 'index'])->name('shoperapply_tournament');
    Route::any('/apply-tournament-data', [ShoperApplyTournamentController::class, 'anydata'])->name('shoperapply_tournament_data');
    Route::get('/apply-tournament/status', [ShoperApplyTournamentController::class, 'changeStatus'])->name('shoperapply_tournament_status');
    Route::get('/apply-tournament-name/{id}', [ShoperApplyTournamentController::class, 'indexname'])->name('shoperapply_tournament_name');
    Route::get('/apply-tournament-name-data/{id}', [ShoperApplyTournamentController::class, 'anydataname'])->name('shoperapply_tournament_name_data');
    Route::get('/apply-tournament-name_delete', [ShoperApplyTournamentController::class, 'delete'])->name('shoperapply_tournament_name_delete');
    Route::get('/apply-tournament-name_edit/{id}', [ShoperApplyTournamentController::class, 'edit'])->name('shoperapply_tournament_name_edit');
    Route::post('/apply-tournament-name_edit_save/{id}', [ShoperApplyTournamentController::class, 'edit_save'])->name('shoperapply_tournament_name_edit_save');
    /*-----------------------Admin apply tournament routes End---------------------*/
	
	
	Route::any('reg_form_single/{id}',[OtherCardEventController::class,'reg_form_single'])->name('other.group_reg_form');
	Route::any('all_single_reg_form/{id}',[OtherCardEventController::class,'all_single_reg_form'])->name('other.all_single_reg_form');
    Route::any('single_card/{id}',[OtherCardEventController::class,'single_card'])->name('other.single_card');
	Route::any('all_single_card/{id}',[OtherCardEventController::class,'all_single_card'])->name('other.all_single_card');
	
	
	

});


Route::group(['middleware' => 'ifnotadmin'], function () {
    Route::get('/admin', [LoginController::class, 'index'])->name('adminlogin');
    Route::post('/admin/login-save', [LoginController::class, 'save'])->name('loginsave');
    Route::post('/admin/login-save_image', [LoginController::class, 'loginsaveimage'])->name('loginsaveimage');
});

Route::group(['prefix' => 'admin', 'middleware' => 'ifadmin'], function () {
    Route::get('/dashboard', [dashboardController::class, 'index'])->name('admin_dashboard');
    Route::get('/change-password', [LoginController::class, 'change_password'])->name('change_password');
    Route::post('/change-password/save', [LoginController::class, 'change_password_save'])->name('change_password_save');
    Route::get('/view-profile', [LoginController::class, 'view_profile'])->name('view_profile');
    Route::post('/update-profile', [LoginController::class, 'update_profile'])->name('update_profile');
    Route::get('/logout', [LoginController::class, 'logout'])->name('adminlogout');
    Route::get('/denied', [PermissionController::class, 'denied'])->name('denied');


    Route::group(['middleware' => 'permission'], function () {
        Route::get('/setting', [SettingController::class, 'index'])->name('setting');
        Route::post('/setting-save', [SettingController::class, 'save'])->name('setting_save');
        
        Route::post('/admin/users/bulk-coach-permission', [UserController::class, 'bulkCoachPermission']);


        Route::get('/email', [SettingController::class, 'email'])->name('email');
        Route::post('/email-send', [SettingController::class, 'email_send'])->name('email_send');

        Route::get('/about_us', [PageController::class, 'about_us'])->name('admin_about_us');
        Route::post('/about_us-save', [PageController::class, 'about_us_save'])->name('about_us_save');
        
        

        /*---------------------Admin pages routes Start---------------------*/
        Route::get('/page/add/{id?}', [PageController::class, 'add'])->name('page_add');
        Route::post('/page/save/{id?}', [PageController::class, 'save'])->name('page_save');
        Route::get('/page', [PageController::class, 'index'])->name('page');
        Route::get('/page-data', [PageController::class, 'anydata'])->name('page_data');
        Route::get('/page/status', [PageController::class, 'changeStatus'])->name('page_status');
        /*-----------------------Admin pages routes End---------------------*/

        
        /*---------------------Admin Sub Admin routes Start---------------------*/
        Route::get('/subadmin/add/{id?}', [SubadminController::class, 'add'])->name('subadmin_add');
        Route::post('/subadmin/save/{id?}', [SubadminController::class, 'save'])->name('subadmin_save');
        Route::any('/subadmin', [SubadminController::class, 'index'])->name('subadmin');
        Route::any('/subadmin-data', [SubadminController::class, 'anydata'])->name('subadmin_data');
        Route::get('/subadmin/delete', [SubadminController::class, 'delete'])->name('subadmin_delete');
        Route::get('/subadmin/status', [SubadminController::class, 'changeStatus'])->name('subadmin_status');
        /*-----------------------Admin Sub Admin routes End---------------------*/

        /*-----------------------Admin permissions routes End---------------------*/
        Route::get('/permissions', [PermissionController::class, 'view'])->name('viewpermission');
        Route::post('/permissions/save', [PermissionController::class, 'save'])->name('savepermission');
        Route::post('/group_check', [PermissionController::class, 'checkdata'])->name('ckeckpermission');
        /*-----------------------Admin permissions routes End---------------------*/

        /*---------------------Admin Category routes Start---------------------*/
        Route::get('/category/add/{id?}', [CategoryController::class, 'add'])->name('category_add');
        Route::post('/category/save/{id?}', [CategoryController::class, 'save'])->name('category_save');
        Route::get('/category', [CategoryController::class, 'index'])->name('category');
        Route::any('/category-data', [CategoryController::class, 'anydata'])->name('category_data');
        Route::get('/category/delete', [CategoryController::class, 'delete'])->name('category_delete');
        Route::get('/category/status', [CategoryController::class, 'changeStatus'])->name('category_status');
        /*-----------------------Admin Category routes End---------------------*/
        
         /*---------------------Admin Specialities routes Start---------------------*/
        Route::get('/event-category/add/{id?}', [EventCategoryController::class, 'add'])->name('event-category_add');
        Route::post('/event-category/save/{id?}', [EventCategoryController::class, 'save'])->name('event-category_save');
        Route::get('/event-category', [EventCategoryController::class, 'index'])->name('event-category');
        Route::any('/event-category-data', [EventCategoryController::class, 'anydata'])->name('event-category_data');
        Route::get('/event-category/delete', [EventCategoryController::class, 'delete'])->name('event-category_delete');
        Route::get('/event-category/status', [EventCategoryController::class, 'changeStatus'])->name('event-category_status');
        /*-----------------------Admin Specialities routes End---------------------*/
        
        /*---------------------Admin banner routes Start---------------------*/
        Route::get('/event/add/{id?}', [EventController::class, 'add'])->name('event_add');
        Route::post('/event/save/{id?}', [EventController::class, 'save'])->name('event_save');
        Route::get('/event', [EventController::class, 'index'])->name('event');
        Route::any('/event-data', [EventController::class, 'anydata'])->name('event_data');
        Route::get('/event/delete', [EventController::class, 'delete'])->name('event_delete');
        Route::get('/event/status', [EventController::class, 'changeStatus'])->name('event_status');
        /*-----------------------Admin banner routes End---------------------*/
        
        /*---------------------Admin Language routes Start---------------------*/
        Route::get('/tournament/add/{id?}', [TournamentController::class, 'add'])->name('tournament_add');
        Route::get('/tournament/edit/{id?}', [TournamentController::class, 'edit'])->name('tournament_edit');
        Route::post('/tournament/save/{id?}', [TournamentController::class, 'save'])->name('tournament_save');
        Route::post('/tournament_edit/save/{id?}', [TournamentController::class, 'editsave'])->name('tournament_editsave');
        Route::get('/tournament', [TournamentController::class, 'index'])->name('tournament');
        Route::any('/tournament-data', [TournamentController::class, 'anydata'])->name('tournament_data');
        Route::get('/district-tournament', [TournamentController::class, 'district_index'])->name('district_tournament');
        Route::any('/district-tournament-data', [TournamentController::class, 'district_anydata'])->name('district_tournament_data');
        Route::get('/tournament/delete', [TournamentController::class, 'delete'])->name('tournament_delete');
        Route::get('/tournament/status', [TournamentController::class, 'changeStatus'])->name('tournament_status'); 
        Route::get('/tournament_name/{id}', [TournamentController::class, 'indexname'])->name('index_apply_tournament');
        Route::get('/tournament_name-data/{id}', [TournamentController::class, 'anydataname'])->name('any_index_apply_tournament');
        /*-----------------------Admin Language routes End---------------------*/
        
        
        /*---------------------apply tournament routes Start---------------------*/
        Route::get('/apply-tournament', [ApplyTournamentController::class, 'index'])->name('apply_tournament');
        Route::any('/apply-tournament-data', [ApplyTournamentController::class, 'anydata'])->name('apply_tournament_data');
        Route::get('/district-apply-tournament', [ApplyTournamentController::class, 'district_index'])->name('district_apply_tournament');
        Route::any('/district-apply-tournament-data', [ApplyTournamentController::class, 'district_anydata'])->name('district_apply_tournament_data');
        Route::get('/apply-tournament/status', [ApplyTournamentController::class, 'changeStatus'])->name('apply_tournament_status');
        Route::get('/apply-tournament-name/{id}', [ApplyTournamentController::class, 'indexname'])->name('apply_tournament_name');
        Route::get('/apply-tournament-name-data/{id}', [ApplyTournamentController::class, 'anydataname'])->name('apply_tournament_name_data');
        Route::get('/apply-tournament-name_delete', [ApplyTournamentController::class, 'delete'])->name('apply_tournament_name_delete');
        Route::get('/apply-tournament-name_edit/{id}', [ApplyTournamentController::class, 'edit'])->name('apply_tournament_name_edit');
        Route::post('/apply-tournament-name_edit_save/{id?}', [ApplyTournamentController::class, 'edit_save'])->name('apply_tournament_name_edit_save');
        Route::post('/update-edit-uid', [ApplyTournamentController::class, 'updateEditUid']);

        Route::get('/apply-tournament-name_add/{id}', [ApplyTournamentController::class, 'add'])->name('apply_tournament_name_add');
        Route::post('/apply-tournament-name_add_save/{id}', [ApplyTournamentController::class, 'edit_save'])->name('apply_tournament_name_edit_save');
        Route::get('/get-tournaments-by-weight', [ApplyTournamentController::class, 'getByWeight'])->name('admin.tournaments.by.weight');
        /*-----------------------Admin apply tournament routes End---------------------*/

        /*---------------------apply Draw Sheet routes Start---------------------*/
        Route::get('/draw-sheet', [DrawSheetController::class, 'index'])->name('draw_sheet');
        Route::get('/draw-sheet/view/{id}', [DrawSheetController::class, 'draw_sheet'])->name('draw_sheet_view');
        Route::any('/draw-sheet-data', [DrawSheetController::class, 'anydata'])->name('draw_sheet_data');
        Route::get('/draw-sheet/status', [DrawSheetController::class, 'changeStatus'])->name('draw_sheet_status');
        Route::get('/draw-sheet-name/{id}', [DrawSheetController::class, 'indexname'])->name('draw_sheet_name');
        Route::get('/draw-sheet-name-data/{id}', [DrawSheetController::class, 'anydataname'])->name('draw_sheet_name_data');
        Route::post('/reset-bracket', [DrawSheetController::class, 'resetBracket'])->name('reset.bracket');
        Route::post('/send-otp', [DrawSheetController::class, 'sendOtp'])->name('send.otp');
        Route::post('/verify-otp-and-reset', [DrawSheetController::class, 'verifyOtpAndReset'])->name('verify.otp.and.reset');
        Route::post('/verify-draw-sheet-subadmin', [DrawSheetController::class, 'verifyDrawSheetForSubadmin'])->name('verify.draw.sheet.subadmin');

        /*-----------------------Admin Draw Sheet routes End---------------------*/
        
        
        /*---------------------Admin Category routes Start---------------------*/
        Route::get('/weightcategory/add/{id?}', [WeightCategoryController::class, 'add'])->name('weightcategory_add');
        Route::post('/weightcategory/save/{id?}', [WeightCategoryController::class, 'save'])->name('weightcategory_save');
        Route::get('/weightcategory', [WeightCategoryController::class, 'index'])->name('weightcategory');
        Route::any('/weightcategory-data', [WeightCategoryController::class, 'anydata'])->name('weightcategory_data');
        Route::get('/weightcategory/delete', [WeightCategoryController::class, 'delete'])->name('weightcategory_delete');
        Route::get('/weightcategory/status', [WeightCategoryController::class, 'changeStatus'])->name('weightcategory_status');
        /*-----------------------Admin weightcategory routes End---------------------*/


        /*---------------------Admin Tag routes Start---------------------*/
        Route::get('/tag/add/{id?}', [TagController::class, 'add'])->name('tag_add');
        Route::post('/tag/save/{id?}', [TagController::class, 'save'])->name('tag_save');
        Route::get('/tag', [TagController::class, 'index'])->name('tag');
        Route::any('/tag-data', [TagController::class, 'anydata'])->name('tag_data');
        Route::get('/tag/delete', [TagController::class, 'delete'])->name('tag_delete');
        Route::get('/tag/status', [TagController::class, 'changeStatus'])->name('tag_status');
        /*-----------------------Admin Tag routes End---------------------*/

        /*---------------------Admin feature routes Start---------------------*/
        Route::get('/gallery/add/{id?}', [GalleryController::class, 'add'])->name('gallery_add');
        Route::post('/gallery/save/{id?}', [GalleryController::class, 'save'])->name('gallery_save');
        Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');
        Route::any('/gallery-data', [GalleryController::class, 'anydata'])->name('gallery_data');
        Route::get('/gallery/delete', [GalleryController::class, 'delete'])->name('gallery_delete');
        Route::get('/gallery/status', [GalleryController::class, 'changeStatus'])->name('gallery_status');
        /*-----------------------Admin gallery routes End---------------------*/

        /*---------------------Admin faq routes Start---------------------*/
        Route::get('/faq/add/{id?}', [FaqController::class, 'add'])->name('faq_add');
        Route::post('/faq/save/{id?}', [FaqController::class, 'save'])->name('faq_save');
        Route::get('/faq', [FaqController::class, 'index'])->name('faq');
        Route::get('/faq-data', [FaqController::class, 'anydata'])->name('faq_data');
        Route::get('/faq/delete', [FaqController::class, 'delete'])->name('faq_delete');
        Route::get('/faq/status', [FaqController::class, 'changeStatus'])->name('faq_status');
        /*-----------------------Admin faq routes End---------------------*/

        /*---------------------Admin testimonial routes Start---------------------*/
        Route::get('/testimonial/add/{id?}', [TestimonialController::class, 'add'])->name('testimonial_add');
        Route::post('/testimonial/save/{id?}', [TestimonialController::class, 'save'])->name('testimonial_save');
        Route::get('/testimonials', [TestimonialController::class, 'index'])->name('testimonial');
        Route::any('/testimonial-data', [TestimonialController::class, 'anydata'])->name('testimonial_data');
        Route::get('/testimonial/delete', [TestimonialController::class, 'delete'])->name('testimonial_delete');
        Route::get('/testimonial/status', [TestimonialController::class, 'changeStatus'])->name('testimonial_status');
        /*-----------------------Admin testimonial routes End---------------------*/


        /*---------------------Admin banner routes Start---------------------*/
        Route::get('/banner/add/{id?}', [BannerController::class, 'add'])->name('banner_add');
        Route::post('/banner/save/{id?}', [BannerController::class, 'save'])->name('banner_save');
        Route::get('/banner', [BannerController::class, 'index'])->name('banner');
        Route::any('/banner-data', [BannerController::class, 'anydata'])->name('banner_data');
        Route::get('/banner/delete', [BannerController::class, 'delete'])->name('banner_delete');
        Route::get('/banner/status', [BannerController::class, 'changeStatus'])->name('banner_status');
        /*-----------------------Admin banner routes End---------------------*/


        /*---------------------Admin User routes Start---------------------*/
        Route::get('/user/add/{id?}', [UserController::class, 'add'])->name('user_add');
        Route::post('/user/save/{id?}', [UserController::class, 'save'])->name('user_save');
        Route::get('/user', [UserController::class, 'index'])->name('user');
        Route::get('/user-data', [UserController::class, 'anydata'])->name('user_data');
        Route::get('/user/delete', [UserController::class, 'delete'])->name('user_delete');
        Route::get('/user/status', [UserController::class, 'changeStatus'])->name('user_status');
        Route::get('/user/coach_act', [UserController::class, 'coach_act'])->name('coach_act');
        Route::get('/user/detail/{id}', [UserController::class, 'detail'])->name('user_detail');
        Route::get('/user_delete_image', [UserController::class, 'user_delete_image'])->name('user_delete_image');
        Route::post('/users/bulk-coach-permission', [UserController::class, 'bulkCoachPermission']);

        /*-----------------------Admin User routes End---------------------*/

        /*---------------------Admin coatc routes Start---------------------*/
        Route::get('/coach/add/{id?}', [CoachController::class, 'add'])->name('coach_add');
        Route::post('/coach/save/{id?}', [CoachController::class, 'save'])->name('coach_save');
        Route::get('/coach', [CoachController::class, 'index'])->name('coach');
        Route::get('/coach-data', [CoachController::class, 'anydata'])->name('coach_data');
        Route::get('/coach/delete', [CoachController::class, 'delete'])->name('coach_delete');
        Route::get('/coach/status', [CoachController::class, 'changeStatus'])->name('coach_status');
        Route::get('/coach/detail/{id}', [CoachController::class, 'detail'])->name('coach_detail');

        /*---------------------Admin referee routes Start---------------------*/
        Route::get('/referee/add/{id?}', [RefereeController::class, 'add'])->name('referee_add');
        Route::post('/referee/save/{id?}', [RefereeController::class, 'save'])->name('referee_save');
        Route::get('/referee', [RefereeController::class, 'index'])->name('referee');
        Route::get('/referee-data', [RefereeController::class, 'anydata'])->name('referee_data');
        Route::get('/referee/delete', [RefereeController::class, 'delete'])->name('referee_delete');
        Route::get('/referee/status', [RefereeController::class, 'changeStatus'])->name('referee_status');
        Route::get('/referee/detail/{id}', [RefereeController::class, 'detail'])->name('referee_detail');

        /*---------------------Admin Shoper routes Start---------------------*/
        Route::get('/shoper/add/{id?}', [ShoperController::class, 'add'])->name('shoper_add');
        Route::post('/shoper/save/{id?}', [ShoperController::class, 'save'])->name('shoper_save');
        Route::get('/shoper', [ShoperController::class, 'index'])->name('shoper');
        Route::get('/shoper-data', [ShoperController::class, 'anydata'])->name('shoper_data');
        Route::get('/shoper/delete', [ShoperController::class, 'delete'])->name('shoper_delete');
        Route::get('/shoper/status', [ShoperController::class, 'changeStatus'])->name('shoper_status');
        Route::get('/shoper/detail/{id}', [ShoperController::class, 'detail'])->name('shoper_detail');

        Route::get('/contact-request', [EnqueryController::class, 'contact_request'])->name('contact_request');
        Route::any('/contact-data', [EnqueryController::class, 'contact_request_data'])->name('contact_data');
        Route::any('/delete-enquery', [EnqueryController::class, 'delete'])->name('delete_enquery');
        Route::any('/contact-data/view_message', [EnqueryController::class, 'view_message'])->name('view_message');

        Route::get('/send_mail', [EnqueryController::class, 'send_mail'])->name('send_mail');
        Route::post('/mail_send_newsletter', [EnqueryController::class, 'mail_send_newsletter'])->name('mail_send_newsletter');

        Route::any('reg_form_single/{id}',[AdminCardController::class,'reg_form_single'])->name('admin.group_reg_form');
        Route::any('single_card/{id}',[AdminCardController::class,'single_card'])->name('admin.single_card');
        
    	Route::any('all_single_reg_form/{id}',[AdminCardController::class,'all_single_reg_form'])->name('admin.all_single_reg_form');
    	Route::any('all_single_card/{id}',[AdminCardController::class,'all_single_card'])->name('admin.all_single_card');


        Route::post('/update-match-slot', [DrawSheetController::class, 'updateMatchSlot'])->name('update.match.slot');


       
    });
});
