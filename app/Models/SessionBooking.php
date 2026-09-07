<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DateTime;


class SessionBooking extends Model
{
    use HasFactory;

    protected $appends = ['user_start_time_timezone','user_start_time','user_end_time'];

    function get_doctor()
    {
        return $this->belongsTo('App\Models\User', 'vender_id');
    }

    function get_patient()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    function get_pre_form()
    {
        return $this->belongsTo('App\Models\ConsultForm', 'session_id');
    }

    public function getUserStartTimeTimezoneAttribute(){
        $doctor = User::find($this->vender_id);
        $patient = User::find($this->user_id);

        $doctorTimezone = $doctor->timezone;
        $patientTimezone = $patient->user_timezone;

        $currentDate = Carbon::now()->format('Y-m-d');
        

        $startDateTime = Carbon::createFromFormat('Y-m-d H:i', $currentDate . ' ' . $this->start_time, $doctorTimezone);
       
        // Convert to patient's timezone
        $startDateTime->setTimezone($patientTimezone);
        
        $startTime = $startDateTime->format('h:i a');
       
        return $startTime;
        
    }

    public function getUserStartTimeAttribute(){
        $doctor = User::find($this->vender_id);
        $patient = User::find($this->user_id);

        $doctorTimezone = $doctor->timezone;
        $patientTimezone = $patient->user_timezone;

        $currentDate = Carbon::now()->format('Y-m-d');
        

        $startDateTime = Carbon::createFromFormat('Y-m-d H:i', $currentDate . ' ' . $this->start_time, $doctorTimezone);
       
        // Convert to patient's timezone
        $startDateTime->setTimezone($patientTimezone);
        
        $startTime = $startDateTime->format('H:i');
       
        return $startTime;
        
    }

    public function getUserEndTimeAttribute(){
        $doctor = User::find($this->vender_id);
        $patient = User::find($this->user_id);

        $doctorTimezone = $doctor->timezone;
        $patientTimezone = $patient->user_timezone;

        $currentDate = Carbon::now()->format('Y-m-d');
        

        $startDateTime = Carbon::createFromFormat('Y-m-d H:i', $currentDate . ' ' . $this->end_time, $doctorTimezone);
       
        // Convert to patient's timezone
        $startDateTime->setTimezone($patientTimezone);
        
        $startTime = $startDateTime->format('H:i');
       
        return $startTime;
        
    }
}
