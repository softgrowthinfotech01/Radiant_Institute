<?php
include('../src/conn.php');

$extra = $conn->prepare("
SELECT 
    results.id,
    results.year,
    courses.title,
    courses.description,

    COALESCE(
        JSON_ARRAYAGG(result_images.image),
        JSON_ARRAY()
    ) AS images

FROM results

LEFT JOIN courses 
    ON courses.id = results.course_id

LEFT JOIN result_images 
    ON results.id = result_images.result_id
    AND result_images.image IS NOT NULL
    AND result_images.image != ''

GROUP BY results.id
ORDER BY results.year DESC
");

$extra->execute();

$extras = $extra->fetchAll(PDO::FETCH_ASSOC);

/* Convert JSON images → PHP array */
foreach ($extras as &$row) {
  $row['images'] = array_filter(
    json_decode($row['images'], true) ?? []
  );
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Radiant Coaching Classes results — top ranks in JEE, NEET, MHT-CET and Boards, year after year.">
  <title>Our Results — Radiant Coaching Classes</title>

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
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 py-16 md:py-24">
      <p class="reveal text-sm font-medium text-gold mb-4">Results</p>
      <h1 class="reveal font-display font-semibold text-4xl md:text-6xl text-white leading-[1.08]">Top Results</h1>
      <p class="reveal mt-5 text-white/70 max-w-xl text-base md:text-lg">Producing rankers, year after year.</p>
    </div>
  </section>

  <!-- ================= RESULTS ================= -->
  <section class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 py-16 md:py-24">
    <?php foreach ($extras as $extra):
      $images = array_filter($extra['images'] ?? []);
      if (empty($images)) continue;
    ?>

      <div class="reveal flex items-center gap-4 mb-8">
        <h2 class="font-display font-semibold text-3xl md:text-4xl text-ink"><?= htmlspecialchars($extra['year']); ?></h2>
        <span class="flex-1 h-px bg-line"></span>
      </div>

      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mb-16">
        <?php foreach ($images as $img): ?>
          <div class="reveal gallery-card img-zoom rounded-lg overflow-hidden border border-line">
            <img
              src="../src/uploads/results/<?= htmlspecialchars($img) ?>"
              class="w-full h-64 object-cover"
              loading="lazy"
              onerror="this.closest('.gallery-card').remove();">
          </div>
        <?php endforeach; ?>
      </div>

    <?php endforeach; ?>
  </section>

  <!-- ================= CTA ================= -->
  <section class="bg-brand text-white text-center">
    <div class="max-w-2xl mx-auto px-5 py-16 md:py-20">
      <h2 class="reveal font-display font-semibold text-3xl md:text-4xl mb-4">Be the next topper</h2>
      <p class="reveal text-white/85 mb-8">Join Radiant Coaching and start your success journey today.</p>
      <a href="enquiry.php" class="reveal inline-flex items-center bg-white text-brand font-medium px-8 py-3.5 rounded-md hover:bg-ink hover:text-white transition-colors">
        Join JEE Batch
      </a>
    </div>
  </section>

  <?php include 'footer.php'; ?>

  <script src="assets/site.js"></script>

</body>
</html>
