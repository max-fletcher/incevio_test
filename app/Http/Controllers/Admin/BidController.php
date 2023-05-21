<?php

namespace App\Http\Controllers\Admin;

use App\Models\Bid;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BidController extends Controller
{
    public function index(int $product_id){
        $bids = Bid::where('product_id', $product_id)
                    ->with('bidder')
                    ->paginate();

        return view('admin.bids.index', compact('bids'));
    }
}
