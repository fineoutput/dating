@extends('admin.base_template')

@section('main')
<!-- Start content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <h4 class="page-title">{{ $title }}</h4>
                    <ol class="breadcrumb" style="display:none">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">{{$title}}</a></li>
                        <li class="breadcrumb-item active"> {{$title}}</li>
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

                            <h4 class="mt-0 header-title">{{$title}} Form</h4>
                            <hr style="margin-bottom: 50px;background-color: darkgrey;">
                            <form action="{{ isset($user) ? route('users.storeOrUpdate', $user->id) : route('users.storeOrUpdate') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                

                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" value="{{ old('name', $user->name ?? '') }}" id="name" name="name" placeholder="Enter name">
                                            <label for="name">Enter Name</label>
                                        </div>
                                        @error('name')
                                            <div style="color:red">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" value="{{ old('number', $user->number ?? '') }}" id="number" name="number" placeholder="Enter number" required autocomplete="off">
                                            <label for="number">Enter Number &nbsp;<span style="color:red;">*</span></label>
                                        </div>
                                        @error('number')
                                            <div style="color:red">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-floating">
                                            <input type="email" class="form-control" value="{{ old('email', $user->email ?? '') }}" id="email" name="email" placeholder="Enter email">
                                            <label for="email">Enter Email</label>
                                        </div>
                                        @error('email')
                                            <div style="color:red">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" value="{{ old('age', $user->age ?? '') }}" id="age" name="age" placeholder="Enter age">
                                            <label for="age">Age</label>
                                        </div>
                                        @error('age')
                                            <div style="color:red">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-sm-4">
                                        <label for="gender">Gender</label>
                                        <select class="form-control" id="gender" name="gender">
                                            <option value="1" {{ old('gender', isset($user) ? $user->gender : null) == 1 ? 'selected' : '' }}>Male</option>
                                            <option value="2" {{ old('gender', isset($user) ? $user->gender : null) == 2 ? 'selected' : '' }}>Female</option>
                                            <option value="3" {{ old('gender', isset($user) ? $user->gender : null) == 3 ? 'selected' : '' }}>Custom</option>
                                        </select>                                        
                                        @error('gender')
                                            <div style="color:red">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-sm-4">
                                        <label for="looking_for">Looking For</label>
                                        <select class="form-control" id="looking_for" name="looking_for">
                                            <option value="1" {{ isset($user) && $user->looking_for == 1 ? 'selected' : '' }}>A relationship</option>
                                            <option value="2" {{ isset($user) && $user->looking_for == 2 ? 'selected' : '' }}>Something casual</option>
                                            <option value="3" {{ isset($user) && $user->looking_for == 3 ? 'selected' : '' }}>I'm not sure yet</option>
                                        </select>
                                        
                                        @error('looking_for')
                                            <div style="color:red">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="state">State</label>
                                        <select class="form-control" id="state" name="state">
                                            @foreach ($states as $state)
                                                <option value="{{ $state->id }}" {{ old('state', isset($user) ? $user->state : null) == $state->id ? 'selected' : '' }}>
                                                    {{ $state->state_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        
                                        @error('state')
                                            <div style="color:red">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-sm-4">
                                        <label for="city">City</label>
                                        <select class="form-control" id="city" name="city">
                                        </select>
                                        @error('city')
                                            <div style="color:red">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-floating">
                                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter password">
                                            <label for="password">Password</label>
                                        </div>
                                        @error('password')
                                            <div style="color:red">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label for="profile_image">Profile Image</label>
                                        <input class="form-control" type="file" id="profile_image" name="profile_image[]" multiple>
                                        @isset($user)
                                        @php
                                        $profileImages = json_decode($user->profile_image, true); // Decode the stored JSON string
                                         @endphp
                                         @if($profileImages)
                                        <div class="mt-2">
                                         @foreach($profileImages as $image)
                                        <img src="{{ asset('uploads/app/profile_images/' . $image) }}" width="100px" alt="Profile Image">
                                        @endforeach
                                        </div>
                                        @endif
                                         @endisset
                                        @error('profile_image')
                                            <div style="color:red">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-sm-6">
                                        <label for="interest">Interest</label>
                                        <select class="form-control" id="interest" name="interest[]" multiple>
                                            @foreach ($interests as $interest)
                                                <option value="{{ $interest->id }}" 
                                                    {{ isset($user) && in_array($interest->id, json_decode($user->interest ?? '[]', true)) ? 'selected' : '' }}>
                                                    {{ $interest->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        
                                        
                                        
                                                                        
                                        @error('interest')
                                            <div style="color:red">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="w-100 text-center">
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fa fa-user"></i> {{ isset($user) ? 'Update' : 'Submit' }}
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

<script>
    $(document).ready(function() {
        // Load cities for the selected state when the page loads
        let selectedState = $('#state').val();  // Get the selected state
        if (selectedState) {
            loadCities(selectedState, "{{ old('city', isset($user) ? $user->city : '') }}");  // Preselect the city if it's available
        }

        // Fetch cities when state is changed
        $('#state').change(function() {
            let stateId = $(this).val();
            loadCities(stateId);  // Load cities based on selected state
        });

        function loadCities(stateId, selectedCity = null) {
            if (stateId) {
                $.ajax({
                    url: '/cities/' + stateId,
                    method: 'GET',
                    success: function(response) {
                        let cities = response.cities;
                        $('#city').empty().append('<option value="">Select a City</option>');
                        cities.forEach(function(city) {
                            // Append the city options
                            $('#city').append('<option value="' + city.id + '" ' + (selectedCity == city.id ? 'selected' : '') + '>' + city.city_name + '</option>');
                        });
                        $('#city').prop('disabled', false);
                    },
                    error: function() {
                        alert('Error fetching cities');
                    }
                });
            } else {
                $('#city').prop('disabled', true).empty().append('<option value="">Select a City</option>');
            }
        }

        // Initialize select2 for interests
        $('#interest').select2({
            placeholder: 'Select interests',
            allowClear: true
        });
    });
</script>

@endsection
