<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>MHT-CET Coaching — Radiant Coaching Classes</title>

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
<section class="page-hero relative overflow-hidden">
  <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 py-16 md:py-24 grid md:grid-cols-2 gap-10 items-center">
    <div>
      <p class="reveal text-sm font-medium text-gold mb-4">MHT-CET</p>
      <h1 class="reveal font-display font-semibold text-4xl md:text-6xl text-white leading-[1.08]">
        Speed, accuracy, and a top percentile.
      </h1>
      <p class="reveal mt-5 text-white/70 text-base md:text-lg max-w-md">
        Master exam strategy to secure a top percentile in MHT-CET, with training built for Maharashtra's engineering aspirants.
      </p>
      <a href="enquiry.php" class="reveal inline-flex items-center mt-8 bg-white text-ink font-medium px-7 py-3.5 rounded-md hover:bg-gold hover:text-white transition-colors">
        Join CET Batch
      </a>
    </div>
    <div class="reveal rounded-lg overflow-hidden aspect-[4/3]">
      <img src="images/mht-cet.png" class="w-full h-full object-cover">
    </div>
  </div>
</section>

<!-- ================= OVERVIEW ================= -->
<section class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 py-16 md:py-24">
  <div class="reveal max-w-2xl mx-auto text-center mb-14">
    <h2 class="font-display font-semibold text-3xl md:text-4xl text-ink">Why Choose Our MHT-CET Program</h2>
    <p class="text-slate mt-4">Designed for Maharashtra students aiming for top engineering colleges.</p>
  </div>

  <div class="grid md:grid-cols-3 gap-6">
    <div class="reveal bg-white border border-line rounded-lg p-6">
      <h3 class="font-display font-semibold text-lg text-ink">Speed Training</h3>
      <p class="text-slate text-sm mt-2">Solve more questions in less time.</p>
    </div>
    <div class="reveal bg-white border border-line rounded-lg p-6">
      <h3 class="font-display font-semibold text-lg text-ink">MCQ Focus</h3>
      <p class="text-slate text-sm mt-2">Practice 1000+ MCQs per subject.</p>
    </div>
    <div class="reveal bg-white border border-line rounded-lg p-6">
      <h3 class="font-display font-semibold text-lg text-ink">High Accuracy</h3>
      <p class="text-slate text-sm mt-2">Avoid negative marking and improve your score.</p>
    </div>
  </div>
</section>

<!-- ================= HIGHLIGHTS ================= -->
<section class="bg-ink text-white">
  <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 py-16 md:py-24 grid md:grid-cols-2 gap-12 items-center">
    <div class="reveal rounded-lg overflow-hidden">
      <img src="images/mht-cet.png" class="w-full h-full object-cover">
    </div>
    <div class="reveal">
      <h3 class="text-sm font-semibold text-white/50 tracking-wide mb-5">Program highlights</h3>
      <div class="grid gap-4">
        <?php foreach ([
          'Daily CET-focused classes',
          'Speed-based practice sessions',
          'Chapter-wise MCQ drills',
          'Weekly CET pattern mock tests',
          'Rank-boosting shortcut techniques',
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

<!-- ================= SUBJECTS ================= -->
<section class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 py-16 md:py-24">
  <h2 class="reveal font-display font-semibold text-3xl md:text-4xl text-ink text-center mb-14">Subjects Covered</h2>

  <div class="grid gap-px bg-line border border-line rounded-lg overflow-hidden md:grid-cols-3">
    <div class="reveal bg-white p-8">
      <h3 class="font-display font-semibold text-lg text-ink mb-2">Physics</h3>
      <p class="text-slate text-sm">Concept clarity plus fast problem-solving.</p>
    </div>
    <div class="reveal bg-white p-8">
      <h3 class="font-display font-semibold text-lg text-ink mb-2">Chemistry</h3>
      <p class="text-slate text-sm">Theory plus quick revision techniques.</p>
    </div>
    <div class="reveal bg-white p-8">
      <h3 class="font-display font-semibold text-lg text-ink mb-2">Mathematics</h3>
      <p class="text-slate text-sm">Formula-based, fast calculation practice.</p>
    </div>
  </div>
</section>

<!-- ================= TEST SERIES ================= -->
<section class="bg-ink text-white text-center">
  <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 py-16 md:py-24">
    <h2 class="reveal font-display font-semibold text-3xl md:text-4xl mb-4">CET Test Series</h2>
    <p class="reveal text-white/70 max-w-2xl mx-auto mb-14">Practice real exam-pattern tests with speed and accuracy analysis.</p>

    <div class="grid md:grid-cols-3 gap-6">
      <div class="reveal bg-white/5 border border-white/10 rounded-lg p-8 font-medium">Full Mock Tests</div>
      <div class="reveal bg-white/5 border border-white/10 rounded-lg p-8 font-medium">Daily Practice Tests</div>
      <div class="reveal bg-white/5 border border-white/10 rounded-lg p-8 font-medium">Performance Tracking</div>
    </div>
  </div>
</section>

<!-- ================= CTA ================= -->
<section class="text-center">
  <div class="max-w-2xl mx-auto px-5 py-16 md:py-20">
    <h2 class="reveal font-display font-semibold text-3xl md:text-4xl text-ink mb-4">Crack CET with a top percentile</h2>
    <p class="reveal text-slate mb-8">Join Radiant Coaching and secure admission in a top engineering college.</p>
    <a href="enquiry.php" class="reveal inline-flex items-center bg-ink text-white font-medium px-8 py-3.5 rounded-md hover:bg-brand transition-colors">
      Enrol Now
    </a>
  </div>
</section>

<?php include 'footer.php'; ?>

<script src="assets/site.js"></script>

</body>
</html>
