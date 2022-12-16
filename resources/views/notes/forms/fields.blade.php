<div class="row">
    <div class="col-md-7">
        <div class="card card-primary card-outline">
            <div class="card-header">{{ $type == 'create' ? 'Create' : 'Edit' }} note</div>
            <div class="card-body">
                <div class="form-group required">
                    <label for="name" class="control-label">Name</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="name" value="{{ old('name', $note->name) }}" autocomplete="off">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="is_private" class="custom-control-input @error('is_private') is-invalid @enderror" id="is-private" value="1" @checked(old('is_private', $note->is_private))>
                        <label class="custom-control-label" for="is-private">Private</label>
                    </div>
                    @error('is_private')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group required">
                    <label for="content" class="control-label">Content</label>
                    <textarea name="content" id="content" class="form-control @error('content') is-invalid @enderror" rows="5" placeholder="content" autocomplete="off">{{ old('content', $note->content) }}</textarea>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
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
                <input type="submit" name="save" class="btn btn-sm btn-primary mr-1" value="Save"> or <a href="
                @if ($parentType == 'project')
                    {{ route('projects.notes', $parentId) }}
                @elseif ($parentType == 'client')
                    {{ route('clients.notes', $parentId) }}
                @else
                    {{ route('notes.index') }}
                @endif" class="cancel-btn">Close</a></span>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="parent_id" value="{{ $parentId }}">
<input type="hidden" name="type" value="{{ $parentType }}">