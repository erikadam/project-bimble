<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" href="{{ asset('images/favicon-baru.png') }}">
        <title>{{ config('app.name', 'Petpideducatin') }}</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <script src="https://cdn.tiny.cloud/1/a5vilflxh0koq7863vn1vxc1ld87vara926ftkiipcix2sn6/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
        <script src="https://unpkg.com/tinymce-mathlive@latest/dist/plugin.min.js"></script>

       <script>
    MathJax = {
      tex: {
        inlineMath: [['$', '$'], ['\\(', '\\)']],
        displayMath: [['$$', '$$'], ['\\[', '\\]']]
      },
      svg: {
        fontCache: 'global'
      }
    };

    document.addEventListener('DOMContentLoaded', function() {
        tinymce.init({
            selector: '.tinymce-editor',
            height: 500,

            external_plugins: {
                'mathlive': 'https://unpkg.com/tinymce-mathlive@latest/dist/plugin.min.js'
            },

            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'mathlive', 'template', 'help', 'wordcount'
            ],

            toolbar: 'undo redo | blocks | ' +
                'bold italic forecolor | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'link image media table mathlive | removeformat | help',

            content_style: `
                body {
                    background-color: #1f2937;
                    color: #d1d5db;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                }
                th, td {
                    border: 1px solid #4a5568;
                    padding: 8px;
                }
                th {
                    background-color: #2d3748;
                }
                img {
                    max-width: 100%;
                    height: auto;
                }
            `
        });
    });
</script>
<script type="text/javascript" id="MathJax-script" async
  src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-svg.js">
</script>
        <script type="text/javascript" id="MathJax-script" async
          src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-svg.js">
        </script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-brand-dark">
            @include('layouts.navigation')

            @isset($header)
                <header class="bg-white dark:bg-brand-dark-secondary shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main>
                {{ $slot }}
            </main>
        </div>
        @stack('scripts')
    </body>
</html>
