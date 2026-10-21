<footer class="relative bg-ink text-white overflow-hidden">

  <div class="max-w-7xl mx-auto px-6 md:px-10 py-16 md:py-20 relative z-10">

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-[1.3fr_1fr_1fr_1.1fr] gap-12 lg:gap-10">

      <!-- ABOUT -->
      <div>
        <span class="font-display font-semibold text-2xl">Radiant</span>
        <p class="text-white/60 leading-relaxed text-sm mt-4 max-w-xs">
          Coaching for JEE, NEET, MHT-CET, Boards and foundation students —
          expert faculty, structured practice, honest results.
        </p>

        <div class="flex gap-3 mt-6">
          <a href="https://www.instagram.com/radiantclasses?igsh=MWVkY245aXV5d3d3cA==" target="_blank" aria-label="Instagram"
            class="w-10 h-10 rounded-full border border-white/15 flex items-center justify-center hover:border-white/40 hover:bg-white/5 transition-colors">
            <img src="https://cdn-icons-png.flaticon.com/512/174/174855.png" class="w-4 h-4 object-contain invert opacity-80">
          </a>
          <a href="https://m.facebook.com/738600269512806/" target="_blank" aria-label="Facebook"
            class="w-10 h-10 rounded-full border border-white/15 flex items-center justify-center hover:border-white/40 hover:bg-white/5 transition-colors">
            <img src="https://cdn-icons-png.flaticon.com/512/733/733547.png" class="w-4 h-4 object-contain invert opacity-80">
          </a>
          <a href="https://youtube.com/@radiantcoachingclasses8774?si=lT0Eig4Lm3KBDuD2" target="_blank" aria-label="YouTube"
            class="w-10 h-10 rounded-full border border-white/15 flex items-center justify-center hover:border-white/40 hover:bg-white/5 transition-colors">
            <img src="https://cdn-icons-png.flaticon.com/512/1384/1384060.png" class="w-4 h-4 object-contain invert opacity-80">
          </a>
        </div>
      </div>

      <!-- QUICK LINKS -->
      <div>
        <h3 class="text-sm font-semibold text-white mb-5">Quick Links</h3>
        <ul class="space-y-3 text-white/60 text-sm">
          <li><a href="home" class="hover:text-white transition-colors">Home</a></li>
          <li><a href="about_us" class="hover:text-white transition-colors">About Us</a></li>
          <li><a href="gallary" class="hover:text-white transition-colors">Gallery</a></li>
          <li><a href="contact_us" class="hover:text-white transition-colors">Contact Us</a></li>
          <li><a href="privacy_policy" class="hover:text-white transition-colors">Privacy Policy</a></li>
          <li><a href="terms_conditions" class="hover:text-white transition-colors">Terms &amp; Conditions</a></li>
        </ul>
      </div>

      <!-- COURSES -->
      <div>
        <h3 class="text-sm font-semibold text-white mb-5">Our Courses</h3>
        <ul class="space-y-3 text-white/60 text-sm">
          <?php
          $footer = $conn->prepare("SELECT * FROM courses WHERE status = 1");
          $footer->execute();
          $ret = $footer->fetchAll(PDO::FETCH_ASSOC);
          for ($i = 0; $i < count($ret); $i++) {
          ?>
            <li><a href="jee?id=<?= $ret[$i]['id']; ?>" class="hover:text-white transition-colors"><?= $ret[$i]['title'] ?></a></li>
          <?php } ?>
        </ul>
      </div>

      <!-- CONTACT -->
      <div>
        <h3 class="text-sm font-semibold text-white mb-5">Contact</h3>
        <div class="space-y-4 text-white/60 text-sm">
          <p>Shree Tower, Beside Jain Bhawaan,<br>Pathanpura Road, Chandrapur</p>
          <p>+91 84217 83479<br>+91 84213 83479</p>
          <p>radiantcoaching@gmail.com<br>shaibazpathan0@gmail.com</p>
        </div>
      </div>

    </div>

    <div class="border-t border-white/10 mt-14 pt-6">
      <div class="flex flex-col md:flex-row items-center justify-between gap-3 text-xs text-white/45">
        <p>© 2026 <a target="_blank" href="https://softgrowthinfotech.com/" class="hover:text-white/70">Softgrowth Infotech</a>. All rights reserved.</p>
      </div>
    </div>

  </div>

  <!-- FLOATING BUTTONS -->
  <a href="https://wa.me/918421783479" target="_blank" aria-label="Chat on WhatsApp"
    class="fixed right-5 bottom-24 z-[9999] w-14 h-14 rounded-full bg-[#25D366] shadow-lg flex items-center justify-center hover:scale-105 transition-transform">
    <img src="https://cdn-icons-png.flaticon.com/512/733/733585.png" class="w-7 h-7">
  </a>

  <a href="tel:+918421783479" aria-label="Call us"
    class="fixed right-5 bottom-8 z-[9999] w-14 h-14 rounded-full bg-white shadow-lg flex items-center justify-center hover:scale-105 transition-transform">
    <img src="https://cdn-icons-png.flaticon.com/512/597/597177.png" class="w-6 h-6">
  </a>

</footer>
