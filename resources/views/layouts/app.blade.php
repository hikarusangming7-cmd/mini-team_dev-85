<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'Laravel') }}</title>
  @vite(['resources/sass/app.scss', 'resources/js/app.js'])
  @stack('head')
</head>
<body>
  <div id="app">
    {{-- 必要ならナビをここに --}}
    <main class="py-4">
      @yield('content')
    </main>
  </div>
  @stack('scripts')
</body>
</html>
