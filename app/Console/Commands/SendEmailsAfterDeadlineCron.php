<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use App\Mail\BidCongratulationsMail;
use App\Mail\BidThankYouMail;
use Illuminate\Support\Facades\Mail;

class SendEmailsAfterDeadlineCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:send-emails-after-deadline-cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $products_with_deadlines_exceeded = Product::where('deadline', '<', now())
                                                    ->where('bid_ended', false)
                                                    ->with([
                                                            'bids' => function($query1){
                                                                return $query1->orderBy('bidding_price', 'desc');
                                                            },
                                                            'bids.bidder' => function($query2){
                                                                return $query2->where('verified', true);
                                                            }
                                                        ])
                                                    ->get();

        foreach($products_with_deadlines_exceeded as $product){
            foreach($product->bids as $key => $bid){
                if($key == 0){
                    $info['usercode'] = $bid->bidder->usercode;
                    $info['subject'] = "Congratulations ! You won our online bid.";
                    $info['title'] = $product->title;
                    $info['bidding_price'] = $bid->bidding_price;
                    Mail::to($bid->bidder->email)->send(new BidCongratulationsMail($info));
                }
                else{
                    $info['usercode'] = $bid->bidder->usercode;
                    $info['subject'] = "Alas you didn't win our online bid.";
                    $info['title'] = $product->title;
                    Mail::to($bid->bidder->email)->send(new BidThankYouMail($info));
                }
            }
            $product->bid_ended = true;
            $product->save();
        }
    }
}
