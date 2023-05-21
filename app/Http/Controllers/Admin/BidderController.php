<?php

namespace App\Http\Controllers\Admin;

use App\Models\Bidder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BidderController extends Controller
{
    public function index(){
        $bidders = Bidder::paginate();

        return view('admin.bidders.index', compact('bidders'));
    }

    public function verify_bidder_ajax(Request $request){
        $bidder = Bidder::where('id', $request->bidder_id)->first();

        if(!$bidder){
            return response()->json([
                'status'        => 'failed',
                'message'       => 'Bidder Not Found!',
                'submessage'    => 'Please contact system admin or try again later.'
            ]);
        }

        $bidder->verified = !$bidder->verified;
        $bidder->save();

        return response()->json([
            'status'                => 'success',
            'verification_status'   => $bidder->verified,
            'message'               => 'Verification status changed successfully!'
        ]);
    }
}
