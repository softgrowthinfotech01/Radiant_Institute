<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>MHT-CET Coaching | Radiant</title>

<script src="https://cdn.tailwindcss.com"></script>
<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

<style>

/* ===== HERO WAVE ===== */
.hero-wave {
  clip-path: polygon(0 0,100% 0,100% 85%,75% 92%,50% 85%,25% 92%,0 85%);
}

/* ===== GLOW ===== */


@keyframes move{
0%{transform:translate(0,0)}
100%{transform:translate(200px,150px)}
}

/* ===== CARD ===== */
.card{
transition:0.4s;
}
.card:hover{
transform:translateY(-10px) scale(1.04);
box-shadow:0 30px 60px rgba(0,0,0,0.15);
}

/* ===== BUTTON ===== */
.btn{
position:relative;
overflow:hidden;
}
.btn::after{
content:"";
position:absolute;
width:0%;
height:100%;
background:rgba(255,255,255,0.2);
top:0;
left:0;
transition:0.4s;
}
.btn:hover::after{
width:100%;
}

/* ===== GLASS ===== */
.glass{
background: rgba(255,255,255,0.1);
backdrop-filter: blur(10px);
border:1px solid rgba(255,255,255,0.2);
}

</style>
</head>

<body class="bg-[#f5f7fb]">

<?php include 'header.php'; ?>

<!-- ================= HERO ================= -->
<section class="relative h-[80vh] flex items-center justify-center text-white overflow-hidden">

<div class="absolute inset-0 bg-cover bg-center hero-wave"
style="background-image:url('images/mht-cet.png')"></div>

<div class="absolute inset-0 bg-[#021969]/10"></div>

<div class="relative z-10 text-center px-6">
<!-- <h1 class="text-4xl md:text-6xl font-bold mb-4">
MHT-CET Preparation
</h1> -->

<!-- <p class="text-lg text-gray-200 max-w-2xl mx-auto">
Master speed, accuracy & exam strategy to secure top percentile in MHT-CET.
</p> -->

<!-- <button class="btn mt-6 bg-[#E41C2A] px-8 py-3 rounded-lg font-semibold">
Join CET Batch
</button> -->
</div>

</section>

<!-- ================= OVERVIEW ================= -->
<section class="relative py-20 px-6 md:px-16 bg-[#DCE0EC] glow">

<div class="text-center mb-14" data-aos="fade-up">
<h2 class="text-3xl md:text-5xl font-bold text-[#E41C2A]">
Why Choose Our MHT-CET Program
</h2>

<p class="text-gray-600 mt-4">
Designed for Maharashtra students aiming for top engineering colleges.
</p>
</div>

<div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">

<div class="card bg-white p-6 rounded-2xl shadow-lg" data-aos="fade-up">
<h3 class="font-bold text-lg text-[#021969]">Speed Training</h3>
<p class="text-gray-600 mt-2">Solve more questions in less time</p>
</div>

<div class="card bg-white p-6 rounded-2xl shadow-lg" data-aos="fade-up" data-aos-delay="100">
<h3 class="font-bold text-lg text-[#021969]">MCQ Focus</h3>
<p class="text-gray-600 mt-2">Practice 1000+ MCQs per subject</p>
</div>

<div class="card bg-white p-6 rounded-2xl shadow-lg" data-aos="fade-up" data-aos-delay="200">
<h3 class="font-bold text-lg text-[#021969]">High Accuracy</h3>
<p class="text-gray-600 mt-2">Avoid negative impact & improve score</p>
</div>

</div>

</section>

<!-- ================= FEATURES ================= -->
<section class="py-20 px-6 md:px-16 bg-[#021969] text-white">

<div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-12 items-center">

<!-- IMAGE -->
<div data-aos="zoom-in">
<img src="images/mht-cet.png" class="rounded-2xl shadow-xl">
</div>

<!-- CONTENT -->
<div data-aos="fade-left">

<h2 class="text-3xl md:text-5xl font-bold mb-6 text-[#E41C2A]">
Program Highlights
</h2>

<ul class="space-y-4 text-lg">

<li>✔ Daily CET-focused classes</li>
<li>✔ Speed-based practice sessions</li>
<li>✔ Chapter-wise MCQ drills</li>
<li>✔ Weekly CET pattern mock tests</li>
<li>✔ Rank boosting short tricks</li>

</ul>

</div>

</div>

</section>

<!-- ================= SUBJECTS ================= -->
<section class="py-20 px-6 md:px-16 bg-[#E5E8F0]">

<div class="text-center mb-14">
<h2 class="text-3xl md:text-5xl font-bold text-[#E41C2A]">
Subjects Covered
</h2>
</div>

<div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">

<div class="card glass p-6 rounded-2xl text-white bg-[#021969]" data-aos="flip-left">
<h3 class="font-bold text-xl mb-2">Physics</h3>
<p>Concept + fast problem solving</p>
</div>

<div class="card glass p-6 rounded-2xl text-white bg-[#021969]" data-aos="flip-left" data-aos-delay="100">
<h3 class="font-bold text-xl mb-2">Chemistry</h3>
<p>Theory + quick revision techniques</p>
</div>

<div class="card glass p-6 rounded-2xl text-white bg-[#021969]" data-aos="flip-left" data-aos-delay="200">
<h3 class="font-bold text-xl mb-2">Mathematics</h3>
<p>Formula-based fast calculations</p>
</div>

</div>

</section>

<!-- ================= TEST SERIES ================= -->
<section class="py-20 px-6 md:px-16 bg-[#021969] text-white text-center">

<h2 class="text-3xl md:text-5xl font-bold mb-6">
CET Test Series
</h2>

<p class="text-gray-300 max-w-2xl mx-auto mb-8">
Practice real exam pattern tests with speed + accuracy analysis.
</p>

<div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">

<div class="card bg-white text-black p-6 rounded-2xl">
Full Mock Tests
</div>

<div class="card bg-white text-black p-6 rounded-2xl">
Daily Practice Tests
</div>

<div class="card bg-white text-black p-6 rounded-2xl">
Performance Tracking
</div>

</div>

</section>

<!-- ================= CTA ================= -->
<section class="py-20 px-6 md:px-16 bg-white text-center text-white">

<h2 class="text-3xl text-black md:text-5xl font-bold mb-6">
Crack CET with Top Percentile 🚀
</h2>

<p class="mb-6 text-gray-800">
Join Radiant Coaching and secure admission in top engineering colleges.
</p>

<a href="enquiry.php"
   class="ctaBtn inline-block px-8 py-3 bg-red-500 text-white font-bold rounded-full shadow-lg hover:scale-105 transition duration-300">
   Enroll Now
</a>

</section>

<?php include 'footer.php'; ?>

<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
AOS.init({duration:1000, once:true});
</script>

</body>
</html>