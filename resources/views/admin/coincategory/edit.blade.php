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
                            <form action="{{ route('coin-category.update', $interest->id) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                            
                                {{-- <div class="form-group row">
                                    <div class="col-sm-12"><br>
                                        <label class="form-label" style="margin-left: 10px" for="power">Select Category</label>
                                        <select class="form-select" name="category" value="{{ old('category', $interest->category ?? '') }}">
                                            <option value="">Select</option>
                                            <option value="bronze" {{ (isset($interest->category) && $interest->category == 'bronze') ? 'selected' : '' }}>Bronze</option>
                                            <option value="silver" {{ (isset($interest->category) && $interest->category == 'silver') ? 'selected' : '' }}>Silver</option>
                                            <option value="gold" {{ (isset($interest->category) && $interest->category == 'gold') ? 'selected' : '' }}>Gold</option>
                                        </select>
                                    </div>
                                </div> --}}

                               <div class="form-group row">
                                    <div class="col-sm-12">
                                        <div class="form-floating">
                                            <input type="number" class="form-control" value="{{ old('extend_chat_coin', $interest->extend_chat_coin ?? '') }}" id="extend_chat_coin" name="extend_chat_coin" placeholder="Enter Extend chat" required>
                                            <label for="extend_chat_coin">Enter Extend chat Coin &nbsp;<span style="color:red;">*</span></label>
                                        </div>
                                        @error('extend_chat_coin')
                                        <div style="color:red">{{$message}}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <div class="form-floating">
                                            <input type="number" class="form-control" value="{{ old('monthly_activities_coin', $interest->monthly_activities_coin ?? '') }}" id="monthly_activities_coin" name="monthly_activities_coin" placeholder="Enter Monthly Activities Coin" required>
                                            <label for="monthly_activities_coin">Enter Monthly Activities Coin &nbsp;<span style="color:red;">*</span></label>
                                        </div>
                                        @error('monthly_activities_coin')
                                        <div style="color:red">{{$message}}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <div class="form-floating">
                                            <input type="number" class="form-control" value="{{ old('monthly_interests_coin', $interest->monthly_interests_coin ?? '') }}" id="monthly_interests_coin" name="monthly_interests_coin" placeholder="Enter Monthly Interests Coin" required>
                                            <label for="monthly_interests_coin">Enter Monthly Interests Coin &nbsp;<span style="color:red;">*</span></label>
                                        </div>
                                        @error('monthly_interests_coin')
                                        <div style="color:red">{{$message}}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <div class="form-floating">
                                            <input type="number" class="form-control" value="{{ old('interest_messages_coin', $interest->interest_messages_coin ?? '') }}" id="interest_messages_coin" name="interest_messages_coin" placeholder="Enter Interest Messages Coin" required>
                                            <label for="interest_messages_coin">Enter Interest Messages Coin &nbsp;<span style="color:red;">*</span></label>
                                        </div>
                                        @error('interest_messages_coin')
                                        <div style="color:red">{{$message}}</div>
                                        @enderror
                                    </div>
                                </div>
                            
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <div class="form-floating">
                                            <input type="number" class="form-control" value="{{ old('cost', $interest->cost) }}" id="cost" name="cost" placeholder="Enter Cost" required>
                                            <label for="cost">Enter Cost &nbsp;<span style="color:red;">*</span></label>
                                        </div>
                                        @error('cost')
                                        <div style="color:red">{{$message}}</div>
                                        @enderror
                                    </div>
                                </div>
                            
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <div class="form-floating">
                                            <textarea class="form-control" id="description" name="description" placeholder="Enter Description" required>{{ old('description', $interest->description) }}</textarea>
                                            <label for="description">Description &nbsp;<span style="color:red;">*</span></label>
                                        </div>
                                        @error('description')
                                        <div style="color:red">{{$message}}</div>
                                        @enderror
                                    </div>
                                </div>
                            
                                <div class="form-group">
                                    <div class="w-100 text-center">
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fa fa-save"></i> Update
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
