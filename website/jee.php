<?php
include('../src/conn.php');

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM course_details WHERE course_id = $id");
$stmt->execute();

$res  = $stmt->fetch(PDO::FETCH_ASSOC);

$data = $res['course_id'];

$ret = $conn->prepare("SELECT * FROM courses where id = $data and status = 1");
$ret->execute();

$sel = $ret->fetch(PDO::FETCH_ASSOC);
// echo "<pre>";
// print_r($sel);
// print_r($res);
// exit();

if ($res == null) {
   echo "<script>alert('please fill details for this course.');</script>";
   echo "<script>window.location.href='home';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>JEE Preparation | Radiant</title>

   <script src="https://cdn.tailwindcss.com"></script>
   <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

   <style>
      /* ===== HERO WAVE FIX (NO WHITE GAP) ===== */
      .hero-wave {
         clip-path: polygon(0 0, 100% 0, 100% 85%, 75% 92%, 50% 85%, 25% 92%, 0 85%);
      }

      /* ===== GLOW BG ===== */


      @keyframes move {
         0% {
            transform: translate(0, 0)
         }

         100% {
            transform: translate(200px, 150px)
         }
      }

      /* ===== CARD HOVER ===== */
      .card {
         transition: 0.4s;
      }

      .card:hover {
         transform: translateY(-10px) scale(1.04);
         box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
      }

      /* ===== IMAGE ZOOM ===== */
      .img-hover {
         transition: 0.6s;
      }

      .card:hover .img-hover {
         transform: scale(1.1);
      }

      /* ===== BUTTON EFFECT ===== */
      .btn {
         position: relative;
         overflow: hidden;
      }

      .btn::after {
         content: "";
         position: absolute;
         width: 0%;
         height: 100%;
         background: rgba(255, 255, 255, 0.2);
         top: 0;
         left: 0;
         transition: 0.4s;
      }

      .btn:hover::after {
         width: 100%;
      }

      /* ===== SECTION WAVE TOP ===== */
      .wave-top {
         margin-top: -80px;
      }

      /* ===== GLASS ===== */
      .glass {
         background: rgba(255, 255, 255, 0.1);
         backdrop-filter: blur(10px);
         border: 1px solid rgba(255, 255, 255, 0.2);
      }

      .lists-dicstype li {
         list-style: disc !important;
      }
   </style>

</head>

<body class="bg-[#f5f7fb]">

   <!-- HEADER -->
   <?php include 'header.php'; ?>

   <!-- ================= HERO ================= -->
   <section class="relative h-[80vh] flex items-center justify-center text-white overflow-hidden">

      <div class="absolute inset-0 bg-cover bg-center hero-wave"
         style="background-image:url('images/hero-bg.png')"></div>

      <div class="absolute inset-0 bg-[#021969]/10"></div>

      <div class="relative z-10 text-center px-6">
         <h1 class="text-4xl md:text-6xl font-bold mb-4">
            <?= $sel['title']; ?> Preparation Program
         </h1>
         <?php 
          
         if($sel['title'] == 'JEE'){
         ?>
         <p class="text-lg text-gray-200 max-w-2xl mx-auto">
            Crack IIT-JEE Mains and Advance with Chandrapur's best faculty.
         </p>
<?php }else{ ?>
 <p class="text-lg text-gray-200 max-w-2xl mx-auto">
            Crack <?= $sel['title']; ?> with Chandrapur's best faculty.
         </p>
<?php } ?>
         <a href="enquiry.php"
            class="ctaBtn inline-block mt-6 px-8 py-3 bg-red-500 text-black font-bold rounded-full shadow-lg hover:scale-105 transition duration-300">
            More information
         </a>
      </div>

   </section>

   <!-- ================= OVERVIEW ================= -->
   <section class="relative py-20 px-6 md:px-16 bg-[#DCE0EC] glow">

      <div class="max-w-6xl mx-auto text-center mb-14" data-aos="fade-up">
         <h2 class="text-3xl md:text-5xl font-bold text-[#E41C2A]">
            More information about our <?= $sel['title']; ?> program
         </h2>
         <p class="text-gray-600 mt-4">
            Designed for serious aspirants.
         </p>
      </div>

      <div class="grid md:grid-cols-1 gap-8 max-w-6xl mx-auto">

         <?php
         if ($sel) {
         ?>
            <div class="card bg-white p-6 rounded-2xl shadow-lg place-items-center" data-aos="fade-up">
               <?= $sel['description']; ?>
            </div>
         <?php } ?>

      </div>



      <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto mt-4">

         <?php
         if ($res['why_title_1']) {
         ?>
            <div class="card bg-white p-6 rounded-2xl shadow-lg" data-aos="fade-up">
               <h3 class="font-bold text-lg text-[#021969]">
                  <?= $res['why_title_1']; ?>
               </h3>
               <p class="text-gray-600 mt-2">
                  <?= $res['why_description_1']; ?>
               </p>
            </div>
         <?php } ?>
         <?php
         if ($res['why_title_2']) {
         ?>
            <div class="card bg-white p-6 rounded-2xl shadow-lg" data-aos="fade-up" data-aos-delay="100">
               <h3 class="font-bold text-lg text-[#021969]">
                  <?= $res['why_title_2']; ?>
               </h3>
               <p class="text-gray-600 mt-2">
                  <?= $res['why_description_2']; ?>
               </p>
            </div>
         <?php } ?>
         <?php
         if ($res['why_title_3']) {
         ?>
            <div class="card bg-white p-6 rounded-2xl shadow-lg" data-aos="fade-up" data-aos-delay="200">
               <h3 class="font-bold text-lg text-[#021969]">
                  <?= $res['why_title_3']; ?>
               </h3>
               <p class="text-gray-600 mt-2">
                  <?= $res['why_description_3']; ?>
               </p>
            </div>
         <?php } ?>
      </div>

   </section>

   <!-- ================= result ================= -->
   <section class="py-10 px-6 md:px-16 bg-[#fff] text-white text-center">
      <a href="jeeresult"
            class="ctaBtn inline-block mt-6 px-8 py-3 bg-red-500 text-black font-bold rounded-full shadow-lg hover:scale-105 transition duration-300">
            See Our Results
         </a>
   </section>
   <!-- <section class="py-10 px-6 md:px-16 bg-[#fff] text-white text-center">
      <a href="result?id=<?= $id ; ?>&co=<?= $sel['title']; ?>"
            class="ctaBtn inline-block mt-6 px-8 py-3 bg-red-500 text-black font-bold rounded-full shadow-lg hover:scale-105 transition duration-300">
            See Our Results
         </a>
   </section> -->

   <!-- ================= FEATURES ================= -->
   <section class="py-20 px-6 md:px-16 bg-[#021969] text-white">

      <div class="max-w-6xl mx-auto text-center mb-14" data-aos="fade-up">
         <h2 class="text-3xl md:text-5xl font-bold text-[#E41C2A]">
            Why Choose Our <?= $sel['title']; ?> Program
         </h2>
         <p class="text-gray-600 mt-4">
            Designed for serious aspirants.
         </p>
      </div>

      <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-12 items-center">

         <!-- IMAGE -->
         <?php
         if ($res['highlight_image']) {
         ?>
            <div data-aos="zoom-in">
               <img src="../src/uploads/course-details/<?= $res['highlight_image']; ?>" class="rounded-2xl shadow-xl">
            </div>
         <?php } ?>
         <!-- CONTENT -->
         <?php if ($res['program_highlight']) { ?>
            <div data-aos="fade-left">

               <h2 class="text-3xl md:text-5xl font-bold mb-6 text-[#E41C2A]">
                  Program Highlights
               </h2>

               <ul class="space-y-4 text-2xl md:text-3xl font-bold list-disc">

                  <li class="lists-dicstype"><?= $res['program_highlight']; ?></li>

               </ul>

            </div>
         <?php } ?>

      </div>

   </section>

   <!-- ================= MODULES ================= -->
   <section class="py-20 px-6 md:px-16 bg-[#E5E8F0]">

      <div class="text-center mb-14">
         <h2 class="text-3xl md:text-5xl font-bold text-[#E41C2A]">
            Our Scholership program for <?= $sel['title']; ?>
         </h2>
      </div>

      <div class="grid md:grid-cols-1 gap-8 max-w-6xl mx-auto">
         <div class="card glass p-6 rounded-2xl text-white bg-[#021969]" data-aos="flip-left">
            <h3 class="font-bold text-xl mb-4">🎓 Scholarship Details</h3>

            <ul class="space-y-3 list-disc pl-5">
               <li>Students must appear for the Scholarship Examination conducted by our institute.</li>
               <li>The examination will be of <strong>100 questions</strong>.</li>
               <li>Scholarships will be awarded based on the marks obtained in the examination.</li>
               <li>Students securing higher ranks will receive greater fee concessions.</li>
               <li>The scholarship is applicable only for the current academic session.</li>
               <li>Students must submit all required documents during admission.</li>
               <li>The scholarship amount cannot be exchanged for cash.</li>
               <li>Scholarship benefits are non-transferable.</li>
               <li>Regular attendance and good academic performance may be required to continue the scholarship.</li>
               <li>The institute reserves the right to modify scholarship rules and eligibility criteria.</li>
            </ul>

            <div class="mt-5 p-4 bg-white/10 rounded-xl">
               <h4 class="font-semibold mb-2">Scholarship Slabs</h4>
               <ul class="space-y-1 text-sm">
                  <li>🏆 90–100 Marks : Up to 50% Scholarship</li>
                  <li>🥇 80–89 Marks : Up to 30% Scholarship</li>
                  <li>🥈 70–79 Marks : Up to 20% Scholarship</li>
                  <li>🥉 60–69 Marks : Up to 10% Scholarship</li>
               </ul>
            </div>
         </div>

      </div>

   </section>

   <!-- ================= CTA ================= -->
   <section class="py-20 px-6 md:px-16 bg-[#021969] text-center text-white">

      <h2 class="text-3xl md:text-5xl font-bold mb-6">
         Start Your IIT Journey Today 🚀
      </h2>

      <p class="mb-6 text-gray-300">
         Join Radiant Coaching & achieve top ranks in JEE.
      </p>

      <a href="enquiry.php"
         class="ctaBtn inline-block px-8 py-3 bg-green-500 text-black font-bold rounded-full shadow-lg hover:scale-105 transition duration-300">
         Book a free demo
      </a>

   </section>

   <!-- FOOTER -->
   <?php include 'footer.php'; ?>

   <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
   <script>
      AOS.init({
         duration: 1000,
         once: true
      });
   </script>

</body>

</html>