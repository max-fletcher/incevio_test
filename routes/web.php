<?php

use App\Models\Product;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// ADMIN ROUTES
Route::get('/', function () {
   return view('welcome');
});

// Route::get('/test', function () {
//    $products_with_deadlines_exceeded = Product::where('deadline', '<', now())
//                                        ->with([
//                                              'bids' => function($query1){
//                                                    return $query1->orderBy('bidding_price', 'desc');
//                                              },
//                                              'bids.bidder' => function($query2){
//                                                    return $query2->where('verified', true);
//                                              }
//                                           ])
//                                        ->get();

//    foreach($products_with_deadlines_exceeded as $product){
//       foreach($product->bids as $key => $bid){
//             if($key == 0){
//                dump($bid);
//             }
//             else{
//                dump($bid);
//             }
//       }
//    }

//    dd($products_with_deadlines_exceeded);
// });

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['prefix'=>'admin', 'middleware'=>['auth']], function(){

   Route::group(['prefix'=>'bidders'], function(){
      Route::get('/', [App\Http\Controllers\Admin\BidderController::class, 'index'])->name('admin.bidders.index');

      Route::post('verify_bidder_ajax', [App\Http\Controllers\Admin\BidderController::class, 'verify_bidder_ajax'])->name('admin.verify_bidder.ajax');
   });

   Route::group(['prefix'=>'products'], function(){
      Route::get('/', [App\Http\Controllers\Admin\ProductController::class, 'index'])->name('admin.products.index');
      Route::get('/create', [App\Http\Controllers\Admin\ProductController::class, 'create'])->name('admin.products.create');
      Route::post('/store', [App\Http\Controllers\Admin\ProductController::class, 'store'])->name('admin.products.store');
   });

   Route::get('bids/{product_id}', [App\Http\Controllers\Admin\BidController::class, 'index'])->name('admin.bids.index');

   // Route::post('verify_bid_ajax', [App\Http\Controllers\Admin\BidController::class, 'verify_bid_ajax'])->name('admin.verify_bid.ajax');
});

// USERS
Route::group(['prefix'=>'all_products'], function(){
   Route::get('/', [App\Http\Controllers\Users\ProductController::class, 'index'])->name('users.products.index');
   Route::get('/product/{product_id}', [App\Http\Controllers\Users\ProductController::class, 'show'])->name('users.products.show');
});

Route::group(['prefix'=>'bid'], function(){
   Route::post('/make_bid_ajax', [App\Http\Controllers\Users\BidController::class, 'make_bid'])->name('users.make_bid.ajax')->middleware('deadline_not_exceeded');
   Route::post('/replace_bid', [App\Http\Controllers\Users\BidController::class, 'replace_bid'])->name('users.replace_bid.ajax')->middleware('deadline_not_exceeded');
});