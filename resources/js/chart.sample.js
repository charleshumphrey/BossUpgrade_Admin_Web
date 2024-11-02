"use strict";
import { initializeApp } from "firebase/app";
import { getDatabase, ref, get } from "firebase/database";
import Chart from "chart.js/auto";

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

const app = initializeApp(firebaseConfig);
const database = getDatabase(app);

// Fetch data from Firebase
const menuRef = ref(database, "menu");

get(menuRef)
    .then((snapshot) => {
        const menuData = snapshot.val();
        console.log("Menu Data:", menuData); // Debugging line

        if (!menuData) {
            console.error("No menu data available."); // Error handling
            return;
        }

        const itemsArray = Object.values(menuData);

        // Sort items by soldCount in descending order
        itemsArray.sort((a, b) => b.soldCount - a.soldCount);

        // Get the top 5 items
        const topItems = itemsArray.slice(0, 5);

        // Prepare data for the chart
        const chartData = topItems.map((item) => item.soldCount);
        const chartLabels = topItems.map((item) => item.name);

        console.log("Chart Data:", chartData); // Debugging line
        console.log("Chart Labels:", chartLabels); // Debugging line

        var ctx = document.getElementById("chart").getContext("2d");
        new Chart(ctx, {
            type: "pie",
            data: {
                datasets: [
                    {
                        data: chartData,
                        backgroundColor: [
                            "#00D1B2",
                            "#209CEE",
                            "#FF3860",
                            "#FFDD57",
                            "#00D1F5",
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

function getMetrics() {
    const usersRef = ref(database, "users");
    const menuRef = ref(database, "menu");
    const ordersRef = ref(database, "orders");

    // Get the number of users
    get(usersRef)
        .then((snapshot) => {
            if (snapshot.exists()) {
                const userCount = snapshot.size || snapshot.numChildren();
                document.getElementById("metrics-users").textContent =
                    userCount;
            } else {
                console.log("No users found");
            }
        })
        .catch((error) => console.error("Error fetching users:", error));

    // Get number of menu items
    get(menuRef)
        .then((snapshot) => {
            if (snapshot.exists()) {
                const menuCount = snapshot.size || snapshot.numChildren();
                document.querySelector(
                    ".metrics-cards:nth-child(2) p:nth-child(2)"
                ).textContent = menuCount;
            } else {
                console.log("No menu items found");
            }
        })
        .catch((error) => console.error("Error fetching menu items:", error));

    // Get number of completed orders
    get(ordersRef)
        .then((snapshot) => {
            if (snapshot.exists()) {
                let ratedCount = 0;

                snapshot.forEach((orderSnapshot) => {
                    const status = orderSnapshot.val().status;
                    if (status === "pending") {
                        ratedCount++;
                    }
                });

                document.querySelector(
                    ".metrics-cards:nth-child(3) p:nth-child(2)"
                ).textContent = ratedCount;
            } else {
                console.log("No orders found");
            }
        })
        .catch((error) => console.error("Error fetching orders:", error));

    // Get number of completed orders
    get(ordersRef)
        .then((snapshot) => {
            if (snapshot.exists()) {
                let ratedCount = 0;

                snapshot.forEach((orderSnapshot) => {
                    const status = orderSnapshot.val().status;
                    if (status === "rated") {
                        ratedCount++;
                    }
                });

                document.querySelector(
                    ".metrics-cards:nth-child(4) p:nth-child(2)"
                ).textContent = ratedCount;
            } else {
                console.log("No orders found");
            }
        })
        .catch((error) => console.error("Error fetching orders:", error));
}

window.onload = getMetrics;
