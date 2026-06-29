            </main>
            <!-- footer -->
            <!-- <footer class="gird p-5 text-center justify-center absolute bottom-0 w-[83%]">
                <div>
                    <div>Copyright E-invite &copy; 2025</div>
                </div>
            </footer> -->
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer">  
    </script>
    <script src="../../dashboard/assets/js/custom.js"></script>


</body>

<script>
document.addEventListener("DOMContentLoaded", function () {
  const sidebar = document.getElementById("sidebar");
  const overlay = document.getElementById("overlay");
  const toggleBtn = document.getElementById("openSidebar");
  const closeBtn = document.getElementById("closeSidebar");

  if (!sidebar || !overlay || !toggleBtn) return;

  function openSidebar() {
    sidebar.classList.remove("-translate-x-full");
    overlay.classList.remove("hidden");
    toggleBtn.classList.add("hamburger-active");
  }

  function closeSidebar() {
    sidebar.classList.add("-translate-x-full");
    overlay.classList.add("hidden");
    toggleBtn.classList.remove("hamburger-active");
  }

  toggleBtn.addEventListener("click", () => {
    const isOpen = !sidebar.classList.contains("-translate-x-full");
    isOpen ? closeSidebar() : openSidebar();
  });

  overlay.addEventListener("click", closeSidebar);
  closeBtn?.addEventListener("click", closeSidebar);

  // Auto-close on menu click (mobile)
  sidebar.querySelectorAll("a").forEach(link => {
    link.addEventListener("click", () => {
      if (window.innerWidth < 1024) closeSidebar();
    });
  });
});
</script>



</html>
