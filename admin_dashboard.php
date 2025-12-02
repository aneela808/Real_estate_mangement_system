<?php
session_start();
include 'connect.php';

// Safe session values
$admin_name = $_SESSION['name'] ?? "";

// --- Fetch Users ---
$users = [];
$user_result = $conn->query("SELECT * FROM users ORDER BY user_id ASC");
while($row = $user_result->fetch_assoc()){ $users[] = $row; }

// --- Fetch Properties ---
$properties = [];
$prop_result = $conn->query("SELECT * FROM properties ORDER BY property_id ASC");
while($row = $prop_result->fetch_assoc()){ $properties[] = $row; }

// --- Fetch Team Members ---
$team_members = [];
$team_result = $conn->query("SELECT * FROM team_members ORDER BY id ASC");
while($row = $team_result->fetch_assoc()){ $team_members[] = $row; }

// --- Fetch Bookings ---
$bookings = [];
$booking_result = $conn->query("
    SELECT b.*, p.title AS property_title, u.name AS user_name 
    FROM bookings b
    LEFT JOIN properties p ON b.property_id = p.property_id
    LEFT JOIN users u ON b.user_id = u.user_id
    ORDER BY b.id DESC
");
while($row = $booking_result->fetch_assoc()){ $bookings[] = $row; }

// --- Fetch Contact Messages ---
$contacts = [];
$contact_result = $conn->query("SELECT * FROM contact_messages ORDER BY id DESC");
while($row = $contact_result->fetch_assoc()){ $contacts[] = $row; }

$conn->close();
?>
<!DOCTYPE html>

<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard | DreamHomes</title>
<link rel="stylesheet" href="admin.css">
</head>

<body>

<header>
    <h1>Admin Dashboard</h1>
    <p>Welcome, <?php echo htmlspecialchars($admin_name ?: "Admin"); ?></p>
    <nav class="navbar">
    
    <ul class="nav-links">
        
<li><a href="login.html" style="color:#fff; background:#d9534f; padding:8px 14px; border-radius:5px;">Logout</a></li>

    </ul>
</nav>
</header>



<!-- USERS -->
<section>
    <h2>Users</h2>
    <?php if(!empty($users)): ?>
        <table border="1">
            <tr>
                <th>User ID</th><th>Name</th><th>Email</th><th>Role</th><th>Actions</th>
            </tr>
            <?php foreach($users as $u): ?>
                <tr>
                    <td><?= $u['user_id'] ?></td>
                    <td><?= $u['name'] ?></td>
                    <td><?= $u['email'] ?></td>
                    <td><?= $u['role'] ?></td>
                    <td>
                        <a href="admin_crud.php?action=edit_user&id=<?= $u['user_id'] ?>">Edit</a>
                        |
                        <a href="admin_crud.php?action=delete_user&id=<?= $u['user_id'] ?>" onclick="return confirm('Are you sure?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?><p>No users found.</p><?php endif; ?>
</section>


<!-- PROPERTIES -->
<section>
    <h2>Properties</h2>
    <?php if(!empty($properties)): ?>
        <table border="1">
            <tr>
                <th>ID</th><th>Title</th><th>Type</th><th>Price</th><th>Actions</th>
            </tr>
            <?php foreach($properties as $p): ?>
                <tr>
                    <td><?= $p['property_id'] ?></td>
                    <td><?= $p['title'] ?></td>
                    <td><?= $p['type'] ?></td>
                    <td><?= $p['price'] ?></td>
                    <td>
                        <a href="admin_crud.php?action=edit_property&id=<?= $p['property_id'] ?>">Edit</a>
                        |
                        <a href="admin_crud.php?action=delete_property&id=<?= $p['property_id'] ?>" onclick="return confirm('Delete property?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?><p>No properties found.</p><?php endif; ?>
</section>


<!-- TEAM MEMBERS -->
<section>
    <h2>Team Members</h2>
    <?php if(!empty($team_members)): ?>
        <table border="1">
            <tr>
                <th>ID</th><th>Name</th><th>Position</th><th>Photo</th><th>Actions</th>
            </tr>
            <?php foreach($team_members as $t): ?>
                <tr>
                    <td><?= $t['id'] ?></td>
                    <td><?= $t['name'] ?></td>
                    <td><?= $t['position'] ?></td>
                    <td><img src="<?= $t['photo'] ?>" width="50"></td>
                    <td>
                        <a href="admin_crud.php?action=edit_member&id=<?= $t['id'] ?>">Edit</a> |
                        <a href="admin_crud.php?action=delete_member&id=<?= $t['id'] ?>" onclick="return confirm('Delete member?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?><p>No team members found.</p><?php endif; ?>
</section>


<!-- BOOKINGS TABLE -->
<section>
    <h2>Bookings</h2>
    <?php if(!empty($bookings)): ?>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Property</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Date</th>
                <th>Message</th>
                <th>Status</th>
            </tr>

            <?php foreach($bookings as $b): ?>
                <tr>
                    <td><?= $b['id'] ?></td>
                    <td><?= $b['user_name'] ?: "Guest" ?></td>
                    <td><?= $b['property_title'] ?></td>
                    <td><?= $b['full_name'] ?></td>
                    <td><?= $b['phone'] ?></td>
                    <td><?= $b['date'] ?></td>
                    <td><?= $b['message'] ?></td>
                    <td><?= $b['status'] ?></td>
                </tr>
            <?php endforeach; ?>

        </table>
    <?php else: ?><p>No bookings found.</p><?php endif; ?>
</section>


<!-- CONTACT MESSAGES -->
<section>
    <h2>Contact Messages</h2>
    <?php if(!empty($contacts)): ?>
        <table border="1">
            <tr>
                <th>ID</th><th>Name</th><th>Email</th><th>Message</th><th>Date</th>
            </tr>

            <?php foreach($contacts as $c): ?>
                <tr>
                    <td><?= $c['id'] ?></td>
                    <td><?= $c['name'] ?></td>
                    <td><?= $c['email'] ?></td>
                    <td><?= $c['message'] ?></td>
                    <td><?= $c['created_at'] ?></td>
                </tr>
            <?php endforeach; ?>

        </table>
    <?php else: ?><p>No contact messages yet.</p><?php endif; ?>
</section>

</body>
</html>
