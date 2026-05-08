<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>NEET Coaching | Radiant</title>

<script src="https://cdn.tailwindcss.com"></script>
<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

<style>

/* ===== HERO WAVE ===== */
.hero-wave {
  clip-path: polygon(0 0,100% 0,100% 85%,75% 92%,50% 85%,25% 92%,0 85%);
}

/* ===== GLOW BG ===== */


@keyframes move{
0%{transform:translate(0,0)}
100%{transform:translate(200px,150px)}
}

/* ===== CARD EFFECT ===== */
.card{
transition:0.4s;
}
.card:hover{
transform:translateY(-10px) scale(1.04);
box-shadow:0 30px 60px rgba(0,0,0,0.15);
}

/* ===== BUTTON EFFECT ===== */
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
style="background-image:url('images/hero-bg.png')"></div>

<div class="absolute inset-0 bg-[#021969]/10"></div>

<div class="relative z-10 text-center px-6">
<h1 class="text-4xl md:text-6xl font-bold mb-4">
NEET Coaching Program
</h1>

<p class="text-lg text-gray-200 max-w-2xl mx-auto">
Prepare for NEET with expert faculty, NCERT-focused learning & proven selection strategies.
</p>

<a href="enquiry.php"
   class="ctaBtn inline-block px-8 py-3 mt-6 bg-red-500 text-white font-bold rounded-full shadow-lg hover:scale-105 transition duration-300">
   Book a free demo
</a>
</div>

</section>

<!-- ================= OVERVIEW ================= -->
<section class="relative py-20 px-6 md:px-16 bg-[#DCE0EC] glow">

<div class="max-w-6xl mx-auto text-center mb-14" data-aos="fade-up">
<h2 class="text-3xl md:text-5xl font-bold text-[#E41C2A]">
Why Choose Our NEET Program
</h2>

<p class="text-gray-600 mt-4">
Focused training for medical aspirants aiming for top government colleges.
</p>
</div>

<div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">

<div class="card bg-white p-6 rounded-2xl shadow-lg" data-aos="fade-up">
<h3 class="font-bold text-lg text-[#021969]">NCERT Mastery</h3>
<p class="text-gray-600 mt-2">Complete syllabus coverage with line-by-line clarity</p>
</div>

<div class="card bg-white p-6 rounded-2xl shadow-lg" data-aos="fade-up" data-aos-delay="100">
<h3 class="font-bold text-lg text-[#021969]">Biology Focus</h3>
<p class="text-gray-600 mt-2">High-weightage subject with diagrams & revision</p>
</div>

<div class="card bg-white p-6 rounded-2xl shadow-lg" data-aos="fade-up" data-aos-delay="200">
<h3 class="font-bold text-lg text-[#021969]">Selection Strategy</h3>
<p class="text-gray-600 mt-2">Time management + accuracy improvement techniques</p>
</div>

</div>

</section>

<!-- ================= FEATURES ================= -->
<section class="py-20 px-6 md:px-16 bg-[#021969] text-white">

<div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-12 items-center">

<!-- IMAGE -->
<div data-aos="zoom-in">
<img src="images/NEET.png" class="rounded-2xl shadow-xl">
</div>

<!-- CONTENT -->
<div data-aos="fade-left">

<h2 class="text-3xl md:text-5xl font-bold mb-6 text-[#E41C2A]">
Program Highlights
</h2>

<ul class="space-y-4 text-lg">

<li>✔ Daily Biology + Physics + Chemistry classes</li>
<li>✔ NCERT-based structured study</li>
<li>✔ Daily practice questions & worksheets</li>
<li>✔ Weekly mock tests & full syllabus tests</li>
<li>✔ Personal doubt-solving sessions</li>

</ul>

</div>

</div>

</section>

<!-- ================= SUBJECT MODULES ================= -->
<section class="py-20 px-6 md:px-16 bg-[#E5E8F0]">

<div class="text-center mb-14">
<h2 class="text-3xl md:text-5xl font-bold text-[#E41C2A]">
Subjects Covered
</h2>
</div>

<div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">

<div class="card glass p-6 rounded-2xl text-white bg-[#021969]" data-aos="flip-left">
<h3 class="font-bold text-xl mb-2">Biology</h3>
<p>Botany + Zoology with diagrams & revision modules</p>
</div>

<div class="card glass p-6 rounded-2xl text-white bg-[#021969]" data-aos="flip-left" data-aos-delay="100">
<h3 class="font-bold text-xl mb-2">Physics</h3>
<p>Concept clarity + numericals + NEET pattern problems</p>
</div>

<div class="card glass p-6 rounded-2xl text-white bg-[#021969]" data-aos="flip-left" data-aos-delay="200">
<h3 class="font-bold text-xl mb-2">Chemistry</h3>
<p>Organic, Inorganic & Physical with shortcuts</p>
</div>

</div>

</section>

<!-- ================= TEST SERIES ================= -->
<section class="py-20 px-6 md:px-16 bg-[#021969] text-white text-center">

<h2 class="text-3xl md:text-5xl font-bold mb-6">
NEET Test Series & Practice
</h2>

<p class="text-gray-300 max-w-2xl mx-auto mb-8">
Real exam pattern mock tests with detailed performance analysis.
</p>

<div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">

<div class="card bg-white text-black p-6 rounded-2xl">
Full Syllabus Tests
</div>

<div class="card bg-white text-black p-6 rounded-2xl">
Chapter-wise Tests
</div>

<div class="card bg-white text-black p-6 rounded-2xl">
Performance Analysis
</div>

</div>

</section>

<!-- ================= CTA ================= -->
<section class="py-20 px-6 md:px-16 bg-white text-center text-white">

<h2 class="text-3xl md:text-5xl text-black font-bold mb-6">
Start Your Medical Journey 🩺
</h2>

<p class="mb-6 text-gray-800">
Join Radiant Coaching and secure your seat in top medical colleges.
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