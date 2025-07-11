@extends('admin.base_template')
@section('main')

<style>
  button.btn{
    border-radius: 105px;
}
</style>
<!-- Start content -->
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-12">
        <div class="page-title-box">
          <h4 class="page-title">View {{$tital}}</h4>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">{{$tital}}</a></li>
            <li class="breadcrumb-item active">View {{$tital}}</li>
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
                  <h4 class="mt-0 header-title">View {{$tital}} List</h4>
                </div>
                <div class="col-md-2"> <a class="btn btn-info cticket" href="{{route('vibe.create')}}" role="button" style="margin-left: 20px;"> Add {{$tital}}</a></div>
              </div>
              <hr style="margin-bottom: 50px;background-color: darkgrey;">
              <div class="table-rep-plugin">
                <div class="table-responsive b-0" data-pattern="priority-columns">
                  <table id="userTable" class="table  table-striped">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th data-priority="1">Name</th>
                        <th data-priority="2">icon</th>
                        <th data-priority="2">Image</th>
                        {{-- <th data-priority="3">Image</th> --}}
                        <th data-priority="4">status</th>
                        <th data-priority="5">created_at</th>
                        <th data-priority="6">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($vibe as $data)
                      <tr>
                        <th>{{$a = $loop->index+1}}</th>
                        <td>{{ $data->name}}</td>
                        <td>{{ $data->image}}</td>
                        <td>
                            <img width="100" height="100" src="{{ asset($data->icon) ?? ''}}" alt="">
                        </td>
                        {{-- <td>{{ $data->desc}}</td> --}}
                        @if($data->status == 1)
                        <td>
                          <p class="label pull-right status-active">Active</p>
                        </td>
                        @else
                        <td>
                          <p class="label pull-right status-inactive">InActive</p>
                        </td>
                        @endif
                        <td>{{ formatDateTime($data->created_at) }}</td>
                        <td>
                          <div class="btn-group" id="btns<?php echo $a ?>">


                            <form action="{{ route('vibe.updateStatus', $data->id) }}" method="POST" style="display:inline;">
                              @csrf
                              @method('PATCH') 
                              @if ($data->status == 1) <!-- If active -->
                                  <button type="submit" class="btn btn-warning " onclick="return confirm('Are you sure you want to deactivate this vendor?');" data-toggle="tooltip" data-placement="top" title="Deactivate vendor">
                                      <i class="fas fa-times"></i> <!-- Times icon for Deactivate -->
                                  </button>
                              @else <!-- If inactive -->
                                  <button type="submit" class="btn btn-success " onclick="return confirm('Are you sure you want to activate this vendor?');" data-toggle="tooltip" data-placement="top" title="Activate vendor">
                                      <i class="fas fa-check"></i> <!-- Check icon for Activate -->
                                  </button>
                              @endif
                          </form>

                            <a href="javascript:();" class="dCnf" mydata="<?php echo $a ?>" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fas fa-trash danger-icon"></i></a>

                            <a href="{{ route('vibe.edit',['id'=> $data->id]) }}" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fas fa-edit info-icon"></i></a>

                          </div>

                          <div style="display:none" id="cnfbox<?php echo $a ?>">
                            <p> Are you sure delete this </p>
                            <form id="deleteForm<?php echo $a; ?>" action="{{ route('vibe.destroy',['id'=> $data->id]) }}" method="post" style="display:inline;">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Delete">
                                  <i class="fas fa-trash"></i> Yes
                              </button>
                          </form>
                            <a href="javascript:();" class="cans btn btn-default" mydatas="<?php echo $a ?>">No</a>
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