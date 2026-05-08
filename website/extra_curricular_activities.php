<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Activities - Radiant</title>

<script src="https://cdn.tailwindcss.com"></script>
<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

<style>

/* HERO ZOOM */
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

/* GLOW */
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

/* IMAGE */
.img{
  transition:0.6s;
}
.card:hover .img{
  transform:scale(1.1);
}

</style>
</head>

<body class="bg-[#f5f7fb] text-gray-800">

<?php include 'header.php'?>

<!-- ================= HERO ================= -->
<section class="relative h-[70vh] flex items-center justify-center text-center text-white overflow-hidden">

<!-- BG -->
<div class="absolute inset-0 hero-bg bg-cover bg-center"
style="background-image:url('images/hero-bg.png')"></div>

<div class="absolute inset-0 bg-[#021969]/60"></div>

<div class="relative z-10 px-6" data-aos="fade-up">
  <h1 class="text-4xl md:text-6xl font-bold text-[#E41C2A]">
    Extra Curricular Activities
  </h1>
  <p class="mt-4 text-gray-200 max-w-2xl mx-auto">
    Beyond academics – building confidence, leadership & real-world skills
  </p>
</div>

<!-- WAVE -->
<svg class="wave" viewBox="0 0 1440 200">
<path fill="#f5f7fb"
d="M0,160L80,150C160,140,320,120,480,110C640,100,800,100,960,120C1120,140,1280,180,1360,190L1440,200V200H0Z"></path>
</svg>

</section>

<!-- ================= ACTIVITIES ================= -->
<section class="py-20 px-6 md:px-16 relative">

<div class="glow top-0 left-0"></div>

<!-- HEADING -->
<div class="text-center mb-14" data-aos="fade-up">
  <h2 class="text-3xl md:text-5xl font-bold text-[#E41C2A]">
    Explore Activities
  </h2>
  <p class="text-gray-600 mt-3">
    We focus on overall development, not just studies
  </p>
</div>

<!-- GRID -->
<div class="grid md:grid-cols-3 gap-10 max-w-6xl mx-auto">

<!-- CARD -->
<div class="card bg-white rounded-2xl overflow-hidden shadow-md" data-aos="zoom-in">
  <img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d" class="img w-full h-60 object-cover">
  <div class="p-6">
    <h3 class="font-bold text-[#021969] text-lg">Career Seminars</h3>
    <p class="text-gray-600 text-sm mt-2">Guidance from industry experts</p>
  </div>
</div>

<!-- CARD -->
<div class="card bg-white rounded-2xl overflow-hidden shadow-md" data-aos="zoom-in">
  <img src="https://images.unsplash.com/photo-1509062522246-3755977927d7" class="img w-full h-60 object-cover">
  <div class="p-6">
    <h3 class="font-bold text-[#021969] text-lg">Workshops</h3>
    <p class="text-gray-600 text-sm mt-2">Improve problem-solving skills</p>
  </div>
</div>

<!-- CARD -->
<div class="card bg-white rounded-2xl overflow-hidden shadow-md" data-aos="zoom-in">
  <img src="https://images.unsplash.com/photo-1517649763962-0c623066013b" class="img w-full h-60 object-cover">
  <div class="p-6">
    <h3 class="font-bold text-[#021969] text-lg">Sports Activities</h3>
    <p class="text-gray-600 text-sm mt-2">Physical & mental fitness</p>
  </div>
</div>

<!-- CARD -->
<div class="card bg-white rounded-2xl overflow-hidden shadow-md" data-aos="zoom-in">
  <img src="https://images.unsplash.com/photo-1492684223066-81342ee5ff30" class="img w-full h-60 object-cover">
  <div class="p-6">
    <h3 class="font-bold text-[#021969] text-lg">Annual Events</h3>
    <p class="text-gray-600 text-sm mt-2">Fun + learning environment</p>
  </div>
</div>

<!-- CARD -->
<div class="card bg-white rounded-2xl overflow-hidden shadow-md" data-aos="zoom-in">
  <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f" class="img w-full h-60 object-cover">
  <div class="p-6">
    <h3 class="font-bold text-[#021969] text-lg">Personality Development</h3>
    <p class="text-gray-600 text-sm mt-2">Confidence & leadership skills</p>
  </div>
</div>

<!-- CARD -->
<div class="card bg-white rounded-2xl overflow-hidden shadow-md" data-aos="zoom-in">
  <img src="https://images.unsplash.com/photo-1552664730-d307ca884978" class="img w-full h-60 object-cover">
  <div class="p-6">
    <h3 class="font-bold text-[#021969] text-lg">Olympiads</h3>
    <p class="text-gray-600 text-sm mt-2">Boost performance & ranking</p>
  </div>
</div>

</div>

</section>

<!-- ================= CTA ================= -->
<section class="py-20 text-center px-6 bg-[#021969] text-white">

<h2 class="text-4xl font-bold mb-6">
Grow Beyond Academics 🚀
</h2>

<p class="text-gray-300 mb-6">
Join Radiant & build skills for life, not just exams.
</p>

<a href="enquiry.php"
   class="ctaBtn inline-block px-8 py-3 bg-green-500 text-black font-bold rounded-full shadow-lg hover:scale-105 transition duration-300">
   Join Now
</a>

</section>

<?php include 'footer.php'?>

<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>

<script>
AOS.init({ duration: 1000, once: true });
</script>

</body>
</html>