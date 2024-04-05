<?php 
require_once('connection.php');

$sql_fetch_data = "SELECT id, name, officer FROM voters";
$stmt = $conn->query($sql_fetch_data);
$voters = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote</title>
    <!-- Include Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
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
                            <h3 class="text-lg font-semibold mb-2"><?php echo $voter['name']; ?></h3>
                            <p class="text-gray-700">Officer: <?php echo $voter['officer']; ?></p>
                        </div>
                        <div class="px-6 py-4 bg-gray-100 border-t border-gray-200">
                            <a href="#" class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Vote</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
</body>
</html>
