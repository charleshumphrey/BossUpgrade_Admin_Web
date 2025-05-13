<!DOCTYPE html>
<html lang="en" class="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>

    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>

<body>
    @include('shared.upper_nav')

    <div class="aside is-placed-left is-expanded bg-black-erie">
        <div class="aside-tools bg-black">
            <div>
                <a href="{{ route('dashboard') }}" class="flex justify-center items-center p-2 h-16">
                    <img class="w-12" src={{ asset('build/assets/images/BossUpgrade_logo.jpg') }} alt="bossupgrade_logo">
                    <p class="appname font-bungee text-white font-poppins_bold font-bol">{{ config('app.name') }}</p>
                </a>
            </div>
        </div>
        @include('shared.nav_sidebar')
    </div>

    <section class="is-title-bar">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-6 md:space-y-0">
            <ul>
                <li>Messages</li>
            </ul>

        </div>
    </section>

    <section class="section main-section h-90vh">

        @include('shared.success')

        <div class="flex flex-grow gap-5">
            <div class="card w-1/3 shadow-lg border border-gray-200 overflow-hidden" style="height:77vh;">
                <header class="card-header text-gray-700">
                    <p class="card-header-title">
                        <span class="icon mr-2"><i class="fa-solid fa-users"></i></span>
                        Users
                    </p>
                </header>
                <div class="card-content flex flex-col p-4 overflow-y-scroll space-y-2 h-full">
                    @foreach ($users as $userId => $user)
                    <button class="flex items-center gap-3 p-3 rounded-lg bg-blue-50 hover:bg-blue-100 transition-colors duration-200 cursor-pointer"
                        onclick="loadMessages('{{ $userId }}')">
                        <img src="{{ $user['profileImage'] }}" alt="Profile Image" class="profile-image rounded-full w-10 h-10 border border-gray-300" />
                        <div class="flex flex-col text-left">
                            <strong class="truncate text-gray-800">{{ $user['fullname'] }}</strong>
                            <span class="text-sm text-gray-500">{{ $user['username'] }}</span>
                        </div>
                    </button>
                    @endforeach
                </div>
            </div>

            <div class="card w-full shadow-lg border border-gray-200">
                <header class="card-header">
                    <p class="card-header-title text-gray-700">
                        <span class="icon mr-2"><i class="fa-solid fa-envelope"></i></span>
                        Chat Messages
                    </p>
                </header>
                <div class="card-content overflow-hidden p-4 h-full" id="chatMessages">
                    <div class="h-full overflow-y-auto bg-white p-4 rounded-lg shadow-inner border border-gray-200" id="chatMessagesContainer">
                        <p class="text-gray-500">Select a user to view messages.</p>
                    </div>
                </div>

                <footer class="p-4 border-t border-gray-200 bg-gray-100 hidden" id="messageFooter">
                    <form id="messageForm" class="flex items-center gap-3" onsubmit="sendMessage(event)">
                        <input type="text" id="messageInput" placeholder="Type your message..." class="flex-grow p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-blue-200" />
                        <button type="submit" class="button bg-blue-500 text-white p-3 rounded-lg hover:bg-blue-600 transition-colors duration-200">
                            Send
                        </button>
                    </form>
                </footer>
            </div>
        </div>
    </section>

    </div>
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css">

    <script>
        let selectedUserId = null;

        function loadMessages(userId) {
            selectedUserId = userId;
            const basePath = '/dashboard/BossUpgrade/public';
            const messagesUrl = `${basePath}/chats/${userId}/messages`;

            fetch(messagesUrl)
                .then(response => response.json())
                .then(messages => {
                    const chatContainer = document.getElementById('chatMessagesContainer');
                    chatContainer.innerHTML = '';


                    if (!messages || Object.keys(messages).length === 0) {
                        chatContainer.innerHTML = '<p>No messages found for this user.</p>';
                        return;
                    }

                    Object.entries(messages).forEach(([messageId, message]) => {
                        const messageElement = document.createElement('div');
                        messageElement.classList.add('chat-message');

                        messageElement.classList.add(message.sender === 'admin' ? 'admin-message' : 'user-message');

                        const formattedDate = new Date(message.timestamp).toLocaleDateString();
                        const formattedTime = new Date(message.timestamp).toLocaleTimeString([], {
                            hour: '2-digit',
                            minute: '2-digit'
                        });

                        messageElement.innerHTML = `
                    <p>${message.messageText}</p>
                    <small>${formattedDate} ${formattedTime}</small>
                `;

                        chatContainer.appendChild(messageElement);
                    });

                    document.getElementById('messageFooter').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error fetching messages:', error);
                    document.getElementById('chatMessagesContainer').innerHTML = '<p>Failed to load messages.</p>';
                });
        }


        function sendMessage(event) {
            event.preventDefault();
            const messageInput = document.getElementById('messageInput');
            const messageText = messageInput.value.trim();

            if (!messageText) {
                alert("Please enter a message.");
                return;
            }

            const messageData = {
                sender: 'admin',
                messageText: messageText,
                timestamp: Date.now()
            };

            const basePath = '/dashboard/BossUpgrade/public';
            fetch(`${basePath}/chats/${selectedUserId}/messages`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(messageData)
                })
                .then(response => response.json())
                .then(data => {

                    messageInput.value = '';
                    loadMessages(selectedUserId);
                })
                .catch(error => {
                    console.error('Error sending message:', error);
                });
        }
    </script>

</body>

</html>