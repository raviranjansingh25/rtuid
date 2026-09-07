<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\GenresController;
use App\Http\Controllers\Admin\SubcategoryController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PurchageController;
use App\Http\Controllers\Frontend\UserController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\FileController;
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

Route::group(['middleware' => 'user'], function ()
    {
    Route::get('/',[UserController::class, 'index'])->name('home');
    Route::any('/login',[UserController::class, 'login'])->name('login_save');
    Route::any('/forgot_password',[UserController::class, 'forgot_password'])->name('forgot_password');
    Route::any('/send_varification',[UserController::class, 'send_varification'])->name('send_varification');
    Route::any('/otp/{id}',[UserController::class, 'otp'])->name('otp');
    Route::any('/match_otp',[UserController::class, 'match_otp'])->name('match_otp');
    Route::any('/reset_password/{id}',[UserController::class, 'reset_password'])->name('reset_password');
    Route::any('/reset-password-save',[UserController::class, 'reset_password_save'])->name('reset_password_save');
});


Route::group(['middleware' => 'usernot'], function ()
{
    Route::any('/index',[HomeController::class, 'index'])->name('index');
    Route::get('/update-account',[HomeController::class, 'update_account'])->name('update_account');
    Route::post('/account-update',[HomeController::class, 'account_update'])->name('account_update');
    Route::any('/admin/stripe_callback', [HomeController::class, 'stripe_callback'])->name('stripe_callback');
    Route::any('/search',[HomeController::class, 'search'])->name('search');

    Route::get('/upload-file/{id?}',[FileController::class, 'upload_file'])->name('upload_file');
    Route::any('/upload-file/save',[FileController::class, 'upload_file_save'])->name('upload_file_save');
    Route::get('/file-delete',[FileController::class, 'file_delete'])->name('file_delete');
    
    Route::get('/profile',[UserController::class, 'profile'])->name('profile');
    Route::get('/logout',[UserController::class, 'logout'])->name('user_logout');
    Route::get('/type_update',[UserController::class, 'type_update'])->name('type_update');
    Route::any('/all-delete',[UserController::class, 'all_delete'])->name('all_delete');
    Route::any('/payment-history',[UserController::class, 'payment_history'])->name('payment_history'); 
});


//admin routes
Route::group(['prefix' => 'admin','middleware'=>'ifnotadmin'],function()
{
    Route::get('/',[LoginController::class, 'index'])->name('adminlogin');
    Route::post('/login-save',[LoginController::class, 'save'])->name('loginsave');
    Route::post('/login-save_image',[LoginController::class, 'loginsaveimage'])->name('loginsaveimage');
});

