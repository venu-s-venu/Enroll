<?php
session_start();
?>
<!DOCTYPE html> 
<html lang="en">

<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Page</title>
    <link rel="stylesheet" href="style1.css">
</head>

<body>
    <div class="card" id="welcomeCard">
    <p> Welcome, <?php echo isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : 'User'; ?>! You have successfully logged in. </p>
    <button onclick="toggleProfile()">Profile</button>
    </div>
    
    <div class="profile-info" id="profileInfo">
        <button class="back-button" onclick="toggleProfile()">← Back</button>
        <h3>Your Profile</h3>
        <div class="profile-field">
            <label>Name:</label>
            <p><?php echo isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : 'N/A'; ?></p>
        </div>
        <div class="profile-field">
            <label>Email:</label>
            <p><?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : 'N/A'; ?></p>
        </div>
    </div>
    
    <script>
        function toggleProfile(){
            const profileInfo = document.getElementById('profileInfo');
            const welcomeCard = document.getElementById('welcomeCard');
            
            profileInfo.classList.toggle('active');
            welcomeCard.classList.toggle('hidden');
        }
    </script>
</body>

</html>


