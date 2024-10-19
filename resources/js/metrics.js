import { initializeApp } from "firebase/app";
import { getDatabase, ref, get, child } from "firebase/database";

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
