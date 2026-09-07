<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\User;
use App\Models\SessionBooking;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe; // Import the Stripe class
use Stripe\StripeClient;
use Stripe\Transfer;
use Stripe\Balance;

class Settlement extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'settlement:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::info('test');
        $currentDate = Carbon::now();
        $session = SessionBooking::where('status','active')->whereDate('booking_date', $currentDate)->get();
        Stripe::setApiKey('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');
        if(count($session)>0){
            foreach($session as $sess){
                $user = User::where('id',$sess->vender_id)->first();
    
                if ($user && $user->commission > 0 && !empty($user->stripe_account_id)) {
                    $comm = ($sess->session_price * $user->commission) / 100;
                    $amount_price = $sess->session_price - $comm;
    
                    $balance = Balance::retrieve();
                    Log::info($balance->available[0]->amount);
    
                    if ($balance->available[0]->amount >= $amount_price*100 ) { 
                        $transfer = Transfer::create([
                            'amount' => number_format($amount_price, 2)*100, // Amount in cents
                            'currency' => 'aud',
                            'destination' => $user->stripe_account_id,
                        ]);
                        Log::info('Balance Transfer');
                    }else{
                        Log::info('Insufficient balance');
                    }
                } else {
                    Log::error('Invalid commission rate for user ID: ' . $sess->vender_id);
                }
            } 
        }else{
            Log::error('Not Any session booking');
        }
        
    }
}

