<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Contactbericht</title>
</head>
<body style="font-family: sans-serif; color: #4a4137;">
<h2>📩 Nieuw contactbericht</h2>
<p><strong>Naam:</strong> {{ $data['name'] }}</p>
<p><strong>Email:</strong> {{ $data['email'] }}</p>
<p><strong>Bericht:</strong></p>
<p style="background: #f9f5ef; padding: 15px; border-radius: 10px;">{{ $data['message'] }}</p>
</body>
</html>
