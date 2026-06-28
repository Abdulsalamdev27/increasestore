<?php include('includes/header-01.php'); 
require_once __DIR__ . "/config/dbconn.php"; 

?>


<section class="w-full bg-white">
  <div class="lg:flex md:flex grid min-h-[90vh]">

    <!-- Form Area -->
    <article class="lg:w-1/2 md:w-3/5 w-full py-10 lg:px-10 md:px-8 px-5">

      <!-- Heading -->
      <div class="mb-8">
        <h2 class="text-2xl font-title font-bold">Create an Account</h2>
        <p class="font-body text-sm text-gray-600 mt-1">
          Sign up to start creating and managing your Increase original suppiler store.
        </p>
      </div>

      <!-- Response Message -->
      <div id="responseBox" class="hidden mb-6 px-4 py-3 rounded text-sm font-medium"></div>

      <!-- Form -->
      <form id="registerForm" class="space-y-5">

        <!-- First & Last Name -->
        <div class="flex flex-col md:flex-row gap-4">
          <div class="w-full">
            <label class="block font-medium mb-1">First Name</label>
            <input
              type="text"
              id="firstName"
              placeholder="Enter first name"
              class="w-full h-11 rounded-lg border border-color-three px-3 focus:ring-2 focus:ring-color-two focus:outline-none"
              required>
          </div>

          <div class="w-full">
            <label class="block font-medium mb-1">Last Name</label>
            <input
              type="text"
              id="lastName"
              placeholder="Enter last name"
              class="w-full h-11 rounded-lg border border-color-three px-3 focus:ring-2 focus:ring-color-two focus:outline-none"
              required>
          </div>
        </div>

        <!-- Email & Phone -->
        <div class="flex flex-col md:flex-row gap-4">
          <div class="w-full">
            <label class="block font-medium mb-1">Email Address</label>
            <input
              type="email"
              id="email"
              placeholder="Enter your email"
              class="w-full h-11 rounded-lg border border-color-three px-3 focus:ring-2 focus:ring-color-two focus:outline-none"
              required>
          </div>

          <div class="w-full">
            <label class="block font-medium mb-1">Phone Number</label>
            <input
              type="tel"
              id="pnumber"
              placeholder="Enter phone number"
              class="w-full h-11 rounded-lg border border-color-three px-3 focus:ring-2 focus:ring-color-two focus:outline-none">
          </div>
        </div>

        <!-- Password -->
        <div class="flex flex-col md:flex-row gap-4">
          <div class="w-full">
            <label class="block font-medium mb-1">Password</label>
            <input
              type="password"
              id="password"
              placeholder="Create password"
              class="w-full h-11 rounded-lg border border-color-three px-3 focus:ring-2 focus:ring-color-two focus:outline-none"
              required>
          </div>

          <div class="w-full">
            <label class="block font-medium mb-1">Confirm Password</label>
            <input
              type="password"
              id="confirmPassword"
              placeholder="Confirm password"
              class="w-full h-11 rounded-lg border border-color-three px-3 focus:ring-2 focus:ring-color-two focus:outline-none"
              required>
          </div>
        </div>

        <!-- Submit Button -->
        <button
          type="button"
          id="registerBtn"
          class="w-full bg-color-two text-white font-semibold py-3 rounded-lg hover:bg-blue-700 transition duration-300 shadow-lg">
          Create Account
        </button>

        <p class="text-center text-sm text-gray-600">
          Already have an account?
          <a href="login.php" class="text-color-two font-semibold hover:underline">
            Login
          </a>
        </p>

      </form>
    </article>

    <!-- Right Image -->
    <article
      class="hidden md:block lg:w-1/2 md:w-2/5 bg-[url('images/herobg4.svg')] bg-no-repeat bg-cover bg-center">
    </article>

  </div>
</section>

<?php include('includes/footer.php'); ?>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
  const API_BASE_URL = "<?php echo API_BASE_URL; ?>";
</script>

<script>
$("#registerBtn").on("click", function () {

  const data = {
    firstName: $("#firstName").val().trim(),
    lastName: $("#lastName").val().trim(),
    email: $("#email").val().trim(),
    pnumber: $("#pnumber").val().trim(),
    pword: $("#password").val(),
    re_pword: $("#confirmPassword").val()
  };

  // Validation
  if (
    !data.firstName ||
    !data.lastName ||
    !data.email ||
    !data.pword ||
    !data.re_pword
  ) {
    showMessage("All required fields must be filled", "error");
    return;
  }

  if (data.pword !== data.re_pword) {
    showMessage("Passwords do not match", "error");
    return;
  }

  $("#registerBtn").text("Creating...").prop("disabled", true);

  $.ajax({
    url: API_BASE_URL + "/register.php",
    method: "POST",
    contentType: "application/json",
    dataType: "json",
    data: JSON.stringify(data),

  success: function (res) {
    if (res.status === true) {
      showMessage(res.message, "success");
      $("#registerForm")[0].reset();

      // Redirect to login after 2 seconds
      setTimeout(function () {
        window.location.href = "login.php";
      }, 1000);

    } else {
      showMessage(res.message || "Registration failed", "error");
    }
  },

    

    error: function () {
      showMessage("Server error. Please try again.", "error");
    },

    complete: function () {
      $("#registerBtn").text("Create Account").prop("disabled", false);
    }
  });
});

function showMessage(message, type) {
  const box = $("#responseBox");

  box
    .removeClass("hidden bg-red-100 bg-green-100 text-red-700 text-green-700");

  if (type === "success") {
    box.addClass("bg-green-100 text-green-700");
  } else {
    box.addClass("bg-red-100 text-red-700");
  }

  box.text(message);
}
</script>

