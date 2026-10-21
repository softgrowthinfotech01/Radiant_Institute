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


// echo "<pre>";
// print_r($extras);
// exit;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Toppers | Radiant</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <style>
        /* ===== HERO ANIMATION ===== */
        .hero-bg {
            animation: zoomBg 15s infinite alternate ease-in-out;
        }

        @keyframes zoomBg {
            0% {
                transform: scale(1);
            }

            100% {
                transform: scale(1.1);
            }
        }

        /* ===== WAVE ===== */
        .wave {
            position: absolute;
            bottom: 0;
            width: 100%;
        }

        /* ===== FLOATING GLOW ===== */
        .glow {
            position: absolute;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(228, 28, 42, 0.4), transparent);
            filter: blur(120px);
            animation: float 10s infinite alternate;
        }

        @keyframes float {
            0% {
                transform: translateY(0);
            }

            100% {
                transform: translateY(-60px);
            }
        }

        /* ===== CARD PREMIUM ===== */
        .result-card {
            backdrop-filter: blur(15px);
            background: rgba(255, 255, 255, 0.8);
            transition: 0.5s;
            transform-style: preserve-3d;
        }

        .result-card:hover {
            transform: rotateX(6deg) rotateY(-6deg) scale(1.05);
            box-shadow: 0 30px 70px rgba(0, 0, 0, 0.25);
        }

        /* ===== RANK BADGE ===== */
        .rank {
            background: linear-gradient(45deg, #E41C2A, #ff6b6b);
            color: white;
            padding: 6px 14px;
            border-radius: 50px;
            display: inline-block;
            font-weight: bold;
            margin-top: 6px;
        }

        /* ===== NUMBER GLOW ===== */
        .rank-number {
            font-size: 32px;
            font-weight: bold;
            color: #E41C2A;
            text-shadow: 0 0 15px rgba(228, 28, 42, 0.6);
        }

        /* ===== ENTRY ANIMATION ===== */
        .result-card {
            opacity: 0;
            transform: translateY(100px);
        }

        .result-card.show {
            opacity: 1;
            transform: translateY(0);
            transition: 0.8s;
        }

        /* ===== BUTTON ===== */
        .btn {
            position: relative;
            overflow: hidden;
        }

        .btn::after {
            content: "";
            position: absolute;
            width: 0%;
            height: 100%;
            background: white;
            top: 0;
            left: 0;
            transition: 0.4s;
            z-index: 0;
        }

        .btn:hover::after {
            width: 100%;
        }

        .btn span {
            position: relative;
            z-index: 1;
        }
    </style>
</head>

<body class="bg-gray-50">

    <?php include 'header.php'; ?>

    <!-- ================= HERO ================= -->
    <section class="relative min-h-[60vh] md:h-[70vh] flex items-center justify-center text-center text-white overflow-hidden">

        <!-- BG -->
        <div class="absolute inset-0 bg-cover bg-center bg-no-repeat 
              md:bg-center 
              bg-[position:center_top]"
            style="background-image:url('images/hero-bg.png')">
        </div>

        <!-- Overlay -->
        <div class="absolute inset-0 bg-[#021969]/10"></div>

        <!-- Glow -->
        <div class="glow top-10 left-10"></div>
        <div class="glow bottom-10 right-10"></div>

        <!-- Content -->
        <div class="relative z-10 px-4">
            <h2 class="text-3xl sm:text-4xl md:text-6xl font-bold leading-tight">
                Our Monthly Toppers
            </h2>
            <p class="mt-3 text-gray-200 text-sm sm:text-base">
                Producing Rankers Year After Year 🚀
            </p>
        </div>

        <!-- Wave -->
        <svg class="wave" viewBox="0 0 1440 120">
            <path fill="#f9fafb" d="M0,64L60,80C120,96,240,128,360,128C480,128,600,96,720,85.3C840,75,960,85,1080,80C1200,75,1320,53,1380,42.7L1440,32V160H0Z"></path>
        </svg>

    </section>

    <!-- ================= RESULTS ================= -->
    <section class="py-20 px-6 md:px-16">

        <div class="grid grid-1">

            <div class="grid gap-10 md:grid-cols-2 lg:grid-cols-3 m-auto">
                <!-- IMAGE CARD -->
                <?php for ($i = 0; $i < count($extras); $i++) { ?>
                    <div>
                        <div class="text-center mb-14" data-aos="fade-up">
                            <h2 class="text-3xl md:text-5xl font-bold text-[#E41C2A]">
                                <?= $extras[$i]['student_name']; ?>
                            </h2>

                            <p class="text-gray-600 mt-3">
                                <?= $extras[$i]['course_title'] . " : " . date("M Y", strtotime($extras[$i]['topper_date'])); ?>
                            </p>
                        </div>
                        <div class="gallery-card group" data-aos="zoom-in">
                            <div class="relative overflow-hidden rounded-2xl shadow-2xl cursor-pointer">

                                <img src="../src/uploads/monthly-toppers/<?= $extras[$i]['image']; ?>"
                                    class="media w-full h-64 object-cover brightness-75">
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>

        </div>
    </section>

    <!-- ================= CTA ================= -->
    <section class="relative py-20 text-center text-white overflow-hidden">

        <div class="absolute inset-0 bg-[#E41C2A]"></div>

        <div class="relative z-10">
            <h2 class="text-4xl md:text-5xl font-bold mb-4">
                Be the Next Topper 🚀
            </h2>

            <p class="mb-8 text-lg text-gray-100">
                Join Radiant Coaching & Start Your Success Journey Today
            </p>

            <a href="enquiry.php"
                class="ctaBtn inline-block px-8 py-3 bg-green-500 text-black font-bold rounded-full shadow-lg hover:scale-105 transition duration-300">
                Join JEE Batch
            </a>
        </div>

    </section>

    <?php include 'footer.php'; ?>

    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000
        });
    </script>

    <script>
        const cards = document.querySelectorAll(".result-card");

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, i) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.classList.add("show");
                    }, i * 150);
                }
            });
        });

        cards.forEach(card => observer.observe(card));
    </script>

</body>

</html>