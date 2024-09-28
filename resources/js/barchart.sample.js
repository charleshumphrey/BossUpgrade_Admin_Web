"use strict";

var monthlyIncome = [
    12000, 15000, 13000, 17000, 10600, 18000, 20000, 10900, 22000, 21000, 25000,
    24000,
];

var monthColors = [
    "#FF6384",
    "#36A2EB",
    "#FFCE56",
    "#4BC0C0",
    "#9966FF",
    "#FF9F40",
    "#FF6384",
    "#36A2EB",
    "#FFCE56",
    "#4BC0C0",
    "#9966FF",
    "#FF9F40",
];

var ctx = document.getElementById("bar-chart").getContext("2d");

new Chart(ctx, {
    type: "bar",
    data: {
        labels: [
            "January",
            "February",
            "March",
            "April",
            "May",
            "June",
            "July",
            "August",
            "September",
            "October",
            "November",
            "December",
        ],
        datasets: [
            {
                label: "Monthly Income",
                data: monthlyIncome,
                backgroundColor: monthColors,
                borderColor: "#000",
                borderWidth: 1,
            },
        ],
    },
    options: {
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: "Income (₱)",
                },
                ticks: {
                    stepSize: 500,
                },
            },
            x: {
                title: {
                    display: true,
                    text: "Months",
                },
            },
        },
        plugins: {
            legend: {
                display: true,
                position: "top",
            },
            tooltip: {
                callbacks: {
                    label: function (tooltipItem) {
                        return `Income: ₱${tooltipItem.raw}`;
                    },
                },
            },
        },
    },
});
