<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login and Register</title>
 
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-200">
    <div class="bg-blue-500 p-4 flex justify-between items-center">
        <div>
            <h1 class="text-white text-3xl font-bold">Kasiglahan Village National High School </h1>
        </div>
        <div class="flex items-center space-x-4">
            <button id="registerBtn" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition duration-300 ease-in-out transform hover:scale-105">
                Register
            </button>
            <button id="loginBtn" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded transition duration-300 ease-in-out transform hover:scale-105">
                Login
            </button>
        </div>
    </div>
    <div class="text-center mt-20">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">Welcome to the voting system</h1>
        <p class="text-lg text-gray-600">Please login or register to proceed.</p>
    </div>

    <!-- JavaScript -->
    <script>
        // Add JavaScript code here
        document.getElementById('loginBtn').addEventListener('click', function() {
            // Redirect to login page
            window.location.href = 'login.php';
        });

        document.getElementById('registerBtn').addEventListener('click', function() {
            // Redirect to register page
            window.location.href = 'register.php';
        });
    </script>
</body>

</html>

