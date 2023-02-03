<i class="far fa-file fa-3x text-danger float-left mr-2"></i>
<a href="{{ asset('storage/' . $file->path) }}" download="{{ $file->file_name }}">{{ $file->file_name }}</a>
<span class="text-muted float-right">
    <i class="fas fa-clock"></i>
    {{ $file->created_at->diffForHumans() }}
</span>
<div class="text-muted">
    Size: {{ $file->kilobytes_size }} Kb
    <span class="float-right">
        <span class="">{{ $file->name }}</span>
        <a href="#" onclick="copyFilePath('{{ asset('storage/' . $file->path) }}')" class="m-l-xs">
            <i class="far fa-copy"></i>
        </a>
        <a href="{{ asset('storage/' . $file->path) }}" download="{{ $file->file_name }}">
            <i class="fas fa-download"></i>
        </a>
    </span>
</div>