@php
    $newLink = request()->segment(2) ?? '';

    $formattedTitle = collect(explode('-', $newLink))
        ->map(function($word) {
            return ucfirst($word); 
        })
        ->join(' '); 

    if ($formattedTitle) {
        $formattedTitle .= ' | ';
    }
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Convert your images effortlessly with our Online Image Converter. Drag, drop, and convert to JPEG, PNG, WEBP, GIF, BMP, and more in seconds!" />
    <meta name="keywords" content="Image Converter, Online Image Conversion, Convert Images, JPEG to PNG, PNG to JPEG, Image File Formats, Online Converter" />
    <meta name="author" content="Your Company Name" />
    <meta name="robots" content="index, follow">
    <meta name="googlebot" content="index, follow">
    <title>{{ $formattedTitle }}Online Image Converter | Effortless Image Format Conversion</title>


    <link href="{{ asset('images/favicon.png') }}" rel="icon">
    <link href="{{ asset('images/favicon.png') }}" rel="apple-touch-icon">

    <!-- Tags OG -->
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:title" content="Online Image Converter | Effortless Image Format Conversion" />
    <meta property="og:description" content="Easily convert images to JPEG, PNG, WEBP, GIF, BMP, AVIF, and more. Drag and drop for seamless online image conversion!" />
    <meta property="og:image" content="{{ asset('images/converter-preview.png') }}" />
    <meta property="og:site_name" content="Online Image Converter" />
    <!-- Tags OG -->

    <!-- Tags Twitter Card -->
    {{-- <meta name="twitter:card" content="summary_large_image"> --}}
    <meta name="twitter:title" content="Online Image Converter | Effortless Image Format Conversion">
    <meta name="twitter:description" content="Convert images online in seconds. Supports JPEG, PNG, WEBP, GIF, BMP, AVIF, and more. Simple drag-and-drop interface for quick conversions!">
    <meta name="twitter:image" content="{{ asset('images/converter-preview.png') }}">
    {{-- <meta name="twitter:site" content="@YourTwitterHandle"> --}}
    <!-- Tags Twitter Card -->

    <!-- Google analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-WMB4K5EVCL"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'G-WMB4K5EVCL');
    </script>
    <!-- Google analytics -->

    <link type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800" rel="stylesheet">

    <script defer src="https://cdn.jsdelivr.net/npm/tsparticles@2/tsparticles.bundle.min.js"></script>
</head>

<body>
    <div id="app"></div>
</body>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/bulma/1.0.2/css/bulma.min.css" rel="stylesheet" integrity="sha512-RpeJZX3aH5oZN3U3JhE7Sd+HG8XQsqmP3clIbu4G28p668yNsRNj3zMASKe1ATjl/W80wuEtCx2dFA8xaebG5w==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<script src="{{ asset('js/bundle.js') }}?v=1.1.1"></script>
<script src="https://www.google.com/recaptcha/api.js"></script>

</html>
