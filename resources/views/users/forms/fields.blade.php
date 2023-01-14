<div class="row">
    <div class="col-md-7">
        <div class="card card-primary card-outline">
            <div class="card-header">{{ $type == 'create' ? 'Create' : 'Edit'}} user</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group required">
                            <label for="name" class="control-label">Name</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="name" value="{{ old('name', $user->name) }}" autocomplete="off">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group required">
                            <label for="surname" class="control-label">Surname</label>
                            <input type="text" name="surname" id="surname" class="form-control @error('surname') is-invalid @enderror" placeholder="surname" value="{{ old('surname', $user->surname) }}" autocomplete="off">
                            @error('surname')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group required">
                            <label for="email" class="control-label">Email</label>
                            <input type="text" name="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="email" value="{{ old('email', $user->email) }}" autocomplete="off">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group required">
                            <label for="username" class="control-label">Username</label>
                            <input type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror" placeholder="username" value="{{ old('username', $user->username) }}" autocomplete="off">
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="avatar">Avatar</label>
                            <input type="file" class="form-control border text-sm rounded-lg @error('avatar') is-invalid @enderror">
                            @error('avatar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror   
                        </div>
                    </div>
                    <div class="col-md-6 text-center">
                        <img src="{{ Auth::user()->avatar_path ? asset('storage/' . Auth::user()->avatar_path) : asset('dist/img/user.png') }}" class="img-circle" alt="User Image" style="width: 80px;height: 80px;">
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="job_title">Job title</label>
                            <input type="text" name="job_title" id="job-title" class="form-control @error('job_title') is-invalid @enderror" placeholder="job title" value="{{ old('job_title', $user->job_title) }}" autocomplete="off">
                            @error('job_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="password" value="{{ old('password') }}" autocomplete="off">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="mobile">Mobile</label>
                            <input type="text" name="mobile" id="mobile" class="form-control @error('mobile') is-invalid @enderror" placeholder="mobile" value="{{ old('mobile', $user->mobile) }}" autocomplete="off">
                            @error('mobile')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="phone" value="{{ old('phone', $user->phone) }}" autocomplete="off">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                @if ($type == 'create')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group required">
                                <label for="rate-name" class="control-label">Rate name</label>
                                <input type="text" name="rate_name" id="rate-name" class="form-control @error('rate_name') is-invalid @enderror" placeholder="rate name" value="{{ old('rate_name') }}" autocomplete="off">
                                @error('rate_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group required">
                                <label for="rate-value" class="control-label">Rate value</label>
                                <input type="text" name="rate_value" id="rate-value" class="form-control @error('rate_value') is-invalid @enderror" placeholder="rate value" value="{{ old('rate_value') }}" autocomplete="off">
                                @error('rate_value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>                    
                @endif
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="street">Street</label>
                            <input type="text" name="street" id="street" class="form-control @error('street') is-invalid @enderror" placeholder="street" value="{{ old('street', $user->street) }}" autocomplete="off">
                            @error('street')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="house_number">House number</label>
                            <input type="text" name="house_number" id="house_number" class="form-control @error('house_number') is-invalid @enderror" placeholder="house number" value="{{ old('house_number', $user->house_number) }}" autocomplete="off">
                            @error('house_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="city">City</label>
                            <input type="text" name="city" id="city" class="form-control @error('city') is-invalid @enderror" placeholder="city" value="{{ old('city', $user->city) }}" autocomplete="off">
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="zip_code">ZIP code</label>
                            <input type="text" name="zip_code" id="zip_code" class="form-control @error('zip_code') is-invalid @enderror" placeholder="zip code" value="{{ old('zip_code', $user->zip_code) }}" autocomplete="off">
                            @error('zip_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="country">Country</label>
                            <input type="text" name="country" id="country" class="form-control @error('country') is-invalid @enderror" placeholder="country" value="{{ old('country', $user->country) }}" autocomplete="off">
                            @error('country')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card card-primary card-outline">
            <div class="card-header">Settings</div>
            <div class="card-body">
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <input type="submit" name="save" class="btn btn-sm btn-primary mr-1" value="Save"><input type="submit" name="save_and_close" class="btn btn-sm btn-secondary" value="Save and close"> or <a href="{{ $type == 'edit' ? route('users.detail', $user->id) : route('users.index') }}" class="cancel-btn">Close</a></span>
            </div>
        </div>
    </div>
</div>