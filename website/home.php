<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Radiant</title>

  <script src="https://cdn.tailwindcss.com"></script>

  <!-- AOS Animation -->
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Basic&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

  <style>
    html,
    body {
      overflow-x: hidden;
    }

    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
    }

    /* mht-cet carsh course */
    @keyframes zoom {
      0% {
        transform: scale(1);
      }

      100% {
        transform: scale(1.15);
      }
    }

    /* gallary slider */
    .card:hover {
      transform: translateY(-8px) scale(1.03);
      transition: 0.3s;
    }

    .slider {
      background-repeat: no-repeat;

    }

    @keyframes scroll {
      0% {
        transform: translateX(0);
      }

      100% {
        transform: translateX(-50%);
      }
    }

    .slider-track {
      width: max-content;
      animation: scroll 30s linear infinite;
    }

    .slider-track:hover {
      animation-play-state: paused;
    }

    .card {
      transition: 0.3s ease;
    }

    .card:hover {
      transform: translateY(-12px) scale(1.06);
    }

    /* Mobile fix for parallax */
    @media (max-width: 768px) {
      .parallax-fix {
        background-attachment: scroll !important;
      }
    }

    /* hero css */


    .cursor {
      display: inline-block;
      margin-left: 4px;
      animation: blink 1s infinite;
    }

    @keyframes blink {

      0%,
      50%,
      100% {
        opacity: 1;
      }

      25%,
      75% {
        opacity: 0;
      }
    }


    /* Background zoom animation */
    .hero-bg {
      animation: zoomBg 12s ease-in-out infinite alternate;
    }

    @keyframes zoomBg {
      0% {
        transform: scale(1);
      }

      100% {
        transform: scale(1.08);
      }
    }

    /* Fade + slide */
    .fade-up {
      opacity: 0;
      transform: translateY(40px);
      transition: all 1s ease;
    }

    .fade-up.show {
      opacity: 1;
      transform: translateY(0);
    }

    /* Button pop */
    .pop {
      opacity: 0;
      transform: scale(0.8);
      transition: all 0.6s ease;
    }

    .pop.show {
      opacity: 1;
      transform: scale(1);
    }

    /* hero sction end */




    /* our courses animation */

    /* ===== FLOATING BACKGROUND LIGHT ===== */
  

    @keyframes moveLight {
      0% {
        transform: translate(0, 0);
      }

      100% {
        transform: translate(200px, 150px);
      }
    }

    /* ===== CARD BASE ===== */
    .course-card {
      position: relative;
      border-radius: 20px;
      overflow: hidden;
      transition: all 0.5s ease;
    }

    /* ===== 3D HOVER EFFECT ===== */
    .course-card:hover {
      transform: rotateX(6deg) rotateY(-6deg) scale(1.03);
      box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
    }

    /* ===== SHINE EFFECT ===== */
    .course-card::after {
      content: "";
      position: absolute;
      top: 0;
      left: -120%;
      width: 60%;
      height: 100%;
      background: linear-gradient(120deg,
          transparent,
          rgba(255, 255, 255, 0.6),
          transparent);
      transform: skewX(-20deg);
    }

    .course-card:hover::after {
      animation: shine 0.9s;
    }

    @keyframes shine {
      100% {
        left: 130%;
      }
    }

    /* ===== IMAGE ZOOM + DEPTH ===== */
    .course-img {
      transition: transform 0.7s ease;
    }

    .course-card:hover .course-img {
      transform: scale(1.12) rotate(1deg);
    }

    /* ===== TEXT SLIDE EFFECT ===== */
    .course-content {
      transform: translateY(10px);
      transition: all 0.4s ease;
    }

    .course-card:hover .course-content {
      transform: translateY(-5px);
    }

    /* ===== ARROW MICRO ANIMATION ===== */
    .arrow {
      display: inline-block;
      transition: transform 0.3s ease;
    }

    .course-card:hover .arrow {
      transform: translateX(6px);
    }

    /* ===== BUTTON UNDERLINE EFFECT ===== */
    .explore-btn {
      position: relative;
    }

    .explore-btn::after {
      content: "";
      position: absolute;
      left: 0;
      bottom: -3px;
      width: 0%;
      height: 2px;
      background: #E41C2A;
      transition: width 0.3s ease;
    }

    .course-card:hover .explore-btn::after {
      width: 100%;
    }

    /* ===== STAGGER ENTRY ANIMATION ===== */
    .fade-up {
      opacity: 0;
      transform: translateY(40px);
      animation: fadeUp 0.8s ease forwards;
    }

    .fade-up:nth-child(1) {
      animation-delay: 0.1s;
    }

    .fade-up:nth-child(2) {
      animation-delay: 0.2s;
    }

    .fade-up:nth-child(3) {
      animation-delay: 0.3s;
    }

    .fade-up:nth-child(4) {
      animation-delay: 0.4s;
    }

    .fade-up:nth-child(5) {
      animation-delay: 0.5s;
    }

    @keyframes fadeUp {
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* our courses animation end */



    /* our result animation  */

    /* PERSPECTIVE */
    #resultsContainer {
      perspective: 1200px;
    }

    /* INITIAL STATE (hidden in depth) */
    .result-card {
      opacity: 0;
      transform: translateY(120px) translateZ(-200px) rotateX(40deg);
      transform-style: preserve-3d;
      transition: transform 0.8s ease, opacity 0.8s ease;
    }

    /* ACTIVE STATE */
    .result-card.show {
      opacity: 1;
      transform: translateY(0) translateZ(0) rotateX(0deg);
    }

    /* DEPTH LAYER INSIDE */
    .result-card .inner {
      transform: translateZ(40px);
    }

    /* IMAGE POP OUT */
    .result-card img {
      transform: translateZ(60px);
    }

    /* HOVER SMALL BOOST */
    .result-card:hover {
      transform: translateY(-8px) scale(1.03);
      box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
    }

    /* 
our result animation end */



    /* about us animation */


    /* IMAGE INITIAL ZOOM */
    .about-img {
      transform: scale(1.3);
    }

    /* TEXT SPLIT */
    .about-text span {

      opacity: 0;
      transform: translateY(30px);
    }

    /* TITLE */
    .about-title {
      opacity: 0;
      transform: translateY(50px);
    }




    /* book a demo */
    /* Background movement */
    @keyframes bgMove {
      0% {
        transform: scale(1) translate(0, 0);
      }

      50% {
        transform: scale(1.1) translate(-10px, -10px);
      }

      100% {
        transform: scale(1) translate(0, 0);
      }
    }

    .animate-bgMove {
      animation: bgMove 15s ease-in-out infinite;
    }

    /* Floating blobs */
    @keyframes float {

      0%,
      100% {
        transform: translateY(0);
      }

      50% {
        transform: translateY(-20px);
      }
    }

    .animate-float {
      animation: float 6s ease-in-out infinite;
    }

    /* Fade Up */
    @keyframes fadeUp {
      0% {
        opacity: 0;
        transform: translateY(40px);
      }

      100% {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .animate-fadeUp {
      animation: fadeUp 1s ease forwards;
    }

    /* Delay */
    .delay-200 {
      animation-delay: 0.2s;
    }

    .delay-2000 {
      animation-delay: 2s;
    }




    /* extra cariicular activities */

    /* CARD BASE */
    .card {
      perspective: 1000px;
    }

    .card-inner {
      position: relative;
      border-radius: 20px;
      overflow: hidden;
      transition: transform 0.3s ease;
    }

    /* IMAGE */
    .img {
      width: 100%;
      height: 260px;
      object-fit: cover;
      transition: transform 0.6s ease;
    }

    .card:hover .img {
      transform: scale(1.15);
    }

    /* OVERLAY */
    .overlay {
      position: absolute;
      inset: 0;
      padding: 20px;
      display: flex;
      flex-direction: column;
      justify-content: flex-end;
      background: linear-gradient(to top, rgba(0, 0, 0, 0.85), transparent);
      transition: all 0.4s ease;
    }

    .overlay h3 {
      background: #E41C2A;
      padding: 5px 10px;
      border-radius: 8px;
      display: inline-block;
      margin-bottom: 5px;
    }

    /* GLOW BORDER */
    .card-inner::before {
      content: "";
      position: absolute;
      inset: -2px;
      border-radius: 20px;
      background: linear-gradient(45deg, #E41C2A, yellow, #E41C2A);
      opacity: 0;
      transition: 0.5s;
      z-index: -1;
    }

    .card:hover .card-inner::before {
      opacity: 1;
    }

    /* FADE UP */
    @keyframes fadeUp {
      from {
        opacity: 0;
        transform: translateY(50px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .animate-fadeUp {
      animation: fadeUp 1s ease forwards;
    }

    /* FLOAT BACKGROUND */
    @keyframes float {

      0%,
      100% {
        transform: translateY(0);
      }

      50% {
        transform: translateY(-30px);
      }
    }

    .animate-float {
      animation: float 6s ease-in-out infinite;
    }

    .delay-2000 {
      animation-delay: 2s;
    }
  </style>

</head>
<?php include 'header.php'; ?>

<body class="bg-lightbg">

  <!-- ================= HERO ================= -->
  <section class="relative min-h-screen flex items-center justify-center bg-white text-white overflow-hidden px-6 md:px-16 mt-14">

    <!-- BACKGROUND -->
<!-- BACKGROUND WITH WAVE CLIP -->
<div class="absolute inset-0 overflow-hidden">

  <div class="w-full h-full bg-cover bg-center bg-no-repeat
              [clip-path:polygon(0%_0%,100%_0%,100%_85%,75%_92%,50%_85%,25%_92%,0%_85%)]"
    style="background-image:url('images/hero-bg.png')">
  </div>

</div>

    <!-- CANVAS -->
    <canvas id="eduCanvas" class="absolute inset-0 w-full h-full pointer-events-none"></canvas>

    <!-- OVERLAY -->
    <div class="absolute inset-0 bg-[#021969]/10"></div>

    <!-- CONTENT WRAPPER -->
    <div class="relative z-10 w-full max-w-7xl grid md:grid-cols-2 gap-10 items-center">

      <!-- LEFT CONTENT -->
      <div>
        <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
          Shape Your Future with <br>
          <span class="text-[#E41C2A]">
            <span id="typing"></span><span>|</span>
          </span>
        </h1>

        <p class="text-lg text-gray-200 mb-8">
          JEE • NEET • MHT-CET • Board Exams – Expert Mentorship + Proven Results
        </p>

        <div class="flex gap-4">
        <a href="enquiry.php"
   class="inline-block bg-[#E41C2A] px-8 py-3 rounded-lg font-semibold hover:bg-red-700 transition duration-300">
   Join Now
</a>

          <!-- <button class="border border-white px-8 py-3 rounded-lg hover:bg-white hover:text-black font-bold">
            Explore Courses
          </button> -->
        </div>
      </div>

      <!-- RIGHT FORM -->
      <!-- RIGHT FORM -->
<div class="backdrop-blur-xl bg-white/10 border border-white/20 rounded-2xl p-6 shadow-2xl max-w-md w-full mx-auto">

  <!-- Heading -->
  <div class="text-center mb-6">
    <h3 class="text-3xl font-bold text-[#E41C2A]">
      Book Free Demo
    </h3>

    <p class="text-sm text-gray-300">
      Quick enquiry – get a call back
    </p>
  </div>

  <!-- Form -->
  <form id="enquiryForm" class="space-y-4">

    <!-- Name -->
    <div class="relative">
      <input type="text" id="name" required
        class="peer w-full p-3 rounded-lg bg-white/20 border border-white/30 text-white placeholder-transparent focus:outline-none focus:ring-2 focus:ring-yellow-400">

      <label class="absolute left-3 top-3 text-gray-300 text-sm transition-all 
        peer-focus:-top-2 peer-focus:text-xs peer-focus:text-yellow-400 
        peer-valid:-top-2 peer-valid:text-xs bg-[#021969] px-1">

        Full Name

      </label>
    </div>

    <!-- Phone -->
    <div class="relative">
      <input type="tel" id="phone" maxlength="10" required
        class="peer w-full p-3 rounded-lg bg-white/20 border border-white/30 text-white placeholder-transparent focus:outline-none focus:ring-2 focus:ring-yellow-400">

      <label class="absolute left-3 top-3 text-gray-300 text-sm transition-all 
        peer-focus:-top-2 peer-focus:text-xs peer-focus:text-yellow-400 
        peer-valid:-top-2 peer-valid:text-xs bg-[#021969] px-1">

        Phone Number

      </label>
    </div>

    <!-- Course -->
    <select id="course"
      class="w-full p-3 rounded-lg bg-transparent border border-white/30 text-white focus:outline-none focus:ring-2 focus:ring-yellow-400">

      <option value="" class="text-black">Select Course</option>

      <option class="text-black">JEE</option>
      <option class="text-black">NEET</option>
      <option class="text-black">MHT-CET</option>
      <option class="text-black">11th & 12th</option>
      <option class="text-black">Crash Course</option>

    </select>

    <!-- Enquiry Type -->
    <select id="type"
      class="w-full p-3 rounded-lg bg-transparent border border-white/30 text-white focus:outline-none focus:ring-2 focus:ring-yellow-400">

      <option value="" class="text-black">Enquiry Type</option>

      <option class="text-black">Demo Class</option>
      <option class="text-black">More Information</option>

    </select>

    <!-- Button -->
    <div class="md:col-span-2 flex justify-center">

      <button type="submit"
        class="relative overflow-hidden bg-green-600 text-white py-3 px-8 rounded-lg font-semibold transition duration-300 hover:scale-105 hover:shadow-lg active:scale-95">

        <span class="relative z-10">
          Submit Enquiry
        </span>

        <!-- Ripple -->
        <span class="absolute inset-0 bg-white opacity-10 scale-0 hover:scale-150 transition duration-500 rounded-full"></span>

      </button>

    </div>

  </form>

</div>

    </div>
    
  </section>

  <!-- ================= our courses ================= -->
  <section class="courses-section relative bg-[#E5E8F0] text-gray-800 py-16 px-6 md:px-16 
bg-cover bg-center bg-no-repeat"
  >
  <!-- style="background-image: url('images/redbg1.png');" -->
    <!-- HEADING -->
    <div class="text-center mb-14" data-aos="fade-up">
      <h2 class="text-3xl md:text-5xl font-bold text-[#E41C2A] mb-3">
        Our Courses
      </h2>
      <p class="text-gray-800">
        Choose the right path for your future success
      </p>
    </div>

    <!-- GRID -->
    <div class="grid gap-10 md:grid-cols-2 lg:grid-cols-3">

      <!-- CARD -->
      <div data-aos="fade-up" data-aos-delay="100"
        class="course-card group bg-white/95 backdrop-blur rounded-2xl overflow-hidden 
      shadow-md hover:shadow-xl transition-all duration-500 
      hover:-translate-y-2">

        <!-- IMAGE -->
        <div class="overflow-hidden">
          <img src="images/iit-jee.jpg"
            class="course-img w-full h-48 object-cover transition duration-700 group-hover:scale-105">
        </div>

        <!-- CONTENT -->
        <div class="p-6 course-content">
          <h3 class="text-xl font-semibold text-[#E41C2A] mb-2">
            JEE Preparation
          </h3>

          <p class="text-gray-700 text-sm leading-relaxed">
            Comprehensive IIT-JEE preparation with strong concept building and test series.
          </p>

          <button class="mt-4 text-[#1B3252] font-semibold flex items-center gap-2 group explore-btn">
            Explore
            <span class="arrow transition-transform duration-300 group-hover:translate-x-1">→</span>
          </button>
        </div>
      </div>

      <!-- CARD 2 -->
      <div data-aos="fade-up" data-aos-delay="200"
        class="course-card group bg-white/95 backdrop-blur rounded-2xl overflow-hidden 
      shadow-md hover:shadow-xl transition-all duration-500 
      hover:-translate-y-2">

        <div class="overflow-hidden">
          <img src="images/NEET.png"
            class="course-img w-full h-48 object-cover transition duration-700 group-hover:scale-105">
        </div>

        <div class="p-6 course-content">
          <h3 class="text-xl font-semibold text-[#E41C2A] mb-2">
            NEET Coaching
          </h3>

          <p class="text-gray-700 text-sm leading-relaxed">
            Focused preparation with deep subject understanding and exam strategy.
          </p>

          <button class="mt-4 text-[#1B3252] font-semibold flex items-center gap-2 group explore-btn">
            Explore
            <span class="arrow transition-transform duration-300 group-hover:translate-x-1">→</span>
          </button>
        </div>
      </div>

      <!-- CARD 3 -->
      <div data-aos="fade-up" data-aos-delay="300"
        class="course-card group bg-white/95 backdrop-blur rounded-2xl overflow-hidden 
      shadow-md hover:shadow-xl transition-all duration-500 
      hover:-translate-y-2">

        <div class="overflow-hidden">
          <img src="images/mht-cet.png"
            class="course-img w-full h-48 object-cover transition duration-700 group-hover:scale-105">
        </div>

        <div class="p-6 course-content">
          <h3 class="text-xl font-semibold text-[#E41C2A] mb-2">
            MHT-CET
          </h3>

          <p class="text-gray-700 text-sm leading-relaxed">
            CET-focused learning with daily practice and mock test system.
          </p>

          <button class="mt-4 text-[#1B3252] font-semibold flex items-center gap-2 group explore-btn">
            Explore
            <span class="arrow transition-transform duration-300 group-hover:translate-x-1">→</span>
          </button>
        </div>
      </div>

      <!-- CARD 4 -->
      <div data-aos="fade-up" data-aos-delay="400"
        class="course-card group bg-white/95 backdrop-blur rounded-2xl overflow-hidden 
      shadow-md hover:shadow-xl transition-all duration-500 
      hover:-translate-y-2">

        <div class="overflow-hidden">
          <img src="images/11-12th.png"
            class="course-img w-full h-48 object-cover transition duration-700 group-hover:scale-105">
        </div>

        <div class="p-6 course-content">
          <h3 class="text-xl font-semibold text-[#E41C2A] mb-2">
            11th & 12th Foundation
          </h3>

          <p class="text-gray-700 text-sm leading-relaxed">
            Strong academic base with board + entrance integrated learning.
          </p>

          <button class="mt-4 text-[#1B3252] font-semibold flex items-center gap-2 group explore-btn">
            Explore
            <span class="arrow transition-transform duration-300 group-hover:translate-x-1">→</span>
          </button>
        </div>
      </div>

      <!-- CARD 5 -->
      <div data-aos="fade-up" data-aos-delay="500"
        class="course-card group bg-white/95 backdrop-blur rounded-2xl overflow-hidden 
      shadow-md hover:shadow-xl transition-all duration-500 
      hover:-translate-y-2">

        <div class="overflow-hidden">
          <img src="images/carsh course.png"
            class="course-img w-full h-48 object-cover transition duration-700 group-hover:scale-105">
        </div>

        <div class="p-6 course-content">
          <h3 class="text-xl font-semibold text-[#E41C2A] mb-2">
            Crash Course
          </h3>

          <p class="text-gray-700 text-sm leading-relaxed">
            Fast-track revision with high-impact practice and tests.
          </p>

          <button class="mt-4 text-[#1B3252] font-semibold flex items-center gap-2 group explore-btn">
            Explore
            <span class="arrow transition-transform duration-300 group-hover:translate-x-1">→</span>
          </button>
        </div>
      </div>

    </div>
  </section>
  <!-- form poster -->
  <section id="crashSection"
    class="relative py-20 px-6 md:px-16 overflow-hidden bg-cover bg-center bg-no-repeat"
    style="background-image: url('images/bgwhite1.jpg');">

    <!-- Overlay -->
    <div class="absolute inset-0 bg-white/10 "></div>

    <!-- Content -->
    <div class="relative z-10 max-w-6xl mx-auto text-center">

      <!-- Heading -->
      <h2 class="crashTitle text-4xl md:text-6xl font-extrabold text-[#E41C2A]">
        MHT-CET Crash Course
      </h2>

      <!-- Subtext -->
      <p class="crashSub text-lg md:text-xl mt-4 text-gray-800">
        Special Intensive Batch for Class 12 Students 🚀
      </p>

      <!-- Badge -->
      <div class="badge inline-block mt-6 px-6 py-2 bg-yellow-400 text-black font-semibold rounded-full shadow-lg">
        Batch Starts: 22nd February
      </div>

      <!-- Glass Card -->
      <div class="card mt-12 backdrop-blur-xl bg-[#E5E8F0] border border-white/20 p-10 rounded-3xl shadow-2xl">

        <h3 class="text-2xl font-bold mb-6 text-yellow-700">
          Course Highlights
        </h3>

        <!-- Features -->
        <div class="grid md:grid-cols-2 gap-6 text-left">

          <div class="feature flex items-start gap-3">
            <span class="text-green-500 text-xl">✔</span>
            <p class="text-gray-800">Daily 6 Hours Power-packed Classes (PCM/Biology)</p>
          </div>

          <div class="feature flex items-start gap-3">
            <span class="text-green-500 text-xl">✔</span>
            <p class="text-gray-800">Topic-wise Concept Building + Shortcut Tricks</p>
          </div>

          <div class="feature flex items-start gap-3">
            <span class="text-green-500 text-xl">✔</span>
            <p class="text-gray-800">Daily Chapter-wise Practice Worksheets</p>
          </div>

          <div class="feature flex items-start gap-3">
            <span class="text-green-500 text-xl">✔</span>
            <p class="text-gray-800">Regular Online MHT-CET Pattern Tests</p>
          </div>

          <div class="feature flex items-start gap-3">
            <span class="text-green-500 text-xl">✔</span>
            <p class="text-gray-800">5+ Full-Length Mock Tests with Analysis</p>
          </div>

          <div class="feature flex items-start gap-3">
            <span class="text-green-500 text-xl">✔</span>
            <p class="text-gray-800">Doubt Solving Sessions with Experts</p>
          </div>

          <div class="feature flex items-start gap-3">
            <span class="text-green-500 text-xl">✔</span>
            <p class="text-gray-800">Focused Exam Strategy & Time Management</p>
          </div>

          <div class="feature flex items-start gap-3">
            <span class="text-green-500 text-xl">✔</span>
            <p class="text-gray-800">Rank Booster Modules & Important Questions</p>
          </div>

        </div>

        <!-- CTA -->
        <div class="mt-10 text-center">
          <a href="enquiry.php"
   class="ctaBtn inline-block px-8 py-3 bg-green-500 text-black font-bold rounded-full shadow-lg hover:scale-105 transition duration-300">
   Enroll Now
</a>
        </div>

      </div>
    </div>
  </section>

  <!-- result -->
  <section class="bg-primary text-white py-16 px-6 md:px-16 bg-cover bg-no-repeat " style="background-image: url('images/ourresultbg.jpg');">
    <!-- Heading -->
    <div class="text-center mb-12">
      <h2 class="text-3xl md:text-5xl font-bold text-[#E41C2A] p-2 rounded-lg">Our Results</h2>
      <p class="text-gray-800 mt-3">Real success stories from our students</p>
    </div>

    <!-- Grid -->
    <div id="resultsContainer" class="result-wrap grid gap-8 md:grid-cols-2 lg:grid-cols-3">

      <!-- Card 1 -->
      <div class="result-card bg-white text-gray-800 rounded-2xl p-6 text-center shadow-lg hover:scale-105">
        <div class="inner">
          <img src="https://randomuser.me/api/portraits/men/32.jpg" class="result-img w-24 h-24 mx-auto rounded-full border-4 border-yellow-400 mb-4">
          <h3 class="text-xl font-bold">Rahul Sharma</h3>
          <p class="bg-[#E41C2A] rounded-lg text-white font-semibold">JEE - 98.7 Percentile</p>
          <p class="text-gray-800 mt-2 text-sm">AIR 1456 • Selected in NIT</p>
        </div>
      </div>

      <!-- Card 2 -->
      <div class="result-card bg-white text-gray-800 rounded-2xl p-6 text-center shadow-lg hover:scale-105">
        <div class="inner">
          <img src="https://randomuser.me/api/portraits/women/44.jpg" class="result-img w-24 h-24 mx-auto rounded-full border-4 border-yellow-400 mb-4">
          <h3 class="text-xl font-bold">Priya Patil</h3>
          <p class="bg-[#E41C2A]  rounded-lg text-white font-semibold">NEET - 620 Marks</p>
          <p class="text-gray-800 mt-2 text-sm">Selected in Govt Medical College</p>
        </div>
      </div>

      <!-- Card 3 -->
      <div class="result-card bg-white text-gray-800 rounded-2xl p-6 text-center shadow-lg hover:scale-105">
        <div class="inner">
          <img src="https://randomuser.me/api/portraits/men/12.jpg" class="result-img w-24 h-24 mx-auto rounded-full border-4 border-yellow-400 mb-4">
          <h3 class="text-xl font-bold">Amit Deshmukh</h3>
          <p class="bg-[#E41C2A] rounded-lg text-white font-semibold">MHT-CET - 99.2%</p>
          <p class="text-gray-800 mt-2 text-sm">Top Engineering College</p>
        </div>
      </div>

      <!-- Card 4 -->
      <div class="result-card bg-white text-gray-800 rounded-2xl p-6 text-center shadow-lg hover:scale-105">
        <div class="inner">
          <img src="https://randomuser.me/api/portraits/women/22.jpg" class="result-img w-24 h-24 mx-auto rounded-full border-4 border-yellow-400 mb-4">
          <h3 class="text-xl font-bold">Sneha Kulkarni</h3>
          <p class="bg-[#E41C2A] rounded-lg text-white font-semibold">Board - 92%</p>
          <p class="text-gray-800 mt-2 text-sm ">Topper in Science Stream</p>
        </div>
      </div>
    </div>

    <!-- Add More Button (for admin use) -->
    <!-- <div class="text-center mt-10">
    <button onclick="addResult()" class="bg-yellow-400 text-black px-6 py-3 rounded-lg font-semibold hover:bg-yellow-300">
      + Add New Result
    </button>
  </div> -->

  </section>



  <!-- enquiry -->


  <!-- ================= ABOUT ================= -->
  <section id="aboutus"
    class="about-section relative py-20 px-6 md:px-16 grid md:grid-cols-2 gap-12 items-center overflow-hidden bg-cover bg-center"
    style="background-image: url('images/bggg.png');">

    <!-- IMAGE WRAP -->
    <div class="img-wrap relative overflow-hidden rounded-2xl">

      <!-- MASK (REAL ELEMENT instead of ::before) -->
      <div class="img-mask absolute inset-0 bg-white z-10"></div>

      <img src="images/radient-9.jpeg"
        class="about-img w-full h-full object-cover">
    </div>

    <!-- CONTENT -->
    <div class="about-content">

      <h2 class="about-title text-3xl md:text-5xl font-bold text-[#E41C2A] mb-6">
        About Us
      </h2>

      <p class="about-text text-gray-700 text-base md:text-lg ">
        Radiant Coaching Classes is a leading institute dedicated to shaping the future of students preparing for competitive exams like MHT-CET, JEE, NEET, and Board exams.

        We believe that every student has the potential to achieve success with the right guidance, structured learning, and consistent practice. Our experienced faculty focuses on concept clarity, problem-solving skills, and exam-oriented preparation.

        At Radiant, we provide a disciplined and motivating environment where students can grow academically and personally. With regular tests, chapter-wise practice, and personalized attention, we ensure that every student is exam-ready and confident.

        Our mission is simple — to help students achieve top ranks and secure admissions in the best colleges.
      </p>

    </div>

  </section>



  <!-- extra-curricular -->

  <section class="relative bg-gray-950 text-white py-20 px-6 md:px-16 overflow-hidden">

    <!-- Background Glow -->
    <div class="absolute w-96 h-96 bg-red-600 opacity-20 blur-3xl rounded-full top-0 left-0 animate-float"></div>
    <div class="absolute w-96 h-96 bg-yellow-500 opacity-20 blur-3xl rounded-full bottom-0 right-0 animate-float delay-2000"></div>

    <!-- Heading -->
    <div class="text-center mb-16 animate-fadeUp">
      <h2 class="text-3xl md:text-5xl font-bold text-[#E41C2A]">
        Extra Curricular Activities
      </h2>
      <p class="text-gray-300 mt-3">
        Beyond academics – building skills, confidence & personality
      </p>
    </div>

    <!-- Cards -->
    <div class="grid gap-10 md:grid-cols-2 lg:grid-cols-3">

      <!-- CARD -->
      <div class="card group" onmousemove="tilt(event,this)" onmouseleave="resetTilt(this)">
        <div class="card-inner">

          <img src="https://images.unsplash.com/photo-1552664730-d307ca884978"
            class="img">

          <div class="overlay">
            <h3>Career Seminars</h3>
            <p>Guidance sessions by experts for career clarity and planning.</p>
          </div>

        </div>
      </div>

      <!-- CARD -->
      <div class="card group" onmousemove="tilt(event,this)" onmouseleave="resetTilt(this)">
        <div class="card-inner">
          <img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d" class="img">
          <div class="overlay">
            <h3>Skill Workshops</h3>
            <p>Hands-on sessions to improve problem-solving and technical skills.</p>
          </div>
        </div>
      </div>

      <!-- CARD -->
      <div class="card group" onmousemove="tilt(event,this)" onmouseleave="resetTilt(this)">
        <div class="card-inner">
          <img src="https://images.unsplash.com/photo-1517649763962-0c623066013b" class="img">
          <div class="overlay">
            <h3>Sports & Fitness</h3>
            <p>Activities that promote health, teamwork and discipline.</p>
          </div>
        </div>
      </div>

      <!-- CARD -->
      <div class="card group" onmousemove="tilt(event,this)" onmouseleave="resetTilt(this)">
        <div class="card-inner">
          <img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b" class="img">
          <div class="overlay">
            <h3>Competitions</h3>
            <p>Quiz, olympiads & contests to boost confidence and performance.</p>
          </div>
        </div>
      </div>

      <!-- CARD -->
      <div class="card group" onmousemove="tilt(event,this)" onmouseleave="resetTilt(this)">
        <div class="card-inner">
          <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f" class="img">
          <div class="overlay">
            <h3>Personality Development</h3>
            <p>Communication, confidence and leadership skill development.</p>
          </div>
        </div>
      </div>

      <!-- CARD -->
      <div class="card group" onmousemove="tilt(event,this)" onmouseleave="resetTilt(this)">
        <div class="card-inner">
          <img src="https://images.unsplash.com/photo-1492684223066-81342ee5ff30" class="img">
          <div class="overlay">
            <h3>Annual Events</h3>
            <p>Cultural and fun events to create a balanced learning environment.</p>
          </div>
        </div>
      </div>

    </div>
  </section>


  <!-- ================= PRODUCTS ================= -->
  <section id="products"
    class="relative w-full py-16 md:py-20
  bg-cover bg-center bg-no-repeat"
    style="background-image: url('images/gallary-bg.png');">

    <!-- OVERLAY -->
    <div class="absolute inset-0 "></div>

    <!-- CONTENT -->
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6">

      <h2 class="text-center text-[#E41C2A] rounded-lg px-6 py-3 
      text-3xl md:text-5xl font-bold  mb-10 md:mb-14">
        Gallery
      </h2>


      <!-- SLIDER -->
      <div class="overflow-hidden">

        <div class="slider-track flex gap-6 sm:gap-8 md:gap-10">

          <!-- CARD -->
          <div class="bg-[#FDFDFD]/90 backdrop-blur p-4 sm:p-5 rounded-xl shadow-lg 
          w-[220px] sm:w-64 md:w-72 flex-shrink-0 card">
            <img src="images/radient-1.jpeg"
              class="rounded-lg w-full h-40 sm:h-44 md:h-72 object-cover">
          </div>

          <div class="bg-[#FDFDFD]/90 backdrop-blur p-4 sm:p-5 rounded-xl shadow-lg 
          w-[220px] sm:w-64 md:w-72 flex-shrink-0 card">
            <img src="images/radient-2.jpeg"
              class="rounded-lg w-full h-40 sm:h-44 md:h-72 object-cover">
          </div>

          <div class="bg-[#FDFDFD]/90 backdrop-blur p-4 sm:p-5 rounded-xl shadow-lg 
          w-[220px] sm:w-64 md:w-72 flex-shrink-0 card">
            <img src="images/radient-3.jpeg"
              class="rounded-lg w-full h-40 sm:h-44 md:h-72 object-cover">
          </div>

          <div class="bg-[#FDFDFD]/90 backdrop-blur p-4 sm:p-5 rounded-xl shadow-lg 
          w-[220px] sm:w-64 md:w-72 flex-shrink-0 card">
            <img src="images/radient-4.jpeg"
              class="rounded-lg w-full h-40 sm:h-44 md:h-72 object-cover">
          </div>

          <div class="bg-[#FDFDFD]/90 backdrop-blur p-4 sm:p-5 rounded-xl shadow-lg 
          w-[220px] sm:w-64 md:w-72 flex-shrink-0 card">
            <img src="images/radient-5.jpeg"
              class="rounded-lg w-full h-40 sm:h-44 md:h-72 object-cover">
          </div>

          <div class="bg-[#FDFDFD]/90 backdrop-blur p-4 sm:p-5 rounded-xl shadow-lg 
          w-[220px] sm:w-64 md:w-72 flex-shrink-0 card">
            <img src="images/radient-6.jpeg"
              class="rounded-lg w-full h-40 sm:h-44 md:h-72 object-cover">
          </div>

          <div class="bg-[#FDFDFD]/90 backdrop-blur p-4 sm:p-5 rounded-xl shadow-lg 
          w-[220px] sm:w-64 md:w-72 flex-shrink-0 card">
            <img src="images/radient-7.jpeg"
              class="rounded-lg w-full h-40 sm:h-44 md:h-72 object-cover">
          </div>

          <div class="bg-[#FDFDFD]/90 backdrop-blur p-4 sm:p-5 rounded-xl shadow-lg 
          w-[220px] sm:w-64 md:w-72 flex-shrink-0 card">
            <img src="images/radient-8.jpeg"
              class="rounded-lg w-full h-40 sm:h-44 md:h-72 object-cover">
          </div>

          <!-- DUPLICATE (for loop effect) -->

          <div class="bg-[#FDFDFD]/90 backdrop-blur p-4 sm:p-5 rounded-xl shadow-lg 
          w-[220px] sm:w-64 md:w-72 flex-shrink-0 card">
            <img src="images/radient-9.jpeg"
              class="rounded-lg w-full h-40 sm:h-44 md:h-72 object-cover">
          </div>

          <div class="bg-[#FDFDFD]/90 backdrop-blur p-4 sm:p-5 rounded-xl shadow-lg 
          w-[220px] sm:w-64 md:w-72 flex-shrink-0 card">
            <img src="images/radinet-10.jpeg"
              class="rounded-lg w-full h-40 sm:h-44 md:h-72 object-cover">
          </div>

          <div class="bg-[#FDFDFD]/90 backdrop-blur p-4 sm:p-5 rounded-xl shadow-lg 
          w-[220px] sm:w-64 md:w-72 flex-shrink-0 card">
            <img src="images/radient-11.jpeg"
              class="rounded-lg w-full h-40 sm:h-44 md:h-72 object-cover">
          </div>

          <div class="bg-[#FDFDFD]/90 backdrop-blur p-4 sm:p-5 rounded-xl shadow-lg 
          w-[220px] sm:w-64 md:w-72 flex-shrink-0 card">
            <img src="images/radient-12.jpeg"
              class="rounded-lg w-full h-40 sm:h-44 md:h-72 object-cover">
          </div>

        </div>
      </div>

    </div>
  </section>

  <section class="bg-white py-16 px-6 md:px-16 bg-cover bg-center bg-no-repeatr" style="background-image: url('images/redbg3.png');">
    <!-- Heading -->
    <div class="text-center mb-12">
      <h2 class="text-3xl md:text-5xl font-bold text-white p-2 rounded-lg text-[#E41C2A]">
        Why Choose Us
      </h2>
      <p class="text-gray-400 mt-3">
        We don’t just teach, we build your future
      </p>
    </div>

    <!-- Grid -->
    <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">

      <!-- Card -->
      <div class="bg-white p-6 rounded-2xl shadow-lg hover:scale-105">
        <div class="text-4xl mb-4">🎯</div>
        <h3 class="text-primary font-bold">Expert Faculty</h3>
        <p class="text-gray-400 text-sm">
          Learn from experienced teachers with proven results in JEE, NEET & CET.
        </p>
      </div>

      <!-- Card -->
      <div class="bg-white p-6 rounded-2xl shadow-lg hover:scale-105">
        <div class="text-4xl mb-4">📊</div>
        <h3 class="text-primary font-bold">Proven Results</h3>
        <p class="text-gray-400 text-sm">
          Consistent top ranks and selections in IITs, Medical & top colleges.
        </p>
      </div>

      <!-- Card -->
      <div class="bg-white p-6 rounded-2xl shadow-lg hover:scale-105">
        <div class="text-4xl mb-4">📚</div>
        <h3 class="text-primary font-bold">Structured Study Plan</h3>
        <p class="text-gray-400 text-sm">
          Well-planned syllabus coverage with regular tests and revisions.
        </p>
      </div>

      <!-- Card -->
      <div class="bg-white p-6 rounded-2xl shadow-lg hover:scale-105">
        <div class="text-4xl mb-4">💬</div>
        <h3 class="text-primary font-bold">Doubt Support</h3>
        <p class="text-gray-400 text-sm">
          Dedicated doubt-solving sessions to clear every concept.
        </p>
      </div>

      <!-- Card -->
      <div class="bg-white p-6 rounded-2xl shadow-lg hover:scale-105">
        <div class="text-4xl mb-4">🏆</div>
        <h3 class="text-primary font-bold">Competitive Environment</h3>
        <p class="text-gray-400 text-sm">
          Healthy competition to push students towards excellence.
        </p>
      </div>

      <!-- Card -->
      <div class="bg-white p-6 rounded-2xl shadow-lg hover:scale-105">
        <div class="text-4xl mb-4">📍</div>
        <h3 class="text-primary font-bold">Personal Attention</h3>
        <p class="text-gray-400 text-sm">
          Small batches ensure individual focus and better learning.
        </p>
      </div>

    </div>

  </section>







  <!-- ================= FOOTER ================= -->
  <?php include 'footer.php'; ?>


  <!-- SCRIPT -->
  <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>


  <!-- mht-cet carsh course -->
  <script>
    AOS.init({
      duration: 1000,
      once: true
    });
  </script>













  <!-- hero slider new  -->
  <script>
    const words = [
      "Radiant Coaching",
      "Your Success Journey",
      "Top Rank Preparation",
      "Future Doctors & Engineers"
    ];

    let i = 0; // word index
    let j = 0; // letter index
    let current = "";
    let isDeleting = false;

    const el = document.getElementById("typing");

    function typeEffect() {
      current = words[i];

      if (isDeleting) {
        el.textContent = current.substring(0, j--);
      } else {
        el.textContent = current.substring(0, j++);
      }

      // typing complete
      if (!isDeleting && j === current.length) {
        isDeleting = true;
        setTimeout(typeEffect, 1200); // pause
        return;
      }

      // deleting complete
      if (isDeleting && j === 0) {
        isDeleting = false;
        i = (i + 1) % words.length;
      }

      setTimeout(typeEffect, isDeleting ? 50 : 100);
    }

    // start typing
    typeEffect();
  </script>


  <!-- <script>
    const canvas = document.getElementById("eduCanvas");
    const ctx = canvas.getContext("2d");

    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;

    // EDUCATION ICONS (USE YOUR OWN OR KEEP THESE)
    const iconSources = [
      "https://cdn-icons-png.flaticon.com/512/3135/3135755.png", // student
      "https://cdn-icons-png.flaticon.com/512/1048/1048953.png", // book
      "https://cdn-icons-png.flaticon.com/512/2721/2721293.png", // chemistry
      "https://cdn-icons-png.flaticon.com/512/2921/2921222.png", // math
    ];

    let icons = [];
    let particles = [];

    // LOAD IMAGES
    iconSources.forEach(src => {
      let img = new Image();
      img.src = src;
      icons.push(img);
    });

    // CREATE ICON OBJECTS ON EDGES
    function createIcons() {
      particles = [];

      for (let i = 0; i < 20; i++) {

        let side = Math.floor(Math.random() * 4);
        let x, y;

        if (side === 0) { // TOP
          x = Math.random() * canvas.width;
          y = Math.random() * 100;
        } else if (side === 1) { // BOTTOM
          x = Math.random() * canvas.width;
          y = canvas.height - Math.random() * 100;
        } else if (side === 2) { // LEFT
          x = Math.random() * 100;
          y = Math.random() * canvas.height;
        } else { // RIGHT
          x = canvas.width - Math.random() * 100;
          y = Math.random() * canvas.height;
        }

        particles.push({
          x: x,
          y: y,
          size: Math.random() * 60 + 60,
          speedX: (Math.random() - 0.5) * 0.6,
          speedY: (Math.random() - 0.5) * 0.6,
          img: icons[Math.floor(Math.random() * icons.length)],
          opacity: Math.random() * 0.6 + 0.3
        });
      }
    }

    createIcons();

    // ANIMATION
    function animate() {
      ctx.clearRect(0, 0, canvas.width, canvas.height);

      particles.forEach(p => {

        p.x += p.speedX;
        p.y += p.speedY;

        // KEEP THEM ON EDGES (DON'T ENTER CENTER)
        if (p.x > 180 && p.x < canvas.width - 180 &&
          p.y > 180 && p.y < canvas.height - 180) {

          p.x += (p.x < canvas.width / 2) ? -2 : 2;
          p.y += (p.y < canvas.height / 2) ? -2 : 2;
        }

        ctx.globalAlpha = p.opacity;
        ctx.drawImage(p.img, p.x, p.y, p.size, p.size);
        ctx.globalAlpha = 1;
      });

      requestAnimationFrame(animate);
    }

    animate();

    // RESPONSIVE
    window.addEventListener("resize", () => {
      canvas.width = window.innerWidth;
      canvas.height = window.innerHeight;
      createIcons();
    });
  </script> -->

  <!-- enquiry whatsapp redirect -->
  <script>
    function sendToWhatsApp() {
      let name = document.getElementById("name").value;
      let phone = document.getElementById("phone").value;
      let course = document.getElementById("course").value;
      let type = document.getElementById("type").value;
      let message = document.getElementById("message").value;

      if (!name || !phone) {
        alert("Please fill Name and Phone");
        return;
      }

      let text = `New Enquiry:%0A
Name: ${name}%0A
Phone: ${phone}%0A
Course: ${course}%0A
Type: ${type}%0A
Message: ${message}`;

      let url = `https://wa.me/YOUR_NUMBER?text=${text}`;

      window.open(url, "_blank");
    }
  </script>

  <!-- our courses -->

  <script>
    AOS.init({
      duration: 1000,
      once: true,
      easing: "ease-out-cubic"
    });
  </script>


  <!-- MHT-CET crash course -->

  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>

  <script>
    window.addEventListener("load", () => {

      gsap.registerPlugin(ScrollTrigger);

      // TITLE
      gsap.from("#crashTitle", {
        scrollTrigger: {
          trigger: "#crashTitle",
          start: "top 85%",
          toggleActions: "play none none none"
        },
        y: 80,
        opacity: 0,
        duration: 1
      });

      // SUBTEXT
      gsap.from("#crashSub", {
        scrollTrigger: {
          trigger: "#crashSub",
          start: "top 90%"
        },
        y: 40,
        opacity: 0,
        duration: 0.8
      });

      // BADGE
      gsap.from("#badge", {
        scrollTrigger: {
          trigger: "#badge",
          start: "top 90%"
        },
        scale: 0,
        opacity: 0,
        duration: 0.6,
        ease: "back.out(2)"
      });

      // CARD
      gsap.from("#card", {
        scrollTrigger: {
          trigger: "#card",
          start: "top 85%"
        },
        y: 100,
        opacity: 0,
        duration: 1
      });

      // FEATURES STAGGER
      gsap.from(".feature", {
        scrollTrigger: {
          trigger: "#card",
          start: "top 80%"
        },
        x: -50,
        opacity: 0,
        duration: 0.5,
        stagger: 0.15
      });

    });
  </script>



  <!-- our result -->
  <script>
    const cards = document.querySelectorAll(".result-card");

    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry, index) => {

        if (entry.isIntersecting) {

          // stagger delay (wave effect)
          setTimeout(() => {
            entry.target.classList.add("show");
          }, index * 120);

        }

      });
    }, {
      threshold: 0.2
    });

    cards.forEach(card => observer.observe(card));
  </script>

  <!-- about us -->
  <script>
    window.addEventListener("load", () => {

      gsap.registerPlugin(ScrollTrigger);

      // SPLIT TEXT
      const text = document.querySelector(".about-text");
      const words = text.innerText.split(" ");
      text.innerHTML = words.map(word => `<span>${word} </span>`).join("");

      // MASK REVEAL (FIXED)
      gsap.to(".img-mask", {
        scrollTrigger: {
          trigger: "#aboutus",
          start: "top 80%"
        },
        x: "100%",
        duration: 1.2,
        ease: "power3.out"
      });

      // IMAGE ZOOM
      gsap.to(".about-img", {
        scrollTrigger: {
          trigger: "#aboutus",
          start: "top 80%"
        },
        scale: 1,
        duration: 1.5
      });

      // TITLE
      gsap.to(".about-title", {
        scrollTrigger: {
          trigger: ".about-title",
          start: "top 85%"
        },
        opacity: 1,
        y: 0,
        duration: 1
      });

      // TEXT STAGGER
      gsap.to(".about-text span", {
        scrollTrigger: {
          trigger: ".about-text",
          start: "top 85%"
        },
        opacity: 1,
        y: 0,
        stagger: 0.02,
        duration: 0.5
      });

    });
  </script>

  <!-- extra curricular activities -->
  <script>
    function tilt(e, el) {
      const rect = el.getBoundingClientRect();
      const x = e.clientX - rect.left;
      const y = e.clientY - rect.top;

      const centerX = rect.width / 2;
      const centerY = rect.height / 2;

      const rotateX = ((y - centerY) / 20);
      const rotateY = ((centerX - x) / 20);

      el.querySelector('.card-inner').style.transform =
        `rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale(1.05)`;
    }

    function resetTilt(el) {
      el.querySelector('.card-inner').style.transform =
        "rotateX(0) rotateY(0) scale(1)";
    }
  </script>


<!-- whatsapp redirect -->
<script>

document.getElementById("enquiryForm").addEventListener("submit", function(e){

    e.preventDefault();

    // VALUES
    let name = document.getElementById("name").value.trim();
    let phone = document.getElementById("phone").value.trim();
    let course = document.getElementById("course").value;
    let type = document.getElementById("type").value;

    // ===== VALIDATION =====

    // NAME VALIDATION
    if(name.length < 3){
        alert("Please enter valid full name");
        return;
    }

    // PHONE VALIDATION
    let phonePattern = /^[6-9]\d{9}$/;

    if(!phonePattern.test(phone)){
        alert("Please enter valid 10 digit mobile number");
        return;
    }

    // COURSE VALIDATION
    if(course === ""){
        alert("Please select course");
        return;
    }

    // TYPE VALIDATION
    if(type === ""){
        alert("Please select enquiry type");
        return;
    }

    // ===== WHATSAPP MESSAGE =====

    let whatsappMessage =
`🎓 *New Enquiry - Radiant Coaching* %0A%0A
👤 Name: ${name}%0A
📞 Phone: ${phone}%0A
📚 Course: ${course}%0A
📝 Enquiry Type: ${type}`;

    // YOUR WHATSAPP NUMBER
    let whatsappNumber = "918421783479";

    // REDIRECT TO WHATSAPP
    window.open(
      `https://wa.me/${whatsappNumber}?text=${whatsappMessage}`,
      "_blank"
    );

});

</script>
</body>

</html>