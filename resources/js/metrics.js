import { initializeApp } from "firebase/app";
import { getDatabase, ref, get, child } from "firebase/database";

// Your Firebase configuration
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
const app = initializeApp(firebaseConfig);

// Get a reference to the database
const database = getDatabase(app);

function getMetrics() {
    // References to the relevant nodes
    const usersRef = ref(database, "users");
    const menuRef = ref(database, "menu");
    const ordersRef = ref(database, "orders");

    // Fetch the number of users
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

    // Fetch the number of menu items
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

    // Fetch the number of orders
    get(ordersRef)
        .then((snapshot) => {
            if (snapshot.exists()) {
                const orderCount = snapshot.size || snapshot.numChildren();
                document.querySelector(
                    ".metrics-cards:nth-child(3) p:nth-child(2)"
                ).textContent = orderCount;
            } else {
                console.log("No orders found");
            }
        })
        .catch((error) => console.error("Error fetching orders:", error));
}

window.onload = getMetrics;
