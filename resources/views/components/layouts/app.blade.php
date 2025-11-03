<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Page Title' }}</title>

    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link href="https://cdn.jsdelivr.net/npm/line-awesome@1.3.0/dist/line-awesome/css/line-awesome.min.css" rel="stylesheet">

</head>

<body>
    <div style="display: flex; min-height: 100vh;">
        {{-- Sidebar --}}
        <div style="width: 250px;">
            @livewire('sidebar')
        </div>

        {{-- Main content --}}
        <div style="flex: 1; display: flex; flex-direction: column;">
            @livewire('topbar')

            {{-- Page body --}}
            <div style="flex: 1; background: #F5F6FA; padding: 31px 30px;">
                <div style="color: #202224; font-size: 32px; font-family: Nunito Sans; font-weight: 700;">
                    {{ $title }}
                </div>
                {{ $slot }}
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>
