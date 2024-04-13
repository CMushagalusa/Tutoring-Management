<?php
    // Start the session
    session_start();

    // Including the connection file
    include("../settings/connection.php");
    include("../function/function.php");
    include("../function/tutor_function.php");

    // Check if the user is logged in
    if (!isset($_SESSION['StudentID'])) {
        // Redirect to login page if the user is not logged in
        header("Location:../view/login.php");
        die;
    }

    elseif(!isset($_SESSION['TutorID'])){
        header("Location:../view/tutor_login.php");
        die;
    }

    $tutorID = $_SESSION['TutorID'];

    // Query to fetch sessions along with the enrolled students
    $query = "SELECT Tutoring_Sessions.SessionID, Courses.CourseName, Courses.CourseCode, CONCAT(Students.FirstName, ' ', Students.LastName) AS StudentName
              FROM Tutoring_Sessions
              JOIN Session_Participants ON Tutoring_Sessions.SessionID = Session_Participants.SessionID
              JOIN Students ON Session_Participants.StudentID = Students.StudentID
              JOIN Courses ON Tutoring_Sessions.CourseID = Courses.CourseID
              WHERE Tutoring_Sessions.TutorID = ?
              ORDER BY Courses.CourseName, Tutoring_Sessions.SessionDate DESC";

    if ($statement = $connection->prepare($query)) {
        $statement->bind_param("i", $tutorID);
        $statement->execute();
        $result = $statement->get_result();

        // Organize data by session for display
        $sessionParticipants = [];
        while ($row = $result->fetch_assoc()) {
            $sessionParticipants[$row['SessionID']]['CourseName'] = $row['CourseName'];
            $sessionParticipants[$row['SessionID']]['CourseCode'] = $row['CourseCode'];
            $sessionParticipants[$row['SessionID']]['Participants'][] = $row['StudentName'];
        }
        $statement->close();
    }
    $connection->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sessions Management</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <!-- custom css file link  -->
    <link rel="stylesheet" href="../css/tutor_management.css">

</head>
<body>

<header class="header">
   
    <section class="flex">

        <p class="logo">Learning Sphere</p>

        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <div id="search-btn" class="fas fa-search"></div>
            <div id="user-btn" class="fas fa-user"></div>
            <div id="toggle-btn" class="fas fa-sun"></div>
        </div>

        <div class="profile">
            <img class="image" src="<?php echo htmlspecialchars($_SESSION['ProfileImage']); ?>"  alt="">
            <h5 class="name"><?php echo htmlspecialchars($_SESSION['Fullname']); ?></h5>
            <p class="role">Tutor</p>
            <a href="../view/profile.php" class="btn">view profile</a>
        </div>

    </section>

</header>   

<div class="side-bar">

    <div id="close-btn">
        <i class="fas fa-times"></i>
    </div>

    <div class="profile">
        <img class="image" src="<?php echo htmlspecialchars($_SESSION['ProfileImage']); ?>"  alt="">
        <h3 class="name"><?php echo htmlspecialchars($_SESSION['Fullname']); ?></h3>
        <p class="role">Tutor</p>
        <a href="../view/profile.php" class="btn">view profile</a>
    </div>

    <nav class="navbar">
        <a href="../view/tutor_dashboard.php"><i class="fas fa-home"></i><span>Home</span></a>
        <a href="../view/tutor_management.php"><i class="las la-clipboard-list"></i><span>Manage Sessions</span></a>
        <a href="../view/participants.php"><i class="fas fa-users"></i><span>Sessions Participants</span></a>
        <a href="../view/about.php"><i class="fas fa-question"></i><span>About</span></a>
        <a href="../view/contact.php"><i class="fas fa-headset"></i><span>Contact Us</span></a>
        <a href="../actions/tutor_logout.php"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a>
    </nav>

</div>

<section class="tutors">
   
    <div class="top-section">
      <h1 class="heading">Your Courses</h1>
    </div>
   
    <div class="box-container">
        <?php foreach ($sessionParticipants as $sessionID => $sessionInfo): ?>
        <div class="box">
            <div class="tutor">
                <span><?php echo htmlspecialchars($sessionInfo['CourseCode']); ?></span>
                <h3><?php echo htmlspecialchars($sessionInfo['CourseName']); ?></h3>
            </div>
            <div class="session-details">
                <div class="session-info">
                    <p>Participants</p>
                    <ul>
                        <?php foreach ($sessionInfo['Participants'] as $participant): ?>
                        <li><?php echo htmlspecialchars($participant); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php if (empty($sessionParticipants)): ?>
            <p><span>No Students registered for your sessions yet.</span></p>
        <?php endif; ?>
    </div>
</section>
<!-- custom js file link  -->
<script src="../js/script.js"></script>
<script src="../js/management.js"></script>
   
</body>
</html>