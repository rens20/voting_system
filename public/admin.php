<?php
require_once('connection.php');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Check if form is for inserting new record or updating existing record
        if (isset($_POST['insert'])) {
            // Prepare an SQL statement for insertion
            $stmt = $conn->prepare("INSERT INTO voters (name, officer) VALUES (:name, :officer)");
            
            // Bind parameters to the named placeholders
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':officer', $officer);
            
            // Set parameters and execute the statement for insertion
            $name = $_POST['name'];
            $officer = $_POST['officer'];
            $stmt->execute();
            
            header("Location: admin.php");
        } elseif (isset($_POST['update'])) {
            // Prepare an SQL statement for updating
            $stmt = $conn->prepare("UPDATE voters SET name = :name, officer = :officer WHERE id = :id");
            
            // Bind parameters to the named placeholders
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':officer', $officer);
            $stmt->bindParam(':id', $id);
            
            // Set parameters and execute the statement for updating
            $name = $_POST['name'];
            $officer = $_POST['officer'];
            $id = $_POST['id'];
            $stmt->execute();
            
            header("Location: admin.php");
        }
    }

    // Fetch voters data
    $sql_fetch_data = "SELECT id, name, officer,vote_counter FROM voters";
    $stmt = $conn->query($sql_fetch_data);
    $voters = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voting system</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 p-6">
    <h1 class="text-3xl font-bold mb-4">Admin panel</h1>
    <form action="" method="post" class="mb-6">
        <input type="hidden" name="id" value="">
        <input type="text" name="name" placeholder="Enter name"
            class="border border-gray-300 rounded-md px-4 py-2 mb-2">
        <select name="officer"
            class="border border-gray-300 rounded-md px-4 py-2 mb-2">
            <option value="President">President</option>
            <option value="Vice President">Vice President</option>
            <option value="Secretary">Secretary</option>
            <option value="Author">Author</option>
            <option value="Surgent">Surgent</option>
        </select>
        <button type="submit" name="insert"
            class="bg-blue-500 text-white px-4 py-2 rounded-md mr-2 hover:bg-blue-600">Insert</button>
        <button type="submit" name="update"
            class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">Update</button>
    </form>

    <table class="w-full border-collapse border border-gray-300">
        <thead>
            <tr>
                <th class="border border-gray-300 px-4 py-2">Name</th>
                <th class="border border-gray-300 px-4 py-2">Officer</th>
                <th class="border border-gray-300 px-4 py-2">Vote Count</th>
                <th class="border border-gray-300 px-4 py-2">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($voters as $voter): ?>
            <tr>
                <td class="border border-gray-300 px-4 py-2"><?php echo $voter['name']; ?></td>
                <td class="border border-gray-300 px-4 py-2"><?php echo $voter['officer']; ?></td>
                <td class="border border-gray-300 px-4 py-2"><?php echo $voter['vote_counter']; ?></td>
                <td class="border border-gray-300 px-4 py-2">
                    <a href="?delete=<?php echo $voter['id']; ?>"
                        class="text-red-500 hover:text-red-700 mr-2">Delete</a>
                    <a href="?edit=<?php echo $voter['id']; ?>"
                        class="text-blue-500 hover:text-blue-700">Edit</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php
    if (isset($_GET['edit'])) {
        $id = $_GET['edit'];
        $sql_fetch_record = "SELECT name, officer FROM voters WHERE id = :id";
        $stmt = $conn->prepare($sql_fetch_record);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $record = $stmt->fetch(PDO::FETCH_ASSOC);
    ?>
    <script>
        document.getElementsByName('name')[0].value = "<?php echo $record['name']; ?>";
        document.getElementsByName('officer')[0].value = "<?php echo $record['officer']; ?>";
        document.getElementsByName('id')[0].value = "<?php echo $id; ?>";
        document.getElementsByName('name')[0].focus();
    </script>
    <?php } ?>

    <?php
    if (isset($_GET['delete'])) {
        $id = $_GET['delete'];
        $sql_delete_record = "DELETE FROM voters WHERE id = :id";
        $stmt = $conn->prepare($sql_delete_record);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        header("Location: admin.php");
    }
    ?>
</body>

</html>
