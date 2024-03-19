<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta name="format-detection" content="telephone=no"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="MobileOptimized" content="176"/>
    <meta name="HandheldFriendly" content="True"/>
    <meta name="robots" content="noindex,nofollow"/>
    <title></title>
    <script src="https://telegram.org/js/telegram-web-app.js?1"></script>
</head>

<body class="" style="visibility: hidden;">
    <form action="{{ route("telegram.user.exists") }}" method="POST" id="form">
        @csrf
        <input type="hidden" id="auth" name="data">
    </form>
<script type="application/javascript">
    Telegram.WebApp.expand();
    @if (session('error'))
        Telegram.WebApp.close();
    @else
    var initData = Telegram.WebApp.initData || '';
    var initDataUnsafe = Telegram.WebApp.initDataUnsafe || {};

    if (initDataUnsafe.query_id && initData) {
        document.getElementById('auth').value = initData;
        document.getElementById('form').submit();
    }
    @endif
</script>
</body>
</html>
