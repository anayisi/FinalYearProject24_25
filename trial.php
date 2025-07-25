<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
            
            <form id="login-form" class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email/Username</label>
                    <input type="email" id="email" name="email" required 
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
            </form>
            
            <div class="mt-6 text-center text-sm text-gray-500">
                <p>For security reasons, please log out when finished.</p>
            </div>
        </div>
    </div>

    <!-- Dashboard (initially hidden) -->
    <div id="adminDashboard" class="hidden">
        <header class="bg-white shadow-sm">
            <button id="logoutBtn" class="text-left block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                <i class="fas fa-sign-out-alt mr-2"></i> Sign out
            </button>
        </header>
        <h1>ADMIN</h1>    
    </div>
    
    <div id="lecturerDashboard" class="hidden">
        <header class="bg-white shadow-sm">
            <button id="lecLogoutBtn" class="text-left block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                <i class="fas fa-sign-out-alt mr-2"></i> Sign out
            </button>
        </header>
        <h1>LECTURER</h1>    
    </div>
    
    <div id="studentDashboard" class="hidden">
        <header class="bg-white shadow-sm">
            <button id="stuLogoutBtn" class="text-left block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                <i class="fas fa-sign-out-alt mr-2"></i> Sign out
            </button>
        </header>
        <h1>student</h1>    
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

    <script>
        // Session management to prevent back button access
        let isLoggedIn = false;
        const loginScreen = document.getElementById('loginScreen');
        const adminDashboard = document.getElementById('adminDashboard');
        const lecturerDashboard = document.getElementById('lecturerDashboard');
        const studentDashboard = document.getElementById ('studentDashboard');
        const loginForm = document.getElementById('login-form');
        const logoutModal = document.getElementById('logoutModal');
        const cancelLogout = document.getElementById('cancelLogout');
        const confirmLogout = document.getElementById('confirmLogout');

        // Prevent back navigation after logout
        window.history.pushState(null, null, window.location.href);
        window.onpopstate = function(event) {
            if (isLoggedIn) {
                // If logged in and back button pressed, force logout
                performLogout();
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
            fetch('login.php', {
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
                    } else if (data.role === 'lecturer') {
                        lecturerDashboard.classList.remove('hidden');
                    } else if (data.role === 'student') {
                        studentDashboard.classList.remove('hidden');
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
        lecLogoutBtn.addEventListener('click', function() {
            logoutModal.classList.remove('modal-hidden');
        });
        stuLogoutBtn.addEventListener('click', function() {
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
            lecturerDashboard.classList.add('hidden');
            studentDashboard.classList.add('hidden');
            loginScreen.classList.remove('hidden');
            logoutModal.classList.add('modal-hidden');
            
            // Clear any existing history states
            window.history.pushState(null, null, window.location.href);
            
            // Show a message about successful logout
            alert('You have been successfully logged out. Please sign in again to access the dashboard.');
        }
    </script>
</body>
</html>