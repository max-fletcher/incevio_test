@extends('layouts.app')

@section('styles')
   <style>
      .create-icon{
         font-size: 30px;
         padding: 5px
      }

      *:focus,
      *:active {
      outline: none !important;
      -webkit-tap-highlight-color: transparent;
      }

      .wrapper {
         display: inline-flex;
      }

      .wrapper .icon {
         position: relative;
         border-radius: 50%;
         padding: 15px;
         margin: 10px;
         width: 50px;
         height: 50px;
         font-size: 18px;
         display: flex;
         justify-content: center;
         align-items: center;
         flex-direction: column;
         box-shadow: 0 10px 10px rgba(0, 0, 0, 0.1);
         cursor: pointer;
         transition: all 0.2s cubic-bezier(0.68, -0.55, 0.265, 1.55);
         -webkit-border-radius: 50%;
         -moz-border-radius: 50%;
         -ms-border-radius: 50%;
         -o-border-radius: 50%;
         -webkit-transition: all 0.2s cubic-bezier(0.68, -0.55, 0.265, 1.55);
         -moz-transition: all 0.2s cubic-bezier(0.68, -0.55, 0.265, 1.55);
         -ms-transition: all 0.2s cubic-bezier(0.68, -0.55, 0.265, 1.55);
         -o-transition: all 0.2s cubic-bezier(0.68, -0.55, 0.265, 1.55);
      }

      .wrapper .tooltip {
         position: absolute;
         top: 0;
         font-size: 14px;
         background: #fff;
         color: #ffffff;
         padding: 5px 8px;
         box-shadow: 0 10px 10px rgba(0, 0, 0, 0.1);
         opacity: 0;
         pointer-events: none;
         border-radius: 4px;
         transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
         -webkit-transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
         -moz-transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
         -ms-transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
         -o-transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
         -webkit-border-radius: 4px;
         -moz-border-radius: 4px;
         -ms-border-radius: 4px;
         -o-border-radius: 4px;
      }

      .wrapper .tooltip::before {
         position: absolute;
         content: "";
         height: 8px;
         width: 8px;
         background: #fff;
         bottom: -3px;
         left: 50%;
         transform: translate(-50%) rotate(45deg);
         transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
         -webkit-transform: translate(-50%) rotate(45deg);
         -moz-transform: translate(-50%) rotate(45deg);
         -ms-transform: translate(-50%) rotate(45deg);
         -o-transform: translate(-50%) rotate(45deg);
         -webkit-transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
         -moz-transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
         -ms-transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
         -o-transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
      }

      .wrapper .icon:hover .tooltip {
         top: -45px;
         opacity: 1;
         visibility: visible;
         pointer-events: auto;
      }

      .wrapper .icon:hover span,
      .wrapper .icon:hover .tooltip {
         text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.01);
      }

      .wrapper .facebook:hover,
      .wrapper .facebook:hover .tooltip,
      .wrapper .facebook:hover .tooltip::before {
         background: #198754;
         color: #fff;
      }
   </style>
@endsection

@section('content')
<div class="container">
   @include('inc.alert')
   <div class="row justify-content-center">
      <div class="col-md-10 mt-3">
         <table class="table table-striped table-hover">
            <thead>
               <tr>
               <th scope="col">#</th>
               <th scope="col">Title</th>
               <th scope="col">Minimum Bidding Price</th>
               <th scope="col">Added(d-m-Y)</th>
               <th scope="col">Deadline(d-m-Y)</th>
               <th scope="col">Actions</th>
               </tr>
            </thead>
            <tbody>
               @forelse($products as $product)
                  <tr>
                     <td>{{ $loop->iteration }}</td>
                     <td>{{ $product->title }}</td>
                     <td>${{ $product->minimum_bidding_price ?? 0 }}</td>
                     <td>{{  \Carbon\Carbon::parse($product->created_at)->format('d-m-Y') }}</td>
                     <td>{{  \Carbon\Carbon::parse($product->deadline)->format('d-m-Y') }}</td>
                     <td>
                        <a class="btn btn-primary"
                           href="{{ route('admin.bids.index', ['product_id' => $product->id]) }}">
                           Show Bids
                        </a>
                     </td>
                  </tr>
               @empty
                  <td class="text-center" colspan="5">
                     <h3>
                        No Products Created Yet !
                     </h3>
                  </td>
               @endforelse
            </tbody>
         </table>

         {{ $products->links() }}
      </div>
   </div>
</div>

<div class="fixed-bottom d-flex justify-content-end wrapper">
   <a href="{{ route('admin.products.create') }}">
      <div class="rounded-circle border-0 btn btn-success me-3 mb-3 icon facebook">
         <div class="tooltip">Create</div>
         <span><i class="fa-solid fa-plus create-icon"></i></span>
      </div>
   </a>
</div>
@endsection