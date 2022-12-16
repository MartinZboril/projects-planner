<div class="table-responsive">
    <table id="@if($user->rates->count() > 0){{ 'rates-table' }}@endif" class="table table-bordered table-striped">
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
            @forelse ($user->rates as $rate)
                <tr>
                    <th>
                        @if ($rate->note)
                            <i class="fas fa-info-circle" data-toggle="popover" title="Note" data-content="{{ $rate->note }}"></i>
                        @else
                            #
                        @endif
                    </th>
                    <td><a href="{{ route('rates.edit', ['user' => $user->id, 'rate' => $rate->id]) }}">{{ $rate->name }}</a></td>
                    <td>{{ $rate->is_active ? 'Yes' : 'No' }}</td>
                    <td>@include('site.partials.amount', ['value' => $rate->value])</td>
                    <td>                                                    
                        <a href="{{ route('rates.edit', ['user' => $user->id, 'rate' => $rate->id]) }}" class="btn btn-sm btn-dark"><i class="fas fa-pencil-alt"></i></a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="100%" class="text-center">No rates were found!</td>
                </tr>
            @endforelse
        </tbody>
    </table>  
</div>