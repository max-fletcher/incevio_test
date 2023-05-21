@extends('layouts.app')

@section('content')
<div class="container">
   @include('inc.alert')
   <div class="row justify-content-center">
      <div class="col-md-10 mt-3">

         <table class="table table-striped table-hover">
            <thead>
               <tr>
               <th scope="col">#</th>
               <th scope="col">User Code</th>
               <th scope="col">Bidding Price</th>
               <th scope="col">Date Submitted</th>
               </tr>
            </thead>
            <tbody>
               @forelse($bids as $bid)
                  <tr>
                     <td>{{ $loop->iteration }}</td>
                     <td>{{ $bid->bidder->usercode }}</td>
                     <td>${{ $bid->bidding_price }}</td>
                     <td>{{ \Carbon\Carbon::parse($bid->updated_at)->format('d-m-Y') }}</td>
                  </tr>
               @empty
                  <td class="text-center" colspan="5">
                     <h1>
                        No Bids Submitted Yet !
                     </h1>
                  </td>
               @endforelse
            </tbody>
         </table>

         {{ $bids->links() }}

      </div>
   </div>
</div>
@endsection