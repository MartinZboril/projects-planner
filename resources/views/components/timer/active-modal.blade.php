<div class="modal" id="{{ $modalId }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title">Timers</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table" id="active-timers-table">
                        <thead>
                            <tr>
                            <th>Project</th>
                            <th>Type</th>
                            <th>Total time</th>
                            <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($timers as $timer)
                                <tr id="timer-{{ $timer->id }}-modal-row">
                                    <td><a href="{{ $timer->project_route }}">{{ $timer->project->name }}</a></td>
                                    <td>{{ $timer->rate->name }}</td>
                                    <td><span id="timer-{{ $timer->id }}-display" class="timer-record" data-since_at="{{ $timer->since_at }}"></span></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-danger" onclick="stopWorkTimer('{{ route('projects.timers.stop', ['project' => $timer->project, 'timer' => $timer]) }}', 'modal', '{{ $modalId }}')">
                                            <i class="fas fa-stop"></i>
                                        </a>
                                    </td>
                                </tr>                                      
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>