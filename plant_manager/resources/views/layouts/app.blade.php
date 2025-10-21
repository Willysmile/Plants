<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'Plants'))</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-50">
            @include('layouts.navigation')

            <!-- Page Content -->
            <main>
                @yield('content', $slot ?? '')
            </main>
        </div>
        
        <!-- Load Lucide Icons at the very end to avoid conflicts -->
    <!-- Alpine.js Loader -->
    <script src="{{ asset('js/alpine.js') }}"></script>
    <!-- Lucide Icons (local fallback vendor) -->
    <script src="{{ asset('vendor/lucide.min.js') }}"></script>

    <!-- Application JavaScript -->
    <script src="{{ asset('js/app.js') }}"></script>

        <!-- Debug gate: set window.DEBUG = true to allow console.log during development -->
        <script>
            window.DEBUG = window.DEBUG || false;
            if (!window.DEBUG) {
                console.log = function(){};
                console.debug = function(){};
                console.info = function(){};
            }
        </script>
        <script>
            (function initLucide() {
                const run = () => {
                    try {
                        if (typeof lucide !== 'undefined' && typeof lucide.createIcons === 'function') {
                            lucide.createIcons();
                        }
                    } catch (e) {
                        if (window.DEBUG) console.error('lucide.init error', e);
                    }
                };

                if (document.readyState === 'complete' || document.readyState === 'interactive') {
                    run();
                } else {
                    document.addEventListener('DOMContentLoaded', run);
                }
            })();
        </script>
        
        <!-- Page-specific scripts -->
        @yield('extra-scripts')
    </body>
</html>
