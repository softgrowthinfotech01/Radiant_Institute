<?php
include('conn.php');

$select = $conn->prepare('SELECT * FROM courses WHERE status = 1');
$select->execute();

$courses = $select->fetchAll(PDO::FETCH_ASSOC);
// print_r($courses);exit;


$notification = $conn->prepare('select * FROM notification where status = "active"');
$notification->execute();

$not = $notification->fetchAll(PDO::FETCH_ASSOC);
// print_r($not);exit;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Navbar</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Merriweather+Sans:wght@300;400;500;700&display=swap" rel="stylesheet">

    <style>
        .merriweather-sans {
            font-family: "Merriweather Sans", sans-serif;
        }

        [data-aos] {
            transform: none !important;
        }

        body {
            position: relative;
            overflow-x: hidden;
        }

        /* slide-out menu: roomier tap targets */
        #mobileMenu a,
        #mobileMenu button.font-semibold {
            padding-top: 0.4rem;
            padding-bottom: 0.4rem;
        }

        /* decorative blurred blobs: never wider than the section, never block taps */
        .glow {
            max-width: 100%;
            pointer-events: none;
        }

        .blink {
                animation: blinker 1.5s linear infinite;
                color: red;
                font-family: sans-serif;
            }

            @keyframes blinker {
                50% {
                    opacity: 0;
                }
            }
}
    </style> 

</head>

<body class="bg-gray-100">



    <!-- ================= NAVBAR ================= -->
    <header class="fixed top-0 left-0 w-full z-[99999] bg-white shadow-md isolate]" style="background-image:url('images/amuly-bg.png');">
        <div class="flex items-center justify-between xl:justify-around px-4 sm:px-6 md:px-10 py-2">

            <!-- LOGO -->
            <a href="home">
                <div class="logo flex items-center -gap-4">
                    <!-- <img src="images/r-logo.png" alt=""
                        class="h-12 sm:h-24 md:h-[90px] md:w-[240px] w-auto object-contain"> -->
<div class="text-4xl md:text-5xl xl:text-6xl 2xl:text-7xl leading-none font-extrabold text-red-600">
    Radiant
</div>

                </div>
            </a>

   
           
            <!-- DESKTOP MENU -->
            <nav class="hidden xl:flex xl:gap-6 2xl:gap-8 whitespace-nowrap text-black items-center">

                <!-- COURSE -->
                <div class="relative">
                    <button onclick="toggleDropdown('course')"
                        class="flex items-center gap-1 font-semibold">
                        Course
                        <span id="arrow-course" class="transition-transform duration-300 mb-2">⌄</span>
                    </button>

                    <div id="dropdown-course"
                        class="absolute left-0 mt-2 w-52 bg-white shadow-lg rounded-lg
      opacity-0 scale-95 invisible transform
      transition-all duration-300 ease-out origin-top">
                        <?php
                        for ($i = 0; $i < count($courses); $i++) {
                        ?>
                            <a href="jee?id=<?php echo $courses[$i]['id']; ?>" class="block px-4 py-2 hover:bg-blue-50"><?= $courses[$i]['title']; ?></a>
                        <?php } ?>
                    </div>
                </div>
             

                <!-- COURSE -->
                <div class="relative">
                    <button onclick="toggleDropdown('results')"
                        class="flex items-center gap-1 font-semibold">
                        Results
                        <span id="arrow-results" class="transition-transform duration-300 mb-2">⌄</span>
                    </button>

                    <div id="dropdown-results"
                        class="absolute left-0 mt-2 w-52 bg-white shadow-lg rounded-lg
      opacity-0 scale-95 invisible transform
      transition-all duration-300 ease-out origin-top">
                       
                            <a href="jeeresult" class="block px-4 py-2 hover:bg-blue-50">Results</a>
                            <a href="mon_topper" class="block px-4 py-2 hover:bg-blue-50">Monthly Topper</a>
                    </div>
                </div>

                <!-- ABOUT -->
                <a class="font-semibold" href="about_us">About Us</a>

                <!-- FACULTY -->
                <a class="font-semibold" href="faculty">Faculty</a>

                <!-- ENQUIRY -->
                <a class="font-semibold" href="enquiry">Enquiry</a>

                <a class="font-semibold" href="extra_curricular_activities">Extra - curricular</a>
                <a class="font-semibold" href="gallary">Gallery</a>
                <a class="font-semibold" href="contact_us">Contact Us</a>

            </nav>
           
            <!-- MOBILE BUTTON -->
            <button onclick="toggleMenu()" aria-label="Open menu" class="xl:hidden text-3xl leading-none text-black p-1 -mr-1">
                ☰
            </button>

        </div>
