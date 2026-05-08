<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>NEET Results | Radiant</title>

<script src="https://cdn.tailwindcss.com"></script>
<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

<style>

/* HERO */
.hero-bg {
  animation: zoomBg 15s infinite alternate ease-in-out;
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
  background: radial-gradient(circle, rgba(34,197,94,0.4), transparent);
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
.rank {
  background: linear-gradient(45deg,#16a34a,#4ade80);
  color: white;
  padding: 6px 14px;
  border-radius: 50px;
  font-weight: bold;
  margin-top: 6px;
  display: inline-block;
}

/* MARKS */
.marks {
  font-size: 32px;
  font-weight: bold;
  color: #16a34a;
  text-shadow: 0 0 12px rgba(34,197,94,0.5);
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

<!-- HERO -->
<section class="relative h-[70vh] flex items-center justify-center text-center text-white overflow-hidden">

  <div class="absolute inset-0 hero-bg bg-cover bg-center"
       style="background-image:url('images/hero-bg.png')"></div>

  <div class="absolute inset-0 bg-[#021969]/10"></div>

  <!-- Glow -->
  <div class="glow top-10 left-10"></div>
  <div class="glow bottom-10 right-10"></div>

  <div class="relative z-10">
    <h2 class="text-4xl md:text-6xl font-bold">NEET Top Results</h2>
    <p class="mt-3 text-gray-200">Future Doctors of Radiant 🩺</p>
  </div>

  <!-- Wave -->
  <svg class="wave" viewBox="0 0 1440 120">
    <path fill="#f9fafb" d="M0,64L60,80C120,96,240,128,360,128C480,128,600,96,720,85.3C840,75,960,85,1080,80C1200,75,1320,53,1380,42.7L1440,32V160H0Z"></path>
  </svg>

</section>

<!-- RESULTS -->
<section class="py-20 px-6 md:px-16">

  <div class="text-center mb-14">
    <h2 class="text-3xl md:text-5xl font-bold text-[#E41C2A]">
      Our NEET Toppers
    </h2>
    <p class="text-gray-600 mt-3">Medical Excellence with Proven Results</p>
  </div>

  <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-10">

    <!-- STUDENT -->
    <div class="result-card p-6 rounded-2xl text-center">
      <img src="https://randomuser.me/api/portraits/women/44.jpg"
        class="w-24 h-24 mx-auto rounded-full border-4 border-green-400 mb-4">

      <h3 class="text-xl font-bold">Priya Patil</h3>
      <div class="rank">620 Marks</div>

      <p class="marks mt-2">AIR 520</p>
      <p class="text-gray-600 text-sm">Govt Medical College</p>
    </div>

    <!-- STUDENT -->
    <div class="result-card p-6 rounded-2xl text-center">
      <img src="https://randomuser.me/api/portraits/women/65.jpg"
        class="w-24 h-24 mx-auto rounded-full border-4 border-green-400 mb-4">

      <h3 class="text-xl font-bold">Sneha Kulkarni</h3>
      <div class="rank">640 Marks</div>

      <p class="marks mt-2">AIR 310</p>
      <p class="text-gray-600 text-sm">Top Govt Medical</p>
    </div>

    <!-- STUDENT -->
    <div class="result-card p-6 rounded-2xl text-center">
      <img src="https://randomuser.me/api/portraits/men/22.jpg"
        class="w-24 h-24 mx-auto rounded-full border-4 border-green-400 mb-4">

      <h3 class="text-xl font-bold">Amit Deshmukh</h3>
      <div class="rank">610 Marks</div>

      <p class="marks mt-2">AIR 780</p>
      <p class="text-gray-600 text-sm">Medical College Selected</p>
    </div>

  </div>

</section>

<!-- CTA -->
<section class="relative py-20 text-center text-white overflow-hidden">

  <div class="absolute inset-0 bg-green-600"></div>

  <div class="relative z-10">
    <h2 class="text-4xl md:text-5xl font-bold mb-4">
      Start Your Medical Journey 🩺
    </h2>

    <p class="mb-8 text-lg text-gray-100">
      Join Radiant NEET Program & Achieve Your Dream Rank
    </p>

    <a href="enquiry.php"
   class="ctaBtn inline-block px-8 py-3 bg-red-500 text-white font-bold rounded-full shadow-lg hover:scale-105 transition duration-300">
   Join NEET Batch
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