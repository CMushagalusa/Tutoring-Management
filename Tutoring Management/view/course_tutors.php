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

   // Initializing the vaariabbbles
   $course = null;
   $tutors = [];

   if (isset($_GET['courseID'])) {
      $courseID = mysqli_real_escape_string($connection, $_GET['courseID']);


      $courseQuery = "SELECT CourseID, CourseName FROM CourseS WHERE CourseID = $courseID LIMIT 1";

      $courseResult = mysqli_query($connection, $courseQuery);
      $course = mysqli_fetch_assoc($courseResult);

         // Fetch tutors for this course
      $tutorsQuery = "SELECT t.TutorID, s.FirstName, s.LastName, s.Class, m.MajorName, s.Profile, COUNT(tc.CourseID) AS CourseCount
                     FROM Tutor_Courses tc JOIN Tutors t ON tc.TutorID = t.TutorID
                     JOIN Students s ON t.StudentID = s.StudentID JOIN Majors m ON s.MajorID = m.MajorID
                     WHERE tc.CourseID = $courseID GROUP BY t.TutorID, s.FirstName, s.LastName, s.Class, m.MajorName";

      $tutorsResult = mysqli_query($connection, $tutorsQuery);
      if ($tutorsResult){

         $tutors = mysqli_fetch_all($tutorsResult, MYSQLI_ASSOC);
      }
      else {
         echo "<script>alert('Course ID is required.'); window.location.href='../view/courses.php';</script>";
      }   
   }
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Course Tutors</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/course_tutors.css">

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

<section class="course-profile">

   <h1 class="heading">Course Details</h1>

   <div class="row">
      <div class="column">
         <div class="thumb">
            <img src="../images/thumb-1.png" alt="">
         </div>
      </div>
      <div class="column">
         <div class="details">
            <h3><?php echo htmlspecialchars($course['CourseName']); ?></h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Illum minus reiciendis, error sunt veritatis exercitationem deserunt velit doloribus itaque voluptate.</p>
         </div>
      </div>
   </div>

</section>

<section class="tutors">
   
   <h1 class="heading"><?php echo htmlspecialchars($course['CourseName']); ?> tutors</h1>
   
   <div class="box-container">
      <?php foreach ($tutors as $tutor): ?>
      <div class="box">
         <div class="tutor">
            <img src="../images/pic-2.jpg" alt="">
            <div>
               <h3><?php echo htmlspecialchars($tutor['FirstName']) . ' ' . htmlspecialchars($tutor['LastName']); ?></h3>
               <span><?php echo htmlspecialchars($tutor['MajorName']); ?></span>
            </div>
         </div>
         <p>Class : <span><?php echo htmlspecialchars($tutor['Class']); ?></span></p>
         <p>Courses: <span><?php echo htmlspecialchars($tutor['CourseCount']); ?></span></p>
         <a href="../view/tutor_profile.php?tutorID=<?php echo $tutor['TutorID']; ?>" class="inline-btn">View Profile</a>
      </div>
      <?php endforeach; ?>
      <?php if(count($tutors) == 0):?>
          <p class="message">No registered tutors at the moment!!!</p><br>
          <p class="message">Come back later, we hope to have one then!!!</p>
      <?php endif;?>
   </div>
</section>

<!-- custom js file link  -->
<script src="../js/script.js"></script>

   
</body>
</html>