<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Stevebauman\Location\Facades\Location;
use App\Models\Setting;
use App\Models\Admin;

use App\Models\City;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Request $request)
    {
        $setting = Setting::first();
        $data = array(
            'setting' => $setting

        );
        View::share($data);

        View::composer(['subadmin.layout.sidebar', 'subadmin.layout.header'], function ($view) {
            $userId = Auth::guard('subadmin')->id();
            if (!$userId) {
                return;
            }

            $admin = Admin::find($userId);
            if (!$admin) {
                return;
            }

            $view->with('admin', $admin);
            $view->with('subadminPermissions', [
                'select_all_district' => ((string) ($admin->district ?? '') === 'all')
                    || ((int) ($admin->select_all_district ?? 0) === 1 && (empty($admin->district) || (string) $admin->district === 'all')),
                'add_weight'          => (int) ($admin->add_weight ?? 0) === 1,
                'can_apply'           => (int) ($admin->can_apply ?? 0) === 1,
                'athlete_detail'      => (int) ($admin->athlete_detail ?? 0) === 1,
                'athlete_edit'        => (int) ($admin->athlete_edit ?? 0) === 1,
                'apply_tournament'    => (int) ($admin->apply_tournament ?? 0) === 1 || (int) ($admin->can_apply_tournament ?? 0) === 1,
                'can_coach'           => (int) ($admin->can_coach ?? 0) === 1,
                'can_referee'         => (int) ($admin->can_referee ?? 0) === 1,
                'can_draw_sheet'      => (int) ($admin->can_draw_sheet ?? 0) === 1,
                'can_create_tournament' => (int) ($admin->can_create_tournament ?? 0) === 1,
            ]);
        });
    }
}
