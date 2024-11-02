let selectedUserId = null;

function loadMessages(userId) {
    selectedUserId = userId;
    const basePath = "/dashboard/BossUpgrade/public";
    const messagesUrl = `${basePath}/chats/${userId}/messages`;

    fetch(messagesUrl)
        .then((response) => response.json())
        .then((messages) => {
            const chatContainer = document.getElementById(
                "chatMessagesContainer"
            );
            chatContainer.innerHTML = "";

            if (!messages || Object.keys(messages).length === 0) {
                chatContainer.innerHTML =
                    "<p>No messages found for this user.</p>";
                return;
            }

            Object.entries(messages).forEach(([messageId, message]) => {
                const messageElement = document.createElement("div");
                messageElement.classList.add("chat-message");

                messageElement.classList.add(
                    message.sender === "admin"
                        ? "admin-message"
                        : "user-message"
                );

                const formattedDate = new Date(
                    message.timestamp
                ).toLocaleDateString();
                const formattedTime = new Date(
                    message.timestamp
                ).toLocaleTimeString([], {
                    hour: "2-digit",
                    minute: "2-digit",
                });

                messageElement.innerHTML = `
            <p>${message.messageText}</p>
            <small>${formattedDate} ${formattedTime}</small>
        `;

                chatContainer.appendChild(messageElement);
            });

            document.getElementById("messageFooter").classList.remove("hidden");
        })
        .catch((error) => {
            console.error("Error fetching messages:", error);
            document.getElementById("chatMessagesContainer").innerHTML =
                "<p>Failed to load messages.</p>";
        });
}

function sendMessage(event) {
    event.preventDefault();
    const messageInput = document.getElementById("messageInput");
    const messageText = messageInput.value.trim();

    if (!messageText) {
        alert("Please enter a message.");
        return;
    }

    const messageData = {
        sender: "admin",
        messageText: messageText,
        timestamp: Date.now(),
    };

    const basePath = "/dashboard/BossUpgrade/public";
    fetch(`${basePath}/chats/${selectedUserId}/messages`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                .content,
        },
        body: JSON.stringify(messageData),
    })
        .then((response) => response.json())
        .then((data) => {
            messageInput.value = "";
            loadMessages(selectedUserId);
        })
        .catch((error) => {
            console.error("Error sending message:", error);
        });
}
