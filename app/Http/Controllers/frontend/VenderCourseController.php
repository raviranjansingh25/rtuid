<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TempUser;
use App\Models\Tags;
use App\Models\Course;
use App\Models\CourseImage;
use App\Models\CourseInclude;
use App\Models\CourseBooking;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Exception;
use Cache;
use Illuminate\Support\Facades\Auth;
use Validator;
use File;

class VenderCourseController extends Controller
{
    public function create_course($id = Null)
    {
        $decrypted_id = get_decrypted_value($id, true);
        $getdata = Course::find($decrypted_id);
        $tags = Tags::where('status', 1)->get();
        $vender = auth()->guard('vender')->user();
        if ($id != "") {
            $saveurl = url('/practitioner_save_course/' . $id);
            $page_title = 'Update Course';
        } else {
            $saveurl = url('/practitioner_save_course');
            $page_title = 'Create Course';
        }

        $data = array(
            'title' => 'My Profile',
            'user' => $vender,
            'getdata'    => $getdata,
            'saveurl'    => $saveurl,
            'tags'    => $tags,

        );
        return view('frontend.vender.create_course')->with($data);
    }

    public function course()
    {
        $vender = auth()->guard('vender')->user();

        $course = Course::where('doctor_id', $vender->id)->orderBy('id', 'DESC')->where('status', 1)->get();
        $data = array(
            'title' => 'My Course',
            'course' => $course,

        );
        if (count($course) > 0) {
            return view('frontend.vender.my_courses')->with($data);
        } else {
            return view('frontend.vender.no_courses')->with($data);
        }
    }

    public function save_course(Request $request)
    {
        $user_data = Auth::guard('vender')->user();
        $validator = Validator::make($request->all(), [
            'title'      => 'required',
            'price'     => 'required',
            'duration'     => 'required',
            'description'     => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 2, 'message' => $validator->errors()->first()]);
        }
        try {
            $vender = auth()->guard('vender')->user();
            if (!empty($request['course_id'])) {
                $data = Course::find($request['course_id']);
            } else {
                $data = new Course;
            }
            if ($request['image'] != "") {
                File::delete($data->image);
                $file = $request->file('image');
                $name = rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
                $ext = pathinfo($name, PATHINFO_EXTENSION);
                $extensions = ['jpg', 'jpeg', 'png', 'JPEG', 'PNG', 'JPG',];
                if (!in_array($ext, $extensions)) {
                    $status = 'File type is not allowed you have uploaded. Please upload any image !';
                    return back()->withInput()->withErrors($status);
                }
                $request->file('image')->move("uploads/category", $name);
                $data->image = 'uploads/category/' . $name;
            }



            if ($request['file'] != "") {
                File::delete($data->file);
                $file = $request->file('file');
                $name1 = rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
                $ext = pathinfo($name1, PATHINFO_EXTENSION);
                $extensions = ['mp4', 'mov', 'ogg', 'webm'];
                if (!in_array($ext, $extensions)) {
                    $status = 'File type is not allowed you have uploaded. Please upload any video !';
                    return back()->withInput()->withErrors($status);
                }
                $request->file('file')->move("uploads/file", $name1);
                $data->file = 'uploads/file/' . $name1;
            }

            if ($request['course_pdf'] != "") {
                File::delete($data->course_pdf);
                $course_pdf = $request->file('course_pdf');
                $name2 = rand(11111, 99999) . '.' . $course_pdf->getClientOriginalExtension();
                $ext = pathinfo($name2, PATHINFO_EXTENSION);

                $request->file('course_pdf')->move("uploads/course_pdf", $name2);
                $data->course_pdf = 'uploads/course_pdf/' . $name2;
            }



            $data->title       = $request['title'];
            $data->doctor_id       = $vender['id'];
            $data->duration       = $request['duration'];
            $data->price       = $request['price'];
            $data->tags       = isset($request['tags']) ? implode(',', $request['tags']) : '';
            $data->video_link       = $request['video_link'];
            $data->description = $request['description'];

            $data->slug        = slug('Course', $request['title']);
            $data->save();

            if (!empty($request['multiimage'])) {
                foreach ($request['multiimage'] as $img) {
                    $multi = new CourseImage;

                    $name1 = rand(11111, 99999) . '.' . $img->getClientOriginalExtension();

                    $img->move("uploads/course", $name1);
                    $multi->image = 'uploads/course/' . $name1;
                    $multi->course_id = $data->id;
                    $multi->save();  // Save the model instance to the database
                }
            }


            if (!empty($request['policy'])) {
                $product_images = array_values($request['policy']);
                $varientimgdata = CourseInclude::where('course_id', $data->id)->get();
                $imagecolumn = array_column($product_images, 'policy_id');

                $ifimage = $varientimgdata->pluck('id')->toArray();
                $notmatchimage = array_diff($ifimage, $imagecolumn);

                foreach ($product_images as $imgkey => $variantt_img) {
                    // p($variantt_img['image']);
                    if ($imgkey >= 0) {
                        $varianttimg_id = $variantt_img['policy_id'];

                        if ($varianttimg_id != "") {
                            $variantImg = CourseInclude::find($varianttimg_id);
                        } else {
                            $variantImg = new CourseInclude;
                        }


                        $variantImg->course_id     = $data->id;
                        $variantImg->title    = $variantt_img['policy'];
                        $variantImg->save();
                    }
                }
                if (count($notmatchimage) > 0) {
                    foreach ($notmatchimage as $key => $notmatchimagevalue) {
                        $imagedelete = CourseInclude::find($notmatchimagevalue);
                        $imagedelete->delete();
                    }
                }
            }


            return response()->json([
                'status' => 1,
                'message' => 'Course Upload successfully.',
            ]);
        } catch (\Exception $e) {
            $error_message = $e->getMessage();
            return response()->json([
                'message' => $error_message,
                'status' => '0'
            ], 200);
            exit;
        }
    }

    public function course_detail(Request $request, $id)
    {

        try {
            $vender = auth()->guard('vender')->user();
            $data = Course::where('slug', $id)->first();
            $course_per = CourseBooking::where('course_id', $data->id)->where('status', 1)->count();
            $include = CourseInclude::where('course_id', $data->id)->get();
            $data = array(
                'title' => $data->title,
                'data' => $data,
                'course_per' => $course_per,
                'include' => $include,
            );
            return view('frontend.vender.course_detail')->with($data);
        } catch (\Exception $e) {
            $error_message = $e->getMessage();
            return response()->json([
                'message' => $error_message,
                'status' => '0'
            ], 200);
            exit;
        }
    }

    public function course_delete(Request $request)
    {

        try {
            $vender = auth()->guard('vender')->user();
            $id = $request['id'];
            $data = Course::find($id);
            if ($data) {
                $data->status = 3;
                $data->save();
                $return_arr = array(
                    'status' => '1',
                    'message' => 'Course Deleted Sussessfully!',
                );
                return response()->json($return_arr);
            } else {
                $return_arr = array(
                    'status' => '0',
                    'message' => 'Course not found!',
                );
                return response()->json($return_arr);
            }
        } catch (\Exception $e) {
            $error_message = $e->getMessage();
            return response()->json([
                'message' => $error_message,
                'status' => '0'
            ], 200);
            exit;
        }
    }
}
