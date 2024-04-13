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

   // Fetching the course information from the database.
   $query = "SELECT c.CourseID, c.CourseName, COUNT(tc.TutorID) AS TutorCount
            FROM Courses c LEFT JOIN Tutor_Courses tc ON c.CourseID = tc.CourseID
            Group BY c.CourseID, c.CourseName ORDER BY c.CourseName;";

   $result = mysqli_query($connection, $query);
   $courses = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Courses</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/courses.css">

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

<section class="courses">

   <div class="top-section">
      <h1 class="heading">Our Courses</h1>
      <div class="right-content">
         <form action="" method="POST" class="search-course">
            <input type="text" id="search_box" name="search_box" placeholder="Search..." required maxlength="100">
            <button type="button" class="fas fa-search" name="search_course"></button>
         </form>
         <div id="search_results"></div>
      </div>
   </div>

   <div class="box-container">
      <?php foreach ($courses as $course): ?>
      <div class="box">
         <div class="thumb">
            <img src="../images/thumb-1.png" alt="">
            <span><?php echo $course['TutorCount']; ?> Tutors</span>
         </div>
         <h3 class="title"><?php echo htmlspecialchars($course['CourseName']); ?></h3>
         <a href="../view/course_tutors.php?courseID=<?php echo $course['CourseID']; ?>" class="inline-btn">Find Tutors</a>
      </div>
      <?php endforeach; ?>
   </div>
</section>

<!-- custom js file link  -->
<script src="../js/script.js"></script>
<script>
   document.addEventListener("DOMContentLoaded", function() {
      var searchBox = document.getElementById('search_box');
      searchBox.addEventListener('keyup', function() {
         var searchTerm = searchBox.value.toLowerCase();
         var courses = document.querySelectorAll('.box-container .box');

         courses.forEach(function(course) {
               var title = course.querySelector('.title').textContent.toLowerCase();
               if (title.includes(searchTerm)) {
                  course.style.display = '';
               } else {
                  course.style.display = 'none';
               }
         });
      });
   });
</script>

 
</body>
</html>