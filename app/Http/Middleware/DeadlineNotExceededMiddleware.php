<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Product;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DeadlineNotExceededMiddleware
{
   /**
    * Handle an incoming request.
    *
    * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
    */
   public function handle(Request $request, Closure $next): Response
   {
      $product = Product::where('id', $request->product_id)->first();

      if(!$product){
         return response()->json([
               'status'    => 'failed',
               'message'   => 'Product not found !',
         ], 404);
      }

      if(now() > $product->deadline){
         return response()->json([
            'status'    => 'failed',
            'message'   => 'Deadline Exceeded !',
      ], 401);
      }
      
      return $next($request);
   }
}