<marquee class ="blink" behavior="scroll" direction="left" scrollamount="5"  onmouseover="this.stop();" onmouseout="this.start();">
<?php for($i = 0; $i<count($not);$i++){ ?>
<span>
<a style="padding:0 5px;" href="<?php echo $not[$i]['url'] ?>"><?php echo $not[$i]['label'] ?></a>|
</span>
<?php 
}
 ?>
</marquee>
    </header>

    <!-- SPACING (so content doesn't go under navbar) -->
    <div class=""></div>



    <!-- ================= MOBILE SIDEBAR ================= -->
    <div id="mobileMenu"
        class="fixed top-[52px] bottom-0 right-0 w-[70%] sm:w-[50%] max-w-[320px] overflow-y-auto
  bg-gray-600 shadow-2xl z-[100000]

  translate-x-full scale-95 opacity-0
  transition-all duration-500 ease-[cubic-bezier(0.16,1,0.3,1)]
  origin-right">

        <div class="relative h-full p-6 flex flex-col gap-6 text-black">

            <!-- CLOSE BUTTON (NEW IMPROVED) -->
            <button onclick="toggleMenu()"
                aria-label="Close menu" class="absolute top-2 right-3 p-2 text-3xl font-bold text-[#FFFD12] 
      hover:text-red-600 transition-transform duration-300 hover:rotate-90">
                ✕
            </button>

            <div class="mt-10 flex flex-col gap-4 text-white">

                <!-- COURSE -->
                <div>
                    <button onclick="toggleMobileDropdown('m-course')"
                        class="w-full flex justify-between items-center font-semibold">
                        Course
                        <span id="m-arrow-m-course" class="transition-transform">⌄</span>
                    </button>

                    <div id="m-dropdown-m-course"
                        class="max-h-0 overflow-hidden transition-all duration-300 flex flex-col ml-3 mt-2 text-sm">
                         <?php
                        for ($i = 0; $i < count($courses); $i++) {
                        ?>
                        <a href="jee?id=<?php echo $courses[$i]['id']; ?>" class="py-1"><?php echo $courses[$i]['title']; ?></a>
                        <?php } ?>
                    </div>
                </div>
   
                <!-- RESULTS -->
                <!-- <div>
                    <button onclick="toggleMobileDropdown('m-results')"
                        class="w-full flex justify-between items-center font-semibold">
                        Results
                        <span id="m-arrow-m-results" class="transition-transform">⌄</span>
                    </button>

                    <div id="m-dropdown-m-results"
                        class="max-h-0 overflow-hidden transition-all duration-300 flex flex-col ml-3 mt-2 text-sm">
                        <a href="jeeresult" class="py-1">JEE</a>
                        <a href="neetresult" class="py-1">NEET</a>
                        <a href="mht-cetresult" class="py-1">MHT-CET</a>
                        <a href="boardresult" class="py-1">Board</a>
                    </div>
                </div> -->

                <!-- ENQUIRY -->
                <!-- RESULTS (same two pages as the desktop dropdown) -->
                <div>
                    <button onclick="toggleMobileDropdown('m-results')"
                        class="w-full flex justify-between items-center font-semibold">
                        Results
                        <span id="m-arrow-m-results" class="transition-transform">⌄</span>
                    </button>

                    <div id="m-dropdown-m-results"
                        class="max-h-0 overflow-hidden transition-all duration-300 flex flex-col ml-3 mt-2 text-sm">
                        <a href="jeeresult" class="py-1">Results</a>
                        <a href="mon_topper" class="py-1">Monthly Topper</a>
                    </div>
                </div>

                <a class="font-semibold" href="enquiry">Enquiry</a>
               
                <!-- NORMAL LINKS -->
                <a href="about_us" class="font-semibold">About Us</a>
                <a href="faculty" class="font-semibold">Faculty</a>
                <a href="extra_curricular_activities" class="font-semibold">Extra Curricular</a>
                <a href="gallary" class="font-semibold">Gallery</a>
                <a href="contact_us" class="font-semibold">Contact Us</a>

            </div>
            
           
        </div>
        
    </div>
   
    <!-- ================= OVERLAY ================= -->
    <div id="overlay"
        class="fixed inset-0 bg-black/60 opacity-0 pointer-events-none
  transition-opacity duration-500 ease-out z-[99999]">
    </div>
<div>    



    <!-- ================= SCRIPT ================= -->
    <script>
        const menu = document.getElementById("mobileMenu");
        const overlay = document.getElementById("overlay");

        function toggleMenu() {
            // keep the panel directly under the header, whatever its height is at this screen size
            menu.style.top = document.querySelector("header").offsetHeight + "px";

            const isOpen = menu.classList.contains("translate-x-0");

            if (isOpen) {
                // CLOSE (smooth exit)
                menu.classList.add("translate-x-full", "scale-95", "opacity-0");
                menu.classList.remove("translate-x-0", "scale-100", "opacity-100");

                overlay.classList.remove("opacity-100");
                overlay.classList.add("opacity-0", "pointer-events-none");

                document.body.style.overflow = "auto";

            } else {
                // OPEN (smooth entrance)
                menu.classList.remove("translate-x-full", "scale-95", "opacity-0");
                menu.classList.add("translate-x-0", "scale-100", "opacity-100");

                overlay.classList.add("opacity-100");
                overlay.classList.remove("opacity-0", "pointer-events-none");

                document.body.style.overflow = "hidden";
            }
        }
    </script>




    <script>
        // close the mobile menu if the screen grows to desktop size or Esc is pressed
        window.addEventListener("resize", function () {
            if (window.innerWidth >= 1280 && menu.classList.contains("translate-x-0")) toggleMenu();
        });
        document.addEventListener("keydown", function (e) {
            if (e.key === "Escape" && menu.classList.contains("translate-x-0")) toggleMenu();
        });
    </script>

    <!-- dropdown after click script -->

    <script>
        function toggleDropdown(name) {
            const dropdown = document.getElementById(`dropdown-${name}`);
            const arrow = document.getElementById(`arrow-${name}`);

            const isOpen = dropdown.classList.contains('visible');

            // Close all dropdowns first
            document.querySelectorAll('[id^="dropdown-"]').forEach(d => {
                d.classList.remove('visible', 'opacity-100', 'scale-100');
                d.classList.add('invisible', 'opacity-0', 'scale-95');
            });

            document.querySelectorAll('[id^="arrow-"]').forEach(a => {
                a.classList.remove('rotate-180');
            });

            // Toggle current
            if (!isOpen) {
                dropdown.classList.remove('invisible', 'opacity-0', 'scale-95');
                dropdown.classList.add('visible', 'opacity-100', 'scale-100');

                arrow.classList.add('rotate-180');
            }
        }

        // Close when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.relative')) {
                document.querySelectorAll('[id^="dropdown-"]').forEach(d => {
                    d.classList.remove('visible', 'opacity-100', 'scale-100');
                    d.classList.add('invisible', 'opacity-0', 'scale-95');
                });

                document.querySelectorAll('[id^="arrow-"]').forEach(a => {
                    a.classList.remove('rotate-180');
                });
            }
        });
    </script>

    <!-- mobile view dropdown script -->
    <script>
        function toggleMobileDropdown(name) {
            const dropdown = document.getElementById(`m-dropdown-${name}`);
            const arrow = document.getElementById(`m-arrow-${name}`);

            const isOpen = dropdown.style.maxHeight && dropdown.style.maxHeight !== "0px";

            // Close all
            document.querySelectorAll('[id^="m-dropdown-"]').forEach(d => {
                d.style.maxHeight = "0px";
            });

            document.querySelectorAll('[id^="m-arrow-"]').forEach(a => {
                a.classList.remove("rotate-180");
            });

            // Open current
            if (!isOpen) {
                dropdown.style.maxHeight = dropdown.scrollHeight + "px";
                arrow.classList.add("rotate-180");
            }
        }
    </script>

</body>

</html>