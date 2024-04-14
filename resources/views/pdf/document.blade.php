<!-- resources/views/pdf/document.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Documento PDF</title>
    <style>
        body { font-family: 'DejaVu Sans'; }
        h1 { color: #333; }
    </style>
</head>
<body>
    <h1>{{ $title }}</h1>
    <p>{{ $content }}</p>
</body>
</html>
