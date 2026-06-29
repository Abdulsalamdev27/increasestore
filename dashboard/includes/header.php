<?php
require_once __DIR__ . "/../../middleware/authentication.php";

/**
 * 🔐 JWT PAGE GUARD
 */
$authUser = jwtPageGuard();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Chivo:ital,wght@0,100..900;1,100..900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">

  <!-- Tailwind -->
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

  <!-- Alpine.js -->
  <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

  <!-- Phosphor Icons -->
  <script src="https://unpkg.com/@phosphor-icons/web"></script>

  <!-- Custom CSS -->
  <link rel="stylesheet" href="/Einvite/dashboard/assets/css/custom.css" />

  <style type="text/tailwindcss">
    @theme {
      --color-color-one: #ffffff;
      --color-color-two: #2557a7c9;
      --font-title: "Chivo", sans-serif;
      --font-body: "Quicksand", sans-serif;
    }
    .input {
      @apply w-full rounded-xl border border-gray-300 px-4 py-3 text-sm text-gray-900 bg-white h-12
        focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition;
    }
    .label {
      @apply block text-sm font-medium text-gray-700 mb-1;
    }
  </style>
</head>

<body class="font-body bg-gray-50">

<!-- NAVBAR -->
<?php include __DIR__ . "/navbar.php"; ?>

<!-- MAIN LAYOUT -->
<div class="flex min-h-screen">

  <!-- SIDEBAR -->
  <?php include __DIR__ . "/sidebar.php"; ?>

  <!-- CONTENT AREA -->
  <div class="flex-1 flex flex-col lg:ml-72 transition-all duration-300">



    <!-- PAGE CONTENT -->
    <main class="flex-1 p-6 bg-gray-50">
      <!-- Your page content goes here -->
