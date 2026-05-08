<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Enquiry | Radiant Coaching</title>

<script src="https://cdn.tailwindcss.com"></script>
<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

<style>

/* ===== PREMIUM GRADIENT BG ===== */
.bg-animate {
  /* background: linear-gradient(135deg, #021969, #0a2fbf, #021969); */
}

/* ===== GLASS ===== */
.glass {
  backdrop-filter: blur(18px);
  background: rgba(255,255,255,0.08);
  border: 1px solid rgba(255,255,255,0.15);
}

/* ===== INPUT ===== */
.input-group {
  position: relative;
}

.input-group input,
.input-group select {
  width: 100%;
  padding: 14px;
  background: rgba(255,255,255,0.1);
  border: 1px solid rgba(255,255,255,0.25);
  border-radius: 10px;
  color: white;
  outline: none;
  transition: 0.3s;
}

.input-group input:focus,
.input-group select:focus {
  border-color: #E41C2A;
  box-shadow: 0 0 10px rgba(228,28,42,0.4);
}

/* FLOAT LABEL */
.input-group label {
  position: absolute;
  top: 14px;
  left: 14px;
  color: #ccc;
  font-size: 14px;
  transition: 0.3s;
  pointer-events: none;
}

.input-group input:focus + label,
.input-group input:valid + label,
.input-group select:focus + label,
.input-group select:valid + label {
  top: -8px;
  left: 10px;
  font-size: 12px;
  background: #021969;
  padding: 0 6px;
  color: #facc15;
}

/* ===== BUTTON ===== */
.btn-main {
  background: linear-gradient(135deg, #E41C2A, #ff4d5a);
  transition: 0.3s;
}

.btn-main:hover {
  transform: scale(1.05);
  box-shadow: 0 10px 25px rgba(228,28,42,0.5);
}

/* ===== LEFT CARD ICON ===== */
.feature {
  display: flex;
  gap: 10px;
  align-items: center;
  color: #e5e7eb;
}

</style>
</head>

<body class="bg-animate min-h-screen flex flex-col">

<?php include 'header.php'; ?>

<!-- ================= MAIN ================= -->
<section class="flex-grow  bg-white flex items-center justify-center px-6 py-12">

<div class="glass rounded-3xl bg-[#00216D] p-4 md:p-12 w-full max-w-6xl grid md:grid-cols-2 gap-12 mt-20 shadow-2xl">

  <!-- LEFT -->
  <div data-aos="fade-right">

    <h2 class="text-4xl font-bold mb-4 text-white">
      Book Your Free Demo 🚀
    </h2>

    <p class="text-gray-300 mb-8">
      Start your journey with expert guidance & proven results.
    </p>

    <div class="space-y-4">
      <div class="feature">✔ Expert Faculty Guidance</div>
      <div class="feature">✔ Personalized Study Plan</div>
      <div class="feature">✔ Free Career Counseling</div>
      <div class="feature">✔ Scholarship Opportunities</div>
    </div>

    <!-- EXTRA VISUAL LINE -->
    <div class="mt-8 h-1 w-24 bg-[#E41C2A] rounded-full"></div>

  </div>

<!-- FORM -->
<form id="enquiryForm" class="space-y-5" data-aos="fade-left">

  <div class="input-group">
    <input type="text" id="name" required>
    <label>Full Name</label>
  </div>

  <div class="input-group">
    <input type="tel" id="phone" required>
    <label>Phone Number</label>
  </div>

  <div class="input-group">
    <select id="course" required>
      <option value=""></option>
      <option class="text-black">JEE</option>
      <option class="text-black">NEET</option>
      <option class="text-black">MHT-CET</option>
      <option class="text-black">11th & 12th</option>
      <option class="text-black">Crash Course</option>
    </select>
    <label>Select Course</label>
  </div>

  <div class="input-group">
    <select id="type" required>
      <option value=""></option>
      <option class="text-black">Demo Class</option>
      <option class="text-black">More Information</option>
    </select>
    <label>Enquiry Type</label>
  </div>

  <div class="input-group">
    <input type="text" id="message">
    <label>Message (Optional)</label>
  </div>

  <!-- BUTTON -->
  <button type="submit"
    class="btn-main w-full py-3 rounded-lg font-bold text-white">
    Submit Enquiry
  </button>

</form>

</div>

</section>

<?php include 'footer.php'; ?>

<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
AOS.init({duration:1000, once:true});
</script>
<script>
document.getElementById("enquiryForm").addEventListener("submit", function(e) {

    e.preventDefault();

    let name = document.getElementById("name").value.trim();
    let phone = document.getElementById("phone").value.trim();
    let course = document.getElementById("course").value;
    let type = document.getElementById("type").value;
    let message = document.getElementById("message").value.trim();

    // ===== VALIDATION =====

    // NAME VALIDATION
    if(name.length < 3){
        alert("Please enter valid full name");
        return;
    }

    // PHONE VALIDATION
    let phonePattern = /^[6-9]\d{9}$/;

    if(!phonePattern.test(phone)){
        alert("Please enter valid 10 digit mobile number");
        return;
    }

    // COURSE VALIDATION
    if(course === ""){
        alert("Please select course");
        return;
    }

    // TYPE VALIDATION
    if(type === ""){
        alert("Please select enquiry type");
        return;
    }

    // ===== WHATSAPP MESSAGE =====

    let whatsappMessage =
`🎓 *New Enquiry - Radiant Coaching* %0A%0A
👤 Name: ${name}%0A
📞 Phone: ${phone}%0A
📚 Course: ${course}%0A
📝 Enquiry Type: ${type}%0A
💬 Message: ${message}`;

    let whatsappNumber = "918421783479";

    // OPEN WHATSAPP
    window.open(
      `https://wa.me/${whatsappNumber}?text=${whatsappMessage}`,
      "_blank"
    );

});
</script>
</body>
</html>