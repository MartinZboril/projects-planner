<div class="table-responsive">
    <table id="{{ $milestones->count() === 0 ?: $tableId }}" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Project</th>
                <th>Owner</th>
                <th>Progress</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($milestones as $milestone)
                <tr>                                        
                    <td><a href="{{ route('projects.milestones.show', ['project' => $milestone->project, 'milestone' => $milestone]) }}">{{ $milestone->name }}</a></td>                                        
                    <td>{{ $milestone->project->name }}</td>
                    <td><x-site.ui.user-icon :user="$milestone->owner" /></td>
                    <td><x-milestone.ui.progress :$milestone /></td>
                    <td>{{ $milestone->start_date->format('d.m.Y') }}</td>
                    <td><span class="text-{{ $milestone->overdue ? 'danger' : 'body' }}">{{ $milestone->due_date->format('d.m.Y') }}</span></td>
                    <td>
                        <a href="{{ route('projects.milestones.edit', ['project' => $milestone->project, 'milestone' => $milestone]) }}" class="btn btn-xs btn-dark"><i class="fas fa-pencil-alt"></i></a>
                        <a href="{{ route('projects.milestones.show', ['project' => $milestone->project, 'milestone' => $milestone]) }}" class="btn btn-xs btn-info"><i class="fas fa-eye"></i></a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="100%" class="text-center">No milestones were found!</td>
                </tr>
            @endforelse
        </tbody>
    </table>  
</div>  

@push('scripts')
    <script>
        $(function () {
            $("#{{ $tableId }}").DataTable();
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush