<div class="table-responsive">
    <table id="@if($tasks->count() > 0){{ 'tasks-table' }}@endif" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Name</th>
                @if(in_array('project', $display))<th>Project</th>@endif
                <th>User</th>
                <th>Due Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tasks as $task)
                <tr>
                    <td><a href="{{ route('tasks.detail', $task->id) }}">{{ $task->name }}</a></td>
                    @if(in_array('project', $display))<td>{{ $task->project->name }}</td>@endif
                    <td>@include('site.partials.user', ['user' => $task->user])</td>
                    <td>{{ $task->due_date->format('d.m.Y') }}</td>
                    <td>@include('tasks.partials.status', ['task' => $task])</td>
                </tr>
            @empty
                <tr>
                    <td colspan="100%" class="text-center">No tasks were found!</td>
                </tr>
            @endforelse
        </tbody>
    </table>  
</div>