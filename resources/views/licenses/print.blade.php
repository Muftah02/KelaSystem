<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ترخيص - {{ $license->client->full_name }}</title>
    <style>
        @media print {
            body {
                font-family: 'Arial', sans-serif;
                margin: 0;
                padding: 20px;
            }
            .container {
                max-width: 800px;
                margin: 0 auto;
                border: 2px solid #000;
                padding: 20px;
            }
            .header {
                text-align: center;
                margin-bottom: 30px;
                border-bottom: 2px solid #000;
                padding-bottom: 20px;
            }
            .content {
                margin-bottom: 30px;
            }
            .row {
                display: flex;
                margin-bottom: 15px;
            }
            .label {
                font-weight: bold;
                width: 200px;
            }
            .value {
                flex: 1;
            }
            .footer {
                text-align: center;
                margin-top: 50px;
                border-top: 2px solid #000;
                padding-top: 20px;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center; margin: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer;">
            طباعة الترخيص
        </button>
    </div>

    <div class="container">
        <div class="header">
            <h1>ترخيص</h1>
        </div>

        <div class="content">
            <div class="row">
                <div class="label">اسم العميل:</div>
                <div class="value">{{ $license->client->full_name }}</div>
            </div>

            <div class="row">
                <div class="label">السجل الصناعي:</div>
                <div class="value">{{ $license->commercial_record ?? '-' }}</div>
            </div>

            <div class="row">
                <div class="label">تاريخ البداية:</div>
                <div class="value">{{ $license->start_date->format('Y-m-d') }}</div>
            </div>

            <div class="row">
                <div class="label">تاريخ الانتهاء:</div>
                <div class="value">{{ $license->end_date->format('Y-m-d') }}</div>
            </div>

            <div class="row">
                <div class="label">الحالة:</div>
                <div class="value">{{ $license->status }}</div>
            </div>
        </div>

        <div class="footer">
            <p>تم إصدار هذا الترخيص بتاريخ: {{ now()->format('Y-m-d') }}</p>
        </div>
    </div>
</body>
</html> 