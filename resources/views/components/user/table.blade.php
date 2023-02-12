<div class="table-responsive">
    <table id="{{ $users->count() == 0 ?: $tableId }}" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Job title</th>
                <th>Mobile</th>
                <th>City</th>
                <th>Date</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr>
                    <td><x-site.ui.user-icon :user="$user" /><a href="{{ route('users.show', $user) }}">{{ $user->full_name }}</a></td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->job_title }}</td>
                    <td>{{ $user->mobile }}</td>
                    <td>{{ $user->city }}</td>
                    <td>{{ $user->created_at->format('d.m.Y') }}</td>
                    <td>
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-dark"><i class="fas fa-pencil-alt"></i></a>
                        <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="100%" class="text-center">No users were found!</td>
                </tr>
            @endforelse
        </tbody>
    </table>  
</div>

@push('scripts')
    <script>
        $(function () {
            $('#{{ $tableId }}').DataTable();
        });
    </script>
@endpush