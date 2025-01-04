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
                <div class="col-md-2"> <a class="btn btn-info cticket" href="{{route('activitysubscriptionCreate')}}" role="button" style="margin-left: 20px;"> Add {{$tital}}</a></div>
              </div>
              <hr style="margin-bottom: 50px;background-color: darkgrey;">
              <div class="table-rep-plugin">
                <div class="table-responsive b-0" data-pattern="priority-columns">
                  <table id="userTable" class="table  table-striped">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th>Expire Days</th>
                        <th>Cost</th>
                        <th>Type</th>
                        <th>Interests</th>
                        <th>Status</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $item->title }}</td>
                            <td>{{ $item->description }}</td>
                            <td>{{ $item->category->category ?? 'N/A' }}</td>
                            <td>{{ $item->expire_days ?? 'N/A' }}</td>
                            <td>{{ $item->category->cost ?? 'N/A' }}</td>
                            <td>{{ ucfirst($item->type) }}</td>
                            <td>
                                @if($item->interests_id)
                                <ul>
                                    @foreach(explode(',', $item->interests_id) as $interestId)
                                        <!-- You can use the Interest ID to fetch the actual Interest name if needed -->
                                        @php
                                            $interest = \App\Models\Interest::find($interestId);
                                        @endphp
                                        @if($interest)
                                            <li>Interest Name: {{ $interest->name }}</li>
                                        @else
                                            <li>Interest Not Found</li>
                                        @endif
                                    @endforeach
                                </ul>
                            @else
                                <p>No Interests Selected</p>
                            @endif
                            </td>
                            <td>
                                @if($item->status == 1)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                            </td>
                            <td>
                                @if($item->status == 1)
                                <!-- If active, show deactivate button -->
                                <a href="{{ route('activity-subscription.update-status', ['status' => 'inactive', 'id' => base64_encode($item->id)]) }}"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Are you sure you want to deactivate this item?');">
                                   Deactivate
                                </a>
                            @else
                                <!-- If inactive, show activate button -->
                                <a href="{{ route('activity-subscription.update-status', ['status' => 'active', 'id' => base64_encode($item->id)]) }}"
                                   class="btn btn-success btn-sm"
                                   onclick="return confirm('Are you sure you want to activate this item?');">
                                   Activate
                                </a>
                            @endif
                                <a href="{{ route('activitysubscription.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('activitysubscription.delete', $item->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
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