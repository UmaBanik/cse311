<?php
$db_sever = "localhost";
$db_user = "root" ;
$db_password = "";
$db_name = "melodise_db";

try{
    $conn = mysqli_connect($db_sever, $db_user, $db_password, $db_name);
}
catch(mysqli_sql_exception){
    echo"Connection to database failed";
}



// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['save-admin'])) {
        // Save a new admin or update an existing one
        $AdminID = $_POST['AdminID'];
        $name = $_POST['Name'];
        $email = $_POST['Email'];
        $password = $_POST['Password'];
        $role = $_POST['Role'];

        if (!empty($AdminID)) {
            // Update admin
            if (!empty($password)) {
                // Update password if provided
                $update_query = "UPDATE admins 
                                 SET Name = '$name', Email = '$email', Password = '$password', Role = '$role' 
                                 WHERE AdminID = $AdminID";
            } else {
                // Keep the existing password
                $update_query = "UPDATE admins 
                                 SET Name = '$name', Email = '$email', Role = '$role' 
                                 WHERE AdminID = $AdminID";
            }
            mysqli_query($conn, $update_query);
        } else {
            // Insert new admin
            $insert_query = "INSERT INTO admins (Name, Email, Password, Role) 
                             VALUES ('$name', '$email', '$password', '$role')";
            mysqli_query($conn, $insert_query);
        }

        // After the form is submitted, reload the page to avoid resubmission and refresh the data
        header("Location: form.php");
        exit;
    } 
}

// Handle delete admin
if (isset($_GET['delete'])) {
    $AdminID = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM admins WHERE AdminID = $AdminID");
    header("Location: form.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            padding: 20px;
            margin: 0;
        }
        .container {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        form input,
        form select,
        form button {
            padding: 10px;
            font-size: 16px;
            
        }
        table {
            width: 100%;
            margin-top: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        table th {
            background-color: #f4f4f4;
        }
        button, .action {
            padding: 5px 10px;
        
            border-radius: 4px;
            cursor: pointer;
            color: white;
        }
        .edit {
            background-color: #007bff;
        }
        .copy {
            background-color: #ffc107;
        }
        .delete {
            background-color: #dc3545;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Admin Update</h1>
    <!-- Admin Form -->
    <form action="form.php" method="POST">
        <input type="hidden" name="AdminID" id="AdminID">
        <input type="text" name="Name" id="name" placeholder="Name" required>
        <input type="email" name="Email" id="email" placeholder="Email" required>
        <input type="password" name="Password" id="password" placeholder="Password">
        <select name="Role" id="role" required>
            <option value="">Select Role</option>
            <option value="Super Admin">Super Admin</option>
            <option value="Moderator">Moderator</option>
            <option value="Viewer">Viewer</option>
        </select>
        <button type="submit" name="save-admin">Save</button>
    </form>

    <!-- Admins Table -->
    <table>
        <thead>
        <tr>
            <th>AdminID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Password</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $result = mysqli_query($conn, "SELECT * FROM admins");
        while ($admin = mysqli_fetch_assoc($result)) {
            echo "
                <tr>
                    <td>{$admin['AdminID']}</td>
                    <td>{$admin['Name']}</td>
                    <td>{$admin['Email']}</td>
                    <td>{$admin['Password']}</td>
                    <td>{$admin['Role']}</td>
                    <td>
                        <button class='action edit' onclick='editAdmin({$admin['AdminID']})'>Edit</button>
                        
                        <a href='form.php?delete={$admin['AdminID']}' class='action delete'>Delete</a>
                    </td>
                </tr>
            ";
        }
        ?>
        </tbody>
    </table>
</div>

<script>
    function editAdmin(id) {
    // Find the row by iterating through table rows
    const rows = document.querySelectorAll('table tbody tr');
    rows.forEach(row => {
        if (row.cells[0].innerText == id) {
            document.getElementById('AdminID').value = row.cells[0].innerText;
            document.getElementById('name').value = row.cells[1].innerText;
            document.getElementById('email').value = row.cells[2].innerText;
            document.getElementById('password').value = "";  // Keep the password empty for security
            document.getElementById('role').value = row.cells[4].innerText;
        }
    });
}
    
</script>

</body>
</html>
