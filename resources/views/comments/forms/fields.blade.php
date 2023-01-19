<textarea name="content" class="form-control" id="{{ $type === 'edit' ? 'content-editor-comment-' . $comment->id : 'content' }}" cols="30" rows="10" placeholder="content">{{ old('content', $comment->content) }}</textarea>
@error('content')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
<input type="file" name="files[]" multiple>
<hr>
@if ($type === 'create')
<input type="hidden" name="parent_id" value="{{ $parentId }}">
<input type="hidden" name="type" value="{{ $parentType }}">
@endif
<input type="submit" name="save" class="btn btn-sm btn-primary mr-1" value="Save"></span>
@if ($type === 'edit')                
    <a class="btn btn-secondary btn-sm" onclick="updateContentView('display', {{ $comment->id }})"><i class="fas fa-times mr-1"></i>Close</a>
@endif
