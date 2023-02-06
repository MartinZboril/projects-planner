<div class="table-responsive">
    <table id="@if($clients->count() > 0){{ 'clients-table' }}@endif" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Contact Person</th>
                <th>Email</th>
                <th>Date</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($clients as $client)
                <tr>
                    <td><a href="{{ route('clients.show', $client->id) }}">{{ $client->name }}</a></td>
                    <td>{{ $client->contact_person_label }}</td>
                    <td>{{ $client->email_label }}</td>
                    <td>{{ $client->created_at->format('d.m.Y') }}</td>
                    <td>
                        <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-xs btn-dark"><i class="fas fa-pencil-alt"></i></a>
                        <a href="{{ route('clients.show', $client->id) }}" class="btn btn-xs btn-info"><i class="fas fa-eye"></i></a>
                        @include('clients.partials.buttons', ['client' => $client, 'buttonSize' => 'xs', 'buttonText' => true])
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="100%" class="text-center">No clients were found!</td>
                </tr>
            @endforelse
        </tbody>
    </table>  
</div>