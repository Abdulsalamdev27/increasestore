<?php require 'config/function.php';?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>E-Invite</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Chivo:wght@100..900&family=Open+Sans:wght@300..800&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <!-- Swiper -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.css"/>

  <!-- Custom CSS -->
  <link rel="stylesheet" href="assets/css/custom.css"/>

  <!-- Tailwind CDN -->
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

  <!-- Tailwind Theme + Utilities -->
  <style type="text/tailwindcss">
    @theme {
      --color-color-one: #ffffff;
      --color-color-two: #2557A7;
      --color-color-three: #D8E2FF;
      --color-color-four: #999999;

      --font-title: "Chivo", sans-serif;
      --font-body: "Open Sans", sans-serif;
    }

    /* =========================
       ANIMATION
    ========================== */
    section .animate {
      opacity: 0;
      transition: all 1s ease;
    }

    section.show-animate .animate {
      opacity: 1;
    }

    .sec-1 .animate { transform: translateX(-80px); }
    .sec-1.show-animate .animate { transform: translateX(0); }

    .sec-2 .animate { transform: translateX(80px); }
    .sec-2.show-animate .animate { transform: translateX(0); }

    .sec-3 .animate { transform: scale(0.85); }
    .sec-3.show-animate .animate { transform: scale(1); }

    .sec-6 .animate { transform: scale(0.75); }
    .sec-6.show-animate .animate { transform: scale(1); }

    /* =========================
       NAVBAR UTILITIES
    ========================== */
    .nav-link {
      @apply px-4 py-2 rounded-md font-medium hover:bg-color-two hover:text-white transition duration-300;
    }

    .nav-link-mobile {
      @apply px-3 py-2 rounded-md hover:bg-color-three transition;
    }

    .btn-primary {
      @apply px-4 py-2 rounded-md bg-color-two text-white hover:bg-indigo-800 transition;
    }

    .btn-secondary {
      @apply px-4 py-2 rounded-md bg-color-three text-color-two hover:bg-indigo-200 transition;
    }

    .btn-danger {
      @apply px-4 py-2 rounded-md bg-red-600 text-white hover:bg-red-700 transition;
    }
/* Event Offer Cards */
  .event-card {
    @apply flex flex-col items-center justify-center text-center
          bg-white p-6 rounded-xl shadow-md
          border border-transparent
          hover:border-color-two hover:bg-[#EAEFFF]
          transition duration-500 ease-in-out;
  }

  .event-card i {
    @apply text-2xl text-color-two mb-2;
  }

  .event-card p {
    @apply font-body font-medium text-sm;
  }

  /* Scroll reveal animation */
  .reveal {
    opacity: 0;
    transform: translateY(40px);
    transition: all 0.9s ease-out;
  }

  .reveal.show {
    opacity: 1;
    transform: translateY(0);
  }

  /* Event cards */
  .event-card {
    @apply flex flex-col items-center justify-center text-center
          bg-white p-6 rounded-xl shadow-md
          border border-transparent
          hover:border-color-two hover:bg-[#EAEFFF]
          transition duration-500 ease-in-out;
  }

  .event-card i {
    @apply text-2xl text-color-two mb-2;
  }

  .event-card p {
    @apply font-body font-medium text-sm;
  }


  .premium-card {
    @apply bg-white rounded-xl shadow-lg p-3 flex flex-col gap-3
    transition duration-300 hover:-translate-y-1 hover:shadow-xl;
  }

  .premium-card h3 {
    @apply font-semibold text-base;
  }

  .premium-card p {
    @apply text-sm text-gray-600 font-body;
  }

  .premium-img {
    @apply w-full h-48 object-cover rounded-lg;
  }

  .premium-btn {
    @apply mt-2 text-center bg-color-two text-white font-medium
    rounded-md py-2 hover:bg-white hover:text-color-two
    border-2 border-color-two transition;
  }

  .event-box {
    @apply bg-white p-4 rounded-xl shadow-md text-center
    hover:bg-[#EAEFFF] transition;
  }

  .event-box i {
    @apply text-color-two text-xl;
  }

  .event-box p {
    @apply mt-2 font-body text-sm font-medium;
  }

  .step-card {
    @apply bg-white p-6 rounded-xl shadow-lg;
  }

  .step-card i {
    @apply text-color-two text-2xl mb-3;
  }

  .step-card h3 {
    @apply font-semibold text-lg;
  }

  .step-card p {
    @apply text-gray-600 text-sm mt-1;
  }

  .btn-primary {
    @apply bg-color-two text-white px-6 py-2 rounded-md
    hover:bg-white hover:text-color-two border-2 border-color-two transition;
  }

  .btn-secondary {
    @apply bg-white text-color-two px-6 py-2 rounded-md
    hover:bg-blue-100 transition;
  }

  .footer-link {
    @apply text-blue-100 hover:text-white transition duration-300;
  }

  .social-icon {
    @apply p-2 rounded-lg border border-white/20 hover:bg-white hover:text-color-two transition duration-300;
  }



  </style>
</head>

<body class="font-body w-full overflow-x-hidden">

<?php include('navbar.php'); ?>
