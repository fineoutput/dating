@extends('admin.base_template')

@section('main')
<style>
  
    form {
      margin-top: 20px;
    }
    
    select {
      width: 400px;
    }
    </style>
<!-- Start content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <h4 class="page-title">{{ isset($interest) ? 'Edit' : 'Add' }} {{ $tital }}</h4>
                    <ol class="breadcrumb" style="display:none">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">{{$tital}}</a></li>
                        <li class="breadcrumb-item active">{{ isset($interest) ? 'Edit' : 'Add' }} {{$tital}}</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="page-content-wrapper">
            <div class="row">
                <div class="col-12">
                    <div class="card m-b-20">
                        <div class="card-body">
                            <!-- Show success and error messages -->
                            @if (session('success'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                            @if (session('error'))
                                <div class="alert alert-danger" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            <h4 class="mt-0 header-title">{{ isset($interest) ? 'Edit' : 'Add' }} {{$tital}} Form</h4>
                            <hr style="margin-bottom: 50px;background-color: darkgrey;">
                            <form action="{{ route('activitySubscription.update', $activity->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                            
                                {{-- <div class="form-group row">
                                    <label for="title" class="col-sm-2 col-form-label">Title</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $activity->title) }}" required>
                                    </div>
                                </div> --}}

                                <div class="form-group row">
                                    <label for="title" class="col-sm-2 col-form-label">Activity Coin Count</label>
                                    <div class="col-sm-4">
                                        <input type="number" class="form-control" id="title" name="activity_count" value="{{ old('activity_count', $activity->activity_count) }}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="title" class="col-sm-2 col-form-label">Interests Count</label>
                                    <div class="col-sm-4">
                                        <input type="number" class="form-control" id="title" name="interests_count" value="{{ old('interests_count', $activity->interests_count) }}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="title" class="col-sm-2 col-form-label">Message Count</label>
                                    <div class="col-sm-4">
                                        <input type="number" class="form-control" id="title" name="message_count" value="{{ old('message_count', $activity->message_count) }}" required>
                                    </div>
                                </div>
                            
                                {{-- <div class="form-group row">
                                    <label for="description" class="col-sm-2 col-form-label">Description</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" id="description" name="description">{{ old('description', $activity->description) }}</textarea>
                                    </div>
                                </div> --}}
                            
                                {{-- <div class="form-group row">
                                    <label for="category_id" class="col-sm-2 col-form-label">Category</label>
                                    <div class="col-sm-10">
                                        <select class="form-select" name="category_id" id="category_id" required>
                                            <option value="">Select Category</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ $category->id == old('category_id', $activity->category_id) ? 'selected' : '' }}>
                                                    {{ $category->category }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            
                                <div class="form-group row">
                                    <label for="type" class="col-sm-2 col-form-label">Type</label>
                                    <div class="col-sm-10">
                                        <select class="form-select" name="type" id="type" required>
                                            <option value="free" {{ $activity->type == 'free' ? 'selected' : '' }}>Free</option>
                                            <option value="paid" {{ $activity->type == 'paid' ? 'selected' : '' }}>Paid</option>
                                        </select>
                                    </div>
                                </div> --}}
                            
                                {{-- <div class="form-group row">
                                    <label for="interests_id" class="col-sm-2 col-form-label">Interests</label>
                                    <div class="col-sm-10">
                                        <div id="output"></div>
                                        <select data-placeholder="" name="interests_id[]" multiple class="chosen-select">
                                            @foreach($interests as $interest)
                                                <option value="{{ $interest->id }}" 
                                                    {{ in_array($interest->id, explode(',', $activity->interests_id)) ? 'selected' : '' }}>
                                                    {{ $interest->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> --}}
                                
                            
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <button type="submit" class="btn btn-primary">Update Activity Subscription</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->
        </div>
        <!-- end page content-->
    </div> <!-- container-fluid -->
</div> <!-- content -->
<link rel="stylesheet" href="https://harvesthq.github.io/chosen/chosen.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
<script src="https://harvesthq.github.io/chosen/chosen.jquery.js"></script>
<script>
    document.getElementById('output').innerHTML = location.search;
    $(".chosen-select").chosen();
</script>
@endsection
