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
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Your Profile</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/profile.css">

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

<section class="user-profile">

   <h1 class="heading">Your Profile</h1>

   <div class="info">

      <div class="user">
         <img src="<?php echo htmlspecialchars($_SESSION['ProfileImage']); ?>"  alt="">
         <h3><?php echo htmlspecialchars($_SESSION['Fullname']); ?></h3>
         <p>Student</p>
         <a href="../view/update.php" class="inline-btn">update profile</a>
      </div>
   
      <div class="box-container">
   
         <div class="box">
            <div class="flex">
               <i class="fas fa-bookmark"></i>
               <div>
                  <span>4</span>
                  <p>saved playlist</p>
               </div>
            </div>
            <a href="#" class="inline-btn">view playlists</a>
         </div>
   
         <div class="box">
            <div class="flex">
               <i class="fas fa-heart"></i>
               <div>
                  <span>33</span>
                  <p>videos liked</p>
               </div>
            </div>
            <a href="#" class="inline-btn">view liked</a>
         </div>
   
         <div class="box">
            <div class="flex">
               <i class="fas fa-comment"></i>
               <div>
                  <span>12</span>
                  <p>videos comments</p>
               </div>
            </div>
            <a href="#" class="inline-btn">view comments</a>
         </div>
   
      </div>
   </div>

</section>












<!-- custom js file link  -->
<script src="../js/script.js"></script>

   
</body>
</html>