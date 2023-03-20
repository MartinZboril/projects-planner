$(function () {
    const chartIdentifiers  = $.map($('.progress-bar-identifier'), function (el) { return el.value; });
    const chartColours  = $.map($('.progress-bar-colour'), function (el) { return el.value; });
    const chartTexts = $.map($('.progress-bar-text'), function (el) { return el.value; });
    const chartValues  = $.map($('.progress-bar-value'), function (el) { return el.value; });

    for(let i = 0; i < chartIdentifiers.length; i++)
    {
        const budgetProgressBar = new ProgressBar.Circle('#' + chartIdentifiers[i], {
            strokeWidth: 15,
            color: chartColours[i],
            trailColor: '#eee',
            trailWidth: 15,
            text: {
                value: chartTexts[i],
                style: {
                    color: chartColours[i],
                    position: 'absolute',
                    left: '50%',
                    top: '50%',
                    padding: 0,
                    margin: 0,
                    fontSize: '1.5rem',
                    fontWeight: 'bold',
                    transform: {
                        prefix: true,
                        value: 'translate(-50%, -50%)',
                    },
                },
            }
        });
    
        budgetProgressBar.animate(chartValues[i]);
    }         
});