@if(session()->has('alert.message'))
   @if(session('alert.status') == 'warning' || session('alert.status') == 'danger' || session('alert.status') == 'success')
      <div class="alert alert-{{ session('alert.status') }} alert-dismissible fade show" role="alert">
         <strong>{{ ucfirst(session('alert.status')).'! ' }} </strong>{{ session('alert.message') }}
         <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
   @else
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
         <strong>{{ ucfirst(session('alert.status')).'! ' }} </strong>{{ session('alert.message') }}
         <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
   @endif
@endif