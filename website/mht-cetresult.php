<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>MHT-CET Results | Radiant</title>

<script src="https://cdn.tailwindcss.com"></script>
<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

<style>

/* HERO */
.hero-bg {
  animation: zoomBg 14s infinite alternate ease-in-out;
}
@keyframes zoomBg {
  0% { transform: scale(1); }
  100% { transform: scale(1.1); }
}

/* GLOW */
.glow {
  position: absolute;
  width: 300px;
  height: 300px;
  background: radial-gradient(circle, rgba(59,130,246,0.4), transparent);
  filter: blur(120px);
  animation: float 10s infinite alternate;
}
@keyframes float {
  0% { transform: translateY(0); }
  100% { transform: translateY(-60px); }
}

/* WAVE */
.wave {
  position: absolute;
  bottom: 0;
  width: 100%;
}

/* CARD */
.result-card {
  backdrop-filter: blur(15px);
  background: rgba(255,255,255,0.85);
  transition: 0.5s;
}

.result-card:hover {
  transform: rotateX(6deg) rotateY(-6deg) scale(1.05);
  box-shadow: 0 30px 70px rgba(0,0,0,0.25);
}

/* BADGE */
.percentile {
  background: linear-gradient(45deg,#2563eb,#38bdf8);
  color: white;
  padding: 6px 14px;
  border-radius: 50px;
  font-weight: bold;
  display: inline-block;
  margin-top: 6px;
}

/* RANK */
.rank {
  font-size: 30px;
  font-weight: bold;
  color: #2563eb;
  text-shadow: 0 0 12px rgba(59,130,246,0.5);
}

/* ENTRY */
.result-card {
  opacity: 0;
  transform: translateY(100px);
}
.result-card.show {
  opacity: 1;
  transform: translateY(0);
  transition: 0.8s;
}

/* BUTTON */
.btn {
  position: relative;
  overflow: hidden;
}
.btn::after {
  content:"";
  position:absolute;
  width:0%;
  height:100%;
  background:white;
  top:0;
  left:0;
  transition:0.4s;
}
.btn:hover::after {
  width:100%;
}
.btn span {
  position:relative;
  z-index:1;
}

</style>
</head>

<body class="bg-gray-50">

<?php include 'header.php'; ?>

<!-- ================= HERO ================= -->
<section class="relative h-[70vh] flex items-center justify-center text-center text-white overflow-hidden">

  <!-- BG -->
  <div class="absolute inset-0 hero-bg bg-cover bg-center"
       style="background-image:url('images/mht-cet.png')"></div>

  <!-- Overlay -->
  <div class="absolute inset-0 bg-[#021969]/80"></div>

  <!-- Glow -->
  <div class="glow top-10 left-10"></div>
  <div class="glow bottom-10 right-10"></div>

  <!-- Content -->
  <div class="relative z-10">
    <h2 class="text-4xl md:text-6xl font-bold">MHT-CET Results</h2>
    <p class="mt-3 text-gray-200">Top Engineering Rankers ⚡</p>
  </div>

  <!-- Wave -->
  <svg class="wave" viewBox="0 0 1440 120">
    <path fill="#f9fafb" d="M0,64L60,80C120,96,240,128,360,128C480,128,600,96,720,85.3C840,75,960,85,1080,80C1200,75,1320,53,1380,42.7L1440,32V160H0Z"></path>
  </svg>

</section>

<!-- ================= RESULTS ================= -->
<section class="py-20 px-6 md:px-16">

  <div class="text-center mb-14">
    <h2 class="text-3xl md:text-5xl font-bold text-[#E41C2A]">
      Our CET Toppers
    </h2>
    <p class="text-gray-600 mt-3">Engineering Success with High Percentiles</p>
  </div>

  <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-10">

    <!-- STUDENT -->
    <div class="result-card p-6 rounded-2xl text-center">
      <img src="https://randomuser.me/api/portraits/men/12.jpg"
        class="w-24 h-24 mx-auto rounded-full border-4 border-blue-400 mb-4">

      <h3 class="text-xl font-bold">Amit Deshmukh</h3>
      <div class="percentile">99.2 Percentile</div>

      <p class="rank mt-2">AIR 320</p>
      <p class="text-gray-600 text-sm">COEP Pune</p>
    </div>

    <!-- STUDENT -->
    <div class="result-card p-6 rounded-2xl text-center">
      <img src="https://randomuser.me/api/portraits/men/55.jpg"
        class="w-24 h-24 mx-auto rounded-full border-4 border-blue-400 mb-4">

      <h3 class="text-xl font-bold">Rohit Patil</h3>
      <div class="percentile">98.8 Percentile</div>

      <p class="rank mt-2">AIR 780</p>
      <p class="text-gray-600 text-sm">VJTI Mumbai</p>
    </div>

    <!-- STUDENT -->
    <div class="result-card p-6 rounded-2xl text-center">
      <img src="https://randomuser.me/api/portraits/women/22.jpg"
        class="w-24 h-24 mx-auto rounded-full border-4 border-blue-400 mb-4">

      <h3 class="text-xl font-bold">Sneha Kulkarni</h3>
      <div class="percentile">97.9 Percentile</div>

      <p class="rank mt-2">AIR 1200</p>
      <p class="text-gray-600 text-sm">Top Engineering College</p>
    </div>

  </div>

</section>

<!-- ================= CTA ================= -->
<section class="relative py-20 text-center text-white overflow-hidden">

  <div class="absolute inset-0 bg-blue-600"></div>

  <div class="relative z-10">
    <h2 class="text-4xl md:text-5xl font-bold mb-4">
      Crack MHT-CET with Top Rank ⚡
    </h2>

    <p class="mb-8 text-lg text-gray-100">
      Join Radiant CET Program & Get Into Top Engineering Colleges
    </p>
<a href="enquiry.php"
   class="ctaBtn inline-block px-8 py-3 bg-green-500 text-black font-bold rounded-full shadow-lg hover:scale-105 transition duration-300">
  Join CET Batch
</a>
  </div>

</section>

<?php include 'footer.php'; ?>

<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>AOS.init({duration:1000});</script>

<script>
const cards = document.querySelectorAll(".result-card");

const observer = new IntersectionObserver((entries)=>{
  entries.forEach((entry,i)=>{
    if(entry.isIntersecting){
      setTimeout(()=>{
        entry.target.classList.add("show");
      }, i*150);
    }
  });
});

cards.forEach(card=>observer.observe(card));
</script>

</body>
</html>