<div class="table-responsive">
    <table id="{{ $tableId }}" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Rate</th>
                <th>Active</th>
                <th>Value</th>
                <th></th>
            </tr>
        </thead>
    </table>  
</div>

@push('scripts')
    <script>
        $(function () {
            const table = $('#{{ $tableId }}').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('users.rates.load', $user) }}",
                columns: [
                    {data: 'note_popover', name: 'note_popover'},
                    {data: 'detail', name: 'detail'},
                    {data: 'is_active', name: 'is_active'},
                    {data: 'value', name: 'value'},
                    {data: 'buttons', name: 'buttons', orderable: false, searchable: false},
                ]
            });
            $('#{{ $tableId }}').on('draw.dt', function() {
                $('[data-toggle="popover"]').popover();
            });
        });
    </script>
@endpush