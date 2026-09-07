<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\GroupPermission;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use File;
use Auth;
use session;

class PermissionController extends Controller
{
    public function view()
    {

        $role_id = Auth::guard('admin')->user()->id;

        $routeArray = app('request')->route()->getAction();
        $cpath = app_path('Http/Controllers/admin');
        $cfiles = File::files($cpath);
        $controllers = [];
        foreach ($cfiles as $key => $value) {
            $controllers[] =   basename($value->getFilename(), '.php');
        }


        $admin = Admin::where('status', 1)->where('id', '>', 1)->get();

        $data = array(
            'saveurl' => route('savepermission'),
            'page_title' => 'Sub Admin Permissions',
            'title' => 'Sub Admin permission',
            'admingroup' => $admin,
            'controllers' => $controllers,
        );
        return view('admin.permission')->with($data);
    }

    public function save(Request $request)
    {
        $subadmin_id = $request->subadmin_id;
        // p($subadmin_id);

        

        foreach ($subadmin_id as $sub){
            $controller = $request->controller;
            $permissiondata = GroupPermission::where(['subadmin_id' => $sub, 'permission' => 1])->get();
            $perdata = $permissiondata->pluck('controller')->toArray();
            $notmatch = array_diff($perdata, $controller);
            foreach ($controller as $condata) {
                $permissiondata = GroupPermission::where('subadmin_id', $sub)->where('controller', $condata)->first();
                if ($permissiondata != "") {
    
                    $permissiondata->permission = "1";
                    $permissiondata->save();
                } else {
    
                    $datainsert = new GroupPermission;
                    $datainsert->subadmin_id = $sub;
                    $datainsert->controller = $condata;
                    $datainsert->permission = "1";
                    $datainsert->save();
                }
                foreach ($notmatch as $notmatchdata) {
                    $pernotdata = GroupPermission::where('subadmin_id', $sub)->where('controller', $notmatchdata)->first();
                    $pernotdata->permission = "2";
                    $pernotdata->save();
                }
            }
        }
        

        session()->forget('permission');

        return back();
    }

    public function denied()
    {
        $data = array(

            'page_title' => 'Dashboard',
            'title' => 'Dashboard',
        );
        return view('admin.denied')->with($data);
    }

    public function checkdata(Request $request)
    {
        $group_id = $request['group_id'];

        $permissiondata = GroupPermission::where(['subadmin_id' => $group_id, 'permission' => 1])->get();
        $perdata = $permissiondata->pluck('controller')->toArray();

        $routeArray = app('request')->route()->getAction();
        $cpath = app_path('Http/Controllers/admin');
        $cfiles = File::files($cpath);
        $controllers = [];
        foreach ($cfiles as $key => $value) {
            $controllers[] =   basename($value->getFilename(), '.php');
        }

        $notmatch = array_diff($controllers, $perdata);
        $match = array_intersect($controllers, $perdata);

        $checkdata = "";

        if ($group_id == 1) {
            foreach ($controllers as $key => $condata) { ?>
                <?php
                $cutController = str_replace("Controller", "", $controller_data);
                $spacedString = preg_replace('/([a-z])([A-Z])/', '$1 $2', $cutController);
                ?>
                <tr>
                    <th><input type="checkbox" name="controller[]" value="<?php echo $condata ?>" checked class="checkdata accent"></th>
                    <td><?php echo $spacedString ?></td>
                </tr>
            <?php }
        } else {
            foreach ($match as $matchdata) { ?>
                <?php
                $cutController = str_replace("Controller", "", $matchdata);
                $spacedString = preg_replace('/([a-z])([A-Z])/', '$1 $2', $cutController);
                ?>
                <tr>
                    <th><input type="checkbox" name="controller[]" checked value="<?php echo $matchdata ?>" class="checkdata accent"></th>
                    <td><?php echo $spacedString ?></td>
                </tr>
            <?php }
            foreach ($notmatch as $notmatchdata) { ?>
                <?php
                $cutController = str_replace("Controller", "", $notmatchdata);
                $spacedString = preg_replace('/([a-z])([A-Z])/', '$1 $2', $cutController);
                ?>
                <tr>
                    <th><input type="checkbox" name="controller[]" value="<?php echo $notmatchdata ?>" class="checkdata accent"></th>
                    <td><?php echo $spacedString ?></td>
                </tr>
<?php }
        }
    }
}
