<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Bevestiging van je afspraak</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f9f5ef; font-family: Arial, sans-serif; color: #4a4137;">
<table width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td align="center" style="padding: 40px 20px;">
            <table width="600" style="background: white; border-radius: 16px; padding: 40px; box-shadow: 0 0 10px rgba(0,0,0,0.05);">
                <tr>
                    <td align="center" style="padding-bottom: 30px;">
                        <h1 style="color: #9e8356; margin-bottom: 0;">✨ Bedankt voor je reservering!</h1>
                        <p style="font-size: 16px;">We kijken ernaar uit je te verwelkomen in onze salon.</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <h3 style="color: #9e8356;">📅 Details van je afspraak</h3>
                        <p><strong>Naam:</strong> {{ $appointment->name }}</p>
                        <p><strong>Datum:</strong> {{ \Carbon\Carbon::parse($appointment->day)->translatedFormat('l d-m-Y') }}</p>
                        <p><strong>Tijd:</strong> {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}</p>
                        <p><strong>Dienst:</strong> {{ $appointment->service->title }}</p>
                        @if ($appointment->notes)
                            <p><strong>Opmerking:</strong> {{ $appointment->notes }}</p>
                        @endif
                    </td>
                </tr>

                <tr>
                    <td style="padding: 30px 0;">
                        <hr style="border: none; border-top: 1px solid #e6e2d8;">
                    </td>
                </tr>

                <tr>
                    <td>
                        <h3 style="color: #9e8356;">📍 Alphen Beauty Lounge</h3>
                        <p><strong>Adres:</strong><br>Ouvertureweg 135<br>2402 DX Alphen aan den Rijn</p>
                        <p><strong>Telefoon:</strong> (06) 24 67 45 53<br>
                            <strong>Email:</strong> Alphen.beauty.lounge@gmail.com</p>

                        <h4 style="margin-top: 20px; color: #9e8356;">🕘 Openingstijden:</h4>
                        <ul style="padding-left: 20px; margin-top: 5px;">
                            <li>Maandag: 15:30 – 18:00</li>
                            <li>Dinsdag: 15:30 – 18:00</li>
                            <li>Woensdag: 15:30 – 18:00</li>
                            <li>Donderdag: 09:00 – 18:00</li>
                            <li>Vrijdag: 09:00 – 19:00</li>
                            <li>Zaterdag: 08:30 – 16:00</li>
                            <li>Zondag: Gesloten</li>
                        </ul>
                    </td>
                </tr>

                <tr>
                    <td align="center" style="padding-top: 30px; font-size: 14px; color: #999;">
                        <p>Tot snel bij <strong>Alphen Beauty Lounge</strong>! 💆‍♀️💅</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
