<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">





        <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    </head>
    <body class="antialiased">
        <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0">
            @if (Route::has('login'))
                <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">

                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Log in</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 dark:text-gray-500 underline">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="logo">
                <svg width="45" height="45" viewBox="0 0 263 250" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="26.6304" y="39.2391" width="209.87" height="209.87" rx="9.5" stroke="rgba(74, 85, 104, 0.8)"/>
                    <rect x="77.2174" y="190.913" width="19.0694" height="8.69562" fill="rgba(74, 85, 104, 0.8)"/>
                    <rect x="107.093" y="190.913" width="19.0694" height="8.69562" fill="rgba(74, 85, 104, 0.8)"/>
                    <rect x="137.468" y="191.413" width="18.0694" height="7.69562" fill="rgba(74, 85, 104, 0.8)" stroke="rgba(74, 85, 104, 0.8)"/>
                    <rect x="166.844" y="190.913" width="19.0694" height="8.69562" fill="rgba(74, 85, 104, 0.8)"/>
                    <circle cx="87.2174" cy="121.783" r="20" fill="rgba(74, 85, 104, 0.8)"/>
                    <circle cx="175.913" cy="121.783" r="20" fill="rgba(74, 85, 104, 0.8)"/>
                    <path d="M236.87 110.761H252.957C258.479 110.761 262.957 115.238 262.957 120.761V161.631C262.957 167.153 258.479 171.631 252.957 171.631H236.87V110.761Z" fill="rgba(74, 85, 104, 0.8)"/>
                    <path d="M0.173492 120.761C0.173492 115.238 4.65065 110.761 10.1735 110.761H26.2604V171.631H10.1735C4.65064 171.631 0.173492 167.153 0.173492 161.631V120.761Z" fill="rgba(74, 85, 104, 0.8)"/>
                    <rect x="122.674" y="0.043457" width="17.7826" height="40" fill="rgba(74, 85, 104, 0.8)"/>
                </svg>
            </div>


        </div>
    </body>
</html>
