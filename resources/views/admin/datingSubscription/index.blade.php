@extends('admin.base_template')
@section('main')
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
                <div class="col-md-2"> <a class="btn btn-info cticket" href="{{route('datingsubscriptionCreate')}}" role="button" style="margin-left: 20px;"> Add {{$tital}}</a></div>
              </div>
              <hr style="margin-bottom: 50px;background-color: darkgrey;">
              <div class="table-rep-plugin">
                <div class="table-responsive b-0" data-pattern="priority-columns">
                  <table id="userTable" class="table  table-striped">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th data-priority="1">Expire Days</th>
                        <th data-priority="2">Dating Feature Type</th>
                        <th data-priority="3">Unlimited Swipes</th>
                        <th data-priority="4">Swipe Message</th>
                        <th data-priority="4">Backtrack</th>
                        <th data-priority="4">Access Admirers</th>
                        <th data-priority="4">Cost</th>
                        <th data-priority="4">Status</th>
                        <th data-priority="5">created_at</th>
                        <th data-priority="6">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($data as $data)
                      <tr>
                        <th>{{$a = $loop->index+1}}</th>
                        <td>
                            {{$data->expire_days ?? ''}}
                        </td>
                        <td>
                            @if( $data->type == 'free')
                            Free dating features
                            @else
                            Subscription features
                            @endif
                        </td>
                        <td>
                            @if($data->unlimited_swipes == 0)
                            No
                            @else
                            Yes
                            @endif
                        </td>
                        <td>
                            @if($data->swipe_message == 0)
                            No
                            @else
                            Yes
                            @endif
                        </td>
                        <td>
                            @if($data->backtrack == 0)
                            No    
                            @else
                            Yes
                            @endif
                        </td>
                        <td>
                            @if($data->access_admirers == 0)
                            No
                            @else
                            Yes
                            @endif
                        </td>
                        <td>{{ $data->cost ?? ''}}</td>
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


                            @if ($data->status == 1)
                                <a href="{{ route('dating-subscription.update-status', ['status' => 'inactive', 'id' => base64_encode($data->id)]) }}" 
                                data-toggle="tooltip" data-placement="top" title="Set to Inactive">
                                    <i class="fas fa-check success-icon"></i>
                                </a>
                            @else
                                <a href="{{ route('dating-subscription.update-status', ['status' => 'active', 'id' => base64_encode($data->id)]) }}" 
                                data-toggle="tooltip" data-placement="top" title="Set to Active">
                                    <i class="fas fa-times danger-icon"></i>
                                </a>
                            @endif
                            <a href="javascript:();" class="dCnf" mydata="<?php echo $a ?>" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fas fa-trash danger-icon"></i></a>
                            <a href="{{ route('datingsubscription.edit', $data->id) }}" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fas fa-edit info-icon"></i></a>

                          </div>
                          <div style="display:none" id="cnfbox<?php echo $a ?>">
                            <p> Are you sure delete this </p>
                            <form id="deleteForm<?php echo $a; ?>" action="{{ route('datingsubscription.delete', $data->id) }}" method="post" style="display:inline;">
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