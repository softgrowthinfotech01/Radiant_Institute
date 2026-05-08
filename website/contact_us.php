<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contact Us - Radiant</title>

<script src="https://cdn.tailwindcss.com"></script>
<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

<style>

/* HERO ZOOM */
.hero-bg{
  animation: zoom 14s infinite alternate;
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
  filter:blur(140px);
  opacity:0.5;
}

/* CARD */
.card{
  transition:0.4s;
  box-shadow:0 20px 50px rgba(0,0,0,0.3);
}
.card:hover{
  transform:translateY(-10px) scale(1.04);
  box-shadow:0 20px 50px rgba(0,0,0,0.3);
}

/* INPUT */
.input{
  background:rgba(255,255,255,0.08);
  border:1px solid rgba(255,255,255,0.2);
  transition:0.3s;
}
.input:focus{
  border-color:#E41C2A;
  box-shadow:0 0 10px rgba(228,28,42,0.5);
  transform:scale(1.02);
  outline:none;
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
  transform:scaleX(0);
  transition:0.4s;
}
.btn:hover::after{
  transform:scaleX(1);
}

/* MAP */
.map{
  filter:grayscale(0.2) contrast(1.1);
  transition:0.4s;
}
.map:hover{
  filter:grayscale(0) contrast(1.2);
}

</style>
</head>

<body class="bg-[#020617] text-white">

<?php include 'header.php' ?>

<!-- ================= HERO ================= -->
<section class="relative h-[65vh] flex items-center justify-center text-center overflow-hidden">

<div class="absolute inset-0 hero-bg bg-cover bg-center"
style="background-image:url('images/hero-bg.png')"></div>

<div class="absolute inset-0 bg-[#021969]/20"></div>

<div class="relative z-10" data-aos="fade-up">
  <h1 class="text-4xl md:text-6xl font-bold text-[#E41C2A]">
    Contact Us
  </h1>
  <p class="text-gray-300 mt-3">
    We’re here to guide your success journey
  </p>
</div>

</section>

<!-- ================= CONTACT ================= -->
<section class="py-20 px-6 md:px-16 bg-[#E5E8F0] relative overflow-hidden">

<!-- GLOW -->


<div class="grid md:grid-cols-2 gap-12 max-w-6xl mx-auto">

<!-- LEFT -->
<div data-aos="fade-right">

<h2 class="text-3xl md:text-5xl  font-bold text-[#E41C2A] mb-6">
Get in Touch
</h2>

<p class="text-gray-800 mb-8">
Have questions? Want to join Radiant? Contact us today and we’ll guide you.
</p>

<div class="space-y-6">

<div class="card bg-white/10 p-6 rounded-xl backdrop-blur border border-white/10 ">
  <h3 class="font-bold text-gray-800">📍 Address</h3>
  <p class="text-gray-800">  Shree Tower, Beside Jain Bhawaan,
              Pathanpura Road, Chandrapur</p>
</div>

<div class="card bg-white/10 p-6 rounded-xl backdrop-blur border border-white/10">
  <h3 class="font-bold text-gray-800">📞 Phone</h3>
  <p class="text-gray-800"> +91 8421783479 ,
              +91 8421383479</p>
</div>

<div class="card bg-white/10 p-6 rounded-xl backdrop-blur border border-white/10">
  <h3 class="font-bold text-gray-800">📧 Email</h3>
  <p class="text-gray-800">info@radiant.com</p>
</div>

</div>

</div>

<!-- FORM -->
<div data-aos="fade-left">

  <div class="backdrop-blur-xl bg-white/10 border border-white/20 p-8 rounded-2xl shadow-2xl">

    <h3 class="text-2xl font-bold mb-6 text-[#E41C2A]">
      Send Message
    </h3>

    <!-- FORM -->
    <form id="contactForm" class="space-y-4">

      <!-- NAME -->
      <input 
        type="text" 
        id="name"
        placeholder="Your Name"
        minlength="3"
        required
        class="input w-full p-3 rounded-lg text-black border-black"
      >

      <!-- PHONE -->
      <input 
        type="tel" 
        id="phone"
        placeholder="Phone Number"
        maxlength="10"
        required
        class="input w-full p-3 rounded-lg text-black border-black"
      >

      <!-- COURSE -->
      <select 
        id="course"
        required
        class="input w-full p-3 rounded-lg text-gray-900 border-black"
      >
        
        <option value="">Select Course</option>

        <option class="text-black">JEE</option>
        <option class="text-black">NEET</option>
        <option class="text-black">MHT-CET</option>
        <option class="text-black">Boards</option>

      </select>

      <!-- MESSAGE -->
      <textarea 
        rows="4" 
        id="message"
        placeholder="Your Message"
        required
        class="input w-full p-3 rounded-lg text-black border-black"
      ></textarea>

      <!-- BUTTON -->
      <button 
        type="submit"
        class="btn w-full bg-[#E41C2A] py-3 rounded-lg font-bold hover:scale-105 transition"
      >
        Send Message
      </button>

    </form>

  </div>

</div>

</section>

<!-- ================= MAP ================= -->
<section class="px-6 md:px-16 pb-20 mt-5">

  <div class="rounded-2xl overflow-hidden shadow-2xl border border-white/10"
       data-aos="zoom-in">

    <iframe
      src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15002.089922345844!2d79.27681668715822!3d19.944517800000014!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bd2d425ed9e4437%3A0xf2add88b4cac5925!2sRadiant%20Coaching%20Classes!5e0!3m2!1sen!2sus!4v1778144136577!5m2!1sen!2sus"
      class="map w-full h-[450px] border-0"
      allowfullscreen=""
      loading="lazy"
      referrerpolicy="no-referrer-when-downgrade">

    </iframe>

  </div>

</section>

<!-- ================= CTA ================= -->


<?php include 'footer.php' ?>

<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>

<script>
AOS.init({ duration: 1000, once: true });
</script>
<script>

document.getElementById("contactForm").addEventListener("submit", function(e){

    e.preventDefault();

    // VALUES
    let name = document.getElementById("name").value.trim();
    let phone = document.getElementById("phone").value.trim();
    let course = document.getElementById("course").value;
    let message = document.getElementById("message").value.trim();

    // VALIDATION

    // NAME
    if(name.length < 3){
        alert("Please enter valid name");
        return;
    }

    // PHONE
    let phonePattern = /^[6-9]\d{9}$/;

    if(!phonePattern.test(phone)){
        alert("Please enter valid 10 digit mobile number");
        return;
    }

    // COURSE
    if(course === ""){
        alert("Please select course");
        return;
    }

    // MESSAGE
    if(message.length < 5){
        alert("Please enter proper message");
        return;
    }

    // WHATSAPP MESSAGE
    let whatsappMessage =
`🎓 *Radiant Coaching Contact Form* %0A%0A
👤 Name: ${name}%0A
📞 Phone: ${phone}%0A
📚 Course: ${course}%0A
💬 Message: ${message}`;

    // YOUR NUMBER
    let whatsappNumber = "918421783479";

    // REDIRECT
    window.open(
      `https://wa.me/${whatsappNumber}?text=${whatsappMessage}`,
      "_blank"
    );

});

</script>
</body>
</html>