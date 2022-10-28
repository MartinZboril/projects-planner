function displayTimers () { 
    $('.timer-record').each(function(i, obj) {
        var since = new Date(Date.parse($(obj).data('since')));
        var now = new Date($.now());

        var diffMiliseconds = Math.abs(now - since);

        var miliseconds = diffMiliseconds % 1000;
        s = (diffMiliseconds - miliseconds) / 1000;
        var seconds = s % 60;
        s = (s - seconds) / 60;
        var minutes = s % 60;
        var hours = (s - minutes) / 60;

        $(obj).html(displayTimer(hours, minutes, seconds));
    });
}

function displayTimer(hours, minutes, seconds) {
    var hoursDisplay = (hours < 10) ? '0' + hours : hours;
    var minutesDisplay = (minutes < 10) ? '0' + minutes : minutes;
    var secondsDisplay = (seconds < 10) ? '0' + seconds : seconds;

    return hoursDisplay + ':' + minutesDisplay + ':' + secondsDisplay;
}

setInterval(displayTimers, 1000);