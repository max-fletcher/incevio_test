@extends('layouts.app')

@section('content')
<div class="container">
   @include('inc.alert')
   <div class="row justify-content-center">
      <div class="col-md-12 mt-3">

         <div class="card text-center">
            <div class="card-header">
               <h3>
                  {{ $product->title }}
               </h3>
            </div>
            <div class="card-body">
               <h5 class="card-title">Minimum Bid: ${{ $product->minimum_bidding_price ?? 0 }}</h5>
               <p class="card-text">
                  Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quos, reiciendis in. Suscipit odio beatae unde enim 
                  odit aperiam repellendus sunt.
               </p>
               <h5>Deadline: {{ \Carbon\Carbon::parse($product->deadline)->format('d-m-Y') }}</h5>
               @if(now() > $product->deadline)
                  <h4 class="mt-3">Deadline for this product has been reached !!</h4>

                  @if($winning_bid)
                     <h3>Winner is {{ $winning_bid->bidder->usercode }} with top bid of ${{ $winning_bid->bidding_price }} !!</h3>
                     <h5>Check your mail to claim your prize</h5>
                  @else
                     <h4>No bids were made so no winner was selected.</h4>
                  @endif

               @endif
               <div class="d-flex flex-column flex-sm-row justify-content-center">
                  <a class="btn btn-primary mx-sm-1 my-2 my-sm-0"
                     href="{{ route('users.products.index') }}">
                     Back To All Products
                  </a>
                  @if(now() < $product->deadline)
                     <button type="button" class="btn btn-primary" id="bid_on_product"
                        data-bs-toggle="modal" data-bs-target="#bid_modal">Bid On Product
                     </button>
                  @endif
               </div>
            </div>
            <div class="card-footer text-muted">
               Added: {{\Carbon\Carbon::parse($product->created_at)->diffForHumans() }}
            </div>
         </div>

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
                     <td>
                        ${{ $bid->bidding_price }}
                     </td>
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

         {{-- MODAL FOR AJAX SUBMISSION --}}
         <div class="modal fade" id="bid_modal" tabindex="-1" aria-labelledby="bid_modal_label" aria-hidden="true">
            <div class="modal-dialog">
               <div class="modal-content">
                  <div class="modal-header">
                     <h5 class="modal-title" id="bid_modalLabel">Make Bid</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">

                     <small class="text-danger fw-bold d-none bid_product_id_error"></small>
                     <small class="text-danger fw-bold d-none bid_general_error"></small>

                     <form>
                        <div class="mb-3">
                           <label for="email" class="col-form-label">Email</label>
                           <input type="text" class="form-control bid_email" id="bid_email">
                           <small class="text-danger fw-bold d-none bid_email_error"></small>
                        </div>
                        <div class="mb-3">
                           <label for="bidding_price" class="col-form-label">Bid Amount</label>
                           <input type="number" class="form-control bidding_price" id="bidding_price">
                           <small class="text-danger fw-bold d-none bidding_price_error"></small>
                        </div>
                     </form>
                  </div>
                  <div class="modal-footer">
                     <button type="button" class="btn btn-primary submit_bid" id="submit_bid">Submit Bid</button>
                  </div>
               </div>
            </div>
         </div>

         {{-- GET PRODUCT ID FROM THIS HIDDEN TAG FOR AJAX SUBMISSION --}}
         <input type="hidden" class="form-control product_id" id="product_id" value="{{ $product->id }}">

         {{-- SUCCESS MESSAGE FOR AJAX SUBMISSION --}}
         <div class="modal fade" id="bid_success_modal" tabindex="-1" aria-labelledby="bid_success_modal_label" aria-hidden="true">
            <div class="modal-dialog">
               <div class="modal-content">
                  <div class="modal-header">
                     <h5 class="modal-title" id="bid_success_modal_title"></h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                     <h5 id="bid_success_modal_message"></h5>
                     <p id="bid_success_modal_submessage"></p>
                  </div>
                  {{-- <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                     <button type="button" class="btn btn-primary">Save changes</button>
                  </div> --}}
               </div>
            </div>
         </div>

         {{-- REPLACE PREVIOUS BID MODAL --}}
         <div class="modal fade" id="bid_replace_modal" tabindex="-1" aria-labelledby="bid_replace_modal_label" aria-hidden="true">
            <div class="modal-dialog">
               <div class="modal-content">
                  <div class="modal-header">
                     <h5 class="modal-title" id="bid_replace_modal_title">Replace Existing Bid</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                     <small class="my-1 d-none" id="bid_replace_error"></small>
                     <h5 id="bid_replace_modal_message"></h5>
                  </div>
                  <div class="modal-footer">
                     <button type="button" class="btn btn-" data-bs-dismiss="modal">Cancel</button>
                     <button type="button" class="btn btn-primary" id="replace_bid">Replace Bid</button>
                  </div>
               </div>
            </div>
         </div>

      </div>
   </div>
