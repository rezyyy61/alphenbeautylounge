<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nieuwe Afspraak</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans">
<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-md overflow-hidden mt-10">
    <div class="bg-[#9e8356] text-white text-center py-4">
        <h1 class="text-2xl font-bold">📌 Nieuwe afspraak ingepland</h1>
    </div>
    <div class="p-6 space-y-4">
        <p>Er is zojuist een nieuwe afspraak geboekt:</p>
        <ul class="space-y-1">
            <li><strong>Naam:</strong> {{ $appointment->name }}</li>
            <li><strong>Email:</strong> {{ $appointment->email }}</li>
            <li><strong>Telefoon:</strong> {{ $appointment->phone }}</li>
            <li><strong>Service:</strong> {{ $appointment->service->title }}</li>
            <li><strong>Datum:</strong> {{ $appointment->start_time->format('d-m-Y') }}</li>
            <li><strong>Tijd:</strong> {{ $appointment->start_time->format('H:i') }} – {{ $appointment->end_time->format('H:i') }}</li>
            @if($appointment->notes)
                <li><strong>Notitie:</strong> {{ $appointment->notes }}</li>
            @endif
        </ul>

        <hr class="my-4 border-gray-300">

        <h2 class="text-xl font-semibold text-[#9e8356]">📖 Afspraken op deze dag:</h2>

        @if(count($conflicts))
            <ul class="list-disc pl-5 space-y-1">
                @foreach($conflicts as $conflict)
                    <li><strong>{{ $conflict->name }}</strong> op {{ $conflict->start_time->format('d-m-Y H:i') }}</li>
                @endforeach
            </ul>
        @else
            <p class="text-gray-600">Geen eerdere afspraken op dit tijdstip.</p>
        @endif
    </div>
</div>
</body>
</html>
