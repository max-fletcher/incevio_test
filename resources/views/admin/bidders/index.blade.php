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
               <th scope="col">Email</th>
               <th scope="col">User Code</th>
               <th scope="col">First Bid Date</th>
               <th scope="col">Verify</th>
               </tr>
            </thead>
            <tbody>
               @forelse($bidders as $bidder)
                  <tr>
                     <td>{{ $loop->iteration }}</td>
                     <td>{{ $bidder->email }}</td>
                     <td>{{ $bidder->usercode }}</td>
                     <td>{{ \Carbon\Carbon::parse($bidder->created_at)->format('d-m-Y h:i A') }}</td>
                     <td>
                        @if($bidder->verified)
                           <button class="btn btn-warning verify_bidder" id="verify_bidder_{{ $bidder->id }}" data-bidder-id="{{ $bidder->id }}">
                              Unverify bidder
                           </button>
                        @else
                           <button class="btn btn-primary verify_bidder" id="verify_bidder_{{ $bidder->id }}" data-bidder-id="{{ $bidder->id }}">
                              Verify bidder
                           </button>
                        @endif
                     </td>
                  </tr>
               @empty
                  <td class="text-center" colspan="5">
                     <h1>
                        No bidders Submitted Yet !
                     </h1>
                  </td>
               @endforelse
            </tbody>
         </table>

         {{ $bidders->links() }}

         {{-- CONFIRM bidder VERIFICATION MODAL --}}
         <div class="modal fade" id="bidder_verify_modal" tabindex="-1" aria-labelledby="bidder_verify_modal_label" aria-hidden="true">
            <div class="modal-dialog">
               <div class="modal-content">
                  <div class="modal-header">
                     <h5 class="modal-title" id="bidder_verify_modal_title">Confirm Verification</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                     <small class="my-1 d-none" id="bidder_replace_error"></small>
                     <h5 id="bidder_verify_modal_message">Verify This bidder ?</h5>
                     <input id="verify_bidder_id" type="hidden">
                  </div>
                  <div class="modal-footer">
                     <button type="button" class="btn btn-" data-bs-dismiss="modal">Cancel</button>
                     <button type="button" class="btn btn-primary" id="confirm_verify_bidder">Verify bidder</button>
                  </div>
               </div>
            </div>
         </div>

         {{-- SUCCESS MESSAGE FOR AJAX SUBMISSION --}}
         <div class="modal fade" id="bidder_verification_success_modal" tabindex="-1" aria-labelledby="bidder_verification_success_modal_label" aria-hidden="true">
            <div class="modal-dialog">
               <div class="modal-content">
                  <div class="modal-header">
                     <h5 class="modal-title" id="bidder_verification_success_modal_title"></h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                     <h5 id="bidder_verification_success_modal_message"></h5>
                     <p id="bidder_verification_success_modal_submessage"></p>
                  </div>
                  {{-- <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                     <button type="button" class="btn btn-primary">Save changes</button>
                  </div> --}}
               </div>
            </div>
         </div>

      </div>
   </div>
</div>
@endsection

@section('scripts')
<script>
   $(document).on('click', '.verify_bidder', function(){
      $bidder_id = $(this).data('bidder-id')

      $('#verify_bidder_id').val($bidder_id)
      $('#bidder_verify_modal').modal('show')
   })
</script>

<script>
   $(document).on('click', '#confirm_verify_bidder', function(){
      bidder_id = $('#verify_bidder_id').val()

      console.log($bidder_id)
      
      $.ajax({
         url: '{{ route("admin.verify_bidder.ajax") }}',
         type: 'POST',
         data: {
            "_token": "{{ csrf_token() }}",
            "bidder_id" : bidder_id
         },
         dataType: 'JSON',
         success: function(response)
         {
            if(response.status === 'success'){

               $('#bidder_verify_modal').modal('hide')
               if(response.verification_status){
                  // verified
                  console.log('verified');
                  console.log('#verify_bidder_'+bidder_id, $('#verify_bidder_'+bidder_id));
                  $('#verify_bidder_'+bidder_id).removeClass('btn-primary').addClass('btn-warning').html('Unverify bidder')
               }
               else{
                  // unverified
                  console.log('unverified');
                  console.log('#verify_bidder_'+bidder_id, $('#verify_bidder_'+bidder_id));
                  $('#verify_bidder_'+bidder_id).removeClass('btn-warning').addClass('btn-primary').html('Verify bidder')
               }
            }
         },
         error: function(error){
            if(error.status === 401 && error.responseJSON.error_message){
               Swal.fire({
                  icon: 'error',
                  title: 'Access Denied!',
                  text: error.responseJSON.error_message,
                  showConfirmButton: false,
                  timer: 1500,
               })
            }
            else if(error.status === 401){
               Swal.fire({
                  icon: 'error',
                  title: 'Access Denied!',
                  text: "You don't have the necessary permissions. Please contact system admin for more information.",
                  showConfirmButton: false,
                  timer: 1500,
               })
            }
            else{
               Swal.fire({
                  icon: 'error',
                  title: 'Something went wrong!',
                  text: "Please try again later.",
                  timer: 1500,
                  showConfirmButton: false,
               })
            }
         }
      });
   })
</script>
@endsection