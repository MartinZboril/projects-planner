<form action="{{ route('tasks.files.upload', $task) }}" method="post" enctype="multipart/form-data">
    @csrf
    @method('POST')
    <input type="file" name="files[]" multiple class="@error('files'){{ 'is-invalid' }}@enderror"> 
    <input type="submit" name="upload" class="btn btn-sm btn-primary mr-1" value="Upload"></span> 
    @error('files')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror               
</form>