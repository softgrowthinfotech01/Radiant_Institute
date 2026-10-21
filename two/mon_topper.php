<?php
include('../src/conn.php');
$extra = $conn->prepare("
    SELECT mt.*, c.title AS course_title
    FROM monthly_toppers mt
    LEFT JOIN courses c 
        ON c.id = mt.course_name
    LIMIT 12
");
$extra->execute();

$extras = $extra->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Meet Radiant Coaching Classes' monthly toppers.">
    <title>Monthly Toppers — Radiant Coaching Classes</title>

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
            <p class="reveal text-sm font-medium text-gold mb-4">Recognition</p>
            <h1 class="reveal font-display font-semibold text-4xl md:text-6xl text-white leading-[1.08]">Our Monthly Toppers</h1>
            <p class="reveal mt-5 text-white/70 max-w-xl text-base md:text-lg">Celebrating consistent, disciplined performance — every single month.</p>
        </div>
    </section>

    <!-- ================= TOPPERS ================= -->
    <section class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 py-16 md:py-24">
        <?php if (empty($extras)): ?>
            <p class="text-center text-slate text-lg py-10">Toppers will be announced soon.</p>
        <?php else: ?>
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                <?php for ($i = 0; $i < count($extras); $i++) { ?>
                    <div class="reveal bg-white border border-line rounded-lg overflow-hidden">
                        <div class="img-zoom aspect-[4/3] overflow-hidden">
                            <img src="../src/uploads/monthly-toppers/<?= $extras[$i]['image']; ?>" class="w-full h-full object-cover">
                        </div>
                        <div class="p-6">
                            <h3 class="font-display font-semibold text-xl text-ink"><?= $extras[$i]['student_name']; ?></h3>
                            <p class="text-slate text-sm mt-1.5">
                                <?= $extras[$i]['course_title'] . ' · ' . date("M Y", strtotime($extras[$i]['topper_date'])); ?>
                            </p>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php endif; ?>
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
