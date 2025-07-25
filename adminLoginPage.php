<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Custom styles for the logout confirmation modal */
        .modal {
            transition: opacity 0.3s ease;
        }
        .modal-hidden {
            opacity: 0;
            pointer-events: none;
        }
        
        /* Prevent text selection for better UX */
        .no-select {
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans no-select">
    <!-- Login Screen (initially shown) -->
    <div id="loginScreen" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-90 z-50 transition-opacity duration-300">
        <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-md">
            <div class="text-center mb-8">
                <i class="fas fa-lock text-5xl text-blue-600 mb-4"></i>
                <h1 class="text-3xl font-bold text-gray-800">Login Portal</h1>
                <p class="text-gray-600 mt-2">Enter your credentials to continue</p>
            </div>
            
            <form id="login-form" class="space-y-4" method="post" action="adminLogin.php">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email/Username</label>
                    <input type="email" id="email" name="email" required 
                        placeholder="Enter your email or username"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" id="password" name="password" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" id="remember" name="remember" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-700">Remember me</label>
                    </div>
                </div>
                
                <button type="submit" id="loginButton" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150">
                    Sign In <i class="fas fa-arrow-right ml-2"></i>
                </button>

                <div class="text-center">
                    <a href="forgot.html" id="forgotPassword" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Forgot Password?</a>
                </div>

            </form>
            
            <div class="mt-6 text-center text-sm text-gray-500">
                <p>For security reasons, please log out when finished.</p>
            </div>
        </div>
    </div>

    <!-- Dashboard (initially hidden) -->
    <div id="adminDashboard" class="hidden">
        <!-- Header Section -->
        <header class="bg-blue-700 text-white p-4 flex items-center justify-between shadow-md">
            <div class="flex items-center">
                <img src="lg.png" alt="School Logo" class="h-10 w-10 mr-2">
            </div>
            <h1 class="text-xl font-bold">UNIVERSITY OF ENERGY AND NATURAL RESOURCES</h1>
            <button id="logoutBtn" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-sign-out-alt mr-2"></i> Logout
            </button>
        </header>

        <!-- Main Content Section -->
        <main class="flex min-h-screen">
            <!-- Sidebar Navigation -->
            <div class="w-64 bg-gray-800 text-white p-4">
                <nav>
                    <ul class="space-y-2">
                        <li>
                            <button class="tab-btn active w-full text-left p-3 rounded-lg bg-blue-600 hover:bg-blue-700 transition" data-tab="personal">
                                <i class="fas fa-user mr-2"></i> Personal Details
                            </button>
                        </li>
                        <li>
                            <button class="tab-btn w-full text-left p-3 rounded-lg hover:bg-gray-700 transition" data-tab="generate">
                                <i class="fas fa-id-card mr-2"></i> Generate IDs
                            </button>
                        </li>
                        <li>
                            <button class="tab-btn w-full text-left p-3 rounded-lg hover:bg-gray-700 transition" data-tab="members">
                                <i class="fas fa-users mr-2"></i> Members
                            </button>
                        </li>
                        <li>
                            <button class="tab-btn w-full text-left p-3 rounded-lg hover:bg-gray-700 transition" data-tab="feedback">
                                <i class="fas fa-comments mr-2"></i> Feedback
                            </button>
                        </li>
                        <li>
                            <button class="tab-btn w-full text-left p-3 rounded-lg hover:bg-gray-700 transition" data-tab="backup">
                                <i class="fas fa-database mr-2"></i> Backup Data
                            </button>
                        </li>
                    </ul>
                </nav>
            </div>

            <!-- Tab Content Area -->
            <div class="flex-1 p-8">
                <!-- Personal Details Tab -->
                <div id="personal" class="tab-content active">
                    <h2 class="text-2xl font-bold mb-6 text-gray-800">Personal Details</h2>
                    <div class="bg-white p-6 rounded-lg shadow-md max-w-lg">
                        <div class="space-y-4">
                            <div>
                                <label class="font-semibold text-gray-700">User Type:</label>
                                <p class="mt-1" id="userType"> </p>
                            </div>
                            <div>
                                <label class="font-semibold text-gray-700">Name:</label>
                                <p class="mt-1" id="userName"> </p>
                            </div>
                            <div>
                                <label class="font-semibold text-gray-700">Email:</label>
                                <p class="mt-1" id="userEmail"> </p>
                            </div>
                            <div>
                                <label class="font-semibold text-gray-700">Date of Birth:</label>
                                <p class="mt-1" id="userDob"> </p>
                            </div>
                            <div>
                                <label class="font-semibold text-gray-700">ID Number:</label>
                                <p class="mt-1" id="userId"> </p>
                            </div>
                        </div>
                        <button id="editDetailsBtn" class="mt-6 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                            <i class="fas fa-edit mr-2"></i> Edit Details
                        </button>
                    </div>
                </div>

                <!-- Generate IDs Tab -->
                <div id="generate" class="tab-content">
                    <h2 class="text-2xl font-bold mb-6 text-gray-800">Generate IDs</h2>
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <!--SYSTEM GENERATED IDs-->
                        <h3 class="text-xl font-semibold mb-4 text-gray-700">System Generated IDs</h3>
                        <div class="mb-6">
                            <div class="flex items-center space-x-4">
                                <select id="idType" class="border rounded-lg p-2 flex-1">
                                    <option value="student">Student ID</option>
                                    <option value="lecturer">Lecturer ID</option>
                                    <option value="admin">Administrator ID</option>
                                </select>
                                <button id="generateIdBtn" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                                    <i class="fas fa-plus-circle mr-2"></i> Generate
                                </button>
                            </div>
                        </div>
                        <!--INDEX NUMBERS INSERTIONS-->
                        <h3 class="text-xl font-semibold mb-4 text-gray-700">Index Numbers</h3>
                        <div class="mb-6">
                            <div class="flex items-center space-x-4">
                                <select id="indexType" class="border rounded-lg p-2 flex-1">
                                    <option value="student">Student Index Numbers</option>
                                    <option value="lecturer">Lecturer Staff Ids</option>
                                    <option value="admin">Administrator Staff Ids</option>
                                </select>
                                <textarea type="text" id="indexNumber" placeholder="Enter Id Number" rows="5" class="border rounded-lg p-2 flex-1 resize-none"></textarea>
                                <button id="addIndexBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                                    <i class="fas fa-plus-circle mr-2"></i> Add
                                </button>
                            </div>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border rounded-lg">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="py-2 px-4 border-b">Student IDs</th>
                                        <th class="py-2 px-4 border-b">Lecturer IDs</th>
                                        <th class="py-2 px-4 border-b">Administrator IDs</th>
                                        <!-- <th class="py-2 px-4 border-b">Actions</th>-->
                                    </tr>
                                </thead>
                                <tbody id="idTableBody">
                                    <!-- Sample data - will be populated from database -->
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Members Tab -->
                <div id="members" class="tab-content">
                    <h2 class="text-2xl font-bold mb-6 text-gray-800">Members</h2>
                    
                    <!-- Administrators Table -->
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold mb-4 text-gray-700">Administrators</h3>
                        <div class="bg-white p-4 rounded-lg shadow-md overflow-x-auto">
                            <table class="min-w-full border rounded-lg">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="py-2 px-4 border-b">Name</th>
                                        <th class="py-2 px-4 border-b">Email</th>
                                        <th class="py-2 px-4 border-b">ID</th>
                                        <th class="py-2 px-4 border-b">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="adminTableBody">
                                    <!-- Sample data - will be populated from database -->

                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Lecturers Table -->
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold mb-4 text-gray-700">Lecturers</h3>
                        <div class="bg-white p-4 rounded-lg shadow-md overflow-x-auto">
                            <table class="min-w-full border rounded-lg">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="py-2 px-4 border-b">Name</th>
                                        <th class="py-2 px-4 border-b">Email</th>
                                        <th class="py-2 px-4 border-b">School</th>
                                        <th class="py-2 px-4 border-b">ID</th>
                                        <th class="py-2 px-4 border-b">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="lecturerTableBody">
                                    <!-- Sample data - will be populated from database -->
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Students Table -->
                    <div>
                        <h3 class="text-xl font-semibold mb-4 text-gray-700">Students</h3>
                        <div class="bg-white p-4 rounded-lg shadow-md overflow-x-auto">
                            <table class="min-w-full border rounded-lg">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="py-2 px-4 border-b">Name</th>
                                        <th class="py-2 px-4 border-b">Level</th>
                                        <th class="py-2 px-4 border-b">Program</th>
                                        <th class="py-2 px-4 border-b">ID</th>
                                        <th class="py-2 px-4 border-b">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="studentTableBody">
                                    <!-- Sample data - will be populated from database -->
        
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Feedback Tab -->
                <div id="feedback" class="tab-content">
                    <h2 class="text-2xl font-bold mb-6 text-gray-800">Feedback</h2>
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div class="flex">
                            <!-- User List -->
                            <div class="w-1/3 pr-4 border-r">
                                <h3 class="text-lg font-semibold mb-4">Users</h3>
                                <div class="space-y-2 max-h-96 overflow-y-auto" id="userList">
                                    <!-- Populated via JS -->
                                </div>
                            </div>

                            <!-- Conversation -->
                            <div class="flex-1 pl-4">
                                <h3 class="text-lg font-semibold mb-4">Conversation</h3>
                                <div id="conversation" class="chat-message mb-4 p-4 bg-gray-50 rounded-lg h-64 overflow-y-auto">
                                    <p class="text-gray-500 italic">Select a user to view conversation</p>
                                </div>

                                <!-- Message Input -->
                                <div class="flex items-center">
                                    <textarea id="messageInput" placeholder="Type your message..." rows="3" class="flex-1 border rounded-l-lg p-2"></textarea>
                                    <button type="submit" id="sendMessageBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-r-lg">
                                        <i class="fas fa-paper-plane"></i> Send
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Backup Data Tab -->
                <div id="backup" class="tab-content">
                    <h2 class="text-2xl font-bold mb-6 text-gray-800">Backup Data</h2>
                    <div class="bg-white p-6 rounded-lg shadow-md max-w-lg">
                        <div class="space-y-6">
                            <div>
                                <label class="block font-semibold text-gray-700 mb-2">Select Data to Backup:</label>
                                <select id="backupType" class="w-full border rounded-lg p-2">
                                    <option value="lecturers">Lecturer's Data</option>
                                    <option value="students">Student's Data</option>
                                    <option value="admins">Administrators Data</option>
                                    <option value="questions">Questions</option>
                                    <option value="chats">Chats</option>
                                </select>
                            </div>
                            <button id="backupBtn" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg flex items-center justify-center">
                                <i class="fas fa-cloud-upload-alt mr-2"></i> Upload to Google Drive
                            </button>
                            <div id="backupStatus" class="hidden p-3 bg-blue-100 text-blue-800 rounded-lg">
                                Backup in progress...
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <!-- Footer Section -->
        <footer class="bg-gray-800 text-white p-4 text-center">
            <p>Developed by <span class="font-semibold">Aadil | Hajara | Muhammadu</span></p>
        </footer>    
        <!-- Edit Details Modal -->
        <div id="editModal" class="modal">
            <div class="modal-content">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold">Edit Personal Details</h3>
                    <button id="closeModal" class="text-red-700 hover:text-gray-700">&times;</button>
                </div>
                <form id="editForm" class="space-y-4">
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Name:</label>
                        <input type="text" id="editName" class="w-full border rounded-lg p-2" required>
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Email:</label>
                        <input type="email" id="editEmail" class="w-full border rounded-lg p-2" required>
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Date of Birth:</label>
                        <input type="date" id="editDob" class="w-full border rounded-lg p-2" required>
                    </div>
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" id="cancelEdit" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg">
                            Cancel
                        </button>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                            <i class="fas fa-save mr-2"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Logout Confirmation Modal -->
    <div id="logoutModal" class="modal fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50 modal-hidden">
        <div class="bg-white rounded-lg shadow-xl max-w-sm w-full p-6">
            <div class="text-center">
                <i class="fas fa-exclamation-circle text-5xl text-yellow-500 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Confirm Logout</h3>
                <p class="text-sm text-gray-500 mb-6">Are you sure you want to sign out? You'll need to enter your credentials again to access the dashboard.</p>
                
                <div class="flex justify-center space-x-4">
                    <button id="cancelLogout" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Cancel
                    </button>
                    <button id="confirmLogout" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        Sign Out
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="admin.js"></script>
    <script src="all.js"></script>
    <script>
        // Session management to prevent back button access
        let isLoggedIn = false;
        const loginScreen = document.getElementById('loginScreen');
        const adminDashboard = document.getElementById('adminDashboard');
        const loginForm = document.getElementById('login-form');
        const logoutModal = document.getElementById('logoutModal');
        const cancelLogout = document.getElementById('cancelLogout');
        const confirmLogout = document.getElementById('confirmLogout');

        // Prevent back navigation after logout
        window.history.pushState(null, null, window.location.href);
        window.onpopstate = function(event) {
            if (isLoggedIn) {
                // If logged in and back button pressed, force logout
                logoutModal.classList.remove('modal-hidden');
            } else {
                // If not logged in, prevent going back to dashboard
                window.history.pushState(null, null, window.location.href);
            }
        };

        // Login form submission
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();

            // Send credentials to login.php via POST
            fetch('adminLogin.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    isLoggedIn = true;
                    loginScreen.classList.add('hidden');

                    // Show dashboard based on role
                    if (data.role === 'admin') {
                        adminDashboard.classList.remove('hidden');
                    }

                    // Clear form
                    loginForm.reset();

                    // Add history entry
                    window.history.pushState(null, null, window.location.href);
                } else {
                    alert(data.message); // Shows "Invalid email or password" or other error messages
                }
            })
            .catch(error => {
                console.error('Error during login:', error);
                alert('An error occurred. Please try again later.');
            });
        });

        // Logout button click
        logoutBtn.addEventListener('click', function() {
            logoutModal.classList.remove('modal-hidden');
        });        

        // Cancel logout
        cancelLogout.addEventListener('click', function() {
            logoutModal.classList.add('modal-hidden');
        });

        // Confirm logout
        confirmLogout.addEventListener('click', performLogout);

        // Perform logout
        function performLogout() {
            isLoggedIn = false;
            adminDashboard.classList.add('hidden');            
            loginScreen.classList.remove('hidden');
            logoutModal.classList.add('modal-hidden');
            
            // Clear any existing history states
            window.history.pushState(null, null, window.location.href);
            
            // Show a message about successful logout
            //alert('You have been successfully logged out. Please sign in again to access the dashboard.');
        }

        $(document).ready(function () {
            let selectedUser = null;
            let pollingConversation = null;
            let pollingUserList = null;

            // Load user list
            function loadUsers(scrollToSelected = false) {
                $.ajax({
                    url: 'fetch_users.php',
                    method: 'GET',
                    success: function (data) {
                        if (data.error) {
                            alert("Error: " + data.error);
                            return;
                        }

                        const userList = $('#userList').empty();
                        data.forEach(user => {

                            // Check if this user is the selected one by comparing IDs
                            const isSelected = selectedUser && (user.id === selectedUser.id);
                            // Show dot only if user has unread AND is NOT the selected user
                            const showDot = user.has_unread && !isSelected;
                            const dot = showDot ? '<span class="inline-block w-2 h-2 bg-red-500 rounded-full ml-2"></span>' : '';
                            
                            const userItem = $(`
                                <div class="cursor-pointer hover:bg-gray-100 p-2 rounded user-item" data-id="${user.id}" data-type="${user.type}">
                                    ${user.name} (${user.id_num}) ${dot}
                                </div>
                            `);
                            userList.append(userItem);
                        });
                        if (scrollToSelected && selectedUser) {
                            $(`.user-item[data-id="${selectedUser.id}"][data-type="${selectedUser.type}"]`).addClass('bg-gray-200');
                        }
                    },
                    error: function (xhr) {
                        alert("Failed to load users: " + xhr.responseText);
                    }
                });
            }

            function startUserListPolling() {
                if (pollingUserList) clearInterval(pollingUserList);
                pollingUserList = setInterval(() => {
                    loadUsers(true);
                }, 5000);
            }

            function startConversationPolling(userType, userId) {
                if (pollingConversation) clearInterval(pollingConversation);
                pollingConversation = setInterval(() => {
                    if (selectedUser) {
                        loadConversation(userType, userId, true);
                        markAsRead(userType, userId);
                    }
                }, 5000);
            }

            // Load conversation
            function loadConversation(userType, userId, scroll = true) {
                $.ajax({
                    url: 'fetch_conversation.php',
                    method: 'POST',
                    data: { user_type: userType, user_id: userId },
                    success: function (data) {
                        if (data.error) {
                            alert("Error: " + data.error);
                            return;
                        }
                        
                        const chatBox = $('#conversation').empty();
                        if (data.length === 0) {
                            chatBox.html('<p class="text-gray-500 italic">No messages yet</p>');
                            return;
                        }

                        data.forEach(msg => {
                            const align = msg.sender_type === 'admin' ? 'text-right' : 'text-left';
                            const bg = msg.sender_type === 'admin' ? 'bg-blue-100' : 'bg-green-100';
                            const bubble = `<div class="${align} mb-2"><span class="inline-block ${bg} px-3 py-2 rounded-lg">${msg.message}</span></div>`;
                            chatBox.append(bubble);
                        });

                        if (scroll) {
                            chatBox.scrollTop(chatBox[0].scrollHeight);
                        }
                    },
                    error: function (xhr) {
                        alert("Failed to load conversation: " + xhr.responseText);
                    }
                });
            }

            function markAsRead(userType, userId) {
                // Mark as read
                $.ajax({
                    url: 'mark_as_read.php',
                    method: 'POST',
                    data: { user_type: userType, user_id: userId },
                    error: function (xhr) {
                        alert("Failed to mark messages as read: " + xhr.responseText);
                    }
                });
            }

            // Send message
            $('#sendMessageBtn').on('click', function () {
                if (!selectedUser) {
                    alert("Select a user to send message.");
                    return;
                }

                const message = $('#messageInput').val().trim();
                if (message === '') {
                    alert("Message cannot be empty.");
                    return;
                }

                $.ajax({
                    url: 'send_message.php',
                    method: 'POST',
                    data: {
                        receiver_type: selectedUser.type,
                        receiver_id: selectedUser.id,
                        message: message
                    },
                    success: function (data) {
                        if (data.error) {
                            alert("Error: " + data.error);
                            return;
                        }

                        $('#messageInput').val('');
                        loadConversation(selectedUser.type, selectedUser.id);
                        loadUsers(true);
                    },
                    error: function (xhr) {
                        alert("Failed to send message: " + xhr.responseText);
                    }
                });
            });

            // Handle user click
            $('#userList').on('click', '.user-item', function () {
                const userType = $(this).data('type');
                const userId = $(this).data('id');
                selectedUser = {type: userType, id: userId};

                // Remove highlight from all
                $('.user-item').removeClass('bg-blue-100 font-semibold text-blue-800');

                // Add highlight to selected
                $(this).addClass('bg-blue-100 font-semibold text-blue-800');

                loadConversation(userType, userId);
                markAsRead(userType, userId);
                // remove notification dot
                $(this).find('span').remove(); 
                $(this).addClass('bg-gray-200');
                
                startConversationPolling(userType, userId);
            });

            loadUsers();
            startUserListPolling();
        });

        //Populate User Details    
        fetch('admin.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('userType').textContent = 'Administrator';
                document.getElementById('userName').textContent = data.administrator.name;
                document.getElementById('userEmail').textContent = data.administrator.email;
                document.getElementById('userDob').textContent = data.administrator.dob;
                document.getElementById('userId').textContent = data.administrator.id_num;
            } else {
                    alert('Failed to load user details.');}
        })
        .catch(error => console.error('Error:', error));

        // Add Index Numbers functionality
        document.getElementById('addIndexBtn').addEventListener('click', () => {
            const type = document.getElementById('indexType').value;
            const rawInput = document.getElementById('indexNumber').value.trim();

            // Split input by line and filter out empty lines
            const ids = rawInput.split('\n').map(id => id.trim()).filter(id => id !== '');

            if (ids.length === 0) {
                alert('Please enter at least one ID.');
                return;
            }

            fetch('add_indexes.php', {
                method: 'POST',
                body: JSON.stringify({ type, ids }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.text().then(text => {
                try {
                    return JSON.parse(text);
                } catch (e) {
                    console.error('Invalid JSON from server:', text);
                    throw new Error('Invalid JSON');
                }
            }))
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    document.getElementById('indexNumber').value = '';

                    // Update the table according to the type of IDs successfully added to the database
                    const tableBody = document.getElementById('idTableBody');
                    ids.forEach(id => {
                        if (data.skippedIds && data.skippedIds.includes(id)) return; // Skip already existing IDs

                        const newRow = document.createElement('tr');
                        if (type === 'student') {
                            newRow.innerHTML = `<td class="py-2 px-4 border-b">${id} <button class="ml-2 text-red-600 hover:text-red-800 delete-id" data-type="student" data-id="${id}"><i class="fas fa-trash"></i></button></td><td class="py-2 px-4 border-b"></td><td class="py-2 px-4 border-b"></td>`;
                        } else if (type === 'lecturer') {
                            newRow.innerHTML = `<td class="py-2 px-4 border-b"></td><td class="py-2 px-4 border-b">${id} <button class="ml-2 text-red-600 hover:text-red-800 delete-id" data-type="lecturer" data-id="${id}"><i class="fas fa-trash"></i></button></td><td class="py-2 px-4 border-b"></td>`;
                        } else if (type === 'admin') {
                            newRow.innerHTML = `<td class="py-2 px-4 border-b"></td><td class="py-2 px-4 border-b"></td><td class="py-2 px-4 border-b">${id} <button class="ml-2 text-red-600 hover:text-red-800 delete-id" data-type="admin" data-id="${id}"><i class="fas fa-trash"></i></button></td>`;
                        }
                        tableBody.appendChild(newRow);
                    });
                    
                } else {
                    alert('Server Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error(error);
                alert('An error occurred while adding IDs.');
            });
        });
    </script>
</body>
</html>