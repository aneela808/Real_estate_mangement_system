<?php
session_start();
include 'connect.php'; // Database connection

// Check if admin is logged in
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header("Location: login.php");
    exit();
}

// Handle Delete
if(isset($_GET['action']) && $_GET['action'] === 'delete'){
    $type = $_GET['type'] ?? '';
    $id = intval($_GET['id'] ?? 0);

    if($type && $id){
        if($type === 'user'){
            $conn->query("DELETE FROM users WHERE user_id=$id");
        } elseif($type === 'property'){
            $conn->query("DELETE FROM properties WHERE property_id=$id");
        } elseif($type === 'team'){
            $conn->query("DELETE FROM team_members WHERE id=$id");
        }
    }
    header("Location: admin_crud.php");
    exit();
}

// Fetch all data
$users = $conn->query("SELECT * FROM users");
$properties = $conn->query("SELECT * FROM properties");
$team = $conn->query("SELECT * FROM team_members");
?>

<!DOCTYPE html>

<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Panel</title>
<style>
body {
    font-family: Arial, sans-serif; 
    margin: 20px;
    background: #f9f9f9;
}
header {
    font-size: 28px; 
    font-weight: bold; 
    margin-bottom: 20px;
}
h1 { color: #333; margin-bottom: 20px; }
h2 { margin-top: 40px; color: #444; }
table {
    border-collapse: collapse; 
    width: 100%; 
    margin-bottom: 30px; 
    background: #fff;
}
th, td {
    border: 1px solid #ccc; 
    padding: 8px; 
    text-align: left;
}
th {
    background: #eaeaea;
}
a {
    margin-right: 10px; 
    text-decoration: none; 
    color: #0066cc;
}
a:hover {
    text-decoration: underline;
}
img { border-radius: 5px; }
.container { max-width: 1200px; margin: auto; }
</style>
</head>
<body>
<header>Admin Panel</header>
<div class="container">

<h1>Dashboard</h1>

<!-- Users Table -->

<h2>Users</h2>
<a href="add_user.php">Add User</a>
<table>
<tr>
<th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Actions</th>
</tr>
<?php while($u = $users->fetch_assoc()): ?>
<tr>
<td><?= $u['user_id'] ?? '' ?></td>
<td><?= htmlspecialchars($u['name'] ?? '') ?></td>
<td><?= htmlspecialchars($u['email'] ?? '') ?></td>
<td><?= htmlspecialchars($u['role'] ?? '') ?></td>
<td>
<a href="edit_user.php?id=<?= $u['user_id'] ?? 0 ?>">Edit</a>
<a href="admin_crud.php?action=delete&type=user&id=<?= $u['user_id'] ?? 0 ?>" onclick="return confirm('Delete this user?')">Delete</a>
</td>
</tr>
<?php endwhile; ?>
</table>

<!-- Properties Table -->

<h2>Properties</h2>
<a href="add_property.php">Add Property</a>
<table>
<tr>
<th>ID</th><th>Title</th><th>Location</th><th>Price</th><th>Actions</th>
</tr>
<?php while($p = $properties->fetch_assoc()): ?>
<tr>
<td><?= $p['property_id'] ?? '' ?></td>
<td><?= htmlspecialchars($p['title'] ?? '') ?></td>
<td><?= htmlspecialchars($p['location'] ?? '') ?></td>
<td><?= htmlspecialchars($p['price'] ?? '') ?></td>
<td>
<a href="edit_property.php?id=<?= $p['property_id'] ?? 0 ?>">Edit</a>
<a href="admin_crud.php?action=delete&type=property&id=<?= $p['property_id'] ?? 0 ?>" onclick="return confirm('Delete this property?')">Delete</a>
</td>
</tr>
<?php endwhile; ?>
</table>

<!-- Team Members Table -->

<h2>Team Members</h2>
<a href="add_team.php">Add Team Member</a>
<table>
<tr>
<th>ID</th><th>Name</th><th>Position</th><th>Photo</th><th>Actions</th>
</tr>
<?php while($t = $team->fetch_assoc()): ?>
<tr>
<td><?= $t['id'] ?? '' ?></td>
<td><?= htmlspecialchars($t['name'] ?? '') ?></td>
<td><?= htmlspecialchars($t['position'] ?? '') ?></td>
<td>
<?php if(!empty($t['photo'])): ?>
<img src="uploads/<?= htmlspecialchars($t['photo']) ?>" alt="Photo" width="50">
<?php else: ?>
N/A
<?php endif; ?>
</td>
<td>
<a href="edit_team.php?id=<?= $t['id'] ?? 0 ?>">Edit</a>
<a href="admin_crud.php?action=delete&type=team&id=<?= $t['id'] ?? 0 ?>" onclick="return confirm('Delete this team member?')">Delete</a>
</td>
</tr>
<?php endwhile; ?>
</table>

</div>
</body>
</html>
