<?php
include('../src/conn.php');

$extra = $conn->prepare("
SELECT 
    galleries.id,
    galleries.title,
    galleries.description,
    COALESCE(
        JSON_ARRAYAGG(gallery_images.image),
        JSON_ARRAY()
    ) AS images
FROM galleries
LEFT JOIN gallery_images 
    ON galleries.id = gallery_images.gallery_id
    AND gallery_images.image IS NOT NULL
    AND gallery_images.image != ''
GROUP BY galleries.id
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
  <meta name="description" content="Gallery — moments of success and growth at Radiant Coaching Classes.">
  <title>Gallery — Radiant Coaching Classes</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,500;0,9..144,600;0,9..144,700;1,9..144,500&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script src="assets/tailwind-config.js"></script>
  <link rel="stylesheet" href="assets/site.css">

  <style>
    body { background: #F5F5F2; }
    .modal-content { max-height: 85vh; object-fit: contain; }
  </style>
</head>

<body class="bg-paper text-ink antialiased">

  <?php include 'header.php' ?>

  <!-- ================= PAGE HERO ================= -->
  <section class="page-hero relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 py-16 md:py-24">
      <p class="reveal text-sm font-medium text-gold mb-4">Life at Radiant</p>
      <h1 class="reveal font-display font-semibold text-4xl md:text-6xl text-white leading-[1.08]">Our Gallery</h1>
      <p class="reveal mt-5 text-white/70 max-w-xl text-base md:text-lg">Moments of success, growth and excellence.</p>
    </div>
  </section>

  <!-- ================= GALLERY ================= -->
  <section class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 py-16 md:py-24">
    <?php
    for ($i = 0; $i < count($extras); $i++) {
      $images = array_filter($extras[$i]['images']);
      if (empty($images)) continue;
    ?>

      <div class="reveal mb-10 <?= $i > 0 ? 'mt-20' : '' ?>">
        <h2 class="font-display font-semibold text-3xl md:text-4xl text-ink"><?= $extras[$i]['title']; ?></h2>
        <p class="text-slate mt-3 max-w-md"><?= $extras[$i]['description']; ?></p>
      </div>

      <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        <?php foreach ($images as $extra_activ): ?>
          <div class="reveal gallery-card img-zoom rounded-lg overflow-hidden border border-line cursor-pointer">
            <img src="../src/uploads/gallery/<?= $extra_activ ?>" class="w-full h-64 object-cover">
          </div>
        <?php endforeach; ?>
      </div>

    <?php } ?>
  </section>

  <!-- ================= MODAL (LIGHTBOX) ================= -->
  <div id="modal" class="fixed inset-0 bg-ink/95 hidden items-center justify-center z-[100000] p-6">
    <img id="modalImg" class="modal-content max-w-5xl w-full rounded-lg hidden">
    <video id="modalVideo" controls class="modal-content max-w-5xl w-full rounded-lg hidden"></video>
  </div>

  <?php include 'footer.php' ?>

  <script src="assets/site.js"></script>
  <script>
    const modal = document.getElementById("modal");
    const modalImg = document.getElementById("modalImg");
    const modalVideo = document.getElementById("modalVideo");

    document.querySelectorAll(".gallery-card img").forEach(img => {
      img.onclick = () => {
        modal.classList.remove("hidden");
        modal.classList.add("flex");
        modalImg.src = img.src;
        modalImg.classList.remove("hidden");
        modalVideo.classList.add("hidden");
      };
    });

    document.querySelectorAll(".gallery-card video").forEach(video => {
      video.onclick = () => {
        modal.classList.remove("hidden");
        modal.classList.add("flex");
        modalVideo.src = video.querySelector("source").src;
        modalVideo.classList.remove("hidden");
        modalImg.classList.add("hidden");
        modalVideo.play();
      };
    });

    modal.onclick = () => {
      modal.classList.add("hidden");
      modal.classList.remove("flex");
      modalVideo.pause();
    };
  </script>

</body>
</html>
