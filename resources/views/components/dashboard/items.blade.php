<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">
            {{ $title }}
            <span class="badge badge-primary ml-2" style="font-size:14px;">{{ $items->count() }}</span>
        </h3>
    </div>    
    <div class="card-body table-responsive p-0">
        <table class="table table-striped table-valign-middle">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Due Date</th>
                    <th></th>
                </tr>
            </thead>
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td><a href="{{ $item->get('url') }}">{{ $item->get('name') }}</a></td>
                    <td><x-dashboard.type :type="$item->get('type')" /></td>
                    <td>{{ $item->get('due_date') ? $item->get('due_date')->format('d.m.Y') : '-' }}</td>
                    <td>
                        <a href="{{ $item->get('url') }}" class="text-muted"><i class="fas fa-eye"></i></a>
                    </td>            
                </tr>
            @endforeach
        </tbody>
        </table>
    </div>
</div>