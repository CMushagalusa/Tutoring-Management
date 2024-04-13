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
   <title>Contact Us</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/contact.css">

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

<section class="contact">

   <div class="row">

      <div class="image">
         <img src="../images/contact-img.svg" alt="">
      </div>

      <form action="" method="POST">
         <h3>Get in Touch</h3>
         <input type="text" placeholder="Your Name" name="name" required class="box">
         <input type="email" placeholder="Your Email" name="email" required class="box">
         <input type="number" placeholder="Your number" name="number" required class="box">
         <textarea name="msg" class="box" placeholder="Your message" required maxlength="1000" cols="30" rows="10"></textarea>
         <input type="submit" value="send message" class="inline-btn" name="submit">
      </form>

   </div>

   <div class="box-container">

      <div class="box">
         <i class="fas fa-phone"></i>
         <h3>Phone Number</h3>
         <a href="tel:+243 825 167 243">+243 825 167 243</a>
         <a href="tel:+233 256 284 489">+233 256 284 489</a>
      </div>
      
      <div class="box">
         <i class="fas fa-envelope"></i>
         <h3>Email Address</h3>
         <a href="mailto:clovis.cirubakaderha@ashesi.edu.gh">clovis.cirubakaderha@ashesi.edu.gh</a>
         <a href="mailto:cloviscirubakaderha@gmail.com">cloviscirubakaderha@gmail.com</a>
      </div>

      <div class="box">
         <i class="fas fa-map-marker-alt"></i>
         <h3>Office Address</h3>
         <a href="https://www.bing.com/ck/a?!&&p=ddd15b8a0e943accJmltdHM9MTcxMjE4ODgwMCZpZ3VpZD0xMmQ1YmM3Ny0xYmQ0LTY5YTEtMGEzOS1hODU3MWFjOTY4Y2ImaW5zaWQ9NTg5OA&ptn=3&ver=2&hsh=3&fclid=12d5bc77-1bd4-69a1-0a39-a8571ac968cb&u=a1L21hcHM_Jm1lcGk9MTI2fkVkdWNhdGlvbn5Vbmtub3dufk1hcF9JbWFnZSZ0eT0xOCZxPWFzaGVzaSUyMHVuaXZlcnNpeSUyMG1hcCUyMGxvY2F0aW9uJnBwb2lzPTUuNzU5NDg4MTA1NzczOTI2Xy0wLjIyMDE4Mzk5ODM0NjMyODc0X2FzaGVzaSUyMHVuaXZlcnNpeSUyMG1hcCUyMGxvY2F0aW9uX34mY3A9NS43NTk0ODh-LTAuMjIwMTg0Jmx2bD0xNiZ2PTImc1Y9MSZGT1JNPU1QU1JQTA&ntb=1">University Avenue, Berekuso, Accra, Eastern Region, Ghana</a>
      </div>

   </div>

</section>

<!-- custom js file link  -->
<script src="../js/script.js"></script>

   
</body>
</html>