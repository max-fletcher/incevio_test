<?php

namespace App\Http\Controllers\Users;

use App\Models\Bid;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
   public function index(){
      $products = Product::all();

      return view('users.products.index', compact('products'));
   }

   public function show(int $product_id){
      $product = Product::where('id', $product_id)->first();

      $bids = Bid::where('product_id', $product_id)
                  ->withWherehas('bidder', function($query){
                     return $query->where('verified', true);
                  })
                  ->orderBy('bidding_price', 'desc')
                  ->paginate();

      if(now() > $product->deadline){
         $winning_bid = Bid::where('product_id', $product_id)
                              ->withWherehas('bidder', function($query){
                                 return $query->where('verified', true);
                              })
                              ->orderBy('bidding_price', 'desc')
                              ->first();
      }
      else{
         $winning_bid = null;
      }

      return view('users.products.show', compact('product', 'bids', 'winning_bid'));
   }
}
