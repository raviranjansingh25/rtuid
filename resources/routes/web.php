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
use App\Http\Controllers\admin\PractitionerController;
use App\Http\Controllers\admin\QualificationsController;
use App\Http\Controllers\admin\AriaIntrestController;
use App\Http\Controllers\admin\LanguageController;
use App\Http\Controllers\admin\CourseController;
use App\Http\Controllers\admin\BlogController;
use App\Http\Controllers\admin\CoachController;
use App\Http\Controllers\admin\CouponController;
use App\Http\Controllers\admin\ConsultController;
use App\Http\Controllers\admin\EmailPageController;
use App\Http\Controllers\admin\EventController;
use App\Http\Controllers\admin\EventCategoryController;
use App\Http\Controllers\admin\TournamentController;

use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\PageController;
use App\Http\Controllers\admin\BannerController;
use App\Http\Controllers\admin\PackageController;
use App\Http\Controllers\admin\TagController;
use App\Http\Controllers\admin\SpecialitiesController;
use App\Http\Controllers\MPESAController;
use App\Http\Controllers\admin\RevenueController;

use App\Http\Controllers\coach\auth\LoginController as CoachLoginController;
use App\Http\Controllers\coach\dashboardController as CoachDashboardController;
use App\Http\Controllers\coach\UserController as CoachUserController;

use App\Http\Controllers\frontend\HomeController;
use App\Http\Controllers\frontend\VenderController;
use App\Http\Controllers\frontend\WebPageController;
use App\Http\Controllers\frontend\UserController as WebUserController;


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
Route::any('/practitioners', [WebPractitionerController::class, 'index'])->name('practitioners');
Route::get('/practitioners-detail/{id}', [WebPractitionerController::class, 'detail']);
Route::any('/newsletter', [HomeController::class, 'newsletter'])->name('newsletter');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::any('/contact-enquery', [HomeController::class, 'contact_enquery'])->name('contact_enquery');

Route::get('/about-us', [WebPageController::class, 'about'])->name('about');
Route::get('/faq', [WebPageController::class, 'web_faq'])->name('web_faq');
Route::get('/register', [HomeController::class, 'register'])->name('register');
Route::get('/blog', [WebBlogController::class, 'web_blog'])->name('web_blog');
Route::get('/blog_detail/{id}', [WebBlogController::class, 'web_blog_detail'])->name('web_blog_detail');
Route::get('/privacy-policy', [WebPageController::class, 'privacy_policy'])->name('privacy_policy');
Route::get('/terms-and-condition', [WebPageController::class, 'terms_condition'])->name('terms_condition');
Route::get('/why-join-telimed/{id?}', [WebPageController::class, 'why_join'])->name('why_join');
Route::any('/filter', [WebPractitionerController::class, 'filter'])->name('filter');
Route::any('/chek_availability', [UserBookingController::class, 'chek_availability'])->name('chek_availability');
Route::get('/imagecroper', [HomeController::class, 'imageCroper'])->name('imageCroper');

Route::get('/callback/gmail', [WebUserController::class, 'handleGoogleCallback'])->name('call_back_gmail');
Route::get('auth/google/{id}', [WebUserController::class, 'redirectToGoogle'])->name('auth.google');

