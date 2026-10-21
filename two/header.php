<?php
include('../src/conn.php');

$select = $conn->prepare('SELECT * FROM courses WHERE status = 1');
$select->execute();

$courses = $select->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- ================= NAVBAR ================= -->
<header id="siteHeader" class="fixed top-0 left-0 w-full z-[99999] bg-paper/95 backdrop-blur border-b border-line transition-shadow">
    <div class="max-w-7xl mx-auto flex items-center justify-between px-5 sm:px-8 lg:px-10 h-16 md:h-20">

        <!-- LOGO -->
        <a href="home" class="flex items-baseline gap-2 shrink-0">
            <span class="font-display font-semibold text-2xl md:text-[28px] tracking-tight text-ink">Radiant</span>
            <span class="hidden sm:inline text-[11px] uppercase tracking-[0.16em] text-slate font-medium">Coaching Classes</span>
        </a>

        <!-- DESKTOP MENU -->
        <nav class="hidden md:flex items-center gap-9 text-[15px] text-ink">

            <!-- COURSE -->
            <div class="relative">
                <button onclick="toggleDropdown('course')" class="flex items-center gap-1.5 font-medium hover:text-brand transition-colors">
                    Courses
                    <span id="arrow-course" class="transition-transform duration-300 text-[10px] text-slate">▾</span>
                </button>

                <div id="dropdown-course"
                    class="absolute left-0 mt-3 w-56 bg-white border border-line shadow-lg rounded-md py-2
                    opacity-0 scale-95 invisible transform transition-all duration-200 ease-out origin-top">
                    <?php for ($i = 0; $i < count($courses); $i++) { ?>
                        <a href="jee?id=<?php echo $courses[$i]['id']; ?>" class="block px-4 py-2.5 text-sm hover:bg-paper hover:text-brand transition-colors"><?= $courses[$i]['title']; ?></a>
                    <?php } ?>
                </div>
            </div>

            <!-- RESULTS -->
            <div class="relative">
                <button onclick="toggleDropdown('results')" class="flex items-center gap-1.5 font-medium hover:text-brand transition-colors">
                    Results
                    <span id="arrow-results" class="transition-transform duration-300 text-[10px] text-slate">▾</span>
                </button>

                <div id="dropdown-results"
                    class="absolute left-0 mt-3 w-52 bg-white border border-line shadow-lg rounded-md py-2
                    opacity-0 scale-95 invisible transform transition-all duration-200 ease-out origin-top">
                    <a href="jeeresult" class="block px-4 py-2.5 text-sm hover:bg-paper hover:text-brand transition-colors">Results</a>
                    <a href="mon_topper" class="block px-4 py-2.5 text-sm hover:bg-paper hover:text-brand transition-colors">Monthly Topper</a>
                </div>
            </div>

            <a class="font-medium hover:text-brand transition-colors" href="about_us">About Us</a>
            <a class="font-medium hover:text-brand transition-colors" href="extra_curricular_activities">Activities</a>
            <a class="font-medium hover:text-brand transition-colors" href="gallary">Gallery</a>
            <a class="font-medium hover:text-brand transition-colors" href="contact_us">Contact</a>
        </nav>

        <div class="hidden md:flex items-center gap-4">
            <a href="tel:+918421783479" class="text-sm font-medium text-ink hover:text-brand transition-colors">+91 84217 83479</a>
            <a href="enquiry" class="inline-flex items-center bg-ink text-white text-sm font-medium px-5 py-2.5 rounded-md hover:bg-brand transition-colors">
                Enquire Now
            </a>
        </div>

        <!-- MOBILE BUTTON -->
        <button onclick="toggleMenu()" class="md:hidden text-ink" aria-label="Open menu">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
        </button>

    </div>
</header>

<!-- SPACER so content doesn't sit under the fixed navbar -->
<div class="h-16 md:h-20"></div>

