@extends('layouts.app')

@section('styles')
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/themes/base/jquery-ui.min.css" integrity="sha512-ELV+xyi8IhEApPS/pSj66+Jiw+sOT1Mqkzlh8ExXihe4zfqbWkxPRi8wptXIO9g73FSlhmquFlUOuMSoXz5IRw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('content')
<div class="container">
   <div class="row justify-content-center">
      <div class="col-md-10 mt-3">

         <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
               <label for="title" class="form-label">Title</label>
               <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" 
                  placeholder="Enter Product Title" aria-describedby="title">
               @error('title')
                  <small class="text-danger fw-bold">{{ $message }}</small>
               @enderror
            </div>
            <div class="mb-3">
               <label for="minimum_bidding_price" class="form-label">Minimum Bidding Price</label>
               <input type="number" class="form-control" id="minimum_bidding_price" name="minimum_bidding_price" 
                  value="{{ old('minimum_bidding_price') }}" placeholder="Enter Product Minimum Bidding Price"
                  aria-describedby="minimum bidding price">
               @error('minimum_bidding_price')
                  <small class="text-danger fw-bold">{{ $message }}</small>
               @enderror
            </div>
            <div class="mb-3">
               <label for="deadline">Deadline</label>

               <div class="input-group">
                  <input class="form-control" type="text" id="deadline" name="deadline" id="deadline" placeholder="Enter Bidding Deadline"
                  autocomplete="off" aria-describedby="minimum bidding price">
                  <span class="input-group-append">
                     <span class="input-group-text bg-light d-block">
                        <i class="fa fa-calendar"></i>
                     </span>
                  </span>
               </div>

               @error('deadline')
                  <small class="text-danger fw-bold">{{ $message }}</small>
               @enderror
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
         </form>
      </div>
   </div>
</div>

@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js" integrity="sha512-57oZ/vW8ANMjR/KQ6Be9v/+/h6bq9/l3f0Oc7vn6qMqyhvPd1cvKBRWWpzu0QoneImqr2SkmO4MSqU+RpHom3Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
   // current date
   var date = new Date();
   // add a day
   date.setDate(date.getDate() + 1);

   $( function() {
      $( "#deadline" ).datepicker({
         dateFormat: "dd-mm-yy",
         minDate: date
      });
   });
   </script>
@endsection