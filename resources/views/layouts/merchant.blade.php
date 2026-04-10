<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Merchant Dashboard</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 font-sans">

<div class="flex h-screen">

    <!-- SIDEBAR -->
    @include('layouts.sidebar')

    <!-- CONTENT -->
    <main class="flex-1 p-6 overflow-y-auto">
        @yield('content')
    </main>

</div>

</body>
</html>