Route::get('/callback/facebook', [WebUserController::class, 'handleFacebookCallback']);
Route::get('auth/facebook/{id}', [WebUserController::class, 'redirectToFacebook'])->name('auth.facebook');
Route::get('/autocomplete', [WebUserController::class, 'autocomplete'])->name('autocomplete');
Route::get('/get-coaches/{district_id}', [WebUserController::class, 'getCoaches']);
Route::get('/resend_otp', [WebUserController::class, 'resend_otp'])->name('resend_otp');

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
    Route::get('/sessions-detail/{id}', [WebUserController::class, 'sessions_detail'])->name('sessions_detail');
    Route::get('/my-favourites', [WebUserController::class, 'my_wishlist'])->name('my_wishlist');




    Route::any('/TreatmentPlanUser_data', [WebUserController::class, 'TreatmentPlan_data'])->name('TreatmentPlanUser_data');


    Route::any('/consult_form/{id?}', [WebUserController::class, 'consult_form_save'])->name('consult_form_save');


    Route::get('/rebook/{id?}', [WebUserController::class, 'rebook'])->name('rebook');
    Route::get('/reschedule/{id}', [UserBookingController::class, 'reschedule'])->name('reschedule');
    Route::post('/reschedule_save', [UserBookingController::class, 'reschedule_save'])->name('reschedule_save');
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
    /*-----------------------Admin User routes End---------------------*/ 
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

        /*---------------------Admin email pages routes Start---------------------*/
        Route::get('/email/add/{id?}', [EmailPageController::class, 'add'])->name('email_add');
        Route::post('/email/save/{id?}', [EmailPageController::class, 'save'])->name('email_save');
        Route::get('/email', [EmailPageController::class, 'index'])->name('email');
        Route::get('/email-data', [EmailPageController::class, 'anydata'])->name('email_data');
        Route::get('/email/status', [EmailPageController::class, 'changeStatus'])->name('email_status');
        /*-----------------------Admin email pages routes End---------------------*/

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
        
        /*---------------------Admin Category routes Start---------------------*/
        Route::get('/weightcategory/add/{id?}', [WeightCategoryController::class, 'add'])->name('weightcategory_add');
        Route::post('/weightcategory/save/{id?}', [WeightCategoryController::class, 'save'])->name('weightcategory_save');
        Route::get('/weightcategory', [WeightCategoryController::class, 'index'])->name('weightcategory');
        Route::any('/weightcategory-data', [WeightCategoryController::class, 'anydata'])->name('weightcategory_data');
        Route::get('/weightcategory/delete', [WeightCategoryController::class, 'delete'])->name('weightcategory_delete');
        Route::get('/weightcategory/status', [WeightCategoryController::class, 'changeStatus'])->name('weightcategory_status');
        /*-----------------------Admin weightcategory routes End---------------------*/

        /*---------------------Admin Specialities routes Start---------------------*/
        Route::get('/specialities/add/{id?}', [SpecialitiesController::class, 'add'])->name('specialities_add');
        Route::post('/specialities/save/{id?}', [SpecialitiesController::class, 'save'])->name('specialities_save');
        Route::get('/specialities', [SpecialitiesController::class, 'index'])->name('specialities');
        Route::any('/specialities-data', [SpecialitiesController::class, 'anydata'])->name('specialities_data');
        Route::get('/specialities/delete', [SpecialitiesController::class, 'delete'])->name('specialities_delete');
        Route::get('/specialities/status', [SpecialitiesController::class, 'changeStatus'])->name('specialities_status');
        /*-----------------------Admin Specialities routes End---------------------*/
        
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
        Route::post('/tournament/save/{id?}', [TournamentController::class, 'save'])->name('tournament_save');
        Route::get('/tournament', [TournamentController::class, 'index'])->name('tournament');
        Route::any('/tournament-data', [TournamentController::class, 'anydata'])->name('tournament_data');
        Route::get('/tournament/delete', [TournamentController::class, 'delete'])->name('tournament_delete');
        Route::get('/tournament/status', [TournamentController::class, 'changeStatus'])->name('tournament_status');
        
        Route::get('/tournament/edit/{id?}', [TournamentController::class, 'edit'])->name('tournament_edit');
        Route::post('/tournament_edit/save/{id?}', [TournamentController::class, 'editsave'])->name('edit_tournament_save');
         Route::get('/tournament_name/{id}', [TournamentController::class, 'indexname'])->name('tournament_name');
        Route::any('/tournament_name-data/{id}', [TournamentController::class, 'anydataname'])->name('tournament_name_data');
        /*-----------------------Admin Language routes End---------------------*/

        /*---------------------Admin Tag routes Start---------------------*/
        Route::get('/tag/add/{id?}', [TagController::class, 'add'])->name('tag_add');
        Route::post('/tag/save/{id?}', [TagController::class, 'save'])->name('tag_save');
        Route::get('/tag', [TagController::class, 'index'])->name('tag');
        Route::any('/tag-data', [TagController::class, 'anydata'])->name('tag_data');
        Route::get('/tag/delete', [TagController::class, 'delete'])->name('tag_delete');
        Route::get('/tag/status', [TagController::class, 'changeStatus'])->name('tag_status');
        /*-----------------------Admin Tag routes End---------------------*/


        /*---------------------Admin consult  routes Start---------------------*/
        Route::get('/consult/add/{id?}', [ConsultController::class, 'add'])->name('consult_add');
        Route::post('/consult/save/{id?}', [ConsultController::class, 'save'])->name('consult_save');
        Route::get('/consult', [ConsultController::class, 'index'])->name('consult');
        Route::any('/consult-data', [ConsultController::class, 'anydata'])->name('consult_data');
        Route::get('/consult/delete', [ConsultController::class, 'delete'])->name('consult_delete');
        Route::get('/consult/status', [ConsultController::class, 'changeStatus'])->name('consult_status');
        /*-----------------------Admin consult routes End---------------------*/

        /*---------------------Admin coupon routes Start---------------------*/
        Route::get('/coupon/add/{id?}', [CouponController::class, 'add'])->name('coupon_add');
        Route::post('/coupon/save/{id?}', [CouponController::class, 'save'])->name('coupon_save');
        Route::get('/coupon', [CouponController::class, 'index'])->name('coupon');
        Route::any('/coupon-data', [CouponController::class, 'anydata'])->name('coupon_data');
        Route::get('/coupon/delete', [CouponController::class, 'delete'])->name('coupon_delete');
        Route::get('/coupon/status', [CouponController::class, 'changeStatus'])->name('coupon_status');
        /*-----------------------Admin coupon routes End---------------------*/

        /*---------------------Admin blog routes Start---------------------*/
        Route::get('/blog/add/{id?}', [BlogController::class, 'add'])->name('blog_add');
        Route::post('/blog/save/{id?}', [BlogController::class, 'save'])->name('blog_save');
        Route::get('/blog', [BlogController::class, 'index'])->name('blog');
        Route::any('/blog-data', [BlogController::class, 'anydata'])->name('blog_data');
        Route::get('/blog/delete', [BlogController::class, 'delete'])->name('blog_delete');
        Route::get('/blog/status', [BlogController::class, 'changeStatus'])->name('blog_status');
        /*-----------------------Admin blog routes End---------------------*/

        /*---------------------Admin course routes Start---------------------*/
        Route::get('/course/add/{id?}', [CourseController::class, 'add'])->name('course_add');
        Route::post('/course/save/{id?}', [CourseController::class, 'save'])->name('course_save');
        Route::get('/course', [CourseController::class, 'index'])->name('course');
        Route::any('/course-data', [CourseController::class, 'anydata'])->name('course_data');
        Route::get('/course/delete', [CourseController::class, 'delete'])->name('course_delete');
        Route::get('/course/status', [CourseController::class, 'changeStatus'])->name('course_status');
        Route::get('/course/detail/{id}', [CourseController::class, 'detail'])->name('course_detail');
        /*-----------------------Admin course routes End---------------------*/

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

        /*---------------------Admin Language routes Start---------------------*/
        Route::get('/language/add/{id?}', [LanguageController::class, 'add'])->name('language_add');
        Route::post('/language/save/{id?}', [LanguageController::class, 'save'])->name('language_save');
        Route::get('/language', [LanguageController::class, 'index'])->name('language');
        Route::any('/language-data', [LanguageController::class, 'anydata'])->name('language_data');
        Route::get('/language/delete', [LanguageController::class, 'delete'])->name('language_delete');
        Route::get('/language/status', [LanguageController::class, 'changeStatus'])->name('language_status');
        /*-----------------------Admin Language routes End---------------------*/

        /*---------------------Admin banner routes Start---------------------*/
        Route::get('/aria/add/{id?}', [AriaIntrestController::class, 'add'])->name('aria_add');
        Route::post('/aria/save/{id?}', [AriaIntrestController::class, 'save'])->name('aria_save');
        Route::get('/aria', [AriaIntrestController::class, 'index'])->name('aria');
        Route::any('/aria-data', [AriaIntrestController::class, 'anydata'])->name('aria_data');
        Route::get('/aria/delete', [AriaIntrestController::class, 'delete'])->name('aria_delete');
        Route::get('/aria/status', [AriaIntrestController::class, 'changeStatus'])->name('aria_status');
        /*-----------------------Admin banner routes End---------------------*/

        /*---------------------Admin banner routes Start---------------------*/
        Route::get('/qualification/add/{id?}', [QualificationsController::class, 'add'])->name('qualification_add');
        Route::post('/qualification/save/{id?}', [QualificationsController::class, 'save'])->name('qualification_save');
        Route::get('/qualification', [QualificationsController::class, 'index'])->name('qualification');
        Route::any('/qualification-data', [QualificationsController::class, 'anydata'])->name('qualification_data');
        Route::get('/qualification/delete', [QualificationsController::class, 'delete'])->name('qualification_delete');
        Route::get('/qualification/status', [QualificationsController::class, 'changeStatus'])->name('qualification_status');
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

        /*---------------------Admin practitioner routes Start---------------------*/
        Route::get('/practitioner/add/{id?}', [PractitionerController::class, 'add'])->name('practitioner_add');
        Route::post('/practitioner/save/{id?}', [PractitionerController::class, 'save'])->name('practitioner_save');
        Route::get('/practitioner', [PractitionerController::class, 'index'])->name('practitioner');
        Route::get('/practitioner-data', [PractitionerController::class, 'anydata'])->name('practitioner_data');
        Route::get('/practitioner/delete', [PractitionerController::class, 'delete'])->name('practitioner_delete');
        Route::get('/practitioner/status', [PractitionerController::class, 'changeStatus'])->name('practitioner_status');
        Route::get('/practitioner/feature', [PractitionerController::class, 'changefeature'])->name('practitioner_feature');
        Route::get('/practitioner/detail/{id}', [PractitionerController::class, 'detail'])->name('practitioner_detail');
        Route::get('/practitioner_delete_image', [PractitionerController::class, 'practitioner_delete_image'])->name('practitioner_delete_image');
        Route::any('/vender/set_commission', [PractitionerController::class, 'set_commission'])->name('set_commission');
        Route::any('/practitioner/session_count', [PractitionerController::class, 'session_count'])->name('session_count');
        /*-----------------------Admin practitioner routes End---------------------*/

        Route::get('/contact-request', [EnqueryController::class, 'contact_request'])->name('contact_request');
        Route::any('/contact-data', [EnqueryController::class, 'contact_request_data'])->name('contact_data');
        Route::any('/delete-enquery', [EnqueryController::class, 'delete'])->name('delete_enquery');
        Route::any('/contact-data/view_message', [EnqueryController::class, 'view_message'])->name('view_message');

        Route::get('/Practitioner-review', [ReviewController::class, 'index'])->name('practitioner_review');
        Route::any('/review-data', [ReviewController::class, 'anydata'])->name('review_data');
        Route::any('/delete-review', [ReviewController::class, 'delete'])->name('delete_review');
        Route::any('/review-data/view_message', [ReviewController::class, 'view_message'])->name('review_view_message');

        Route::get('/send_mail', [EnqueryController::class, 'send_mail'])->name('send_mail');
        Route::post('/mail_send_newsletter', [EnqueryController::class, 'mail_send_newsletter'])->name('mail_send_newsletter');

        Route::get('/revenue', [RevenueController::class, 'index'])->name('revenue.data');
        Route::any('/getRevenueData', [RevenueController::class, 'anydata'])->name('getRevenueData');
        Route::get('/income_index', [RevenueController::class, 'income_index'])->name('income_index');
        Route::any('/getIncomeData', [RevenueController::class, 'income_anydata'])->name('admin.getIncomeData');
        Route::get('/booking-invoice/{id}', [RevenueController::class, 'booking_invoice'])->name('admin.booking_invoice');
        Route::get('/pre_consult_form/{id}', [RevenueController::class, 'pre_consult_form'])->name('admin.pre_consult_form');
    });
});
