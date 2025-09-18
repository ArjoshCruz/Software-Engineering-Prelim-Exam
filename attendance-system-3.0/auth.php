<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Attendance System</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center">

<?php
    include "include/headerAuth.php";
?>

<div class="max-w-4xl w-full grid grid-cols-1 md:grid-cols-2 gap-6 p-6 mt-36">
    <!-- Student Container -->
    <div class="bg-lime-400 rounded-lg shadow p-8 flex flex-col items-center justify-center">
    <div class="flex items-center justify-center w-20 h-20 rounded-full border-4 border-white mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M5.121 17.804A13.937 13.937 0 0112 15c2.485 0 4.779.64 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
    </div>
    <h2 class="text-2xl font-bold mb-4 text-white">Student</h2>
    <a href="student/auth/login.php"
        class="bg-white text-lime-600 font-semibold px-6 py-3 rounded hover:bg-lime-100 transition">Go to Student</a>
    </div>

    <!-- Admin Container -->
    <div class="bg-blue-900 rounded-lg shadow p-8 flex flex-col items-center justify-center">
    <div class="flex items-center justify-center w-20 h-20 rounded-full border-4 border-white mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 17v-6h6v6m2 4H7a2 2 0 01-2-2v-8h2V9a5 5 0 0110 0v2h2v8a2 2 0 01-2 2z" />
        </svg>
    </div>
    <h2 class="text-2xl font-bold mb-4 text-white">Admin</h2>
    <a href="admin/auth/login.php"
        class="bg-white text-blue-900 font-semibold px-6 py-3 rounded hover:bg-blue-100 transition">Go to Admin</a>
    </div>


</div>

</body>
</html>
