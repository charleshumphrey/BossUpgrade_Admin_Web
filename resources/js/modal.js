document.addEventListener("DOMContentLoaded", function () {
    const archiveButtons = document.querySelectorAll(".button.red");
    const modal = document.getElementById("archive-modal");
    const confirmButton = document.getElementById("confirm-archive");
    const closeButton = document.querySelector(".--jb-modal-close");
    let currentItemId;

    archiveButtons.forEach((button) => {
        button.addEventListener("click", function () {
            currentItemId = button
                .getAttribute("data-target")
                .replace("archive-modal", "");
            modal = document.getElementById(`archive-modal${currentItemId}`);
            modal.classList.remove("hidden");
        });
    });

    closeButton.onclick = function () {
        modal.classList.add("hidden");
    };

    document.getElementById("cancel-archive").onclick = function () {
        modal.classList.add("hidden");
    };

    confirmButton.onclick = function () {
        archiveItem(currentItemId);
        modal.classList.add("hidden");
        alert(currentItemId);
    };

    function archiveItem(itemId) {
        fetch(`/archive-menu-item/${itemId}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
                "Content-Type": "application/json",
            },
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    location.reload();
                } else {
                    alert("Failed to archive the item.");
                }
            })
            .catch((error) => console.error("Error:", error));
    }
});
