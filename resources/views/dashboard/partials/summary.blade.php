<div class="card card-primary card-outline">
    <div class="card-header">
        {{ $title }}
        <span class="badge badge-primary ml-2" style="font-size:14px;">{{ $items->count() }}</span>
    </div>
    <div class="card-body">
        <!-- Content -->
        <div class="table-responsive">
            <table id="@if($items->count() > 0){{ $id }}@endif" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Due date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr>
                            <td><a href="{{ $item->get('url') }}">{{ $item->get('name') }}</a></td>
                            <td>@include('dashboard.partials.type', ['type' => $item->get('type')]){{ __('pages.title.' . $item->get('type')) }}</td>
                            <td>{{ $item->get('due_date') ? $item->get('due_date')->format('d.m.Y') : '-' }}</td>
                            <td>
                                <a href="{{ $item->get('url') }}" class="btn btn-xs btn-info"><i class="fas fa-eye"></i></a>
                                <!-- Action forms -->
                                @include('dashboard.partials.buttons', ['item' => $item->get('item'), 'type' => $item->get('type')])
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="100%" class="text-center">You are free for today!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>