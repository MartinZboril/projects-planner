<div class="row">
    <div class="col-md-6">
        <div class="card card-primary card-outline">
            <div class="card-header">{{ $type === 'edit' ? 'Edit' : 'Create' }} User</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group required">
                            <label for="name" class="control-label">Name</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="name" value="{{ old('name', $user->name ?? null) }}" >
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group required">
                            <label for="surname" class="control-label">Surname</label>
                            <input type="text" name="surname" id="surname" class="form-control @error('surname') is-invalid @enderror" placeholder="surname" value="{{ old('surname', $user->surname ?? null) }}" >
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
                            <input type="text" name="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="email" value="{{ old('email', $user->email ?? null) }}" >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group required">
                            <label for="username" class="control-label">Username</label>
                            <input type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror" placeholder="username" value="{{ old('username', $user->username ?? null) }}" >
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="form-group required">
                    <label for="role-id" class="control-label">Role</label>
                    <select class="form-control @error('role_id') is-invalid @enderror" name="role_id" id="role-id" style="width: 100%;">
                        <option disabled selected value>select role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" @selected((int) old('role_id', $user->role->id ?? null) === $role->id)>{{ $role->name }}</option>
                        @endforeach
                    </select>
                    @error('role_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="avatar">Avatar</label>
                            <input type="file" name="avatar" class="form-control border text-sm rounded-lg @error('avatar') is-invalid @enderror">
                            @error('avatar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6 text-center">
                        <img id="user-avatar-image" src="{{ ($user->avatar->id ?? false) ? asset('storage/' . $user->avatar->path) : asset('dist/img/user.png') }}" class="img-circle" alt="User Image" style="width: 80px;height: 80px;">
                        @if ($user->avatar->id ?? false)
                            <div id="remove-user-avatar">
                                <a href="#" class="text-danger" onclick="removeUserAvatar('{{ route('users.avatar.remove', $user) }}', '{{ asset('dist/img/user.png') }}')">
                                    <i class="fas fa-unlink"></i> Remove
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="job_title">Job title</label>
                            <input type="text" name="job_title" id="job-title" class="form-control @error('job_title') is-invalid @enderror" placeholder="job title" value="{{ old('job_title', $user->job_title ?? null) }}" >
                            @error('job_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" class="form-control bg-white @error('password') is-invalid @enderror" placeholder="password" autocomplete="off" readonly
                            onfocus="this.removeAttribute('readonly');">
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
                            <input type="text" name="mobile" id="mobile" class="form-control @error('mobile') is-invalid @enderror" placeholder="mobile" value="{{ old('mobile', $user->mobile ?? null) }}" >
                            @error('mobile')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="phone" value="{{ old('phone', $user->phone ?? null) }}" >
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="street">Street</label>
                            <input type="text" name="street" id="street" class="form-control @error('street') is-invalid @enderror" placeholder="street" value="{{ old('street', $user->address->street ?? null) }}" >
                            @error('street')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="house_number">House number</label>
                            <input type="text" name="house_number" id="house_number" class="form-control @error('house_number') is-invalid @enderror" placeholder="house number" value="{{ old('house_number', $user->address->house_number ?? null) }}" >
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
                            <input type="text" name="city" id="city" class="form-control @error('city') is-invalid @enderror" placeholder="city" value="{{ old('city', $user->address->city ?? null) }}" >
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="zip_code">ZIP code</label>
                            <input type="text" name="zip_code" id="zip_code" class="form-control @error('zip_code') is-invalid @enderror" placeholder="zip code" value="{{ old('zip_code', $user->address->zip_code ?? null) }}" >
                            @error('zip_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="country">Country</label>
                            <input type="text" name="country" id="country" class="form-control @error('country') is-invalid @enderror" placeholder="country" value="{{ old('country', $user->address->country ?? null) }}" >
                            @error('country')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card card-primary card-outline">
            <div class="card-header">Notifications settings</div>
            <div class="card-body">
                <h6>Project</h6>
                <div class="row mt-3 mb-2">
                    <x-notification.settings-option title="Project reminder" parent="project" action="reminder" :$user :$type />
                    <x-notification.settings-option title="Project deleted" parent="project" action="deleted" :$user :$type />
                    <x-notification.settings-option title="Project assigned" parent="project" action="assigned" :$user :$type />
                    <x-notification.settings-option title="Project unassigned" parent="project" action="unassigned" :$user :$type />
                    <x-notification.settings-option title="Project finished" parent="project" action="finished" :$user :$type />
                    <x-notification.settings-option title="Project reactivated" parent="project" action="reactivated" :$user :$type />
                    <x-notification.settings-option title="Project archived" parent="project" action="archived" :$user :$type />
                </div>
                <h6>Milestone</h6>
                <div class="row mt-3 mb-2">
                    <x-notification.settings-option title="Milestone reminder" parent="milestone" action="reminder" :$user :$type />
                    <x-notification.settings-option title="Milestone assigned" parent="milestone" action="assigned" :$user :$type />
                    <x-notification.settings-option title="Milestone unassigned" parent="milestone" action="unassigned" :$user :$type />
                </div>
                <h6>Task</h6>
                <div class="row mt-3 mb-2">
                    <x-notification.settings-option title="Task reminder" parent="task" action="reminder" :$user :$type />
                    <x-notification.settings-option title="Task deleted" parent="task" action="deleted" :$user :$type />
                    <x-notification.settings-option title="Task assigned" parent="task" action="assigned" :$user :$type />
                    <x-notification.settings-option title="Task unassigned" parent="task" action="unassigned" :$user :$type />
                    <x-notification.settings-option title="Task in progressed" parent="task" action="in_progressed" :$user :$type />
                    <x-notification.settings-option title="Task completed" parent="task" action="completed" :$user :$type />
                    <x-notification.settings-option title="Task returned" parent="task" action="returned" :$user :$type />
                    <x-notification.settings-option title="Task paused" parent="task" action="paused" :$user :$type />
                    <x-notification.settings-option title="Task resumed" parent="task" action="resumed" :$user :$type />
                </div>
                <h6>Todo</h6>
                <div class="row mt-3 mb-2">
                    <x-notification.settings-option title="Todo reminder" parent="todo" action="reminder" :$user :$type />
                </div>
                <h6>Ticket</h6>
                <div class="row mt-3 mb-2">
                    <x-notification.settings-option title="Ticket reminder" parent="ticket" action="reminder" :$user :$type />
                    <x-notification.settings-option title="Ticket deleted" parent="ticket" action="deleted" :$user :$type />
                    <x-notification.settings-option title="Ticket assigned" parent="ticket" action="assigned" :$user :$type />
                    <x-notification.settings-option title="Ticket unassigned" parent="ticket" action="unassigned" :$user :$type />
                    <x-notification.settings-option title="Ticket closed" parent="ticket" action="closed" :$user :$type />
                    <x-notification.settings-option title="Ticket reopened" parent="ticket" action="reopened" :$user :$type />
                    <x-notification.settings-option title="Ticket archived" parent="ticket" action="archived" :$user :$type />
                    <x-notification.settings-option title="Ticket converted" parent="ticket" action="converted" :$user :$type />
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <input type="submit" name="save" class="btn btn-sm btn-primary mr-1" value="Save"><input type="submit" name="save_and_close" class="btn btn-sm btn-secondary" value="Save and close"> or <a href="{{ $closeRoute }}" class="cancel-btn">Close</a></span>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#role-id').select2({
                theme: 'bootstrap4',
                placeholder: 'select role'
            });
        });
    </script>
@endpush
