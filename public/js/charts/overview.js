$(function () {
    const chartIdentifiers  = $.map($('.overview-identifier'), function (el) { return el.value; });
    const chartMonths  = $.map($('.overview-month'), function (el) { return el.value; });
    const chartTotalCount = $.map($('.overview-total-count'), function (el) { return el.value; });
    // generate overview charts
    for(let i = 0; i < chartIdentifiers.length; i++) {
        // months
        let months = chartMonths[i].replace(/'/g, '"');
        months = JSON.parse(months);
        // total counts
        let totalCount = chartTotalCount[i].replace(/'/g, '"');
        totalCount = JSON.parse(totalCount);
        // chart
        new Chart(chartIdentifiers[i], {
            type: "line",
            data: {
                labels: months,
                datasets: [{ 
                    data: totalCount,
                    borderColor: '#007bff',
                    fill: false,
                    label: 'Total'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        min: 0,
                        ticks: {
                            stepSize: 5
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                    }
                }
            },
        });  
    }
});