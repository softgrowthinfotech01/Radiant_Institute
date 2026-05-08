<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>About Us - Radiant</title>

<script src="https://cdn.tailwindcss.com"></script>
<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

<style>

/* HERO BG */
.hero-bg{
  animation: zoom 12s infinite alternate;
}
@keyframes zoom{
  0%{transform:scale(1)}
  100%{transform:scale(1.08)}
}

/* WAVE */
.wave{
  position:absolute;
  bottom:-1px;
  width:100%;
}

/* GLOW LIGHT */
.glow{
  position:absolute;
  width:400px;
  height:400px;
  background:radial-gradient(circle,#E41C2A30,transparent);
  filter:blur(120px);
}

/* CARD */
.card{
  transition:0.4s;
}
.card:hover{
  transform:translateY(-10px) scale(1.03);
  box-shadow:0 20px 40px rgba(0,0,0,0.1);
}

/* COUNTER */
.counter{
  font-size:2rem;
  font-weight:bold;
  color:#E41C2A;
}

</style>
</head>

<body class="bg-[#f5f7fb] text-gray-800">

<?php include 'header.php'?>

<!-- ================= HERO ================= -->
<section class="relative h-[80vh] flex items-center justify-center text-center text-white overflow-hidden">

<!-- BG IMAGE -->
<div class="absolute inset-0 hero-bg bg-cover bg-center"
style="background-image:url('images/hero-bg.png')"></div>

<!-- BLUE OVERLAY -->
<div class="absolute inset-0 bg-[#021969]/20"></div>

<!-- CONTENT -->
<div class="relative z-10 px-6" data-aos="fade-up">
  <h1 class="text-4xl md:text-6xl font-bold text-[#E41C2A]">
    About Radiant
  </h1>
  <p class="mt-4 text-gray-200 max-w-2xl mx-auto">
    Shaping future doctors & engineers with excellence, discipline and results.
  </p>
</div>

<!-- WAVE -->
<svg class="wave" viewBox="0 0 1440 200">
<path fill="#f5f7fb"
d="M0,160L80,150C160,140,320,120,480,110C640,100,800,100,960,120C1120,140,1280,180,1360,190L1440,200V200H0Z"></path>
</svg>

</section>

<!-- ================= ABOUT ================= -->
<section class="py-20 px-6 md:px-16 relative">

<div class="glow top-0 left-0"></div>

<div class="grid md:grid-cols-2 gap-12 items-center max-w-6xl mx-auto">

<!-- IMAGE -->
<div class="gallery-card group" data-aos="zoom-in" data-aos-delay="200">
  <div class="relative overflow-hidden rounded-2xl shadow-2xl cursor-pointer">

    <video 
      class="media w-full h-64 object-cover brightness-75"
      autoplay 
      muted 
      loop 
      playsinline>
      
      <source src="images/radient-r-7.mp4" type="video/mp4">
    </video>

  </div>
</div>

<!-- TEXT -->
<div data-aos="fade-left">
  <h2 class="text-3xl md:text-5xl font-bold text-[#E41C2A] mb-6">
    Who We Are
  </h2>

  <p class="text-gray-700 leading-relaxed mb-6">
    Radiant Coaching Classes is a leading institute dedicated to preparing students for JEE, NEET, MHT-CET, and Board Exams.
  </p>

  <p class="text-gray-600">
    Our approach focuses on concept clarity, disciplined learning, and continuous testing to ensure top results.
  </p>

</div>

</div>
</section>

<!-- ================= VISION ================= -->
<section class="py-20 px-6 md:px-16 bg-[#021969] text-white">

<div class="grid md:grid-cols-2 gap-10 max-w-6xl mx-auto">

<div class="card bg-white/10 backdrop-blur p-8 rounded-2xl" data-aos="fade-up">
  <h3 class="text-2xl font-bold text-[#E41C2A] mb-4">Our Vision</h3>
  <p class="text-gray-200">
    To become the most trusted coaching institute delivering top rankers every year.
  </p>
</div>

<div class="card bg-white/10 backdrop-blur p-8 rounded-2xl" data-aos="fade-up">
  <h3 class="text-2xl font-bold text-[#E41C2A] mb-4">Our Mission</h3>
  <p class="text-gray-200">
    To provide quality education, personalized attention, and a competitive environment.
  </p>
</div>

</div>
</section>

<!-- ================= STATS ================= -->
<section class="py-20 px-6 md:px-16 text-center bg-white">

<div class="grid md:grid-cols-4 gap-10 max-w-6xl mx-auto">

<div data-aos="zoom-in">
  <div class="counter" data-count="5000">0</div>
  <p>Students Trained</p>
</div>

<div data-aos="zoom-in">
  <div class="counter" data-count="500">0</div>
  <p>Selections</p>
</div>

<div data-aos="zoom-in">
  <div class="counter" data-count="50">0</div>
  <p>Expert Faculty</p>
</div>

<div data-aos="zoom-in">
  <div class="counter" data-count="10">0</div>
  <p>Years Experience</p>
</div>

</div>
</section>

<!-- ================= WHY ================= -->
<section class="py-20 px-6 md:px-16 bg-[#f5f7fb]">

<div class="text-center mb-12">
  <h2 class="text-4xl font-bold text-[#E41C2A]">Why Choose Us</h2>
</div>

<div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">

<div class="card bg-white p-6 rounded-xl shadow-md">
  <h3 class="font-bold text-[#021969]">Expert Faculty</h3>
  <p class="text-gray-600">Highly experienced teachers</p>
</div>

<div class="card bg-white p-6 rounded-xl shadow-md">
  <h3 class="font-bold text-[#021969]">Regular Tests</h3>
  <p class="text-gray-600">Weekly + full syllabus tests</p>
</div>

<div class="card bg-white p-6 rounded-xl shadow-md">
  <h3 class="font-bold text-[#021969]">Personal Attention</h3>
  <p class="text-gray-600">Small batch sizes</p>
</div>

</div>
</section>

<!-- ================= CTA ================= -->
<section class="py-20 text-center px-6 bg-[#021969] text-white">

<h2 class="text-4xl font-bold mb-6">
Start Your Success Journey Today 🚀
</h2>

<a href="enquiry.php"
   class="ctaBtn inline-block px-8 py-3 bg-green-500 text-black font-bold rounded-full shadow-lg hover:scale-105 transition duration-300">
   Enroll Now
</a>

</section>

<?php include 'footer.php'?>

<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>

<script>
AOS.init({ duration: 1000, once: true });

// COUNTER
const counters = document.querySelectorAll(".counter");

counters.forEach(counter => {
  let update = () => {
    let target = +counter.getAttribute("data-count");
    let count = +counter.innerText;
    let speed = target / 50;

    if(count < target){
      counter.innerText = Math.ceil(count + speed);
      setTimeout(update, 40);
    } else {
      counter.innerText = target;
    }
  }
  update();
});
</script>

</body>
</html>