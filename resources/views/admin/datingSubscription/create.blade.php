@extends('admin.base_template')

@section('main')
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
                            <form action="{{ route('datingSubscription.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                            
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <div class="form-floating">
                                            <input type="number" class="form-control" id="expire_days" name="expire_days" placeholder="Enter Expire Days" required>
                                            <label for="expire_days">Expire Days &nbsp;<span style="color:red;">*</span></label>
                                        </div>
                                        @error('expire_days')
                                        <div style="color:red">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <div class="form-floating">
                                            <input type="number" class="form-control" id="cost" name="cost" placeholder="Enter Cost" required>
                                            <label for="cost">Cost &nbsp;<span style="color:red;">*</span></label>
                                        </div>
                                        @error('cost')
                                        <div style="color:red">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="free_dating_feature" name="free_dating_feature" value="1">
                                            <label class="form-check-label" for="free_dating_feature">Free Dating Feature</label>
                                        </div>
                                        @error('free_dating_feature')
                                        <div style="color:red">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="unlimited_swipes" name="unlimited_swipes" value="1">
                                            <label class="form-check-label" for="unlimited_swipes">Unlimited Swipes</label>
                                        </div>
                                        @error('unlimited_swipes')
                                        <div style="color:red">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="swipe_message" name="swipe_message" value="1">
                                            <label class="form-check-label" for="swipe_message">Swipe Message</label>
                                        </div>
                                        @error('swipe_message')
                                        <div style="color:red">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="backtrack" name="backtrack" value="1">
                                            <label class="form-check-label" for="backtrack">Backtrack</label>
                                        </div>
                                        @error('backtrack')
                                        <div style="color:red">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="access_admirers" name="access_admirers" value="1">
                                            <label class="form-check-label" for="access_admirers">Access Admirers</label>
                                        </div>
                                        @error('access_admirers')
                                        <div style="color:red">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            
                                <div class="form-group">
                                    <div class="w-100 text-center">
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fa fa-save"></i> Submit
                                        </button>
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
@endsection
