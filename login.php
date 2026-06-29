<?php include('includes/header.php'); 
require_once __DIR__ . "/config/dbconn.php"; 

$logoutMessage = null;

if (isset($_GET['logout']) && $_GET['logout'] === 'success') {
    $logoutMessage = "You have been logged out successfully.";
}
?>



<section class="w-full bg-white">
  <div class="flex min-h-[90vh]">

    <!-- Left Image -->
    <article
      class="hidden md:block w-1/2 bg-[url('images/herobg4.svg')] bg-no-repeat bg-cover bg-center">
    </article>

    <!-- Login Form -->
    <article class="w-full md:w-1/2 flex items-center justify-center py-10 px-5">
      <div class="w-full max-w-md">


        <!-- Header -->
        <div class="text-center mb-8">
          <h2 class="text-2xl font-title font-bold">
            Log In to Your Account
          </h2>
          <p class="font-body text-sm text-gray-600 mt-1">
            Welcome back! Please enter your details to continue.
          </p>
        </div>
        <?php if ($logoutMessage): ?>
        <div class="mb-4 px-4 py-3 rounded bg-green-100 text-green-700 text-sm font-medium">
            <?= htmlspecialchars($logoutMessage) ?>
        </div>
        <?php endif; ?>
        <!-- Response Message -->
        <div id="responseBox" class="hidden mb-4 px-4 py-3 rounded text-sm font-medium"></div>

        <!-- Form -->
        <form id="loginForm" class="space-y-4">

          <!-- Email -->
          <div>
            <label class="block font-medium mb-1">Email Address</label>
            <input
              type="email"
              id="email"
              placeholder="Enter your email address"
              class="w-full h-11 rounded-lg border border-color-three px-3 focus:ring-2 focus:ring-color-two focus:outline-none"
              required>
          </div>

          <!-- Password -->
          <div>
            <label class="block font-medium mb-1">Password</label>
            <input
              type="password"
              id="password"
              placeholder="Enter your password"
              class="w-full h-11 rounded-lg border border-color-three px-3 focus:ring-2 focus:ring-color-two focus:outline-none"
              required>
          </div>

          <!-- Submit -->
          <button
            type="button"
            id="loginBtn"
            class="w-full bg-color-two text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-300 shadow-lg mt-5">
            Log In
          </button>

          <!-- Signup -->
          <!-- <p class="text-center text-sm text-gray-600 mt-6">
            New here?
            <a href="signup.php" class="text-color-two font-semibold hover:underline">
              Create an account
            </a>
          </p> -->

        </form>
      </div>
    </article>

  </div>
</section>

/*<?php include('includes/footer.php'); ?>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
  const API_BASE_URL = "<?php echo API_BASE_URL; ?>";
</script>

<script>
$("#loginBtn").on("click", function () {

  const data = {
    email: $("#email").val().trim(),
    password: $("#password").val()
  };

  if (!data.email || !data.password) {
    showMessage("Email and password are required", "error");
    return;
  }

  $("#loginBtn").text("Logging in...").prop("disabled", true);

  $.ajax({
    url: API_BASE_URL + "/auth/login.php",
    method: "POST",
    contentType: "application/json",
    dataType: "json",
    data: JSON.stringify(data),

    success: function (res) {
      if (res.status === true) {

        /* ===============================
           ✅ TOKEN STORAGE (CRITICAL FIX)
           =============================== */

        // 1️⃣ Store for JavaScript usage
        localStorage.setItem("auth_token", res.token);

        // 2️⃣ Store for PHP (middleware access)
        document.cookie =
          "auth_token=" + res.token +
          "; path=/; SameSite=Lax";

        showMessage("Login successful.", "success");

        setTimeout(function () {
          window.location.href = "<?= BASE_URL ?>/dashboard/pages/index.php";
        }, 1000);

      } else {
        showMessage(res.message || "Login failed", "error");
      }
    },

    error: function () {
      showMessage("Invalid credentials. Please try again.", "error");
    },

    complete: function () {
      $("#loginBtn").text("Log In").prop("disabled", false);
    }
  });
});

function showMessage(message, type) {
  const box = $("#responseBox");

  box.removeClass("hidden bg-red-100 bg-green-100 text-red-700 text-green-700");

  if (type === "success") {
    box.addClass("bg-green-100 text-green-700");
  } else {
    box.addClass("bg-red-100 text-red-700");
  }

  box.text(message);
}
</script>
