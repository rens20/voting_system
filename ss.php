<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
  
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
   
        .form-container {
            max-width: 400px;
            margin: 0 auto;
        }

        .form-input {
            margin-bottom: 1rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
        }

        .form-submit {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
        }

        .form-submit:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto py-12">
        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 form-container">
            <h2 class="text-2xl mb-6">User Registration</h2>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-input">
                    <label for="username" class="form-label">Username:</label>
                    <input type="text" id="username" name="username" required class="w-full px-3 py-2 border rounded">
                </div>

                <div class="form-input">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" id="password" name="password" required class="w-full px-3 py-2 border rounded">
                </div>

                <div class="form-input">
                    <label for="confirm_password" class="form-label">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required class="w-full px-3 py-2 border rounded">
                </div>

                <input type="submit" value="Register" class="form-submit">
            </form>
        </div>
    </div>

    <?php
    // Database credentials
    $host = 'localhost'; 
    $dbname = 'mydb'; 
    $username = 'root'; 
    $password = '@wasie123'; 

    try {
        // Create a PDO connection
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

        // Set PDO attributes (optional)
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        // Function to check for duplicate username
        function checkDuplicateUsername($username, $pdo) {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            return $stmt->fetch();
        }

        // Function to encrypt password
        function encryptPassword($password) {
            return password_hash($password, PASSWORD_DEFAULT);
        }

        // Function to confirm password
        function confirmPassword($password, $confirmPassword) {
            return $password === $confirmPassword;
        }

        // Example usage
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirm_password'];

            // Check for duplicate username
            $duplicateUser = checkDuplicateUsername($username, $pdo);
            if ($duplicateUser) {
                // Handle duplicate username error
                echo "<script>alert('Error: Username already exists.');</script>";
            } else {
                // Confirm password
                if (confirmPassword($password, $confirmPassword)) {
                    // Encrypt password
                    $hashedPassword = encryptPassword($password);

                    // Insert user into database with hashed password
                    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
                    $stmt->bindParam(':username', $username);
                    $stmt->bindParam(':password', $hashedPassword);
                    $stmt->execute();

                    echo "<script>alert('User registered successfully.');</script>";
                } else {
                    // Handle password mismatch error
                    echo "<script>alert('Error: Passwords do not match.');</script>";
                }
            }
        }
    } catch (PDOException $e) {
        // Handle database connection error
        echo "<script>alert('Connection failed: " . $e->getMessage() . "');</script>";
    }
    ?>
</body>

</html>
