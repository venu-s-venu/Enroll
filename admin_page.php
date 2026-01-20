<?php
session_start();
require_once 'config.php';

// Fetch all users from database
$usersResult = $conn->query("SELECT id, name, email, role FROM users");
$users = [];
if ($usersResult) {
    while ($row = $usersResult->fetch_assoc()) {
        $users[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        body {
            height: 100vh;
            background-color: #f0f0f0;
            overflow-y: auto;
        }
        p{
            font-size: 24px;
            color: #333;
        }
        button{
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            background:#5b86e5;
            border-radius:6px;
            color:#fff;
            border:none;
            margin-top: 20px;
        }
        button:hover{
            background:#4a6fd0;
        }
        .card{
            margin:auto;
            display:flex;
            flex-direction:column;
            width:900px;
            text-align:center;
            padding:20px;
            border-radius:8px;
            background:#fff;
            margin-top: 20px;
        }
        .card.hidden{
            display:none;
        }
        .profile-info{
            display:none;
            flex-direction:column;
            width:900px;
            padding:20px;
            border-radius:8px;
            background:#fff;
            margin:auto;
            margin-top: 20px;
        }
        .profile-info.active{
            display:flex;
        }
        .profile-info h3{
            margin-bottom:15px;
            color:#333;
            font-size:28px;
        }
        .profile-field{
            margin:15px 0;
            text-align:left;
            padding:15px;
            background:#f9f9f9;
            border-radius:6px;
        }
        .profile-field label{
            display:block;
            font-weight:600;
            color:#5b86e5;
            margin-bottom:8px;
            font-size:14px;
        }
        .profile-field p{
            font-size:18px;
            margin:0;
            color:#333;
        }
        .back-button{
            align-self:flex-start;
            padding:10px 20px;
            font-size:16px;
            cursor:pointer;
            background:#6c757d;
            border-radius:6px;
            color:#fff;
            border:none;
            margin-bottom:20px;
            margin-top:0;
        }
        .back-button:hover{
            background:#5a6268;
        }
        .users-container{
            display:none;
            flex-direction:column;
            width:900px;
            padding:20px;
            border-radius:8px;
            background:#fff;
            margin:auto;
            margin-top: 20px;
        }
        .users-container.active{
            display:flex;
        }
        .users-container h3{
            margin-bottom:20px;
            color:#333;
            font-size:28px;
        }
        .users-table{
            width:100%;
            border-collapse:collapse;
            margin-bottom:20px;
        }
        .users-table thead{
            background:#5b86e5;
            color:#fff;
        }
        .users-table th{
            padding:15px;
            text-align:left;
            font-weight:600;
        }
        .users-table td{
            padding:15px;
            border-bottom:1px solid #ddd;
        }
        .users-table tbody tr:hover{
            background:#f9f9f9;
        }
        .view-btn{
            padding:8px 15px;
            font-size:14px;
            background:#28a745;
            margin-top:0;
        }
        .view-btn:hover{
            background:#218838;
        }
        .user-detail{
            display:none;
            flex-direction:column;
            width:900px;
            padding:20px;
            border-radius:8px;
            background:#fff;
            margin:auto;
            margin-top: 20px;
        }
        .user-detail.active{
            display:flex;
        }
        .user-detail h3{
            margin-bottom:15px;
            color:#333;
            font-size:28px;
        }
    </style>
</head>

<body>
    <!-- Welcome Card -->
    <div class="card" id="welcomeCard">
        <p> Welcome Admin, <?php echo isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : 'Admin'; ?>! You are logged in as Administrator. </p>
        <button onclick="showSection('profile')">My Profile</button>
        <button onclick="showSection('users')">All Users</button>
    </div>
    
    <!-- Admin Profile Section -->
    <div class="profile-info" id="profileInfo">
        <button class="back-button" onclick="showSection('welcome')">← Back</button>
        <h3>Your Admin Profile</h3>
        <div class="profile-field">
            <label>Name:</label>
            <p><?php echo isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : 'N/A'; ?></p>
        </div>
        <div class="profile-field">
            <label>Email:</label>
            <p><?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : 'N/A'; ?></p>
        </div>
        <div class="profile-field">
            <label>Role:</label>
            <p>Administrator</p>
        </div>
    </div>
    
    <!-- Users List Section -->
    <div class="users-container" id="usersContainer">
        <button class="back-button" onclick="showSection('welcome')">← Back</button>
        <h3>All Registered Users</h3>
        <table class="users-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($users) > 0): ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['id']); ?></td>
                            <td><?php echo htmlspecialchars($user['name']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <span style="background: <?php echo $user['role'] === 'admin' ? '#5b86e5' : '#28a745'; ?>; color: white; padding: 5px 10px; border-radius: 4px;">
                                    <?php echo ucfirst(htmlspecialchars($user['role'])); ?>
                                </span>
                            </td>
                            <td>
                                <button class="view-btn" onclick="viewUserDetail(<?php echo htmlspecialchars(json_encode($user)); ?>)">View</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align:center; padding:20px;">No users found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- User Detail Section -->
    <div class="user-detail" id="userDetail">
        <button class="back-button" onclick="showSection('users')">← Back to Users</button>
        <h3>User Details</h3>
        <div class="profile-field">
            <label>ID:</label>
            <p id="detailId">N/A</p>
        </div>
        <div class="profile-field">
            <label>Name:</label>
            <p id="detailName">N/A</p>
        </div>
        <div class="profile-field">
            <label>Email:</label>
            <p id="detailEmail">N/A</p>
        </div>
        <div class="profile-field">
            <label>Role:</label>
            <p id="detailRole">N/A</p>
        </div>
    </div>
    
    <script>
        function showSection(section){
            // Hide all sections
            document.getElementById('welcomeCard').classList.remove('active');
            document.getElementById('profileInfo').classList.remove('active');
            document.getElementById('usersContainer').classList.remove('active');
            document.getElementById('userDetail').classList.remove('active');
            
            document.getElementById('welcomeCard').classList.add('hidden');
            document.getElementById('profileInfo').classList.add('hidden');
            document.getElementById('usersContainer').classList.add('hidden');
            document.getElementById('userDetail').classList.add('hidden');
            
            // Show selected section
            switch(section){
                case 'welcome':
                    document.getElementById('welcomeCard').classList.remove('hidden');
                    break;
                case 'profile':
                    document.getElementById('profileInfo').classList.remove('hidden');
                    document.getElementById('profileInfo').classList.add('active');
                    break;
                case 'users':
                    document.getElementById('usersContainer').classList.remove('hidden');
                    document.getElementById('usersContainer').classList.add('active');
                    break;
            }
        }
        
        function viewUserDetail(user){
            document.getElementById('detailId').textContent = user.id;
            document.getElementById('detailName').textContent = user.name;
            document.getElementById('detailEmail').textContent = user.email;
            document.getElementById('detailRole').textContent = user.role.charAt(0).toUpperCase() + user.role.slice(1);
            
            document.getElementById('userDetail').classList.add('active');
            document.getElementById('usersContainer').classList.remove('active');
        }
    </script>
</body>

</html>
