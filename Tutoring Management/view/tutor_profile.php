<?php
   // Start the session
   session_start();

   // Including the connection file
   include("../settings/connection.php");
   include("../function/function.php");

   // Check if the user is logged in
   if (!isset($_SESSION['StudentID'])) {
      // Redirect to login page if the user is not logged in
      header("Location:../view/login.php");
      die;
   }

   // Initializing variables.
   $tutorsInfo = [];
   $courses = [];

   // Fetching the specific course information and the tutor.
   if(isset($_GET['tutorID'])){
     $tutorID = mysqli_real_escape_string($connection, $_GET['tutorID']);

      // Fetching the specific tutor information
      $tutorQuery = "SELECT s.FirstName, s.LastName, s.Class, m.MajorName, s.Profile FROM Tutors t
                    JOIN Students s ON t.StudentID = s.StudentID JOIN Majors m ON s.MajorID = m.MajorID
                    WHERE t.TutorID = $tutorID LIMIT 1";
      
      $tutorResult = mysqli_query($connection, $tutorQuery);
      $tutorsInfo = mysqli_fetch_array($tutorResult);

      $coursesQuery = "SELECT c.CourseID, c.CourseName, ts.SessionID, ts.MaxParticipants, ts.SessionDate,
                     (SELECT COUNT(*) FROM Session_Participants sp WHERE sp.SessionID = ts.SessionID) AS ParticipantCount
                     FROM Tutor_Courses tc JOIN Courses c ON tc.CourseID = c.CourseID
                     LEFT JOIN Tutoring_Sessions ts ON c.CourseID = ts.CourseID AND ts.TutorID = tc.TutorID
                     WHERE tc.TutorID = $tutorID AND (ts.SessionDate >= CURDATE() OR ts.SessionDate IS NULL)
                     ORDER BY c.CourseName";
     
      $coursesResult = mysqli_query($connection, $coursesQuery);
      $courses = mysqli_fetch_all($coursesResult, MYSQLI_ASSOC);
   }

   else{
      echo "<script>alert('No tutor selected.'); window.location.href='../view/courses.php';</script>";
      exit;
   }
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Tutor Profile</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/tutor_profile.css">

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
         <p class="role">Student</p>
         <a href="../view/profile.php" class="btn">view profile</a>
         <div class="flex-btn">
            <a href="../view/tutor_login.php" class="option-btn">Login</a>
            <a href="../view/tutor_register.php" class="option-btn">Be Tutor</a>
         </div>
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
      <p class="role">Student</p>
      <a href="../view/profile.php" class="btn">view profile</a>
   </div>

   <nav class="navbar">
      <a href="../view/home.php"><i class="fas fa-home"></i><span>Home</span></a>
      <a href="../view/tutors.php"><i class="fas fa-user"></i><span>Tutors</span></a>
      <a href="../view/courses.php"><i class="fas fa-graduation-cap"></i><span>Courses</span></a>
      <a href="../view/about.php"><i class="fas fa-question"></i><span>About</span></a>
      <a href="../view/contact.php"><i class="fas fa-headset"></i><span>Contact Us</span></a>
      <a href="../actions/logout.php"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a>
   </nav>

</div>

<section class="teacher-profile">

   <h1 class="heading">Tutor Profile</h1>

   <div class="details">
      <div class="tutor">
         <img src="<?php echo !empty($tutorsInfo['Profile']) ? htmlspecialchars($tutorsInfo['Profile']) : '../images/pic-2.jpg'; ?>" alt="">
         <h3><?php echo htmlspecialchars($tutorsInfo['FirstName'] . ' ' . $tutorsInfo['LastName']); ?></h3>
         <span><?php echo htmlspecialchars($tutorsInfo['MajorName']); ?></span>
      </div>
      <div class="flex">
         <p>Class : <span><?php echo htmlspecialchars($tutorsInfo['Class']); ?></span></p>
         <p>Courses Taught: <span><?php echo count($courses); ?></span></p>
      </div>
   </div>

</section>

<section class="courses">

   <h1 class="heading">Courses Taught</h1>

   <div class="box-container">
      <?php foreach($courses as $course): ?>
      <div class="box">
         <div class="thumb">
            <img src="../images/thumb-1.png" alt="">
         </div>
         <h3 class="title"><?php echo htmlspecialchars($course['CourseName']); ?></h3>
         <divv class="inline-btn">
            <?php
               foreach($courses as $course) {
                  echo "Max Participants: " . (isset($course['MaxParticipants']) ? $course['MaxParticipants'] : "None") . "<br>";
                  echo "Session Date: " . (isset($course['SessionDate']) ? $course['SessionDate'] : "None") . "<br>";
                  echo "Registered: " . (isset($course['ParticipantCount']) ? $course['ParticipantCount'] : "None") . "<br>";
               }
            ?>
         </divv>
         <?php if (isset($course['SessionID']) && $course['ParticipantCount'] < $course['MaxParticipants'] && new DateTime($course['SessionDate']) > new DateTime()): ?>
         <form action="../actions/book_session.php" method="POST">
            <input type="hidden" name="SessionID" value="<?php echo $course['SessionID']; ?>">
            <input type="hidden" name="StudentID" value="<?php echo $_SESSION['StudentID']; ?>">
            <button type="submit" class="inline-btn">Book Session</button>
         </form>
         <?php else: ?>
            <p class="inline-btn">No tutoring session available at the moment.</p>
         <?php endif; ?>
      </div>
      
      <?php endforeach; ?>
   </div>
   
</section>


<!-- custom js file link  -->
<script src="../js/script.js"></script>
   
</body>
</html>