Route::group(['prefix' => 'admin','middleware'=>'ifadmin'],function()
{
	Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
	Route::get('/view-profile',[LoginController::class, 'view_profile'])->name('view_profile');
    Route::post('/update-profile',[LoginController::class, 'update_profile'])->name('update_profile');
    Route::any('/admin_delete_image',[LoginController::class, 'admin_delete_image'])->name('admin_delete_image');
    Route::get('/change-password',[LoginController::class, 'change_password'])->name('change_password');
    Route::post('/change-password/save',[LoginController::class, 'change_password_save'])->name('change_password_save');
	Route::get('/setting',[SettingController::class, 'index'])->name('setting');
    Route::post('/setting-save',[SettingController::class, 'save'])->name('setting_save');
	Route::get('/logout',[LoginController::class, 'logout'])->name('adminlogout');


	/*---------------------Admin User routes Start---------------------*/
    Route::get('/user/add/{id?}',[UsersController::class, 'add'])->name('user_add');
    Route::post('/user/save/{id?}',[UsersController::class, 'save'])->name('user_save');
    Route::get('/user',[UsersController::class, 'index'])->name('user');
    Route::get('/user-data',[UsersController::class, 'anydata'])->name('user_data');
    Route::get('/user/delete',[UsersController::class, 'delete'])->name('user_delete');
    Route::get('/user/status',[UsersController::class, 'changeStatus'])->name('user_status');
    Route::get('/user/detail/{id}',[UsersController::class, 'detail'])->name('user_detail');
    Route::get('/user_delete_image',[UsersController::class, 'user_delete_image'])->name('user_delete_image');
    /*-----------------------Admin User routes End---------------------*/

    /*---------------------Admin pages routes Start---------------------*/
    Route::get('/page/add/{id?}',[PageController::class, 'add'])->name('page_add');
    Route::post('/page/save/{id?}',[PageController::class, 'save'])->name('page_save');
    Route::get('/page',[PageController::class, 'index'])->name('page');
    Route::get('/page-data',[PageController::class, 'anydata'])->name('page_data');
    Route::get('/page/status',[PageController::class, 'changeStatus'])->name('page_status');
    /*-----------------------Admin pages routes End---------------------*/

    /*---------------------Admin Category routes Start---------------------*/
    Route::get('/category/add/{id?}',[CategoryController::class, 'add'])->name('category_add');
    Route::post('/category/save/{id?}',[CategoryController::class, 'save'])->name('category_save');
    Route::get('/category',[CategoryController::class, 'index'])->name('category');
    Route::any('/category-data',[CategoryController::class, 'anydata'])->name('category_data');
    Route::get('/category/delete',[CategoryController::class, 'delete'])->name('category_delete');
    Route::get('/category/status',[CategoryController::class, 'changeStatus'])->name('category_status');
    Route::get('/category_delete_image',[CategoryController::class, 'category_delete_image'])->name('category_delete_image');
    /*-----------------------Admin Category routes End---------------------*/

    /*---------------------Admin Sub Category routes Start---------------------*/
    Route::get('/sub-category/add/{id?}',[SubcategoryController::class, 'add'])->name('subcategory_add');
    Route::post('/sub-category/save/{id?}',[SubcategoryController::class, 'save'])->name('subcategory_save');
    Route::get('/sub-category',[SubcategoryController::class, 'index'])->name('subcategory');
    Route::any('/sub-category-data',[SubcategoryController::class, 'anydata'])->name('subcategory_data');
    Route::get('/sub-category/delete',[SubcategoryController::class, 'delete'])->name('subcategory_delete');
    Route::get('/sub-category/status',[SubcategoryController::class, 'changeStatus'])->name('subcategory_status');
    /*-----------------------Admin Sub Category routes End---------------------*/

    /*---------------------Admin Genres routes Start---------------------*/
    Route::get('/genres/add/{id?}',[GenresController::class, 'add'])->name('genres_add');
    Route::post('/genres/save/{id?}',[GenresController::class, 'save'])->name('genres_save');
    Route::get('/genres',[GenresController::class, 'index'])->name('genres');
    Route::any('/genres-data',[GenresController::class, 'anydata'])->name('genres_data');
    Route::get('/genres/delete',[GenresController::class, 'delete'])->name('genres_delete');
    Route::get('/genres/status',[GenresController::class, 'changeStatus'])->name('genres_status');
    /*-----------------------Admin Genres routes End---------------------*/

    /*---------------------Admin Media routes Start---------------------*/
    Route::get('/media/add/{id?}',[MediaController::class, 'add'])->name('media_add');
    Route::post('/media/save/{id?}',[MediaController::class, 'save'])->name('media_save');
    Route::get('/media',[MediaController::class, 'index'])->name('media');
    Route::any('/media-data',[MediaController::class, 'anydata'])->name('media_data');
    Route::get('/media/delete',[MediaController::class, 'delete'])->name('media_delete');
    Route::get('/media/status',[MediaController::class, 'changeStatus'])->name('media_status');
    Route::any('/media_delete_image',[MediaController::class, 'media_delete_image'])->name('media_delete_image');
    Route::any('/media/detail/{id}',[MediaController::class, 'detail'])->name('media_detail');
    /*-----------------------Admin Media routes End---------------------*/
    Route::get('/user-audio/video',[MediaController::class, 'user_media'])->name('user_media');
    Route::any('/audio-video-data',[MediaController::class, 'user_media_data'])->name('user_media_data');

    Route::get('/transaction_history',[PurchageController::class, 'transaction_history'])->name('transaction_history');
    Route::any('/transaction_data',[PurchageController::class, 'transaction_data'])->name('transaction_data');
    Route::any('/invoice/{id}',[PurchageController::class, 'detail']);
});