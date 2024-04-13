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

    // Fetch tutoring sessions for the logged-in tutor
    $sessions = [];
    $query = "SELECT Tutoring_Sessions.SessionID, Courses.CourseName, Session_Type.TypeName AS SessionType, Tutoring_Sessions.SessionDate, Tutoring_Sessions.Location, Tutoring_Sessions.Duration, Tutoring_Sessions.StartingTime, Tutoring_Sessions.MaxParticipants
            FROM Tutoring_Sessions JOIN Courses ON Tutoring_Sessions.CourseID = Courses.CourseID
            JOIN Session_Type ON Tutoring_Sessions.TypeID = Session_Type.TypeID WHERE Tutoring_Sessions.TutorID = ?
            ORDER BY Tutoring_Sessions.SessionDate DESC";

    if ($statement = $connection->prepare($query)) {
        $statement->bind_param("i", $tutorID);
        $statement->execute();
        $result = $statement->get_result();

        while ($row = $result->fetch_assoc()) {
            $sessions[] = $row;
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
        <h1 class="heading">Sessions Details</h1>
        <div class="right-content">
            <form action="" method="POST" class="search-tutor">
                <input type="text" name="search_box" placeholder="Search Tutors..." required maxlength="100">
                <button type="submit" class="fas fa-search" name="search_tutor"></button>
            </form>
            <a href="../view/tutor_register.php" class="become-tutor">Add a Course</a>
        </div>
    </div>
   
    <div class="box-container">
        <div>
            <table width="100%">
                <thead>
                    <tr>
                        <th><span class="las la-sort"></span> Course</th>
                        <th><span class="las la-sort"></span> Session Type</th>
                        <th><span class="las la-sort"></span> Date</th>
                        <th><span class="las la-sort"></span> Time</th>
                        <th><span class="las la-sort"></span> Location</th>
                        <th><span class="las la-sort"></span> Duration</th>
                        <th><span class="las la-sort"></span> Maximum Participent</th>
                        <th><span class="las la-sort"></span> Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sessions as $session): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($session['CourseName']); ?></td>
                        <td><?php echo htmlspecialchars($session['SessionType']); ?></td>
                        <td><?php echo htmlspecialchars($session['SessionDate']); ?></td>
                        <td><?php echo htmlspecialchars($session['StartingTime']); ?></td>
                        <td><?php echo htmlspecialchars($session['Location']); ?></td>
                        <td><?php echo htmlspecialchars($session['Duration']); ?></td>
                        <td><?php echo htmlspecialchars($session['MaxParticipants']); ?></td>
                        <td>
                            <button class="las la-pen edit" data-session-id="<?php echo $session['SessionID']; ?>"></button>
                            <button class="las la-trash delete" data-session-id="<?php echo $session['SessionID']; ?>"></button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(count($sessions) == 0):?>
                        <tr>
                            <td colspan="7">You have not set any session!!!</td>
                        </tr>
                    <?php endif;?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="edit_session" class="popupBox" style="display: none;">
        <form action="../actions/create_session.php" method="POST" enctype="multipart/form-data">
            <h3>Session Details</h3>
            <input type="hidden" name="SessionID" id="popupSessionID" value="">
            <div class="session">
                <div class="form-field">
                    <select name="type" class="choose" required>
                        <option value="">Session Type</option>
                        <?php foreach ($sessions as $session): ?>
                        <option value="<?php echo htmlspecialchars($session['TypeID']); ?>">
                            <?php echo htmlspecialchars($session['TypeName']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-field">
                    <input type="date" name="SessionDate" id="SessionDate" class="box" required>
                    <i class='bx bxs-user'></i>
                </div>

                <div class="form-field">
                    <input type="time" name="SessionTime" id="SessionTime" class="box" required>
                    <i class='bx bxs-time'></i>
                </div>

                <div class="form-field">
                    <input type="text" pattern="^[A-Za-z0-9]+$" placeholder="Location" name="Location" id="Location" class="box" required>
                    <i class='bx bxs-user'></i>
                </div>

                <div class="form-field">
                    <input type="number" min="1" placeholder="Duration (minutes)" name="duration" id="duration" class="box" required>
                    <i class='bx bxs-hourglass'></i>
                </div>

                <div class="form-field">
                    <input type="number" min="1" placeholder="Maximum Participants" name="maxParticipants" id="maxParticipants" class="box" required>
                    <i class='bx bxs-group'></i>
                </div>
                <button type="submit" class="edit-session">Edit Session</button>
            </div>
        </form>
    </div>

    <!-- Delete Session Confirmation-->
    <div id="deleteSession" class="popupBox" style="display: none;">
        <form action="../actions/delete_session.php" method="POST">
            <h2>Delete Session</h2>
            <span class="closeButton">&times;</span>
            <input type="hidden" name="SessionID" id="deleteSessionID" value="">
            <p>Are you sure you want to delete this session?</p>
            <button class="delete-btn" type="submit">Delete</button>
        </form>
    </div>

</section>

<!-- custom js file link  -->
<script src="../js/script.js"></script>
<script src="../js/management.js"></script>
   
</body>
</html>