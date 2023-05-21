<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class ProductController extends Controller
{
   public function index(){
      $products = Product::paginate();

      return view('admin.products.index', compact('products'));
   }

   public function create(){
      return view('admin.products.create');
   }

   public function store(Request $request){

      $request->validate([
               'title'                 => ['required', 'string', 'max:255'],
               'minimum_bidding_price' => ['nullable','numeric', 'integer', 'min:1'],
               'deadline'              => ['required','string', 'date_format:d-m-Y', 'after:'.now()],
         ], 
         [
               'deadline.after' => 'The deadline must be greater than '.now()->format('d-m-Y')
         ]
      );

      $product                            = new Product();
      $product->title                     = $request->title;
      $product->minimum_bidding_price     = $request->minimum_bidding_price ?? 0;
      $product->deadline                  = Carbon::createFromFormat("d-m-Y", $request->deadline)->endOfDay();
      $product->save();

      return redirect()->route('admin.products.index')
               ->with('alert.status', 'success')
               ->with('alert.message', 'App User updated successfully!');
   }
}
