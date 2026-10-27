<!DOCTYPE html>
<html>

<head>
    <title>419 - Page Expired</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: Arial;
            text-align: center;
            padding: 100px;
            background: #f8f9fa;
        }

        .box {
            background: white;
            padding: 40px;
            border-radius: 10px;
            display: inline-block;
        }
    </style>
</head>

<body>
    <div class="box">
        <h1>419</h1>
        <h3>Page Expired</h3>
        <p>Sesi anda telah habis, silakan kembali kehalaman sebelumnya dan refresh halaman tersebut.</p>

        <a href="{{ url()->previous() }}">Kembali</a>
    </div>
</body>

</html>
