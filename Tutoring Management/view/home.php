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
            Group BY c.CourseID, c.CourseName ORDER BY c.CourseName ASC LIMIT 6;";

   $result = mysqli_query($connection, $query);
   $courses = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Home Page</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
   <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/home.css">

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

<section class="home-grid">
   <h1 class="heading">welcome to our tutoring platform</h1>

   <div class="tutor-message">
      <h3 class="title">Become a Tutor Today</h3><br>
      <p>
         Join us at Learning Sphere, where your knowledge and passion can light the path for others in their academic journey.<br> 
         As a tutor with us, you have the unique opportunity to make a significant impact, helping students overcome challenges and achieve their full potential. 
         Beyond the reward of seeing your learners succeed, tutoring offers you a chance to refine your understanding, enhance your skills, and connect with a vibrant community dedicated to mutual growth and excellence. 
         Embrace the opportunity to shape the future, one lesson at a time, and discover how much you can grow alongside your students. 
         Become a part of Learning Sphere today and turn your knowledge into a powerful tool for change. 
      </p>
      <a href="../view/tutor_register.php" class="become-tutor">Become Tutor</a>
   </div>

</section>

<section class="courses">

   <h1 class="heading">Available Courses</h1>
   
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

   <div class="more-btn">
      <a href="../view/courses.php" class="inline-option-btn">view all courses</a>
   </div>
</section>

<!-- custom js file link  -->
<script src="../js/script.js"></script>
   
</body>
</html>