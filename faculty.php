<?php
include('conn.php');

// only members enabled in the admin panel are shown, in the order they were added
$stmt = $conn->prepare("
  SELECT *
  FROM faculty
  WHERE status = 1
  ORDER BY id ASC
");

$stmt->execute();

$faculty = $stmt->fetchAll(PDO::FETCH_ASSOC);

function e($value)
{
  return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Our Faculty - Radiant</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

  <style>
    /* HERO ZOOM */
    .hero-bg {
      animation: zoom 12s infinite alternate;
    }

    @keyframes zoom {
      0% {
        transform: scale(1)
      }

      100% {
        transform: scale(1.08)
      }
    }

    /* WAVE */
    .wave {
      position: absolute;
      bottom: -1px;
      width: 100%;
    }

    /* GLOW */
    .glow {
      position: absolute;
      width: 400px;
      height: 400px;
      background: radial-gradient(circle, #E41C2A30, transparent);
      filter: blur(120px);
    }

    /* CARD */
    .card {
      transition: 0.4s;
    }

    .card:hover {
      transform: translateY(-10px);
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }

    /* IMAGE */
    .img {
      transition: 0.6s;
    }

    .card:hover .img {
      transform: scale(1.06);
    }
  </style>
</head>

<body class="bg-[#f5f7fb] text-gray-800">

  <?php include 'header.php' ?>

  <!-- ================= HERO ================= -->
  <section class="relative h-[70vh] flex items-center justify-center text-center text-white overflow-hidden">

    <!-- BG -->
    <div class="absolute inset-0 hero-bg bg-cover bg-center"
      style="background-image:url('images/hero-bg.png')"></div>

    <div class="absolute inset-0 bg-[#021969]/60"></div>

    <div class="relative z-10 px-6" data-aos="fade-up">
      <h1 class="text-4xl md:text-6xl font-bold text-[#E41C2A]">
        Our Faculty
      </h1>
      <p class="mt-4 text-gray-200 max-w-2xl mx-auto">
        Experienced mentors who guide every student toward their goal
      </p>
    </div>

    <!-- WAVE -->
    <svg class="wave" viewBox="0 0 1440 200">
      <path fill="#f5f7fb"
        d="M0,160L80,150C160,140,320,120,480,110C640,100,800,100,960,120C1120,140,1280,180,1360,190L1440,200V200H0Z"></path>
    </svg>

  </section>

  <!-- ================= FACULTY ================= -->
  <section class="py-20 px-6 md:px-16 relative">

    <div class="glow top-0 left-0"></div>

    <?php if (count($faculty) > 0) { ?>

      <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-10 max-w-6xl mx-auto">

        <?php foreach ($faculty as $member) { ?>

          <div class="card bg-white rounded-2xl overflow-hidden shadow-md flex flex-col" data-aos="zoom-in">

            <!-- PHOTO -->
            <div class="h-72 overflow-hidden bg-gray-100">

              <?php if ($member['image']) { ?>

                <img
                  src="admin/uploads/faculty/<?= rawurlencode($member['image']) ?>"
                  alt="<?= e($member['name']) ?>"
                  class="img w-full h-full object-cover object-top">

              <?php } else { ?>

                <div class="w-full h-full flex items-center justify-center bg-[#021969] text-white text-7xl font-bold">
                  <?= e(mb_strtoupper(mb_substr($member['name'], 0, 1))) ?>
                </div>

              <?php } ?>

            </div>

            <!-- DETAILS -->
            <div class="p-6 flex-1 flex flex-col">

              <h3 class="text-xl font-bold text-[#021969]">
                <?= e($member['name']) ?>
              </h3>

              <?php if ($member['designation']) { ?>
                <p class="text-[#E41C2A] font-semibold mt-1">
                  <?= e($member['designation']) ?>
                </p>
              <?php } ?>

              <div class="flex flex-wrap gap-2 mt-4">

                <?php if ($member['subject']) { ?>
                  <span class="px-3 py-1 rounded-full bg-[#021969]/10 text-[#021969] text-sm font-medium">
                    <?= e($member['subject']) ?>
                  </span>
                <?php } ?>

                <?php if ($member['experience']) { ?>
                  <span class="px-3 py-1 rounded-full bg-[#E41C2A]/10 text-[#E41C2A] text-sm font-medium">
                    <?= e($member['experience']) ?> experience
                  </span>
                <?php } ?>

              </div>

              <?php if ($member['qualification']) { ?>
                <p class="text-sm text-gray-500 mt-4">
                  <?= e($member['qualification']) ?>
                </p>
              <?php } ?>

              <?php if ($member['description']) { ?>
                <p class="text-gray-600 mt-3 text-sm leading-relaxed">
                  <?= nl2br(e($member['description'])) ?>
                </p>
              <?php } ?>

            </div>

          </div>

        <?php } ?>

      </div>

    <?php } else { ?>

      <p class="text-center text-gray-500 py-10">
        Our faculty details will be updated soon.
      </p>

    <?php } ?>

  </section>

  <!-- ================= CTA ================= -->
  <section class="py-20 text-center px-6 bg-[#021969] text-white">

    <h2 class="text-4xl font-bold mb-6">
      Learn From The Best 🎓
    </h2>

    <p class="text-gray-300 mb-6">
      Join Radiant and get guidance from teachers who care about your results.
    </p>

    <a href="enquiry"
      class="inline-block px-8 py-3 bg-green-500 text-black font-bold rounded-full shadow-lg hover:scale-105 transition duration-300">
      Join Now
    </a>

  </section>

  <?php include 'footer.php' ?>

  <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>

  <script>
    AOS.init({
      duration: 1000,
      once: true
    });
  </script>

</body>

</html>
