<html>
    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>贝兼兼职，最懂你的兼职平台</title>
        <link rel="shortcut icon" href="images/favicon.ico" />
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
    <script src="{{ mix('/js/app.js') }}"></script>
    <link rel="stylesheet" href="css/main.css">
</html>