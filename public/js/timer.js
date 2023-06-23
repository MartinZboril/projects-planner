// Start work on project
function startWorkTimer(url, projectId, rateId, type) {
    const token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: url,
        type: 'POST',
        data: {
            "_token": token,
            'project_id': projectId,
            'rate_id': rateId,
        },
        error: function() {
            toastr.error('An error has occurred!');
        },
        success: function (data) {
            const project = data.project;
            const timer = data.timer;
            const activeTimers = data.active_timers;
            // Change project view
            if (type === 'table') {
                $('#projects-table').DataTable().ajax.reload(); 
            } else {
                $('#project-' + project.id + '-stop-work').attr('onclick', 'stopWorkTimer(\'' + timer.stop_route +'\', \'project\', \'timers-preview-modal\')');
                $('#project-' + project.id + '-stop-work').show();
                $('#project-' + project.id + '-start-work-div').hide();
            }
            // Update timers datatable
            if ($('.dataTable').length) {
                if ($('#timers-table').DataTable()) {
                    $('#timers-table').DataTable().ajax.reload();
                }
            }            
            // Update active timers count
            if (activeTimers.length > 0) {
                $('#timer-nav').show();
            } else {
                $('#timer-nav').hide();
            }     
            $('#active-timers-table tr:last').after('<tr id="timer-' + timer.id + '-modal-row"><td><a href="' + timer.project_route + '">' + project.name + '</a></td><td>' + timer.rate.name + '</td><td><span id="timer-' + timer.id + '-display" class="timer-record" data-since_at="' + timer.since_at + '"></span></td><td><a href="#" class="btn btn-sm btn-danger" onclick="stopWorkTimer(\'' + timer.stop_route +'\', \'modal\', \'timers-preview-modal\')"><i class="fas fa-stop"></i></a></td></tr>');
            $('#timer-counter').html('<i class="fas fa-clock mr-1"></i>' + activeTimers.length);            
            // Send message about start timer
            toastr.info(data.message);
        }
    });
}
// Stop work on project
function stopWorkTimer(url, type, modalId) {
    const token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: url,
        type: 'PATCH',
        data: {
            "_token": token,
        },
        error: function() {
            toastr.error('An error has occurred!');
        },
        success: function (data) {
            const timer = data.timer;
            const project = data.project;
            const activeTimersCount = data.active_timers_count;  
            // Change content of modal     
            $('#timer-' + timer.id + '-modal-row').html('');
            if (type === 'modal') {
                $('#' + modalId).removeClass("in");
                $(".modal-backdrop").remove();
                $('#' + modalId).hide(); 
            }
            // Project buttons
            if ($('#project-' + project.id + '-stop-work')) {
                $('#project-' + project.id + '-stop-work').hide();
            }
            if ($('#project-' + project.id + '-start-work-div')) {
                $('#project-' + project.id + '-start-work-div').show();
            }
            // Update datatables
            if ($('.dataTable').length) {
                if ($('#projects-table').DataTable()) {
                    $('#projects-table').DataTable().ajax.reload();
                }
                if ($('#timers-table').DataTable()) {
                    $('#timers-table').DataTable().ajax.reload();
                }
            }
            // Update active timers count
            if (activeTimersCount > 0) {
                $('#timer-nav').show();
            } else {
                $('#timer-nav').hide();
            }     
            $('#timer-counter').html('<i class="fas fa-clock mr-1"></i>' + activeTimersCount);
            // Send message about stop timer
            toastr.info(data.message);
        }
    });
}
// Delete timer
function deleteTimer(url, type, tableIdentifier = '#timers-table', redirect) {
    if (!confirm('Do you really want to remove timer?')) return false;

    const token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: url,
        type: 'DELETE',
        data: {
            "_token": token,
        },
        error: function() {
            toastr.error('An error has occurred!');
        },
        success: function (data) {
            console.log(redirect);
            if (type === 'table') {
                $(tableIdentifier).DataTable().ajax.reload(); 
            } else {
                window.location.href = redirect ? redirect : window.location.href;
            }
            toastr.info(data.message);
        }
    });
}
// Display timers
function displayTimers () { 
    $('.timer-record').each(function(i, obj) {
        const since_at = new Date(Date.parse($(obj).data('since_at')));
        const now = new Date($.now());
        // Diff between now and since_at
        const diffMiliseconds = Math.abs(now - since_at);
        // Calculate the information that will be displayed to the user
        const miliseconds = diffMiliseconds % 1000;
        let s = (diffMiliseconds - miliseconds) / 1000;
        const seconds = s % 60;
        s = (s - seconds) / 60;
        const minutes = s % 60;
        const hours = (s - minutes) / 60;
        // Display current timer
        $(obj).html(displayTimer(hours, minutes, seconds));
    });
}

function displayTimer(hours, minutes, seconds) {
    const hoursDisplay = (hours < 10) ? '0' + hours : hours;
    const minutesDisplay = (minutes < 10) ? '0' + minutes : minutes;
    const secondsDisplay = (seconds < 10) ? '0' + seconds : seconds;
    return hoursDisplay + ':' + minutesDisplay + ':' + secondsDisplay;
}

setInterval(displayTimers, 1000);