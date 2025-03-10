<?php

// Set default page title
$pageTitle = "Dashboard";
// HIDE THE USER LINK FROM THE MENU IF THE USER ROLE IS 'USER'
$user_role = $_SESSION['user_role'];

$manage_users = "";

if($user_role == 'admin'){
    $manage_users = '<li><a href="users.php"><i class="fas fa-users"></i> Users</a></li>';
}


// Check if a custom page title is provided
if (isset($pageTitleOverride)) {
    $pageTitle = $pageTitleOverride;
}

// display the sidebar
echo '<div id="sidebar">
    <h1>'.$pageTitle.'</h1>
    <ul>
        <li><a href="dashboard.php"><i class="fas fa-home"></i> Home</a></li>
        <li><a href="students.php"><i class="fas fa-user"></i> Students</a></li>
        <li><a href="courses.php"><i class="fas fa-book"></i> Courses</a></li>

        
        '. $manage_users .'        
    </ul>
    <div id="user-info">
        <p>Welcome, '.$_SESSION['fullname'].'</p>
        <p><a href="logout.php">Logout</a></p>
    </div>
</div>';

?>
