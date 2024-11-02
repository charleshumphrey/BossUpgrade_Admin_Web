// Firebase configuration
const firebaseConfig = {
    apiKey: "AIzaSyDtsmGee-3Uj6ZkCMiBlpqBAm67-K4jrp8",
    authDomain: "bossupgrade-101.firebaseapp.com",
    databaseURL: "https://bossupgrade-101-default-rtdb.firebaseio.com",
    projectId: "bossupgrade-101",
    storageBucket: "bossupgrade-101.appspot.com",
    messagingSenderId: "89210308859",
    appId: "1:89210308859:web:17e130e80253262eddc85e",
    measurementId: "G-28ED8WK9K7",
};

// Initialize Firebase
firebase.initializeApp(firebaseConfig);

// Get the reference to the 'menu' node in the Firebase Realtime Database
const menuRef = firebase.database().ref("menu");

// Fetch data from Firebase
menuRef
    .once("value")
    .then((snapshot) => {
        const menuData = snapshot.val();
        const itemsArray = Object.values(menuData);

        // Sort items by soldCount in descending order
        itemsArray.sort((a, b) => b.soldCount - a.soldCount);

        // Get the top 5 items
        const topItems = itemsArray.slice(0, 5);

        // Prepare data for the chart
        const chartData = topItems.map((item) => item.soldCount);
        const chartLabels = topItems.map((item) => item.name);

        // Chart configuration
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
                        data: chartData,
                        backgroundColor: [
                            chartColors.default.primary,
                            chartColors.default.info,
                            chartColors.default.danger,
                            chartColors.default.warning,
                            chartColors.default.success,
                        ],
                    },
                ],
                labels: chartLabels,
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
    })
    .catch((error) => {
        console.error("Error fetching data: ", error);
    });
