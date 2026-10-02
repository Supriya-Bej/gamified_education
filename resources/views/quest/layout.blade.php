<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | EcoQuest</title>
    <link rel="stylesheet" href="{{ asset('Asset/Bootstrap-5/css/bootstrap.min.css') }}">
    <style>
        body { background:#0f1117; color:#e8eaf0; }
        .quest-box { max-width:760px; margin:40px auto; background:#181b25;
                     border:1px solid #2a2f3d; border-radius:16px; padding:32px; }
        .q-card { border:1px solid #2a2f3d; border-radius:12px; padding:16px; margin-bottom:16px; }
    </style>
</head>
<body>
    <div class="quest-box">
        @yield('content')
    </div>
</body>
</html>