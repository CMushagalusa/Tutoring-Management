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

   // Fetch the courses registered by the tutor
   $registeredCourses = [];
   $courseQuery = "SELECT Courses.CourseID, Courses.CourseName, Courses.CourseCode FROM Tutor_Courses
                  JOIN Courses ON Tutor_Courses.CourseID = Courses.CourseID WHERE Tutor_Courses.TutorID = ?";

   if ($statement = $connection->prepare($courseQuery)) {
       $statement->bind_param("i", $tutorID);
       $statement->execute();
       $result = $statement->get_result();
       while ($course = $result->fetch_assoc()) {
           $registeredCourses[] = $course;
       }
       $statement->close();
   }

   // Fetch the tutoring sessions details
   $tutoringSessions = [];
   $sessionQuery = "SELECT Courses.CourseName, Session_Type.TypeName AS SessionType, Tutoring_Sessions.SessionDate, Tutoring_Sessions.Location, Tutoring_Sessions.Duration, Tutoring_Sessions.MaxParticipants
                     FROM Tutoring_Sessions JOIN Courses ON Tutoring_Sessions.CourseID = Courses.CourseID
                     JOIN Session_Type ON Tutoring_Sessions.TypeID = Session_Type.TypeID WHERE Tutoring_Sessions.TutorID = ?
                     ORDER BY Tutoring_Sessions.SessionDate DESC";
   
   if ($statement = $connection->prepare($sessionQuery)) {
       $statement->bind_param("i", $tutorID);
       $statement->execute();
       $result = $statement->get_result();
       while ($session = $result->fetch_assoc()) {
           $tutoringSessions[] = $session;
       }
       $statement->close();
   }

   // Correlate sessions to their courses
   foreach ($registeredCourses as $key => $course) {
       $registeredCourses[$key]['sessions'] = [];
       foreach ($tutoringSessions as $session) {
           if ($session['CourseName'] == $course['CourseName']) {
               $registeredCourses[$key]['sessions'][] = $session;
           }
       }
   }

   // Fetch types from database
   $sessionsQuery = "SELECT TypeID, TypeName FROM Session_Type";
   $sessionsResult = mysqli_query($connection, $sessionsQuery);
   $sessions = mysqli_fetch_all($sessionsResult, MYSQLI_ASSOC);

   // Fetch courses from database for tutor registration
   $coursesQuery = "SELECT CourseID, CourseName FROM courses";
   $coursesResult = mysqli_query($connection, $coursesQuery);
   $courses = mysqli_fetch_all($coursesResult, MYSQLI_ASSOC);

   $connection->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Tutor Dashboard</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
   <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/tutor_dashboard.css">

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
      <div class="right-content">
         <form action="" method="POST" class="search-tutor">
            <input type="text" name="search_box" placeholder="Search Tutors..." required maxlength="100">
            <button type="submit" class="fas fa-search" name="search_tutor"></button>
         </form>
         <a href="../view/tutor_register.php" class="become-tutor">Add a Course</a>
      </div>
   </div>
   
   <div class="box-container">
      <?php foreach ($registeredCourses as $course): ?>
      <div class="box">
         <div class="tutor">
            <span><?php echo htmlspecialchars($course['CourseCode']); ?></span>
            <h3><?php echo htmlspecialchars($course['CourseName']); ?></h3>
         </div>
         <?php if (!empty($course['sessions'])): ?>
         <div class="session-details">
            <?php foreach ($course['sessions'] as $session): ?>
            <div class="session-info">
               <p>Session Type: <span><?php echo htmlspecialchars($session['SessionType']); ?></span></p>
               <p>Date: <span><?php echo htmlspecialchars($session['SessionDate']); ?></span></p>
               <p>Duration: <span><?php echo htmlspecialchars($session['Duration']); ?></span></p>
               <p>Location: <span><?php echo htmlspecialchars($session['Location']); ?></span></p>
               <p>Maximum Participants: <span><?php echo htmlspecialchars($session['MaxParticipants']); ?></span></p>
            </div>
            <?php endforeach; ?>
         </div>
         <?php else: ?>
            <p><span>No sessions available for this course!!!</span></p>
         <?php endif; ?>
         <a id="set-session" class="inline-btn set-session-btn" data-courseid="<?php echo $course['CourseID']; ?>">Set Session</a>
      </div>
      <?php endforeach; ?>
   </div>
</section>

<div id="create_session" class="popupBox" style="display: none;">
   <form action="../actions/create_session.php" method="POST" enctype="multipart/form-data">
      <h3>Session Details</h3>
      <input type="hidden" name="TutorID" value="<?php echo $_SESSION['TutorID']; ?>">
      <input type="hidden" name="CourseID" id="popupCourseID" value="">
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
            <input type="text" pattern="^[A-Za-z0-9 ]+$" placeholder="Location" name="Location" id="Location" class="box" required>
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

         <button type="submit" class="set-session">Set Session</button>
      </div>
   </form>

</div>

<!-- custom js file link  -->
<script src="../js/script.js"></script>
<script>
   document.addEventListener('DOMContentLoaded', function() {
      var sessionButtons = document.querySelectorAll('.set-session-btn');
      sessionButtons.forEach(function(button) {
         button.addEventListener('click', function() {
            document.getElementById('popupCourseID').value = this.getAttribute('data-courseid');
            document.getElementById('create_session').style.display = 'block';
         });
      });
   });
</script>
   
</body>
</html>