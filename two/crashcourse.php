<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Crash Course — Radiant Coaching Classes</title>

<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,500;0,9..144,600;0,9..144,700;1,9..144,500&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="assets/tailwind-config.js"></script>
<link rel="stylesheet" href="assets/site.css">

<style>body { background: #F5F5F2; }</style>
</head>

<body class="bg-paper text-ink antialiased">

<?php include 'header.php'; ?>

<!-- ================= PAGE HERO ================= -->
<section class="page-hero relative overflow-hidden text-center">
  <div class="max-w-3xl mx-auto px-5 py-16 md:py-24">
    <p class="reveal text-sm font-medium text-gold mb-4">Crash Course 2026</p>
    <h1 class="reveal font-display font-semibold text-4xl md:text-6xl text-white leading-[1.08]">
      Fast-track your final preparation.
    </h1>
    <p class="reveal mt-5 text-white/70 text-base md:text-lg">
      Intensive revision, mock tests and exam strategy — built for the final stretch.
    </p>
    <span class="reveal inline-block mt-6 text-xs font-medium text-ink bg-gold rounded-full px-4 py-2">
      Batch starts soon · Limited seats
    </span>
    <div>
      <a href="enquiry.php" class="reveal inline-flex items-center mt-8 bg-white text-ink font-medium px-7 py-3.5 rounded-md hover:bg-gold hover:text-white transition-colors">
        Enrol Now
      </a>
    </div>
  </div>
</section>

<!-- ================= FEATURES ================= -->
<section class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 py-16 md:py-24">
  <div class="reveal max-w-2xl mx-auto text-center mb-14">
    <h2 class="font-display font-semibold text-3xl md:text-4xl text-ink">Why a Crash Course?</h2>
    <p class="text-slate mt-4">Designed for last-minute preparation and maximum score improvement.</p>
  </div>

  <div class="grid md:grid-cols-3 gap-6">
    <div class="reveal bg-white border border-line rounded-lg p-6">
      <h3 class="font-display font-semibold text-lg text-ink">Revision Focus</h3>
      <p class="text-slate text-sm mt-2">Complete syllabus revision in a limited time.</p>
    </div>
    <div class="reveal bg-white border border-line rounded-lg p-6">
      <h3 class="font-display font-semibold text-lg text-ink">Mock Tests</h3>
      <p class="text-slate text-sm mt-2">Daily and full-length exam simulation.</p>
    </div>
    <div class="reveal bg-white border border-line rounded-lg p-6">
      <h3 class="font-display font-semibold text-lg text-ink">Exam Strategy</h3>
      <p class="text-slate text-sm mt-2">Time management and scoring techniques.</p>
    </div>
  </div>
</section>

<!-- ================= HIGHLIGHTS ================= -->
<section class="bg-ink text-white">
  <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 py-16 md:py-24 grid md:grid-cols-2 gap-12 items-center">
    <div class="reveal rounded-lg overflow-hidden">
      <img src="images/carsh course.png" class="w-full h-full object-cover">
    </div>
    <div class="reveal">
      <h3 class="text-sm font-semibold text-white/50 tracking-wide mb-5">Course highlights</h3>
      <div class="grid gap-4">
        <?php foreach ([
          'Daily 5–6 hours of intensive classes',
          'Chapter-wise quick revision',
          'Shortcut tricks and concepts',
          'Daily practice sheets',
          '5+ full mock tests',
          'Doubt-solving sessions',
        ] as $point) { ?>
          <div class="flex items-start gap-3">
            <span class="mt-1 w-1.5 h-1.5 rounded-full bg-gold shrink-0"></span>
            <p class="text-white/85 text-[15px] leading-relaxed"><?= $point ?></p>
          </div>
        <?php } ?>
      </div>
    </div>
  </div>
</section>

<!-- ================= STUDY PLAN ================= -->
<section class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 py-16 md:py-24">
  <h2 class="reveal font-display font-semibold text-3xl md:text-4xl text-ink text-center mb-14">Study Plan</h2>

  <div class="grid md:grid-cols-4 gap-6">
    <div class="reveal border border-line rounded-lg p-6 text-center">
      <span class="text-xs font-medium text-brand">Week 1</span>
      <p class="text-ink text-sm mt-2 font-medium">Concept Revision</p>
    </div>
    <div class="reveal border border-line rounded-lg p-6 text-center">
      <span class="text-xs font-medium text-brand">Week 2</span>
      <p class="text-ink text-sm mt-2 font-medium">Practice + Tests</p>
    </div>
    <div class="reveal border border-line rounded-lg p-6 text-center">
      <span class="text-xs font-medium text-brand">Week 3</span>
      <p class="text-ink text-sm mt-2 font-medium">Mock Tests</p>
    </div>
    <div class="reveal border border-line rounded-lg p-6 text-center">
      <span class="text-xs font-medium text-brand">Week 4</span>
      <p class="text-ink text-sm mt-2 font-medium">Final Strategy</p>
    </div>
  </div>
</section>

<!-- ================= CTA ================= -->
<section class="bg-ink text-white text-center">
  <div class="max-w-2xl mx-auto px-5 py-16 md:py-20">
    <h2 class="reveal font-display font-semibold text-3xl md:text-4xl mb-4">Boost your rank in just a few weeks</h2>
    <p class="reveal text-white/70 mb-8">Join the Crash Course and maximise your exam score.</p>
    <a href="enquiry.php" class="reveal inline-flex items-center bg-white text-ink font-medium px-8 py-3.5 rounded-md hover:bg-gold hover:text-white transition-colors">
      Book a Free Demo
    </a>
  </div>
</section>

<?php include 'footer.php'; ?>

<script src="assets/site.js"></script>

</body>
</html>
