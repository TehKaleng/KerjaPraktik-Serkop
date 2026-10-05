/*
 * Navbar pintar: sembunyi saat scroll ke bawah, muncul lagi saat scroll ke atas.
 * Dipakai oleh serenatacoffee.blade.php dan layouts/app.blade.php.
 * Taruh di: public/js/navbar-scroll.js
 */
(function () {
  var header = document.querySelector('header');
  if (!header) return;

  var lastY = window.pageYOffset || 0;
  var ticking = false;
  var DELTA = 8; // abaikan gerakan scroll yang sangat kecil supaya navbar tidak "bergetar"

  function update() {
    var y = window.pageYOffset || 0;

    if (y <= 0) {
      // sedang di paling atas halaman: navbar selalu tampil
      header.classList.remove('header-hidden');
      lastY = 0;
    } else if (Math.abs(y - lastY) > DELTA) {
      if (y > lastY && y > header.offsetHeight) {
        header.classList.add('header-hidden');    // scroll ke bawah -> sembunyi
      } else if (y < lastY) {
        header.classList.remove('header-hidden'); // scroll ke atas -> muncul
      }
      lastY = y;
    }

    ticking = false;
  }

  window.addEventListener('scroll', function () {
    if (ticking) return;
    ticking = true;                       // kunci dulu, baru jadwalkan pembaruan
    window.requestAnimationFrame(update);
  }, { passive: true });
})();
