<html>
    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <style>
            body {
                margin:0 auto;
            }
        </style>
    </head>
    <body>
        <div id="app">
        <router-view></router-view>
        </div>
    </body>
    <script src="/js/app.js"></script>
    <link rel="stylesheet" href="css/main.css">
</html>