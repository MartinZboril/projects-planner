<div class="row">
    <div class="col-md-7">
        <div class="card card-primary card-outline">
            <div class="card-header">{{ $type === 'edit' ? 'Edit' : 'Create' }} Rate</div>
            <div class="card-body">
                <div class="form-group required">
                    <label for="name" class="control-label">Name</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="name" value="{{ old('name', $rate->name ?? null) }}" >
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="is_active" class="custom-control-input @error('is_active') is-invalid @enderror" id="is-active" value="1" @checked(old('is_active', $rate->is_active ?? null))>
                        <label class="custom-control-label" for="is-active">Active</label>
                    </div>
                    @error('is_active')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>  
                <div class="form-group required">
                    <label for="value" class="control-label">Value</label>
                    <input type="number" name="value" id="value" class="form-control @error('value') is-invalid @enderror" placeholder="value" value="{{ old('value', $rate->value ?? null) }}" >
                    @error('value')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="note">Note</label>
                    <textarea name="note" id="note" class="form-control @error('note') is-invalid @enderror" rows="5" placeholder="note" >{{ old('note', $rate->note ?? null) }}</textarea>
                    @error('note')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card card-primary card-outline">
            <div class="card-header">Users</div>
            <div class="card-body">
                <div class="form-group" style="column-count:4;">
                    @forelse ($users as $user)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="users[]" id="user-{{ $user->id }}-option" value="{{ $user->id }}" @checked(old('users.'.$loop->index, $rate->users->contains($user)))>
                            <label class="form-check-label" for="user-{{ $user->id }}-option">{{ $user->full_name }}</label>
                        </div>                
                    @empty
                        <p>No Users</p>
                    @endforelse
                </div>
                @error('users')
                    <div class="mt-1 text-danger">{{ $message }}</div>
                @enderror                
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <input type="submit" name="save" class="btn btn-sm btn-primary mr-1" value="Save"> or <a href="{{ $closeRoute }}" class="cancel-btn">Close</a></span>
            </div>
        </div>
    </div>
</div>