<?php
require_once('connection.php');
// Check if the user has already voted
if (isset($_SESSION['voted'])) {
    echo "You have already voted.";
    exit; // Exit to prevent further execution
}
// Check if the vote button is clicked
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['voter_id'])) {
    // Process the vote
    $voterId = $_POST['voter_id'];
    // Your code to update the vote count in the database goes here

    // Set the session variable to mark that the user has voted
    $_SESSION['voted'] = true;
}
$sql_fetch_data = "SELECT id, name, officer,image FROM voters";
$stmt = $conn->query($sql_fetch_data);
$voters = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote</title>
    
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
<header class="bg-blue-500 py-4">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <h1 class="text-white text-2xl font-bold">Vote App</h1>
          <a href="../index.php" class="text-white">Logout</a>

        </div>
    </div>
</header>
<div class="container mx-auto px-4 py-8">
    <?php 
    // Array to store voters based on officer type
    $voters_by_type = array(
        'President' => array(),
        'Vice President' => array(),
        'Surgent' => array(),
        'Author' => array(),
        'Secretary' => array()
    );

    // Group voters by officer type
    foreach ($voters as $voter) {
        $voters_by_type[$voter['officer']][] = $voter;
    }

    // Display voters by officer type
    foreach ($voters_by_type as $officer => $voters):
    ?>
        <div class="mb-8">
            <h2 class="text-3xl font-bold mb-4 text-gray-800"><?php echo $officer; ?></h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($voters as $voter): ?>
                    <div class="bg-white shadow-md rounded-lg overflow-hidden">
                         <div class="px-6 py-4">
                             <img src="<?php echo $voter['image']; ?>" alt="Voter Image" class="mt-3 w-full h-auto object-cover">
                            <h3 class="text-lg font-semibold mb-2 text-center"><?php echo $voter['name']; ?></h3>
                            <p class="text-gray-700">Officer: <?php echo $voter['officer']; ?></p>
                           
                        </div>
                        <div class="px-6 py-4 bg-gray-100 border-t border-gray-200">
                            <a href="#" class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="castVote(<?php echo $voter['id']; ?>, this)">Vote</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script>
    function castVote(voterId, button) {
        // Check if the button is already disabled
        if (!button.disabled) {
            // Send AJAX request
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "update_vote.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Update button text and disable it
                    button.innerText = "Voted";
                    button.disabled = true;
                }
            };
            xhr.send("voter_id=" + voterId);
            <?php $_SESSION['voted'] = true; ?>;
        }
    }
</script>

</body>
</html>
