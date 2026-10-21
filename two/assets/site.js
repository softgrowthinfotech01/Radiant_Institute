// ---- scroll reveal, used across every page ----
document.addEventListener('DOMContentLoaded', function () {
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
});

// ---- generic fade slider, used on home + course pages ----
function initSlider(containerSelector, dotsSelector, intervalMs) {
  const root = document.querySelector(containerSelector);
  if (!root) return;
  const slides = root.querySelectorAll('.slide');
  const dotsWrap = dotsSelector ? document.querySelector(dotsSelector) : null;
  if (slides.length <= 1) return;

  let index = 0;
  let dots = [];

  if (dotsWrap) {
    slides.forEach((_, i) => {
      const d = document.createElement('div');
      d.className = 'dot' + (i === 0 ? ' active' : '');
      d.onclick = () => show(i);
      dotsWrap.appendChild(d);
    });
    dots = dotsWrap.querySelectorAll('.dot');
  }

  function show(i) {
    slides[index].classList.remove('active');
    if (dots[index]) dots[index].classList.remove('active');
    index = i;
    slides[index].classList.add('active');
    if (dots[index]) dots[index].classList.add('active');
  }

  setInterval(() => show((index + 1) % slides.length), intervalMs || 4500);
}
