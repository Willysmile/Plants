<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Plantes')</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <script src="https://unpkg.com/lucide@latest"></script>
  <style>
    [x-cloak]{display:none!important}
    @keyframes spin {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }
  </style>
  @yield('extra-head')
</head>
<body class="bg-gray-50 text-gray-900">
  <script>
    console.log('=== APP LAYOUT LOADED ===');
  </script>
  
  @yield('content')
  
  @yield('extra-scripts')
  
  @stack('scripts')
  
  <script>
    console.log('=== END OF BODY ===');
    // Initialize Lucide icons
    if (typeof lucide !== 'undefined') {
      lucide.createIcons();
    }
  </script>
</body>
</html>
