<?php
require_once('connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['insert'])) {
        // Retrieve form data
        $name = $_POST['name'];
        $officer = $_POST['officer'];
        
        // Check if an image is uploaded
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imageFileName = $_FILES['image']['name'];
            $imageTempName = $_FILES['image']['tmp_name'];
            $imageUploadPath = 'uploads/' . $imageFileName; // Upload path relative to the script
            
            // Move the uploaded image to the uploads folder
            if (move_uploaded_file($imageTempName, $imageUploadPath)) {
                // Image uploaded successfully, now insert into database
                try {
                    $stmt = $conn->prepare("INSERT INTO voters (name, officer, image) VALUES (:name, :officer, :image)");
                    $stmt->bindParam(':name', $name);
                    $stmt->bindParam(':officer', $officer);
                    $stmt->bindParam(':image', $imageUploadPath);
                    $stmt->execute();
                    header("Location: admin.php");
                    exit();
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
            } else {
                echo "Error uploading image.";
            }
        } else {
            echo "No image uploaded.";
        }
    } elseif (isset($_POST['update'])) {
        $name = $_POST['name'];
        $officer = $_POST['officer'];
        $id = $_POST['id'];

        // Check if an image is uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageFileName = $_FILES['image']['name'];
        $imageTempName = $_FILES['image']['tmp_name'];
        $imageUploadPath = 'uploads/' . $imageFileName; // Upload path relative to the script

        // Move the uploaded image to the uploads folder
        if (move_uploaded_file($imageTempName, $imageUploadPath)) {

        // Image uploaded successfully, now update the database
            try {
                $stmt = $conn->prepare("UPDATE voters SET name = :name, officer = :officer, image = :image WHERE id = :id");
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':officer', $officer);
                $stmt->bindParam(':id', $id);
                $stmt->bindParam(':image', $imageUploadPath);
                $stmt->execute();
                header("Location: admin.php");
                exit();
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
        }
    } else {
        echo "error uploading image";
    }
}else {
     try {
            $stmt = $conn->prepare("UPDATE voters SET name = :name, officer = :officer WHERE id = :id");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':officer', $officer);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            header("Location: admin.php");
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
}
    

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $voters = searchVoters($searchQuery);
} else {
    $voters = fetchAllVoters();
}

function fetchAllVoters() {
    global $conn;
    try {
        $stmt = $conn->query("SELECT id, name, officer, vote_counter,image FROM voters");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return [];
    }
}

function searchVoters($searchQuery) {
    global $conn;
    try {
        $search = '%' . $searchQuery . '%';
        $stmt = $conn->prepare("SELECT id, name, officer, vote_counter, image FROM voters WHERE name LIKE :search");
        $stmt->bindParam(':search', $search);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return [];
    }
}
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
    <form action="" method="get" class="mb-6"  enctype="multipart/form-data">
        
        <div class="flex mb-4">
            <input type="text" name="search" placeholder="Search by name"
                class="border border-gray-300 rounded-md px-4 py-2 mr-2">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Search</button>
            <a href="admin.php" class="bg-gray-400 text-white px-4 py-2 rounded-md hover:bg-gray-600 ml-2">Clear</a>
        </div>
    </form>
    <form action="" method="post" class="mb-6" enctype="multipart/form-data">
        
        <input type="hidden" name="id" value="">
        <input type="text" name="name" placeholder="Enter name"
            class="border border-gray-300 rounded-md px-4 py-2 mb-2">
        <select name="officer"
            class="border border-gray-300 rounded-md px-4 py-2 mb-2">
            <option value="President">President</option>
            <option value="Vice President">Vice President</option>
            <option value="PIO">PIO</option>
            <option value="Secretary">Secretary</option>
            <option value="Auditor">Auditor</option>
            <option value="Treasurer">Treasurer</option>
            <option value="Author">Author</option>
            <option value="Protocol officers">Protocol officers</option>
            <option value="Representative">Representative</option>
        </select>

        <input type="file" name="image" accept="image/*" class="border border-gray-300 rounded-md px-4 py-2 mb-2">
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
                <th class="border border-gray-300 px-4 py-2">Image</th>

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
                <td class="border border-gray-300 px-4 py-2">
                        <?php if (!empty($voter['uploads'])): ?>
                            <img src="<?php echo $voter['image']; ?>" alt="User Image" class="w-24 h-24 object-cover rounded">
                        <?php else: ?>
                            No Image
                        <?php endif; ?>
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
