@extends('layouts.app')

@section('content')
<div class="container">
   @include('inc.alert')
   <div class="row justify-content-center">
      <div class="col-md-10 mt-3">

         <div class="row">
            @forelse($products as $product)
               <div class="col-12 col-md-4 mb-3">
                  <div class="card text-center">
                     {{-- <div class="card-header">
                        Featured
                     </div> --}}
                     <div class="card-body">
                        <h5 class="card-title">{{ $product->title }}</h5>
                        <h6 class="card-text">Minimum Bid: ${{ $product->minimum_bidding_price ?? 0 }}</h6>
                        <p>Deadline: {{ \Carbon\Carbon::parse($product->deadline)->format('d-m-Y h:i A') }}</p>
                        <a class="btn btn-primary"
                           href="{{ route('users.products.show', ['product_id' => $product->id]) }}">
                           Show Product
                        </a>
                     </div>
                     <div class="card-footer text-muted">
                        Added: {{ \Carbon\Carbon::parse($product->created_at)->diffForHumans() }}
                     </div>
                  </div>
               </div>
            @empty
               <div class="text-center">
                  <h1>
                     No Products Created Yet !
                  </h1>
               </div>
            @endforelse
         </div>

      </div>
   </div>
</div>
@endsection