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
                {{-- <div class="col-md-2"> <a class="btn btn-info cticket" href="{{route('vibe.create')}}" role="button" style="margin-left: 20px;"> Add {{$tital}}</a></div>
              </div> --}}
              <hr style="margin-bottom: 50px;background-color: darkgrey;">
              <div class="table-rep-plugin">
                <div class="table-responsive b-0" data-pattern="priority-columns">
                  <table id="userTable" class="table  table-striped">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th data-priority="1">Reporting User</th>
                        <th data-priority="2">Reported User</th>
                        <th data-priority="2">Reason</th>
                        {{-- <th data-priority="3">Image</th> --}}
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($vibe as $data)
                      <tr>
                        <th>{{$a = $loop->index+1}}</th>
                        <td>
                          <a href="{{route('users.index')}}">
                              {{ $data->reportingUser->name ?? 'N/A' }}
                          </a>
                       </td>

                        <td>
                          <a href="{{route('users.index')}}">
                              {{ $data->reportedUser->name ?? 'N/A' }}
                          </a>
                          {{-- {{ $data->reportedUser->name ?? 'N/A' }}</td> --}}
                        <td>{{ $data->reason}}</td>
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