<?php
include('../src/conn.php');

$extra = $conn->prepare("
SELECT 
    extra_curricular_activities.id,
    extra_curricular_activities.occasion,
    extra_curricular_activities.description,
    COALESCE(
        JSON_ARRAYAGG(activity_images.image),
        JSON_ARRAY()
    ) AS images
FROM extra_curricular_activities
LEFT JOIN activity_images 
    ON extra_curricular_activities.id = activity_images.activity_id
    AND activity_images.image IS NOT NULL
    AND activity_images.image != ''
GROUP BY extra_curricular_activities.id
");

$extra->execute();

$extras = $extra->fetchAll(PDO::FETCH_ASSOC);

foreach ($extras as &$course) {
  $course['images'] = json_decode($course['images'], true);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Extra-curricular activities at Radiant Coaching Classes — building confidence, leadership and real-world skills beyond academics.">
  <title>Extra-Curricular Activities — Radiant Coaching Classes</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,500;0,9..144,600;0,9..144,700;1,9..144,500&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script src="assets/tailwind-config.js"></script>
  <link rel="stylesheet" href="assets/site.css">

  <style>body { background: #F5F5F2; }</style>
</head>

<body class="bg-paper text-ink antialiased">

  <?php include 'header.php' ?>

  <!-- ================= PAGE HERO ================= -->
  <section class="page-hero relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 py-16 md:py-24">
      <p class="reveal text-sm font-medium text-gold mb-4">Beyond academics</p>
      <h1 class="reveal font-display font-semibold text-4xl md:text-6xl text-white leading-[1.08]">Extra-Curricular Activities</h1>
      <p class="reveal mt-5 text-white/70 max-w-xl text-base md:text-lg">Building confidence, leadership and real-world skills.</p>
    </div>
  </section>

  <!-- ================= ACTIVITIES ================= -->
  <section class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 py-16 md:py-24">
    <?php
    for ($i = 0; $i < count($extras); $i++) {
      $images = array_filter($extras[$i]['images']);
      if (empty($images)) continue;
    ?>

      <div class="reveal mb-10 <?= $i > 0 ? 'mt-20' : '' ?>">
        <h2 class="font-display font-semibold text-3xl md:text-4xl text-ink"><?= $extras[$i]['occasion']; ?></h2>
        <p class="text-slate mt-3 max-w-md"><?= $extras[$i]['description']; ?></p>
      </div>

      <div class="grid md:grid-cols-3 gap-6">
        <?php foreach ($images as $extra_activ): ?>
          <div class="reveal img-zoom rounded-lg overflow-hidden border border-line">
            <img src="../src/uploads/activities/<?= $extra_activ ?>" class="w-full h-64 object-cover">
          </div>
        <?php endforeach; ?>
      </div>

    <?php } ?>
  </section>

  <!-- ================= CTA ================= -->
  <section class="bg-ink text-white text-center">
    <div class="max-w-2xl mx-auto px-5 py-16 md:py-20">
      <h2 class="reveal font-display font-semibold text-3xl md:text-4xl mb-4">Grow beyond academics</h2>
      <p class="reveal text-white/70 mb-8">Join Radiant and build skills for life, not just for exams.</p>
      <a href="enquiry.php" class="reveal inline-flex items-center bg-white text-ink font-medium px-8 py-3.5 rounded-md hover:bg-gold hover:text-white transition-colors">
        Join Now
      </a>
    </div>
  </section>

  <?php include 'footer.php' ?>

  <script src="assets/site.js"></script>

</body>
</html>
