<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Contact Radiant Coaching Classes, Chandrapur — visit, call or send us a message.">
<title>Contact Us — Radiant Coaching Classes</title>

<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,500;0,9..144,600;0,9..144,700;1,9..144,500&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="assets/tailwind-config.js"></script>
<link rel="stylesheet" href="assets/site.css">

<style>
  body { background: #F5F5F2; }
  .map { filter: grayscale(0.25) contrast(1.05); transition: filter .4s; }
  .map:hover { filter: grayscale(0) contrast(1.1); }
</style>
</head>

<body class="bg-paper text-ink antialiased">

<?php include 'header.php' ?>

<!-- ================= PAGE HERO ================= -->
<section class="page-hero relative overflow-hidden">
  <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 py-16 md:py-24">
    <p class="reveal text-sm font-medium text-gold mb-4">Get in touch</p>
    <h1 class="reveal font-display font-semibold text-4xl md:text-6xl text-white max-w-2xl leading-[1.08]">Contact Us</h1>
    <p class="reveal mt-5 text-white/70 max-w-xl text-base md:text-lg">We're here to guide your success journey — visit, call or write to us.</p>
  </div>
</section>

<!-- ================= CONTACT ================= -->
<section class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 py-16 md:py-24">
  <div class="grid md:grid-cols-2 gap-12 md:gap-16">

    <!-- LEFT: DETAILS -->
    <div class="reveal">
      <h2 class="font-display font-semibold text-3xl md:text-4xl text-ink mb-4">Reach us directly</h2>
      <p class="text-slate mb-10 max-w-md">Have questions, or want to enrol? Reach out and our team will guide you through the next steps.</p>

      <div class="space-y-4">
        <div class="border border-line rounded-lg p-6 bg-white">
          <h3 class="font-semibold text-ink text-sm mb-1.5">Address</h3>
          <p class="text-slate text-sm leading-relaxed">Shree Tower, Beside Jain Bhawaan, Pathanpura Road, Chandrapur</p>
        </div>

        <div class="border border-line rounded-lg p-6 bg-white">
          <h3 class="font-semibold text-ink text-sm mb-1.5">Phone</h3>
          <p class="text-slate text-sm leading-relaxed">+91 84217 83479<br>+91 84213 83479</p>
        </div>

        <div class="border border-line rounded-lg p-6 bg-white">
          <h3 class="font-semibold text-ink text-sm mb-1.5">Email</h3>
          <p class="text-slate text-sm leading-relaxed">shaibazpathan0@gmail.com</p>
        </div>
      </div>
    </div>

    <!-- RIGHT: FORM -->
    <div class="reveal">
      <div class="bg-white border border-line rounded-lg p-8 md:p-10">
        <h3 class="font-display font-semibold text-2xl text-ink mb-6">Send a message</h3>

        <form id="contactForm" class="space-y-4">
          <input type="text" id="name" placeholder="Your name" minlength="3" required class="field">
          <input type="tel" id="phone" placeholder="Phone number" maxlength="10" minlength="10" required class="field">

          <select id="course" required class="field">
            <option value="">Select course</option>
            <option>JEE</option>
            <option>NEET</option>
            <option>MHT-CET</option>
            <option>Boards</option>
          </select>

          <textarea rows="4" id="message" placeholder="Your message" required class="field"></textarea>

          <button type="submit" class="w-full bg-ink text-white py-3.5 rounded-md font-medium hover:bg-brand transition-colors">
            Send Message
          </button>
        </form>
      </div>
    </div>

  </div>
</section>

<!-- ================= MAP ================= -->
<section class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 pb-20">
  <div class="reveal rounded-lg overflow-hidden border border-line">
    <iframe
      src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15002.089922345844!2d79.27681668715822!3d19.944517800000014!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bd2d425ed9e4437%3A0xf2add88b4cac5925!2sRadiant%20Coaching%20Classes!5e0!3m2!1sen!2sus!4v1778144136577!5m2!1sen!2sus"
      class="map w-full h-[420px] border-0"
      allowfullscreen=""
      loading="lazy"
      referrerpolicy="no-referrer-when-downgrade">
    </iframe>
  </div>
</section>

<?php include 'footer.php' ?>

<script src="assets/site.js"></script>
<script>
document.getElementById("contactForm").addEventListener("submit", function(e) {
  e.preventDefault();

  let name = document.getElementById("name").value.trim();
  let phone = document.getElementById("phone").value.trim();
  let course = document.getElementById("course").value;
  let message = document.getElementById("message").value.trim();

  if (name.length < 3) { alert("Please enter a valid name"); return; }
  if (course === "") { alert("Please select a course"); return; }
  if (message.length < 5) { alert("Please enter a proper message"); return; }

  let whatsappMessage =
`🎓 *Radiant Coaching Contact Form* %0A%0A
👤 Name: ${name}%0A
📞 Phone: ${phone}%0A
📚 Course: ${course}%0A
💬 Message: ${message}`;

  let whatsappNumber = "918421783479";
  window.open(`https://wa.me/${whatsappNumber}?text=${whatsappMessage}`, "_blank");
});
</script>
</body>
</html>
