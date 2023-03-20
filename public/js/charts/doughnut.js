$(function () {
    const chartIdentifiers  = $.map($('.doughnut-identifier'), function (el) { return el.value; });
    const chartLabels  = $.map($('.doughnut-label'), function (el) { return el.value; });
    const chartColours = $.map($('.doughnut-colour'), function (el) { return el.value; });
    const chartData  = $.map($('.doughnut-data'), function (el) { return el.value; });

    for(let i = 0; i < chartIdentifiers.length; i++)
    {
        // labels
        labels = chartLabels[i].replace(/'/g, '"');
        labels = JSON.parse(labels);
        // colours
        colours = chartColours[i].replace(/'/g, '"');
        colours = JSON.parse(colours);
        // data
        data = chartData[i].replace(/'/g, '"');
        data = JSON.parse(data);
        // Chart
        new Chart(chartIdentifiers[i], {
            type: "doughnut",
            data: {
                labels: labels,
                datasets: [{
                    backgroundColor: colours,
                    data: data
                }]
            },
            options: {
                title: {
                    display: true,
                },
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }         
});