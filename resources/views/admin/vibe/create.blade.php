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
                            <form action="{{ isset($interest) ? route('vibe.update',['id'=> $interest->id]) : route('vibe.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @isset($interest)
                                    @method('PUT')
                                @endisset

                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" value="{{ old('name', $interest->name ?? '') }}" id="name" name="name" placeholder="Enter name" required>
                                            <label for="name">Enter Name &nbsp;<span style="color:red;">*</span></label>
                                        </div>
                                        @error('name')
                                        <div style="color:red">{{$message}}</div>
                                        @enderror
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" value="{{ old('icon', $interest->icon ?? '') }}" id="icon" name="icon" placeholder="Enter icon" required>
                                            <label for="icon">Icon &nbsp;<span style="color:red;">*</span></label>
                                        </div>
                                        @error('icon')
                                        <div style="color:red">{{$message}}</div>
                                        @enderror
                                    </div>
                     
{{-- 
                                    <div class="col-sm-6">
                                        <label class="form-label" for="icon">Icon</label>
                                        <input class="form-control" type="text" id="icon" name="icon">
                                    </div> --}}
                                </div>
                                <div class="form-group">
                                    <div class="w-100 text-center">
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fa fa-user"></i> {{ isset($interest) ? 'Update' : 'Submit' }}
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
