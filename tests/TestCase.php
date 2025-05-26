<?php

function processOrder($order) {
    // حساب المجموع
    $total = 0;
    foreach ($order['items'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    // تطبيق الخصم
    if ($order['customer']['type'] == 'VIP') {
        $total *= 0.9;
    }

    // عرض التفاصيل
    echo "Customer: " . $order['customer']['name'] . "\n";
    echo "Total: " . $total . "\n";

    // حفظ الفاتورة
    file_put_contents('invoice.txt', "Customer: " . $order['customer']['name'] . "\nTotal: " . $total);
}

