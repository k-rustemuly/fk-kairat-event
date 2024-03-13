<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        html {
            background-color: #002e5e;
        }
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #002e5e;
        }
        img {
            max-width: 80%;
            max-height: 80%;
            display: block;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        @page { margin:0px; }
    </style>
</head>

<body>
    <img src="data:image/png;base64,{{ $qrCode }}">
</body>
</html>
