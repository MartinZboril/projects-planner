@forelse ($files as $file)
    <li class="list-group-item small">
        @include('site.files.file', ['file' => $file])
    </li>
@empty
    <li class="list-group-item">No files were uploaded!</li>
@endforelse

@push('scripts')
    <script>
        function copyFilePath(copyText) {
            navigator.clipboard.writeText(copyText);
            // Notifiy user about copying file path
            toastr.info('File path was copied!');
        }
    </script>
@endpush