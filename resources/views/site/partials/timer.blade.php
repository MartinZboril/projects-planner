<div class="modal" id="timers-preview-modal">
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
                    <table class="table">
                        <thead>
                            <tr>
                            <th>Project</th>
                            <th>Type</th>
                            <th>Total time</th>
                            <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(Auth::User()->activeTimers as $timer)
                                <tr>
                                    <td><a href="{{ route('projects.show', $timer->project->id) }}">{{ $timer->project->name }}</a></td>
                                    <td>{{ $timer->rate->name }}</td>
                                    <td><span id="timer-{{ $timer->id }}-display" class="timer-record" data-since="{{ $timer->since }}"></span></td>
                                    <td><a href="#" class="btn btn-sm btn-danger" onclick="event.preventDefault(); document.getElementById('stop-working-on-timer-{{ $timer->id }}').submit();"><i class="fas fa-stop"></i></a></td>
                                </tr>
                                @include('timers.forms.stop', ['id' => 'stop-working-on-timer-' . $timer->id, 'timerId' => $timer->id])            
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>