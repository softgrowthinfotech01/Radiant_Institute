<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gallery - Radiant</title>

<script src="https://cdn.tailwindcss.com"></script>
<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

<style>

/* HERO ZOOM */
.hero-bg{
  animation: zoom 14s infinite alternate ease-in-out;
}
@keyframes zoom{
  0%{transform:scale(1)}
  100%{transform:scale(1.1)}
}

/* GLOW */
.glow{
  position:absolute;
  width:500px;
  height:500px;
  filter:blur(150px);
  opacity:0.4;
}

/* CARD */
.gallery-card{
  transition:0.5s;
}
.gallery-card:hover{
  transform:translateY(-12px) scale(1.04);
}

/* IMAGE / VIDEO */
.media{
  transition:0.7s ease;
}
.gallery-card:hover .media{
  transform:scale(1.15);
}

/* OVERLAY */
.overlay{
  background:linear-gradient(to top, rgba(2,25,105,0.95), rgba(2,25,105,0.3), transparent);
}

/* RED HOVER */
.red-glow{
  background:rgba(228,28,42,0.15);
  opacity:0;
  transition:0.4s;
}
.gallery-card:hover .red-glow{
  opacity:1;
}

/* MODAL */
#modal{
  backdrop-filter: blur(12px);
}
.modal-content{
  animation: zoomIn 0.4s ease;
}
@keyframes zoomIn{
  from{transform:scale(0.8);opacity:0}
  to{transform:scale(1);opacity:1}
}

/* PLAY ICON */
.play-btn{
  position:absolute;
  inset:0;
  display:flex;
  align-items:center;
  justify-content:center;
  font-size:40px;
  color:white;
  opacity:0.8;
}

</style>
</head>

<body class="bg-[#020617] text-white">

<?php include 'header.php'?>

<!-- HERO -->
<section class="relative h-[60vh] flex items-center justify-center text-center overflow-hidden">

<div class="absolute inset-0 hero-bg bg-cover bg-center"
style="background-image:url('images/hero-bg.png')"></div>

<div class="absolute inset-0 bg-[#021969]/70"></div>

<div class="relative z-10" data-aos="fade-up">
  <h1 class="text-4xl md:text-6xl font-bold text-[#E41C2A]">
    Our Gallery
  </h1>
  <p class="text-gray-300 mt-3">
    Moments of success, growth & excellence
  </p>
</div>

</section>

<!-- GALLERY -->
<section class="py-20 bg-white px-6 md:px-16 relative overflow-hidden">

<!-- GLOW -->


<!-- GRID -->
<div class="grid gap-10 md:grid-cols-2 lg:grid-cols-3">

<!-- IMAGE CARD -->
<div class="gallery-card group" data-aos="zoom-in">
  <div class="relative overflow-hidden rounded-2xl shadow-2xl cursor-pointer">

    <img src="images/radient-r-2.webp"
      class="media w-full h-64 object-cover brightness-75">
  </div>
</div>

<div class="gallery-card group" data-aos="zoom-in">
  <div class="relative overflow-hidden rounded-2xl shadow-2xl cursor-pointer">

    <img src="images/gallaray-r-4.webp"
      class="media w-full h-64 object-cover brightness-75">
  </div>
</div>


<!-- VIDEO CARD -->
<div class="gallery-card group" data-aos="zoom-in" data-aos-delay="200">
  <div class="relative overflow-hidden rounded-2xl shadow-2xl cursor-pointer">

    <video 
      class="media w-full h-64 object-cover brightness-75"
      autoplay 
      muted 
      loop 
      playsinline>
      
      <source src="images/radient-r-8.mp4" type="video/mp4">
    </video>

  </div>
</div>

<div class="gallery-card group" data-aos="zoom-in">
  <div class="relative overflow-hidden rounded-2xl shadow-2xl cursor-pointer">

    <img src="images/radient-r-3.webp"
      class="media w-full h-64 object-cover brightness-75">
  </div>
</div>

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

<div class="gallery-card group" data-aos="zoom-in">
  <div class="relative overflow-hidden rounded-2xl shadow-2xl cursor-pointer">

    <img src="images/radient-r-7.jpeg"
      class="media w-full h-64 object-cover brightness-75">
  </div>
</div>

<div class="gallery-card group" data-aos="zoom-in" data-aos-delay="200">
  <div class="relative overflow-hidden rounded-2xl shadow-2xl cursor-pointer">

    <video 
      class="media w-full h-64 object-cover brightness-75"
      autoplay 
      muted 
      loop 
      playsinline>
      
      <source src="images/radient-r-6.mp4" type="video/mp4">
    </video>

  </div>
</div>

<div class="gallery-card group" data-aos="zoom-in">
  <div class="relative overflow-hidden rounded-2xl shadow-2xl cursor-pointer">

    <img src="images/gallary-r-1.webp"
      class="media w-full h-64 object-cover brightness-75">
  </div>
</div>

<div class="gallery-card group" data-aos="zoom-in">
  <div class="relative overflow-hidden rounded-2xl shadow-2xl cursor-pointer">

    <img src="images/galllary-r-.webp"
      class="media w-full h-64 object-cover brightness-75">
  </div>
</div>
<!-- VIDEO CARD -->

</div>

</section>

<!-- MODAL -->
<div id="modal" class="fixed inset-0 bg-black/95 hidden items-center justify-center z-50">
  
  <!-- IMAGE -->
  <img id="modalImg" class="modal-content max-w-5xl w-full rounded-2xl hidden">

  <!-- VIDEO -->
  <video id="modalVideo" controls class="modal-content max-w-5xl w-full rounded-2xl hidden"></video>

</div>

<?php include 'footer.php'?>

<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>

<script>
AOS.init({ duration: 1000, once: true });

const modal = document.getElementById("modal");
const modalImg = document.getElementById("modalImg");
const modalVideo = document.getElementById("modalVideo");

// IMAGE CLICK
document.querySelectorAll("img").forEach(img => {
  img.onclick = () => {
    modal.classList.remove("hidden");
    modal.classList.add("flex");

    modalImg.src = img.src;
    modalImg.classList.remove("hidden");
    modalVideo.classList.add("hidden");
  }
});

// VIDEO CLICK
document.querySelectorAll("video").forEach(video => {
  video.onclick = () => {
    modal.classList.remove("hidden");
    modal.classList.add("flex");

    modalVideo.src = video.querySelector("source").src;
    modalVideo.classList.remove("hidden");
    modalImg.classList.add("hidden");

    modalVideo.play();
  }
});

// CLOSE MODAL
modal.onclick = () => {
  modal.classList.add("hidden");
  modal.classList.remove("flex");

  modalVideo.pause();
};
</script>

</body>
</html>