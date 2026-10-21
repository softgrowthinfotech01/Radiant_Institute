<?php
include('../src/conn.php');
$select = $conn->prepare("
SELECT 
    courses.id,
    courses.title,
    courses.description,
    courses.status,
    COALESCE(
        JSON_ARRAYAGG(course_images.image),
        JSON_ARRAY()
    ) AS images
FROM courses
LEFT JOIN course_images 
    ON courses.id = course_images.course_id
    AND course_images.image IS NOT NULL
    AND course_images.image != ''
WHERE courses.status = 1
GROUP BY courses.id
");

$select->execute();

$coursess = $select->fetchAll(PDO::FETCH_ASSOC);

foreach ($coursess as &$course) {
  $course['images'] = json_decode($course['images'], true);
}
// echo "<pre>";
// print_r($coursess);
// exit;

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
// echo "<pre>";
// print_r($extras);
// exit;

// gallery

$gallery = $conn->prepare("SELECT image FROM gallery_images LIMIT 4");
$gallery->execute();

$galleries = $gallery->fetchAll(PDO::FETCH_ASSOC);

/*
|--------------------------------------------------------------------------
| FETCH ACTIVE CRASH COURSE
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
SELECT 
    crash_courses.id,
    crash_courses.title,
    crash_courses.start_date,
    crash_courses.duration,
    crash_courses.description,
    COALESCE(
        JSON_ARRAYAGG(crash_course_highlights.highlight),
        JSON_ARRAY()
    ) AS images
FROM crash_courses
LEFT JOIN crash_course_highlights 
    ON crash_courses.id = crash_course_highlights.crash_course_id
    AND crash_course_highlights.highlight IS NOT NULL
    AND crash_course_highlights.highlight != ''

WHERE crash_courses.status = 1

GROUP BY crash_courses.id
ORDER BY crash_courses.id DESC
");

$stmt->execute();

$stmts = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($stmts as &$course) {
  $course['high'] = json_decode($course['images'], true);
}
// echo "<pre>";
// print_r($stmts);
// exit;


$result = $conn->prepare("
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
ORDER BY results.year DESC LIMIT 4
");

$result->execute();

$results = $result->fetchAll(PDO::FETCH_ASSOC);


/* Convert JSON images → PHP array */
foreach ($results as &$row) {
  $row['images'] = array_filter(
    json_decode($row['images'], true) ?? []
  );
}

// echo "<pre>";
// print_r($results);
// exit;

/* =========================
   FETCH SLIDER IMAGES
========================= */

$sliderStmt = $conn->prepare("
    SELECT image
    FROM sliders
    ORDER BY id DESC
");

$sliderStmt->execute();

$sliders = $sliderStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Radiant Coaching Classes, Chandrapur — coaching for JEE, NEET, MHT-CET, Boards and foundation students.">
  <title>Radiant Coaching Classes — JEE, NEET &amp; MHT-CET Coaching in Chandrapur</title>

  <script src="https://cdn.tailwindcss.com"></script>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,500;0,9..144,600;0,9..144,700;1,9..144,500&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            ink: '#10233F',
            brand: '#C31F30',
            paper: '#F5F5F2',
            line: '#E2E1DC',
            slate: '#5B6472',
            gold: '#B8862E'
          },
          fontFamily: {
            display: ['Fraunces', 'serif'],
            sans: ['Inter', 'sans-serif']
          }
        }
      }
    }
  </script>

  <style>
    html { scroll-behavior: smooth; }
    body { font-family: 'Inter', sans-serif; background: #F5F5F2; }

    /* reveal on scroll */
    .reveal { opacity: 0; transform: translateY(18px); transition: opacity .7s ease, transform .7s ease; }
    .reveal.show { opacity: 1; transform: translateY(0); }
    @media (prefers-reduced-motion: reduce) {
      .reveal { opacity: 1; transform: none; transition: none; }
    }

    /* hero slider */
    #heroSlider { position: relative; overflow: hidden; background: #0B1B33; }
    .slide { position: absolute; inset: 0; opacity: 0; transition: opacity .8s ease; }
    .slide.active { opacity: 1; z-index: 1; }
    .slide img { width: 100%; height: 100%; object-fit: cover; }

    .heroDot { width: 6px; height: 6px; border-radius: 999px; background: rgba(255,255,255,.35); transition: all .3s ease; cursor: pointer; }
    .heroDot.active { background: #fff; width: 20px; }

    /* gallery marquee */
    .marquee-track { display: flex; width: max-content; animation: marquee 32s linear infinite; }
    .marquee-track:hover { animation-play-state: paused; }
    @keyframes marquee { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }

    .course-card:hover .course-img { transform: scale(1.04); }
    .course-img { transition: transform .6s ease; }

    input:focus-visible, button:focus-visible, a:focus-visible { outline: 2px solid #C31F30; outline-offset: 2px; }
  </style>
</head>

<?php include 'header.php'; ?>

<body class="bg-paper text-ink antialiased">

  <!-- ================= HERO ================= -->
  <section class="relative border-b border-line">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 py-12 md:py-20 grid md:grid-cols-2 gap-12 md:gap-10 items-center">

      <!-- CONTENT -->
      <div class="reveal order-2 md:order-1">
        <p class="text-sm font-medium text-brand mb-4">Chandrapur's coaching institute for competitive exams</p>

        <h1 class="font-display font-semibold text-[2.4rem] leading-[1.08] sm:text-5xl md:text-[3.4rem] md:leading-[1.06] text-ink">
          Serious preparation, for students who mean business.
        </h1>

        <p class="mt-6 text-slate text-base md:text-lg max-w-md leading-relaxed">
          Radiant Coaching Classes prepares students for JEE, NEET, MHT-CET and Board exams
          through structured teaching, regular testing and honest, one-on-one attention.
        </p>

        <div class="mt-8 flex flex-wrap items-center gap-4">
          <a href="enquiry" class="inline-flex items-center bg-ink text-white font-medium px-7 py-3.5 rounded-md hover:bg-brand transition-colors">
            Book a seat
          </a>
          <a href="jeeresult" class="inline-flex items-center text-ink font-medium px-2 py-3.5 border-b-2 border-ink hover:border-brand hover:text-brand transition-colors">
            See our results
          </a>
        </div>

        <div class="mt-10 pt-8 border-t border-line flex flex-wrap gap-x-8 gap-y-3 text-sm text-slate">
          <span>JEE</span>
          <span class="text-line">/</span>
          <span>NEET</span>
          <span class="text-line">/</span>
          <span>MHT&#8209;CET</span>
          <span class="text-line">/</span>
          <span>11th &amp; 12th Boards</span>
        </div>
      </div>

      <!-- SLIDER -->
      <div class="order-1 md:order-2 reveal">
        <div id="heroSlider" class="w-full aspect-[4/5] sm:aspect-[5/4] md:aspect-[4/5] rounded-lg overflow-hidden">
          <?php foreach ($sliders as $key => $slide): ?>
            <div class="slide <?= $key == 0 ? 'active' : '' ?>">
              <img src="../src/uploads/sliders/<?= htmlspecialchars($slide['image']) ?>" alt="Radiant Coaching Classes">
            </div>
          <?php endforeach; ?>
        </div>
        <?php if (count($sliders) > 1): ?>
          <div id="heroDots" class="flex justify-center gap-2 mt-5"></div>
        <?php endif; ?>
      </div>

    </div>
  </section>

  <!-- ================= OUR COURSES ================= -->
  <section class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 py-16 md:py-24">
    <div class="reveal mb-12 md:mb-14 flex flex-col md:flex-row md:items-end md:justify-between gap-4">
      <div>
        <h2 class="font-display font-semibold text-3xl md:text-4xl text-ink">Our Courses</h2>
        <p class="text-slate mt-3 max-w-md">Choose the right track for your goal — every course is built around the exam it targets.</p>
      </div>
    </div>

    <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-4">
      <?php for ($i = 0; $i < count($coursess); $i++) { ?>
        <a href="jee?id=<?= $coursess[$i]['id']; ?>" class="course-card reveal group block bg-white rounded-lg border border-line overflow-hidden hover:shadow-lg transition-shadow">
          <div class="overflow-hidden aspect-[4/3]">
            <img src="../src/uploads/courses/<?= $coursess[$i]['images'][0]; ?>" class="course-img w-full h-full object-cover">
          </div>
          <div class="p-6">
            <h3 class="font-display font-semibold text-lg text-ink mb-2"><?= $coursess[$i]['title']; ?></h3>
            <p class="text-slate text-sm leading-relaxed line-clamp-2"><?= $coursess[$i]['description']; ?></p>
            <span class="mt-4 inline-flex items-center gap-1.5 text-sm font-medium text-brand">
              View course
              <span class="transition-transform duration-300 group-hover:translate-x-1">→</span>
            </span>
          </div>
        </a>
      <?php } ?>
    </div>
  </section>

  <!-- ================= CRASH COURSE ================= -->
  <?php if (!empty($stmts)): ?>
    <section class="bg-ink text-white">
      <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 py-16 md:py-24">
        <?php for ($i = 0; $i < count($stmts); $i++) { ?>
          <div class="grid md:grid-cols-2 gap-12 items-start">

            <div class="reveal">
              <span class="inline-block text-xs font-medium tracking-wide text-gold border border-gold/40 rounded-full px-3 py-1 mb-6">
                Batch starts <?= date('d F', strtotime($stmts[$i]['start_date'])) ?>
              </span>
              <h2 class="font-display font-semibold text-3xl md:text-5xl leading-[1.1]">
                <?= htmlspecialchars($stmts[$i]['title']) ?>
              </h2>
              <p class="mt-5 text-white/70 text-base md:text-lg leading-relaxed max-w-md">
                <?= htmlspecialchars($stmts[$i]['description']) ?>
              </p>
              <a href="enquiry.php" class="mt-8 inline-flex items-center bg-white text-ink font-medium px-7 py-3.5 rounded-md hover:bg-gold hover:text-white transition-colors">
                Enrol now
              </a>
            </div>

            <div class="reveal bg-white/5 border border-white/10 rounded-lg p-8">
              <h3 class="text-sm font-semibold text-white/50 tracking-wide mb-6">Course highlights</h3>
              <div class="grid gap-4">
                <?php foreach ($stmts[$i]['high'] as $highlight) { ?>
                  <div class="flex items-start gap-3">
                    <span class="mt-1 w-1.5 h-1.5 rounded-full bg-gold shrink-0"></span>
                    <p class="text-white/85 text-[15px] leading-relaxed"><?= htmlspecialchars($highlight) ?></p>
                  </div>
                <?php } ?>
              </div>
            </div>

          </div>
        <?php } ?>
      </div>
    </section>
  <?php endif; ?>

  <!-- ================= RESULTS ================= -->
  <?php if (!empty($results)): ?>
    <section class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 py-16 md:py-24">
      <div class="reveal mb-12 md:mb-14">
        <h2 class="font-display font-semibold text-3xl md:text-4xl text-ink">Our Results</h2>
        <p class="text-slate mt-3 max-w-md">Real outcomes, year on year.</p>
      </div>

      <div class="grid md:grid-cols-2 gap-6">
        <?php foreach ($results as $extra):
          $images = array_filter($extra['images'] ?? []);
          if (empty($images)) continue;
          $firstImage = reset($images);
        ?>
          <div class="reveal group relative rounded-lg overflow-hidden border border-line">
            <img src="../src/uploads/results/<?= htmlspecialchars($firstImage) ?>"
              class="w-full h-72 md:h-80 object-cover transition-transform duration-700 group-hover:scale-[1.03]"
              loading="lazy"
              onerror="this.closest('.group').remove();">
            <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-ink/85 to-transparent px-6 pt-14 pb-5">
              <span class="font-display font-semibold text-2xl text-white"><?= htmlspecialchars($extra['year']); ?></span>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </section>
  <?php endif; ?>

  <!-- ================= ABOUT ================= -->
  <section id="aboutus" class="border-t border-line bg-white">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 py-16 md:py-24 grid md:grid-cols-2 gap-12 md:gap-16 items-center">

      <div class="reveal order-2 md:order-1">
        <p class="text-sm font-medium text-brand mb-4">About us</p>
        <h2 class="font-display font-semibold text-3xl md:text-4xl text-ink mb-6">
          Built for students who want to be exam-ready, not just taught.
        </h2>
        <div class="text-slate text-base leading-relaxed space-y-4 max-w-lg">
          <p>Radiant Coaching Classes is a leading institute dedicated to shaping the future of students preparing for competitive exams like MHT&#8209;CET, JEE, NEET, and Board exams.</p>
          <p>We believe every student has the potential to succeed with the right guidance, structured learning and consistent practice. Our experienced faculty focuses on concept clarity, problem-solving and exam-oriented preparation.</p>
          <p>With regular tests, chapter-wise practice and personal attention, we make sure every student walks into their exam confident and ready.</p>
        </div>
      </div>

      <div class="reveal order-1 md:order-2">
        <div class="rounded-lg overflow-hidden aspect-[4/5]">
          <img src="images/radient-9.jpeg" class="w-full h-full object-cover">
        </div>
      </div>

    </div>
  </section>

  <!-- ================= EXTRA CURRICULAR ================= -->
  <?php if (!empty($extras)): ?>
    <section class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 py-16 md:py-24">
      <div class="reveal mb-12 md:mb-14">
        <h2 class="font-display font-semibold text-3xl md:text-4xl text-ink">Beyond Academics</h2>
        <p class="text-slate mt-3 max-w-md">Extra-curricular activities that build skills, confidence and personality.</p>
      </div>

      <div class="grid gap-8 md:grid-cols-3">
        <?php for ($i = 0; $i < min(3, count($extras)); $i++) { ?>
          <div class="reveal group relative rounded-lg overflow-hidden aspect-[4/5]">
            <img src="../src/uploads/activities/<?php echo $extras[$i]['images'][0]; ?>"
              class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
            <div class="absolute inset-0 bg-gradient-to-t from-ink/90 via-ink/10 to-transparent"></div>
            <div class="absolute inset-x-0 bottom-0 p-6">
              <h3 class="font-display font-semibold text-xl text-white"><?= $extras[$i]['occasion']; ?></h3>
              <p class="text-white/75 text-sm mt-1.5 leading-relaxed line-clamp-2"><?= $extras[$i]['description']; ?></p>
            </div>
          </div>
        <?php } ?>
      </div>
    </section>
  <?php endif; ?>

  <!-- ================= GALLERY ================= -->
  <?php if (!empty($galleries)): ?>
    <section class="border-y border-line bg-white py-16 md:py-20 overflow-hidden">
      <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 mb-10">
        <h2 class="reveal font-display font-semibold text-3xl md:text-4xl text-ink">Gallery</h2>
      </div>

      <?php $galleryItems = array_merge($galleries, $galleries); ?>
      <div class="overflow-hidden">
        <div class="marquee-track gap-6 px-6">
          <?php for ($i = 0; $i < count($galleryItems); $i++) { ?>
            <div class="w-56 sm:w-64 md:w-72 shrink-0 rounded-lg overflow-hidden border border-line">
              <img src="../src/uploads/gallery/<?php echo $galleryItems[$i]['image']; ?>" class="w-full h-56 sm:h-64 md:h-72 object-cover" alt="Gallery image">
            </div>
          <?php } ?>
        </div>
      </div>
    </section>
  <?php endif; ?>

  <!-- ================= WHY CHOOSE US ================= -->
  <section class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 py-16 md:py-24">
    <div class="reveal mb-12 md:mb-14">
      <h2 class="font-display font-semibold text-3xl md:text-4xl text-ink">Why Choose Us</h2>
      <p class="text-slate mt-3 max-w-md">We don't just teach — we build your future.</p>
    </div>

    <div class="grid gap-px bg-line border border-line rounded-lg overflow-hidden md:grid-cols-3">
      <?php
      $whyUs = [
        ['Expert Faculty', 'Learn from experienced teachers with a proven record in JEE, NEET & CET.'],
        ['Proven Results', 'Consistent top ranks and selections in IITs, medical colleges and top institutes.'],
        ['Structured Study Plan', 'A well-planned syllabus with regular tests and revisions.'],
        ['Doubt Support', 'Dedicated doubt-solving sessions to clear every concept.'],
        ['Competitive Environment', 'Healthy competition that pushes students towards excellence.'],
        ['Personal Attention', 'Small batches ensure individual focus and better learning.'],
      ];
      foreach ($whyUs as $item) {
      ?>
        <div class="reveal bg-white p-8">
          <h3 class="font-display font-semibold text-lg text-ink mb-2"><?= $item[0] ?></h3>
          <p class="text-slate text-sm leading-relaxed"><?= $item[1] ?></p>
        </div>
      <?php } ?>
    </div>
  </section>

  <!-- ================= FOOTER ================= -->
  <?php include 'footer.php'; ?>

  <!-- ================= SCRIPTS ================= -->
  <script>
    // reveal on scroll
    const revealEls = document.querySelectorAll('.reveal');
    const revealObserver = new IntersectionObserver((entries) => {
      entries.forEach((entry, i) => {
        if (entry.isIntersecting) {
          setTimeout(() => entry.target.classList.add('show'), (i % 4) * 90);
          revealObserver.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12 });
    revealEls.forEach(el => revealObserver.observe(el));

    // hero slider
    (function() {
      const slides = document.querySelectorAll('#heroSlider .slide');
      const dotsWrap = document.getElementById('heroDots');
      if (slides.length <= 1) return;

      let index = 0;
      slides.forEach((_, i) => {
        const d = document.createElement('div');
        d.className = 'heroDot' + (i === 0 ? ' active' : '');
        d.onclick = () => show(i);
        dotsWrap.appendChild(d);
      });
      const dots = dotsWrap.querySelectorAll('.heroDot');

      function show(i) {
        slides[index].classList.remove('active');
        dots[index].classList.remove('active');
        index = i;
        slides[index].classList.add('active');
        dots[index].classList.add('active');
      }

      setInterval(() => show((index + 1) % slides.length), 4500);
    })();
  </script>
</body>
</html>
