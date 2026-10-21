<?php
include('../src/conn.php');

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM course_details WHERE course_id = $id");
$stmt->execute();

$res  = $stmt->fetch(PDO::FETCH_ASSOC);

$data = $res['course_id'];

$ret = $conn->prepare("SELECT * FROM courses where id = $data and status = 1");
$ret->execute();

$sel = $ret->fetch(PDO::FETCH_ASSOC);

if ($res == null) {
   echo "<script>alert('please fill details for this course.');</script>";
   echo "<script>window.location.href='home';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title><?= htmlspecialchars($sel['title'] ?? 'Course'); ?> Preparation — Radiant Coaching Classes</title>

   <script src="https://cdn.tailwindcss.com"></script>
   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,500;0,9..144,600;0,9..144,700;1,9..144,500&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
   <script src="assets/tailwind-config.js"></script>
   <link rel="stylesheet" href="assets/site.css">

   <style>
      body { background: #F5F5F2; }
      .prose-body :is(p, ul, ol) { margin-bottom: .75rem; }
      .prose-body ul, .prose-body ol { padding-left: 1.25rem; list-style: disc; }
   </style>
</head>

<body class="bg-paper text-ink antialiased">

   <?php include 'header.php'; ?>

   <!-- ================= PAGE HERO ================= -->
   <section class="page-hero relative overflow-hidden">
      <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 py-16 md:py-24 text-center">
         <p class="reveal text-sm font-medium text-gold mb-4">Course</p>
         <h1 class="reveal font-display font-semibold text-4xl md:text-6xl text-white leading-[1.08]">
            <?= $sel['title']; ?> Preparation Program
         </h1>
         <?php if ($sel['title'] == 'JEE') { ?>
            <p class="reveal mt-5 text-white/70 max-w-2xl mx-auto text-base md:text-lg">
               Crack IIT-JEE Mains and Advanced with Chandrapur's best faculty.
            </p>
         <?php } else { ?>
            <p class="reveal mt-5 text-white/70 max-w-2xl mx-auto text-base md:text-lg">
               Crack <?= $sel['title']; ?> with Chandrapur's best faculty.
            </p>
         <?php } ?>
         <a href="enquiry.php" class="reveal inline-flex items-center mt-8 bg-white text-ink font-medium px-7 py-3.5 rounded-md hover:bg-gold hover:text-white transition-colors">
            More information
         </a>
      </div>
   </section>

   <!-- ================= OVERVIEW ================= -->
   <section class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 py-16 md:py-24">

      <div class="reveal max-w-2xl mx-auto text-center mb-14">
         <h2 class="font-display font-semibold text-3xl md:text-4xl text-ink">
            More about our <?= $sel['title']; ?> program
         </h2>
         <p class="text-slate mt-4">Designed for serious aspirants.</p>
      </div>

      <?php if ($sel) { ?>
         <div class="reveal prose-body text-slate leading-relaxed bg-white border border-line rounded-lg p-8 md:p-10 max-w-4xl mx-auto">
            <?= $sel['description']; ?>
         </div>
      <?php } ?>

      <div class="grid md:grid-cols-3 gap-6 max-w-6xl mx-auto mt-10">
         <?php if ($res['why_title_1']) { ?>
            <div class="reveal bg-white border border-line rounded-lg p-6">
               <h3 class="font-display font-semibold text-lg text-ink"><?= $res['why_title_1']; ?></h3>
               <p class="text-slate text-sm mt-2 leading-relaxed"><?= $res['why_description_1']; ?></p>
            </div>
         <?php } ?>
         <?php if ($res['why_title_2']) { ?>
            <div class="reveal bg-white border border-line rounded-lg p-6">
               <h3 class="font-display font-semibold text-lg text-ink"><?= $res['why_title_2']; ?></h3>
               <p class="text-slate text-sm mt-2 leading-relaxed"><?= $res['why_description_2']; ?></p>
            </div>
         <?php } ?>
         <?php if ($res['why_title_3']) { ?>
            <div class="reveal bg-white border border-line rounded-lg p-6">
               <h3 class="font-display font-semibold text-lg text-ink"><?= $res['why_title_3']; ?></h3>
               <p class="text-slate text-sm mt-2 leading-relaxed"><?= $res['why_description_3']; ?></p>
            </div>
         <?php } ?>
      </div>

   </section>

   <!-- ================= RESULTS LINK ================= -->
   <section class="border-t border-line bg-white py-10 px-5 text-center">
      <a href="jeeresult" class="inline-flex items-center text-ink font-medium px-2 py-2 border-b-2 border-ink hover:border-brand hover:text-brand transition-colors">
         See our results →
      </a>
   </section>

   <!-- ================= FEATURES / HIGHLIGHTS ================= -->
   <?php if ($res['highlight_image'] || $res['program_highlight']) { ?>
      <section class="bg-ink text-white">
         <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 py-16 md:py-24">

            <div class="reveal max-w-2xl mx-auto text-center mb-14">
               <h2 class="font-display font-semibold text-3xl md:text-4xl">
                  Why Choose Our <?= $sel['title']; ?> Program
               </h2>
               <p class="text-white/60 mt-4">Designed for serious aspirants.</p>
            </div>

            <div class="grid md:grid-cols-2 gap-12 items-center">
               <?php if ($res['highlight_image']) { ?>
                  <div class="reveal rounded-lg overflow-hidden">
                     <img src="../src/uploads/course-details/<?= $res['highlight_image']; ?>" class="w-full h-full object-cover">
                  </div>
               <?php } ?>

               <?php if ($res['program_highlight']) { ?>
                  <div class="reveal">
                     <h3 class="text-sm font-semibold text-white/50 tracking-wide mb-5">Program highlights</h3>
                     <p class="font-display font-semibold text-2xl md:text-3xl leading-snug"><?= $res['program_highlight']; ?></p>
                  </div>
               <?php } ?>
            </div>

         </div>
      </section>
   <?php } ?>

   <!-- ================= SCHOLARSHIP ================= -->
   <section class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 py-16 md:py-24">
      <div class="reveal max-w-2xl mb-14">
         <h2 class="font-display font-semibold text-3xl md:text-4xl text-ink">
            Scholarship program for <?= $sel['title']; ?>
         </h2>
      </div>

      <div class="reveal grid md:grid-cols-2 gap-10 bg-white border border-line rounded-lg p-8 md:p-10">
         <div>
            <h3 class="font-display font-semibold text-xl text-ink mb-5">Scholarship details</h3>
            <ul class="space-y-2.5 text-slate text-sm leading-relaxed list-disc pl-5">
               <li>Students must appear for the Scholarship Examination conducted by our institute.</li>
               <li>The examination will be of 100 questions.</li>
               <li>Scholarships will be awarded based on marks obtained in the examination.</li>
               <li>Students securing higher ranks will receive greater fee concessions.</li>
               <li>The scholarship is applicable only for the current academic session.</li>
               <li>Students must submit all required documents during admission.</li>
               <li>The scholarship amount cannot be exchanged for cash and is non-transferable.</li>
               <li>Regular attendance and good academic performance may be required to continue the scholarship.</li>
               <li>The institute reserves the right to modify scholarship rules and eligibility criteria.</li>
            </ul>
         </div>
         <div>
            <h3 class="font-display font-semibold text-xl text-ink mb-5">Scholarship slabs</h3>
            <div class="space-y-3">
               <div class="flex items-center justify-between border-b border-line pb-3">
                  <span class="text-slate text-sm">90 – 100 marks</span>
                  <span class="font-medium text-ink text-sm">Up to 50% scholarship</span>
               </div>
               <div class="flex items-center justify-between border-b border-line pb-3">
                  <span class="text-slate text-sm">80 – 89 marks</span>
                  <span class="font-medium text-ink text-sm">Up to 30% scholarship</span>
               </div>
               <div class="flex items-center justify-between border-b border-line pb-3">
                  <span class="text-slate text-sm">70 – 79 marks</span>
                  <span class="font-medium text-ink text-sm">Up to 20% scholarship</span>
               </div>
               <div class="flex items-center justify-between">
                  <span class="text-slate text-sm">60 – 69 marks</span>
                  <span class="font-medium text-ink text-sm">Up to 10% scholarship</span>
               </div>
            </div>
         </div>
      </div>
   </section>

   <!-- ================= CTA ================= -->
   <section class="bg-ink text-white text-center">
      <div class="max-w-2xl mx-auto px-5 py-16 md:py-20">
         <h2 class="reveal font-display font-semibold text-3xl md:text-4xl mb-4">Start your journey today</h2>
         <p class="reveal text-white/70 mb-8">Join Radiant Coaching and work towards a top rank in <?= $sel['title']; ?>.</p>
         <a href="enquiry.php" class="reveal inline-flex items-center bg-white text-ink font-medium px-8 py-3.5 rounded-md hover:bg-gold hover:text-white transition-colors">
            Book a free demo
         </a>
      </div>
   </section>

   <?php include 'footer.php'; ?>

   <script src="assets/site.js"></script>

</body>
</html>