</div>
@endsection

@section('scripts')
<script>
   var product_id = null
   var bid_email = null
   var bidding_price = null
</script>
<script>
   $(document).on('click', '.submit_bid', function(){
      // CLEAR THE ERRORS AND ADD d-none TO THE ERROR TAGS
      $('.bid_email_error').html('').addClass('d-none')
      $('.bidding_price_error').html('').addClass('d-none')
      $('.bid_product_id_error').html('').addClass('d-none')
      $('.bid_general_error').html('').addClass('d-none')

      // GET BID DATA
      product_id = $("#product_id").val()
      bid_email = $("#bid_email").val()
      bidding_price = $("#bidding_price").val()
   
      console.log("HIT", bid_email, bidding_price, product_id);

      $.ajax({
         url: '{{ route("users.make_bid.ajax") }}',
         type: 'POST',
         data: {
            "_token": "{{ csrf_token() }}",
            "product_id" : product_id,
            "bid_email" : bid_email,
            "bidding_price" : bidding_price,
         },
         dataType: 'JSON',
         success: function(response){
            console.log(response);
            // console.log(response?.exists, response?.exists === true);

            $('#bid_modal').modal('hide');

            if(response?.exists === true){
               // IF IT IS A REPLACEMENT BID
               $('.bid_replace_error').html('').addClass('d-none') //REMOVE ERROR MESSAGE FROM BID_REPLACEMENT MODAL
               replace_message = 'A bid for this email address already exists. The current bid is $' + response.bid_value + '. Do you want to replace it ?'
               $('#bid_replace_modal_message').html(replace_message)
               $('#bid_replace_modal').modal('show')
            }
            else{
               // IF NEW BID IS SUCCESSFUL

               // CLEAR INPUT FIELDS
               $("#bid_email").val('')
               $("#bidding_price").val('')

               $('#bid_success_modal_title').html('Success!!')
               $('#bid_success_modal_message').html('Bid Submitted Successfully!!')
               $('#bid_success_modal_submessage').html('Please wait for admin to verify if your bid hasn\'t been verified yet.')
               $('#bid_success_modal').modal('show')

               $("#bid_success_modal").on("hidden.bs.modal", function () {
                  location.reload()
               });
            }
         },
         error: function(error){
            if(error.status === 422){
               console.log(error.responseJSON.errors);
               $('.bid_email_error').html(error.responseJSON.errors.bid_email).removeClass('d-none')
               $('.bidding_price_error').html(error.responseJSON.errors.bidding_price).removeClass('d-none')
            }
            else if(error.status === 404 || error.status === 401){
               console.log(error.responseJSON.message);
               $('.bid_product_id_error').html(error.responseJSON.message).removeClass('d-none')
            }
            else{
               $('.bid_general_error').html("Something went wrong. Please contact system admin or try again later.").removeClass('d-none')
            }
         }
      });

   });
</script>

<script>
   $(document).on('click', '#bid_on_product', function(){
      // CLEAR THE ERRORS AND ADD d-none TO THE ERROR TAGS
      $('.bid_email_error').html('').addClass('d-none')
      $('.bidding_price_error').html('').addClass('d-none')
      $('.bid_product_id_error').html('').addClass('d-none')
      $('.bid_general_error').html('').addClass('d-none')
   });
</script>

<script>
   $(document).on('click', '#replace_bid', function(){

      product_id = $("#product_id").val()
      bid_email = $("#bid_email").val()
      bidding_price = $("#bidding_price").val()

      $.ajax({
         url: '{{ route("users.replace_bid.ajax") }}',
         type: 'POST',
         data: {
            "_token": "{{ csrf_token() }}",
            "product_id" : product_id,
            "bid_email" : bid_email,
            "bidding_price" : bidding_price,
         },
         dataType: 'JSON',
         success: function(response){
            console.log(response);
            // console.log(response?.exists, response?.exists === true);

            // CLEAR INPUT FIELDS
            $("#bid_email").val('')
            $("#bidding_price").val('')

            $('#bid_replace_modal').modal('hide')
            $('#bid_success_modal_title').html('Success!!')
            $('#bid_success_modal_message').html('Bid Replaced Successfully!!')
            $('#bid_success_modal_submessage').html('Please wait for admin to verify if your bid hasn\'t been verified yet.')
            $('#bid_success_modal').modal('show')

            $("#bid_success_modal").on("hidden.bs.modal", function () {
               location.reload()
            });
         },
         error: function(error){
            console.log(error.responseJSON.message);
            if(error.status === 404){
               $('.bid_replace_error').html(error.responseJSON.message).removeClass('d-none')
            }
            else{
               $('.bid_replace_error').html("Something went wrong. Please contact system admin or try again later.").removeClass('d-none')
            }
         }
      });
   });
</script>
@endsection