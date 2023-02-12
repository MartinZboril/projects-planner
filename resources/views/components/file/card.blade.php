<div class="card card-primary card-outline">
    <div class="card-header">Files</div>
    <div class="card-body">
        <x-file.uploader :$uploadFormRoute />
        <hr>
        <x-file.list :$files />
    </div>
</div>