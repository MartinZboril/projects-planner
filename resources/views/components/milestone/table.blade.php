<div class="table-responsive">
    <table id="{{ $milestones->count() === 0 ?: $tableId }}" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Name</th>
                @if ($type === 'milestones')
                    <th>Project</th>                        
                @endif                  
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
                    <td><a href="{{ $milestone->show_route }}">{{ $milestone->name }}</a></td>
                    @if ($type === 'milestones')
                        <td><a href="{{ $milestone->project_show_route }}">{{ $milestone->project->name }}</a></td>                        
                    @endif                                        
                    <td><x-site.ui.user-icon :user="$milestone->owner" /></td>
                    <td><x-milestone.ui.progress :$milestone /></td>
                    <td>{{ $milestone->start_date->format('d.m.Y') }}</td>
                    <td><span class="text-{{ $milestone->overdue ? 'danger' : 'body' }}">{{ $milestone->due_date->format('d.m.Y') }}</span></td>
                    <td>
                        <a href="{{ $milestone->edit_route }}" class="btn btn-xs btn-dark"><i class="fas fa-pencil-alt"></i></a>
                        <a href="{{ $milestone->show_route }}" class="btn btn-xs btn-info"><i class="fas fa-eye"></i></a>
                        @include('projects.milestones.partials.buttons', ['buttonSize' => 'xs', 'hideButtonText' => ''])
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
        });
    </script>
@endpush