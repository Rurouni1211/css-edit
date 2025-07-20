<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title inertia>{{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Shippori+Mincho:wght@400;500;600;700;800&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .font-shippori {
            font-family: 'Shippori Mincho', sans-serif;
        }
        .font-montserrat {
            font-family: 'Montserrat', sans-serif;
        }
    </style>
    @routes
    @vite(['resources/css/store.css', 'resources/js/store.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp,container-queries"></script>
    <script>
        tailwind.config = {
            theme: {
                screens: {
                    sm: "350px",
                    xs: "640px",
                    md: "760px",
                    lg: "1170px",
                    xl: "1280px",
                    "2xl": "1400px",
                },
                extend: {
                    colors: {
                        clifford: "#da373d",
                    },
                },
            },
        };
    </script>
</head>
<body class="bg-[#f2f2f3]" >
    @inertia
</body>
</html>
