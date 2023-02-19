<div class="table-responsive">
    <table id="{{ $rates->count() === 0 ?: $tableId }}" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Rate</th>
                <th>Active</th>
                <th>Value</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rates as $rate)
                <tr>
                    <th>
                        @if ($rate->note)
                            <i class="fas fa-info-circle" data-toggle="popover" title="Note" data-content="{{ $rate->note }}"></i>
                        @else
                            #
                        @endif
                    </th>
                    <td><a href="{{ $rate->edit_route }}">{{ $rate->name }}</a></td>
                    <td>{{ $rate->active }}</td>
                    <td>@money($rate->value)</td>
                    <td><a href="{{ $rate->edit_route }}" class="btn btn-sm btn-dark"><i class="fas fa-pencil-alt"></i></a></td>
                </tr>
            @empty
                <tr>
                    <td colspan="100%" class="text-center">No rates were found!</td>
                </tr>
            @endforelse
        </tbody>
    </table>  
</div>

@push('scripts')
    <script>
        $(function () {
            $("#{{ $tableId }}").DataTable();
            $('[data-toggle="popover"]').popover();
        });
    </script>
@endpush