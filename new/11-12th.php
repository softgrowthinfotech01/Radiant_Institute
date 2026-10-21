<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>11th & 12th Foundation | Radiant</title>

<script src="https://cdn.tailwindcss.com"></script>
<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

<style>

/* HERO BG ANIMATION */
.hero-bg {
  animation: zoomBg 12s ease-in-out infinite alternate;
}
@keyframes zoomBg {
  0% { transform: scale(1); }
  100% { transform: scale(1.08); }
}

/* CARD HOVER */
.card {
  transition: all 0.4s ease;
}
.card:hover {
  transform: translateY(-10px) scale(1.05);
  box-shadow: 0 25px 50px rgba(0,0,0,0.2);
}

/* SUBJECT GLOW */
.subject-card {
  position: relative;
  overflow: hidden;
}
.subject-card::before {
  content: "";
  position: absolute;
  inset: -2px;
  background: linear-gradient(45deg,#E41C2A,yellow,#E41C2A);
  opacity: 0;
  transition: 0.4s;
}
.subject-card:hover::before {
  opacity: 1;
}

</style>

</head>

<body class="bg-gray-50">

<!-- HEADER -->
<?php include 'header.php'; ?>


<!-- HERO -->
<section class="relative h-[80vh] flex items-center justify-center text-center text-white overflow-hidden">

  <div class="absolute inset-0 hero-bg bg-cover bg-center mt-20"
       style="background-image:url('images/11-12th.png')"></div>

  <div class="absolute inset-0 bg-[#021969]/10"></div>

  <!-- <div class="relative z-10 px-6">
    <h2 class="text-4xl md:text-6xl font-bold mb-4" data-aos="fade-up">
      11th & 12th Foundation Program
    </h2>
    <p class="text-lg md:text-xl text-gray-200" data-aos="fade-up" data-aos-delay="200">
      Build strong concepts for Boards + JEE/NEET/CET together
    </p>
  </div> -->

</section>

<!-- OVERVIEW -->
<section class="py-16 px-6 md:px-16 text-center">
  <h2 class="text-3xl md:text-5xl font-bold text-[#E41C2A] mb-6" data-aos="fade-up">
    Program Overview
  </h2>

  <p class="max-w-3xl mx-auto text-gray-700 text-lg" data-aos="fade-up" data-aos-delay="200">
    Our integrated 11th & 12th program ensures complete board preparation along with 
    entrance exam readiness. We focus on building a strong foundation, logical thinking, 
    and consistent performance improvement.
  </p>
</section>

<!-- FEATURES -->
<section class="py-16 px-6 md:px-16 bg-white">
  <div class="grid md:grid-cols-3 gap-10">

    <div class="card bg-white p-6 rounded-2xl" data-aos="fade-up">
      <h3 class="text-xl font-bold text-[#E41C2A]">Integrated Learning</h3>
      <p class="text-gray-600 mt-2">Boards + JEE/NEET/CET combined preparation</p>
    </div>

    <div class="card bg-white p-6 rounded-2xl" data-aos="fade-up" data-aos-delay="200">
      <h3 class="text-xl font-bold text-[#E41C2A]">Regular Tests</h3>
      <p class="text-gray-600 mt-2">Weekly & monthly performance tracking</p>
    </div>

    <div class="card bg-white p-6 rounded-2xl" data-aos="fade-up" data-aos-delay="400">
      <h3 class="text-xl font-bold text-[#E41C2A]">Personal Mentorship</h3>
      <p class="text-gray-600 mt-2">Individual attention & doubt solving</p>
    </div>

  </div>
</section>

<!-- SUBJECTS -->
<section class="py-16 px-6 md:px-16 bg-gray-100 text-center">
  <h2 class="text-3xl md:text-5xl font-bold text-[#E41C2A] mb-10" data-aos="fade-up">
    Subjects Covered
  </h2>

  <div class="grid md:grid-cols-3 gap-8">

    <div class="subject-card bg-white p-8 rounded-2xl shadow-lg" data-aos="zoom-in">
      <h3 class="text-xl font-bold">Physics</h3>
      <p class="text-gray-600 mt-2">Concepts + Numericals</p>
    </div>

    <div class="subject-card bg-white p-8 rounded-2xl shadow-lg" data-aos="zoom-in" data-aos-delay="200">
      <h3 class="text-xl font-bold">Chemistry</h3>
      <p class="text-gray-600 mt-2">Organic + Physical + Inorganic</p>
    </div>

    <div class="subject-card bg-white p-8 rounded-2xl shadow-lg" data-aos="zoom-in" data-aos-delay="400">
      <h3 class="text-xl font-bold">Mathematics / Biology</h3>
      <p class="text-gray-600 mt-2">Based on stream selection</p>
    </div>

  </div>
</section>

<!-- STRATEGY -->
<section class="py-16 px-6 md:px-16 text-center bg-white">

  <h2 class="text-3xl md:text-5xl font-bold text-[#E41C2A] mb-8" data-aos="fade-up">
    Our Strategy
  </h2>

  <div class="grid md:grid-cols-4 gap-6">

    <div class="card bg-gray-50 p-6 rounded-xl" data-aos="fade-up">
      📚<br>Concept Building
    </div>

    <div class="card bg-gray-50 p-6 rounded-xl" data-aos="fade-up" data-aos-delay="200">
      📝<br>Practice Sheets
    </div>

    <div class="card bg-gray-50 p-6 rounded-xl" data-aos="fade-up" data-aos-delay="400">
      📊<br>Testing System
    </div>

    <div class="card bg-gray-50 p-6 rounded-xl" data-aos="fade-up" data-aos-delay="600">
      🎯<br>Performance Analysis
    </div>

  </div>

</section>

<!-- RESULTS -->
<section class="py-16 px-6 md:px-16 bg-[#021969] text-white text-center">

  <h2 class="text-3xl md:text-5xl font-bold text-[#E41C2A] mb-10">
    Student Results
  </h2>

  <div class="grid md:grid-cols-3 gap-8">

    <div class="bg-white text-black p-6 rounded-xl shadow-lg" data-aos="fade-up">
      <h3 class="font-bold text-xl">Amit</h3>
      <p>Board: 92%</p>
    </div>

    <div class="bg-white text-black p-6 rounded-xl shadow-lg" data-aos="fade-up" data-aos-delay="200">
      <h3 class="font-bold text-xl">Sneha</h3>
      <p>Board: 95%</p>
    </div>

    <div class="bg-white text-black p-6 rounded-xl shadow-lg" data-aos="fade-up" data-aos-delay="400">
      <h3 class="font-bold text-xl">Rahul</h3>
      <p>CET: 98%</p>
    </div>

  </div>

</section>

<!-- CTA -->
<section class="py-20 text-center bg-gradient-to-r from-[#E41C2A] to-red-600 text-white">

  <h2 class="text-3xl md:text-5xl font-bold mb-6" data-aos="zoom-in">
    Start Strong From 11th Itself 🚀
  </h2>

  <p class="mb-8 text-lg" data-aos="fade-up">
    Build your base early and stay ahead in competition
  </p>

 <a href="enquiry.php"
   class="ctaBtn inline-block px-8 py-3 bg-green-500 text-black font-bold rounded-full shadow-lg hover:scale-105 transition duration-300">
   Join
</a>

</section>

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