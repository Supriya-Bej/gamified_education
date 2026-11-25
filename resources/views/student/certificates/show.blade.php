<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $certificate->title }} | EcoQuest</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 32px;
            background: #edf3ef;
            color: #173d32;
            font-family: Georgia, "Times New Roman", serif;
        }
        .certificate {
            position: relative;
            display: flex;
            min-height: 680px;
            max-width: 1120px;
            margin: 0 auto;
            padding: 64px;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: 12px double #b58b3b;
            outline: 1px solid #b58b3b;
            outline-offset: -24px;
            background: #fffefa;
            text-align: center;
        }
        .brand { color: #34745f; font: 700 16px Arial, sans-serif; letter-spacing: 5px; text-transform: uppercase; }
        h1 { margin: 28px 0 12px; color: #173d32; font-size: clamp(38px, 6vw, 64px); }
        .subtitle { color: #64746d; font: 16px Arial, sans-serif; letter-spacing: 2px; text-transform: uppercase; }
        .recipient { margin: 28px 0 8px; color: #9a7126; font-size: clamp(30px, 5vw, 48px); }
        .description { max-width: 700px; margin: 12px auto; font-size: 19px; line-height: 1.6; }
        .quest { margin: 12px 0 26px; font-size: 25px; font-weight: bold; }
        .details { display: flex; width: min(680px, 100%); justify-content: space-between; gap: 24px; margin-top: 40px; font: 14px Arial, sans-serif; }
        .detail { flex: 1; min-width: 0; }
        .detail-value { display: flex; min-height: 48px; align-items: flex-end; justify-content: center; padding-bottom: 8px; border-bottom: 1px solid #aeb9b2; }
        .detail-label { display: block; padding-top: 8px; }
        .signature { color: #173d32; font: 400 14px/1.1 "Segoe Script", "Brush Script MT", cursive; }
        .actions { margin: 24px auto; text-align: center; font: 15px Arial, sans-serif; }
        .actions a, .actions button { margin: 0 6px; border: 0; border-radius: 6px; padding: 10px 16px; background: #246b55; color: white; text-decoration: none; cursor: pointer; }
        @media print {
            @page { size: landscape; margin: 0; }
            body { padding: 0; background: white; print-color-adjust: exact; -webkit-print-color-adjust: exact; }
            .certificate { width: 100vw; height: 100vh; min-height: 0; max-width: none; border-width: 12px; }
            .actions { display: none; }
        }
        @media (max-width: 600px) {
            body { padding: 12px; }
            .certificate { min-height: 600px; padding: 46px 28px; }
            .details { flex-direction: column; }
        }
    </style>
</head>
<body>
    <main class="certificate">
        <div class="brand">EcoQuest · Gamified Learning</div>
        <div class="subtitle">Official Recognition</div>
        <h1>{{ $certificate->title }}</h1>
        <p class="description">This certificate is proudly presented to</p>
        <div class="recipient">{{ $certificate->user->name }}</div>
        <p class="description">
            @if ($certificate->interest)
                for completing all 25 beginner, 25 intermediate, and 25 advanced games in {{ ucfirst($certificate->interest) }}, approved by an EcoQuest administrator
            @else
                for achieving a perfect score and completing the quest
            @endif
        </p>
        <div class="quest">{{ $certificate->task->title }}</div>
        <div class="details">
            <div class="detail">
                <span class="detail-value">{{ $certificate->certificate_number }}</span>
                <strong class="detail-label">Certificate No.</strong>
            </div>
            <div class="detail">
                <span class="detail-value">{{ $certificate->issued_at->format('F j, Y') }}</span>
                <strong class="detail-label">Date issued</strong>
            </div>
            @php($issuerName = $certificate->issuer?->name ?? 'EcoQuest Administrator')
            <div class="detail">
                <span class="detail-value signature" role="img" aria-label="Digital signature of {{ $issuerName }}">{{ $issuerName }}</span>
                <strong class="detail-label">Issued by {{ $issuerName }}</strong>
            </div>
        </div>
    </main>
    <div class="actions">
        <button type="button" onclick="window.print()">Print / Save as PDF</button>
        <a href="{{ route('student.certificates.index') }}">Back to certificates</a>
    </div>
</body>
</html>
