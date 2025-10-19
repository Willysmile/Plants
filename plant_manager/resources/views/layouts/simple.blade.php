<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>@yield('title', 'Formulaire')</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="p-6 bg-gray-50">
  <div class="max-w-2xl mx-auto">
    @yield('content')
  </div>
  
  @yield('extra-scripts')
</body>
</html>
