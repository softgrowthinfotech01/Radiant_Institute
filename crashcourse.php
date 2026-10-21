<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Crash Course | Radiant</title>

<script src="https://cdn.tailwindcss.com"></script>
<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

<style>

/* HERO ANIMATION */
.hero-bg{
animation:zoom 12s infinite alternate;
}
@keyframes zoom{
0%{transform:scale(1)}
100%{transform:scale(1.08)}
}

/* GLOW LIGHT */
.light{
position:absolute;
width:400px;
height:400px;
background:radial-gradient(circle,#E41C2A40,transparent);
filter:blur(120px);
animation:move 10s infinite alternate;
}
@keyframes move{
0%{transform:translate(0,0)}
100%{transform:translate(200px,150px)}
}

/* CARD EFFECT */
.card{
transition:0.4s;
}
.card:hover{
transform:translateY(-12px) scale(1.05);
box-shadow:0 30px 60px rgba(0,0,0,0.2);
}

/* GLASS */
.glass{
background:rgba(255,255,255,0.1);
backdrop-filter:blur(12px);
border:1px solid rgba(255,255,255,0.2);
}

/* BUTTON */
.btn{
position:relative;
overflow:hidden;
}
.btn::after{
content:"";
position:absolute;
inset:0;
background:rgba(255,255,255,0.2);
transform:scale(0);
transition:0.4s;
}
.btn:hover::after{
transform:scale(1.5);
}

.wave{
position:absolute;
bottom:0;
width:100%;
}

</style>
</head>

<body class="bg-[#f5f7fb]">

<?php include 'header.php'; ?>

<!-- ================= HERO ================= -->
<section class="relative h-[85vh] flex items-center justify-center text-white overflow-hidden">

<div class="absolute inset-0 hero-bg bg-cover bg-center"
style="background-image:url('images/bgwhite1.jpg')"></div>

<div class="absolute inset-0 bg-[#021969]/85"></div>

<div class="light top-0 left-0"></div>
<div class="light bottom-0 right-0"></div>

<div class="relative z-10 text-center px-6">

<h1 class="text-4xl md:text-6xl font-bold mb-4">
Crash Course 2026 🚀
</h1>

<p class="text-lg text-gray-200 max-w-2xl mx-auto">
Fast-track your preparation with intensive revision, mock tests & exam strategy.
</p>

<div class="mt-6 inline-block bg-yellow-400 text-black px-6 py-2 rounded-full font-semibold shadow-lg">
Batch Starts Soon | Limited Seats
</div>

<a href="enquiry.php"
   class="ctaBtn inline-block px-8 py-3 bg-green-500 text-black font-bold rounded-full shadow-lg hover:scale-105 transition duration-300">
   Enroll Now
</a>

</div>

<!-- WAVE -->
<svg class="wave" viewBox="0 0 1440 200">
<path fill="#ffffff" d="M0,160L80,154.7C160,149,320,139,480,122.7C640,107,800,85,960,96C1120,107,1280,149,1360,170.7L1440,192V200H0Z"></path>
</svg>

</section>

<!-- ================= FEATURES ================= -->
<section class="py-20 px-6 md:px-16 bg-white">

<div class="text-center mb-14">
<h2 class="text-3xl md:text-5xl font-bold text-[#E41C2A]">
Why Crash Course?
</h2>
<p class="text-gray-600 mt-3">
Designed for last-minute preparation & maximum score improvement
</p>
</div>

<div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">

<div class="card bg-white p-6 rounded-2xl shadow-lg">
<h3 class="font-bold text-lg text-[#021969]">Revision Focus</h3>
<p class="text-gray-600 mt-2">Complete syllabus revision in limited time</p>
</div>

<div class="card bg-white p-6 rounded-2xl shadow-lg">
<h3 class="font-bold text-lg text-[#021969]">Mock Tests</h3>
<p class="text-gray-600 mt-2">Daily & full-length exam simulation</p>
</div>

<div class="card bg-white p-6 rounded-2xl shadow-lg">
<h3 class="font-bold text-lg text-[#021969]">Exam Strategy</h3>
<p class="text-gray-600 mt-2">Time management + scoring techniques</p>
</div>

</div>

</section>

<!-- ================= HIGHLIGHTS ================= -->
<section class="py-20 px-6 md:px-16 bg-[#021969] text-white">

<div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-12 items-center">

<div data-aos="zoom-in">
<img src="images/carsh course.png" class="rounded-2xl shadow-xl">
</div>

<div>

<h2 class="text-3xl md:text-5xl font-bold text-[#E41C2A] mb-6">
Course Highlights
</h2>

<ul class="space-y-4 text-lg">

<li>✔ Daily 5-6 Hours Intensive Classes</li>
<li>✔ Chapter-wise Quick Revision</li>
<li>✔ Shortcut Tricks & Concepts</li>
<li>✔ Daily Practice Sheets</li>
<li>✔ 5+ Full Mock Tests</li>
<li>✔ Doubt Solving Sessions</li>

</ul>

</div>

</div>

</section>

<!-- ================= TIMELINE ================= -->
<section class="py-20 px-6 md:px-16 bg-white">

<div class="text-center mb-14">
<h2 class="text-3xl md:text-5xl font-bold text-[#E41C2A]">
Study Plan
</h2>
</div>

<div class="grid md:grid-cols-4 gap-6 max-w-6xl mx-auto">

<div class="glass bg-[#021969] text-white p-6 rounded-2xl text-center">
Week 1<br>Concept Revision
</div>

<div class="glass bg-[#021969] text-white p-6 rounded-2xl text-center">
Week 2<br>Practice + Tests
</div>

<div class="glass bg-[#021969] text-white p-6 rounded-2xl text-center">
Week 3<br>Mock Tests
</div>

<div class="glass bg-[#021969] text-white p-6 rounded-2xl text-center">
Week 4<br>Final Strategy
</div>

</div>

</section>

<!-- ================= CTA ================= -->
<section class="py-20 px-6 md:px-16 bg-[#021969] text-center text-white">

<h2 class="text-3xl md:text-5xl font-bold mb-6">
Boost Your Rank in Just Few Weeks 🚀
</h2>

<p class="text-gray-300 mb-6">
Join Crash Course & maximize your exam score
</p>

<a href="enquiry.php"
   class="ctaBtn inline-block px-8 py-3 bg-red-500 text-white font-bold rounded-full shadow-lg hover:scale-105 transition duration-300">
   Book a free demo
</a>

</section>

<?php include 'footer.php'; ?>

<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
AOS.init({duration:1000, once:true});
</script>

</body>
</html>