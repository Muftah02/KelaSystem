<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>مستند توريد مواد طبية</title>
    <style>
        body { font-family: Arial, sans-serif; direction: rtl; margin: 20px; }
        .container { width: 100%; margin: auto; }
        .header { border: 2px solid #000; padding: 10px; display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; }
        .header .header-text { text-align: center; flex-grow: 1; }
        .header img { height: 70px; margin-left: 20px; }
        .document-title { font-size: 22px; font-weight: bold; text-align: center; margin: 20px 0; border-bottom: 2px solid #000; padding-bottom: 5px; }
        .details { font-size: 16px; margin-bottom: 20px; border: 1px solid #000; padding: 10px; }
        .details p { margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: center; font-size: 14px; }
        th { background: #f4f4f4; }
        .signature-table { width: 100%; margin-top: 30px; border-collapse: collapse; }
        .signature-table th, .signature-table td { border: 1px solid #000; padding: 15px; text-align: center; font-size: 14px; }
        .signature-table th { background: #f4f4f4; }
        .signature-space { height: 60px; vertical-align: top; text-align: right; padding-right: 20px; }
        @media print { button { display: none; } }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <img src="{{ asset('images/kela.svg') }}" alt="شعار المؤسسة">
        <div class="header-text">
            <h2 style="margin: 5px 0;">دولة ليبيا</h2>
            <h3 style="margin: 5px 0;">مركز زليتن لخدمات الكلى</h3>
        </div>
    </div>

    <div class="document-title">مستند توريد مواد طبية</div>

    <div class="details">
        <p><strong>التاريخ:</strong> {{ $date }}</p>
        <p><strong>المورد:</strong> {{ $supplier_name }}</p>
        <p><strong>رقم الفاتورة:</strong> {{ $invoice_number }}</p><br>
        <p><strong>ملاحظة :</strong> ----------------------------------------------------------------------</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>رقم الصنف</th>
                <th>اسم الصنف</th>
                <th>الشركة</th>
                <th>الوحدة</th>
                <th>العدد المورد</th>
            </tr>
        </thead>
        <tbody>
            @php $total_qty = 0; @endphp
            @foreach ($sales as $index => $sale)
                @php $total_qty += (int) $sale['qty']; @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $sale['product_id'] }}</td>
                    <td>{{ $sale['product_name'] }}</td>
                    <td>{{ $sale['category_name'] }}</td>
                    <td>{{ $sale['unit_name'] }}</td>
                    <td>{{ $sale['qty'] }}</td>
                </tr>
            @endforeach

            <tr style="font-weight: bold; background-color: #f8f9fa;">
                <td colspan="5" style="text-align: left; padding-right: 20px;">المجموع الكلي</td>
                <td>{{ $total_qty }}</td>
            </tr>
        </tbody>
    </table>

    <table class="signature-table">
        <thead>
            <tr>
                <th>أمين المخزن</th>
                <th>قسم المنظومة</th>
                <th>الإذن بالصرف مدير الإدارة</th>
                <th>الإذن بالصرف المدير العام</th>
                <th>المستلم</th>
            </tr>
        </thead>
        <tbody>
            <tr class="signature-space">
                <td><p>الاسم: ____________</p><p>التوقيع: ____________</p></td>
                <td><p>الاسم: ____________</p><p>التوقيع: ____________</p></td>
                <td><p>الاسم: ____________</p><p>التوقيع: ____________</p></td>
                <td><p>الاسم: ____________</p><p>التوقيع: ____________</p></td>
                <td><p>الاسم: ____________</p><p>التوقيع: ____________</p></td>
            </tr>
        </tbody>
    </table>
</div>

<script> window.print(); </script>

</body>
</html> 