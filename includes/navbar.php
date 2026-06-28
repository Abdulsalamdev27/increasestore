<section class="bg-color-one w-full sticky top-0 z-50 shadow-md">

  <!-- ================= DESKTOP NAV ================= -->
  <nav class="hidden lg:flex items-center justify-between px-10 py-4 text-color-two">

    <!-- Logo -->
    <a href="index.php" class="text-2xl font-title font-bold">
      Increase original suppiler store
    </a>

    <!-- Links -->
    <ul class="flex gap-1">
      <!-- <li><a href="index.php" class="nav-link">Home</a></li>
      <li><a href="features.php" class="nav-link">Features</a></li>
      <li><a href="pricing.php" class="nav-link">Pricing</a></li>
      <li><a href="contact.php" class="nav-link">Contact</a></li> -->
    </ul>

    <!-- Auth -->
    <ul class="flex gap-2">
      <!-- <li><a href="signup.php" class="btn-primary">Sign Up</a></li> -->

      <?php if (isset($_SESSION['loggedIn'])) : ?>
        <li>
          <a href="dashboard/index.php" class="btn-secondary capitalize">
            <?= $_SESSION['loggedInUser']['user_firstname']; ?>
          </a>
        </li>
        <li>
          <a href="logout.php" class="btn-danger">Logout</a>
        </li>
      <?php else : ?>
        <li>
          <a href="login.php" class="btn-secondary">Login</a>
        </li>
      <?php endif; ?>
    </ul>
  </nav>

  <!-- ================= MOBILE NAV ================= -->
  <nav class="lg:hidden px-4 py-4 relative text-color-two">

    <!-- Top bar -->
    <div class="flex items-center justify-between">
      <a href="index.php" class="text-xl font-title font-bold">
        E-Invite
      </a>

      <button id="menuBtn" class="text-2xl focus:outline-none">
        <i class="fa-solid fa-bars"></i>
      </button>
    </div>

    <!-- Mobile Menu -->
    <div
      id="mobileMenu"
      class="hidden absolute left-0 top-full w-full bg-white shadow-lg border-t border-gray-100"
    >
      <div class="p-4 space-y-4">

        <!-- Links -->
        <!-- <ul class="flex flex-col gap-3">
          <li><a href="index.php" class="nav-link-mobile">Home</a></li>
          <li><a href="features.php" class="nav-link-mobile">Features</a></li>
          <li><a href="pricing.php" class="nav-link-mobile">Pricing</a></li>
          <li><a href="contact.php" class="nav-link-mobile">Contact</a></li>
        </ul> -->

        <!-- Auth buttons -->
        <!-- <div class="flex flex-col gap-2 pt-3 border-t">
          <a href="signup.php" class="btn-primary text-center w-full">
            Sign Up
          </a> -->
<!-- 
          <?php if (isset($_SESSION['loggedIn'])) : ?>
            <a href="dashboard/index.php" class="btn-secondary text-center w-full capitalize">
              Dashboard
            </a>
            <a href="logout.php" class="btn-danger text-center w-full">
              Logout
            </a>
          <?php else : ?>
            <a href="login.php" class="btn-secondary text-center w-full">
              Login
            </a>
          <?php endif; ?>
        </div> -->

        <!-- Close -->
        <!-- <button id="closeBtn" class="absolute top-4 right-4 text-xl">
          <i class="fa-solid fa-xmark"></i>
        </button> -->

      </div>
    </div>
  </nav>
</section>

</section>

<!-- ================= MOBILE MENU SCRIPT ================= -->
<script>
  const menuBtn = document.getElementById('menuBtn');
  const closeBtn = document.getElementById('closeBtn');
  const mobileMenu = document.getElementById('mobileMenu');

  menuBtn.addEventListener('click', () => {
    mobileMenu.classList.remove('hidden');
  });

  closeBtn.addEventListener('click', () => {
    mobileMenu.classList.add('hidden');
  });
</script>
