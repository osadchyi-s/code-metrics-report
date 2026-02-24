<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Code Metrics Report – SmartStudio</title>
    <script>
        (function () {
            var theme = localStorage.getItem('novaTheme');
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else if (theme === 'system' || theme === null) {
                if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    document.documentElement.classList.add('dark');
                }
            }
        })();
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { darkMode: 'class' }</script>
    @livewireStyles
</head>
<body class="bg-gradient-to-br from-slate-50 to-slate-100 dark:from-gray-950 dark:to-gray-900 min-h-screen transition-colors duration-200">
    {{ $slot }}
    @livewireScripts
</body>
</html>
