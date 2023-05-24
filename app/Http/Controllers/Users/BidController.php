<?php

namespace App\Http\Controllers\Users;

use App\Models\Bid;
use App\Models\Bidder;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;

class BidController extends Controller
{
   public function make_bid(Request $request){

      $product = Product::where('id', $request->product_id)->first();

      $request->validate([
               'bid_email'     => ['required', 'string', 'email:rfc,dns'],
               'bidding_price' => ['required', 'numeric', 'integer', 'min:'.$product->minimum_bidding_price],
         ], 
         [
               'bidding_price.min' => 'Minimum bidding price is '.$product->minimum_bidding_price.'. Please enter a value greater than that.',
               'bidding_price.max' => 'Bidding price cannot be greater than $999.'
         ]
      );

      $bid_email = $request->bid_email;

      $bid_exists = Bid::where('product_id', $request->product_id)
                     ->whereHas(
                        'bidder', function(Builder $query)use($bid_email){
                           return $query->where('email', $bid_email);
                        }
                     )
                     ->first();

      $bidder = Bidder::where('email', $bid_email)->first();
      
      DB::beginTransaction();

      try {
         if(!$bidder){ //CHECK IF BIDDER WITH THIS EMAIL EXISTS OR NOT
            do {
               $usercode = 'BID' . rand(100000000, 999999999);
               $bid_with_usercode_exists = Bidder::where('usercode', $usercode)->count();
            } while ($bid_with_usercode_exists);

            $bidder = new Bidder();
            $bidder->email          = $request->bid_email;
            $bidder->usercode       = $usercode;
            $bidder->save();
         }
         else{
            
         }

         if($bid_exists){
            return response()->json([
               'status'    => 'success',
               'exists'    => true,
               'bid_value' => $bid_exists->bidding_price,
               'message'   => 'A bid already exists for this email. Replace existing bid ?',
            ]);
         }
         else{
            $new_bid                    = new Bid();
            $new_bid->bidder_id         = $bidder->id;
            $new_bid->product_id        = $request->product_id;
            $new_bid->bidding_price     = $request->bidding_price;
            $new_bid->save();

            DB::commit();
      
            return response()->json([
               'status' => 'success',
               'message' => 'Bid Submitted Successfully !!',
            ]);
         }

      } catch (\Exception $e) {
         DB::rollBack();

         return response()->json([
            'status' => 'success',
            'message' => 'Something went wrong ! Please try again.',
         ],500);
      }
   }

   public function replace_bid(Request $request){

      $bid_email = $request->bid_email;

      $bid = Bid::where('product_id', $request->product_id)
                  ->whereHas(
                     'bidder', function(Builder $query)use($bid_email){
                        return $query->where('email', $bid_email);
                     }
                  )
                  ->first();
      $bid->bidding_price = $request->bidding_price;
      $bid->save();

      return response()->json([
         'status' => 'success',
         'message' => 'Bid Replaced Successfully !!',
      ]);
   }
}