<!-- ================= MOBILE SIDEBAR ================= -->
<div id="mobileMenu"
    class="fixed top-0 right-0 h-full w-[78%] max-w-[320px] bg-ink shadow-2xl z-[100000]
    translate-x-full transition-transform duration-400 ease-[cubic-bezier(0.16,1,0.3,1)]">

    <div class="relative h-full p-7 flex flex-col gap-1 text-white overflow-y-auto">

        <div class="flex items-center justify-between mb-8">
            <span class="font-display font-semibold text-xl">Radiant</span>
            <button onclick="toggleMenu()" class="text-white/70 hover:text-white transition-colors" aria-label="Close menu">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><line x1="4" y1="4" x2="20" y2="20"/><line x1="20" y1="4" x2="4" y2="20"/></svg>
            </button>
        </div>

        <div class="flex flex-col divide-y divide-white/10 text-[15px]">

            <div class="py-3">
                <button onclick="toggleMobileDropdown('m-course')" class="w-full flex justify-between items-center font-medium">
                    Courses
                    <span id="m-arrow-m-course" class="transition-transform text-white/60 text-xs">▾</span>
                </button>
                <div id="m-dropdown-m-course" class="max-h-0 overflow-hidden transition-all duration-300 flex flex-col ml-1 mt-1 text-sm text-white/75">
                    <?php for ($i = 0; $i < count($courses); $i++) { ?>
                        <a href="jee?id=<?php echo $courses[$i]['id']; ?>" class="py-2"><?php echo $courses[$i]['title']; ?></a>
                    <?php } ?>
                </div>
            </div>

            <a class="py-3 font-medium" href="jeeresult">Results</a>
            <a class="py-3 font-medium" href="enquiry">Enquiry</a>
            <a href="about_us" class="py-3 font-medium">About Us</a>
            <a href="extra_curricular_activities" class="py-3 font-medium">Activities</a>
            <a href="gallary" class="py-3 font-medium">Gallery</a>
            <a href="contact_us" class="py-3 font-medium">Contact Us</a>
        </div>

        <a href="tel:+918421783479" class="mt-auto pt-6 text-sm text-white/70">+91 84217 83479</a>
    </div>
</div>

<!-- ================= OVERLAY ================= -->
<div id="overlay" class="fixed inset-0 bg-ink/60 opacity-0 pointer-events-none transition-opacity duration-400 ease-out z-[99999]"></div>

<!-- ================= SCRIPTS ================= -->
<script>
    const menu = document.getElementById("mobileMenu");
    const overlay = document.getElementById("overlay");

    function toggleMenu() {
        const isOpen = !menu.classList.contains("translate-x-full");

        if (isOpen) {
            menu.classList.add("translate-x-full");
            overlay.classList.remove("opacity-100");
            overlay.classList.add("opacity-0", "pointer-events-none");
            document.body.style.overflow = "auto";
        } else {
            menu.classList.remove("translate-x-full");
            overlay.classList.add("opacity-100");
            overlay.classList.remove("opacity-0", "pointer-events-none");
            document.body.style.overflow = "hidden";
        }
    }

    overlay.addEventListener("click", () => {
        if (!menu.classList.contains("translate-x-full")) toggleMenu();
    });

    function toggleDropdown(name) {
        const dropdown = document.getElementById(`dropdown-${name}`);
        const arrow = document.getElementById(`arrow-${name}`);
        const isOpen = dropdown.classList.contains('visible');

        document.querySelectorAll('[id^="dropdown-"]').forEach(d => {
            d.classList.remove('visible', 'opacity-100', 'scale-100');
            d.classList.add('invisible', 'opacity-0', 'scale-95');
        });
        document.querySelectorAll('[id^="arrow-"]').forEach(a => a.classList.remove('rotate-180'));

        if (!isOpen) {
            dropdown.classList.remove('invisible', 'opacity-0', 'scale-95');
            dropdown.classList.add('visible', 'opacity-100', 'scale-100');
            arrow.classList.add('rotate-180');
        }
    }

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.relative')) {
            document.querySelectorAll('[id^="dropdown-"]').forEach(d => {
                d.classList.remove('visible', 'opacity-100', 'scale-100');
                d.classList.add('invisible', 'opacity-0', 'scale-95');
            });
            document.querySelectorAll('[id^="arrow-"]').forEach(a => a.classList.remove('rotate-180'));
        }
    });

    function toggleMobileDropdown(name) {
        const dropdown = document.getElementById(`m-dropdown-${name}`);
        const arrow = document.getElementById(`m-arrow-${name}`);
        const isOpen = dropdown.style.maxHeight && dropdown.style.maxHeight !== "0px";

        document.querySelectorAll('[id^="m-dropdown-"]').forEach(d => d.style.maxHeight = "0px");
        document.querySelectorAll('[id^="m-arrow-"]').forEach(a => a.classList.remove("rotate-180"));

        if (!isOpen) {
            dropdown.style.maxHeight = dropdown.scrollHeight + "px";
            arrow.classList.add("rotate-180");
        }
    }

    // subtle shadow once page scrolls
    window.addEventListener('scroll', () => {
        const h = document.getElementById('siteHeader');
        if (window.scrollY > 8) h.classList.add('shadow-sm');
        else h.classList.remove('shadow-sm');
    });
</script>
