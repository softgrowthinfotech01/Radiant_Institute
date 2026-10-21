<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Enquiry — Radiant Coaching Classes</title>

<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,500;0,9..144,600;0,9..144,700;1,9..144,500&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="assets/tailwind-config.js"></script>
<link rel="stylesheet" href="assets/site.css">

<style>
  body { background: #F5F5F2; }
  .input-group { position: relative; }
  .input-group label {
    position: absolute; top: 12px; left: 14px; color: #9099A6;
    font-size: 14px; transition: .2s; pointer-events: none; background: #fff; padding: 0 4px;
  }
  .input-group input:focus + label,
  .input-group input:valid + label,
  .input-group select:focus + label,
  .input-group select:valid + label {
    top: -9px; left: 10px; font-size: 12px; color: #C31F30;
  }
</style>
</head>

<body class="bg-paper text-ink antialiased min-h-screen flex flex-col">

<?php include 'header.php'; ?>

<!-- ================= MAIN ================= -->
<section class="flex-grow flex items-center justify-center px-5 py-12 md:py-20">

  <div class="bg-white border border-line rounded-lg p-6 md:p-12 w-full max-w-6xl grid md:grid-cols-2 gap-12 shadow-sm">

    <!-- LEFT -->
    <div class="reveal flex flex-col justify-center">
      <p class="text-sm font-medium text-brand mb-4">Get started</p>
      <h2 class="font-display font-semibold text-3xl md:text-4xl text-ink mb-4">
        Book your free demo
      </h2>
      <p class="text-slate mb-8">
        Start your journey with expert guidance and proven results.
      </p>

      <div class="space-y-3">
        <?php foreach ([
          'Expert faculty guidance',
          'Personalised study plan',
          'Free career counselling',
          'Scholarship opportunities',
        ] as $point) { ?>
          <div class="flex items-center gap-3">
            <span class="w-1.5 h-1.5 rounded-full bg-brand shrink-0"></span>
            <span class="text-slate text-sm"><?= $point ?></span>
          </div>
        <?php } ?>
      </div>
    </div>

    <!-- FORM -->
    <form id="enquiryForm" class="reveal space-y-5">

      <div class="input-group">
        <input type="text" id="name" required class="field">
        <label>Full Name</label>
      </div>

      <div class="input-group">
        <input type="tel" id="phone" required class="field">
        <label>Phone Number</label>
      </div>

      <div class="input-group">
        <select id="course" required class="field">
          <option value=""></option>
          <?php
          $stmt = $conn->prepare("SELECT * FROM courses WHERE status = 1");
          $stmt->execute();
          $ret = $stmt->fetchAll(PDO::FETCH_ASSOC);
          for ($i = 0; $i < count($ret); $i++) {
          ?>
            <option value="<?= $ret[$i]['title']; ?>"><?= $ret[$i]['title']; ?></option>
          <?php } ?>
        </select>
        <label>Select Course</label>
      </div>

      <div class="input-group">
        <select id="type" required class="field">
          <option value=""></option>
          <option>Demo Class</option>
          <option>More Information</option>
        </select>
        <label>Enquiry Type</label>
      </div>

      <div class="input-group">
        <input type="text" id="message" class="field">
        <label>Message (optional)</label>
      </div>

      <button type="submit" class="w-full bg-ink text-white py-3.5 rounded-md font-medium hover:bg-brand transition-colors">
        Submit Enquiry
      </button>

    </form>

  </div>

</section>

<?php include 'footer.php'; ?>

<script src="assets/site.js"></script>
<script>
document.getElementById("enquiryForm").addEventListener("submit", function(e) {
  e.preventDefault();

  let name = document.getElementById("name").value.trim();
  let phone = document.getElementById("phone").value.trim();
  let course = document.getElementById("course").value;
  let type = document.getElementById("type").value;
  let message = document.getElementById("message").value.trim();

  if (name.length < 3) { alert("Please enter a valid full name"); return; }

  let phonePattern = /^[6-9]\d{9}$/;
  if (!phonePattern.test(phone)) { alert("Please enter a valid 10 digit mobile number"); return; }

  if (course === "") { alert("Please select a course"); return; }
  if (type === "") { alert("Please select an enquiry type"); return; }

  let whatsappMessage =
`🎓 *New Enquiry - Radiant Coaching* %0A%0A
👤 Name: ${name}%0A
📞 Phone: ${phone}%0A
📚 Course: ${course}%0A
📝 Enquiry Type: ${type}%0A
💬 Message: ${message}`;

  let whatsappNumber = "918421783479";
  window.open(`https://wa.me/${whatsappNumber}?text=${whatsappMessage}`, "_blank");
});
</script>
</body>
</html>
