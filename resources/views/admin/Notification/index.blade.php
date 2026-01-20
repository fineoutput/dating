@extends('admin.base_template')
@section('main')
<!-- Start content -->
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-12">
        <div class="page-title-box">
          <h4 class="page-title">View Push Notification</h4>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">Push Notification</a></li>
            <li class="breadcrumb-item active">View Push Notification</li>
          </ol>
        </div>
      </div>
    </div>
    <!-- end row -->
    <div class="page-content-wrapper">
      <div class="row">
        <div class="col-12">
          <div class="card m-b-20">
            <div class="card-body">
              <!-- show success and error messages -->
              @if (session('success'))
              <div class="alert alert-success" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </div>
              @endif
              @if (session('error'))
              <div class="alert alert-danger" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </div>
              @endif
              <!-- End show success and error messages -->
              <div class="row">
                <div class="col-md-10">
                  <h4 class="mt-0 header-title">View Push Notification List</h4>
                </div>
                <div class="col-md-2"> <a class="btn btn-info cticket" href="{{route('add_notification')}}" role="button" style="margin-left: 20px;"> Add Push Notification</a></div>
              </div>
              <hr style="margin-bottom: 50px;background-color: darkgrey;">
              <div class="table-rep-plugin">
                <div class="table-responsive b-0" data-pattern="priority-columns">
                  <table id="userTable" class="table  table-striped">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th data-priority="1">Title</th>
                        <th data-priority="3">Description</th>
                        <th data-priority="3">Date</th>
                        <th data-priority="6">Images</th>
                        <th data-priority="6">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                     
                      @php $a = 0; @endphp
                      @foreach($notification as $team)
                      @php $a++; @endphp
                      <tr>
                        <th>{{$a}}</th>
                        <td>{{ $team->title ?? ''}}</td>
                        <td>{{ $team->description ?? ''}}</td>
                        <td>{{ $team->created_at ?? ''}}</td>
                        @if($team->image)
                            <td>
                                <img id="slide_img_path" height="100" width="100" 
                                src="{{ asset($team->image) }}">
                            </td>
                        @else
                            <td>Sorry, No Image!</td>
                        @endif

                    
                        <td>
                          <div class="btn-group" id="btns<?php echo $a ?>">
                            <a href="javascript:();" class="dCnf" mydata="<?php echo $a ?>" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fas fa-trash danger-icon"></i></a>
                          </div>
                          <div style="display:none" id="cnfbox<?php echo $a ?>">
                            <p> Are you sure delete this </p>
                            <a href="{{route('deletenotification',$team->id)}}" class="btn btn-danger">Yes</a>
                            
                          </div>
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div> <!-- end col -->
      </div> <!-- end row -->
    </div>
    <!-- end page content-->
  </div> <!-- container-fluid -->
</div> <!-- content -->

@endsection