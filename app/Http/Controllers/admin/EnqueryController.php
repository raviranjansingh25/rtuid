<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Associate;
use App\Models\UserReview;
use App\Models\ContactEnquery;
use App\Models\NewsLetter;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use DataTables;
use Validator;
use File;
use Intervention\Image\Facades\Image;
use Mail;
use App\Mail\TestimonialEmail;

class EnqueryController extends Controller
{
    public function index()
    {
        $data = array(
            'title' => 'Associate Inquiry',
            'page_title' => 'Associate Inquiry',
        );
        return view('admin.enquery.associate')->with($data);
    }
    public function hospital_request()
    {
        $data = array(
            'title' => 'Hospital Enquery',
            'page_title' => 'Hospital Request',
        );
        return view('admin.enquery.hospital')->with($data);
    }
    public function doctor_request()
    {
        $data = array(
            'title' => 'Doctors Enquery',
            'page_title' => 'Doctors Enquery',
        );
        return view('admin.enquery.doctor')->with($data);
    }
    public function specialities_request()
    {
        $data = array(
            'title' => 'Specialities Enquery',
            'page_title' => 'Specialities Enquery',
        );
        return view('admin.enquery.specialities')->with($data);
    }

    public function anydata(Request $request)
    {
        $anydata = [];
        $anydata = Associate::orderBy('id', 'DESC')->get();

        return Datatables::of($anydata)



            // ->rawColumns()
            ->addIndexColumn()->make(true);
    }

    public function contact_request()
    {
        $data = array(
            'title' => 'Contact Inquiry',
            'page_title' => 'Contact Inquiry',
        );
        return view('admin.enquery.contact')->with($data);
    }

    public function contact_request_data(Request $request)
    {
        $anydata = [];
        $anydata = ContactEnquery::orderBy('id', 'DESC')->get();
        return Datatables::of($anydata)

            ->addColumn('view', function ($anydata) {

                return '<span onclick="viewdata(' . $anydata->id . ')"  class="btn btn-success btn-rounded btn-sm waves-effect waves-light">View</span>';
            })

            ->addColumn('date', function ($anydata) {
                return date_format($anydata->created_at, "d-M-Y");
            })
            ->addColumn('action', function ($anydata) {

                $action = '
                        <i class="fas fa-trash-alt text-danger delete-button" id="deletebtn" data-id="' . $anydata->id . '" data-toggle="tooltip" data-placement="bottom" title="Delete"></i>';
                return $action;
            })
            ->rawColumns(['date', 'view', 'action'])
            ->addIndexColumn()->make(true);
    }

    public function view_message(Request $request)
    {
        $id   = $request['id'];

        $data  =  ContactEnquery::find($id);
        if ($data) {
            $return_arr = array(
                'status' => 'success',
                'message' => $data->message,
            );
            return response()->json($return_arr);
        }
    }

    public function delete(Request $request)
    {
        $id = $request['id'];
        $data = ContactEnquery::find($id);
        if ($data) {

            $data->delete();
            $return_arr = array(
                'status' => 'success',
                'message' => 'Enquery Deleted Sussessfully!',
            );
            return response()->json($return_arr);
        }
    }

    public function send_mail()
    {
        $data = array(
            'title' => 'Send Mail',
            'page_title' => 'Send Mail',
        );
        return view('admin.enquery.mail')->with($data);
    }

    public function mail_send_newsletter(Request $request)
    {
        $Validatior = Validator::make($request->all(), [
            'subject'      => 'required',
            'editor1'     => 'required',
        ]);

        if ($Validatior->fails()) {
            return back()->withInput()->withErrors($Validatior);
        }
        try {
            $user_email = NewsLetter::get();
            foreach ($user_email as $key => $user) {
                $mailData = [
                    'subject' => $request['subject'],
                    'message' => $request['editor1'],
                ];
                Mail::to($user->email)->send(new TestimonialEmail($mailData));
            }

            $success_msg   = 'Mail Send Successfully.';
            return redirect()->back()->withSuccess($success_msg);
            exit;
        } catch (\Exception $e) {
            $error_message = $e->getMessage();
            // p($error_message);
            return response()->json([
                'message' => $error_message,
                'status' => '0'
            ], 500);
            exit;
        }
    }
}
