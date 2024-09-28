"use strict";

var randomChartData = function randomChartData(n) {
    var data = [];

    for (var i = 0; i < n; i++) {
        data.push(Math.round(Math.random() * 200));
    }

    return data;
};

var chartColors = {
    default: {
        primary: "#00D1B2",
        info: "#209CEE",
        danger: "#FF3860",
        warning: "#FFDD57",
        success: "#00D1F5",
    },
};

var ctx = document.getElementById("chart").getContext("2d");
new Chart(ctx, {
    type: "pie",
    data: {
        datasets: [
            {
                data: randomChartData(5),
                backgroundColor: [
                    chartColors.default.primary,
                    chartColors.default.info,
                    chartColors.default.danger,
                    chartColors.default.warning,
                    chartColors.default.success,
                ],
            },
        ],
        labels: [
            "Chicharon Bulaklak",
            "Pork Kare Kare",
            "Pork Sisig",
            "Pork Dinuguan",
            "Carbonara",
        ],
    },
    options: {
        maintainAspectRatio: false,
        legend: {
            display: true,
            position: "top",
        },
        responsive: true,
        tooltips: {
            backgroundColor: "#f5f5f5",
            titleFontColor: "#333",
            bodyFontColor: "#666",
            bodySpacing: 4,
            xPadding: 12,
            mode: "nearest",
            intersect: 0,
            position: "nearest",
        },
    },
});
