<!-- footer -->  
<footer class="bg-color-two text-color-one pb-6">

  <!-- Divider -->
  <div class="border-t border-white/20 pt-4 text-center text-sm text-blue-100">
    © 2026 increase original suppiler store. All rights reserved.
  </div>
</footer>


    <script>
    const menuBar = document.querySelector(".menubar");
    const close = document.querySelector(".close");
        const menu = document. querySelector(".header-bottom");

        menuBar.addEventListener("click", function () {
        menu.classList.add("open");

        });

        close.addEventListener("click", function () {
        menu.classList.remove("open");

        });
    </script>

<!-- swiper js script -->
 <script src="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.js"></script>
   <script src="js/invite.js"></script>

 <script>
  const revealElements = document.querySelectorAll('.reveal');

  const revealObserver = new IntersectionObserver(
    entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('show');
          revealObserver.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.2 }
  );

  revealElements.forEach(el => revealObserver.observe(el));
</script>

</body>
</html>