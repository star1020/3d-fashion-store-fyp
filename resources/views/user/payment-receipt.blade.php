<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Receipt</title>
</head>
<body>
    <h1>Payment Receipt</h1>
    <p>Transaction ID: {{ $details['transactionId'] }}</p>
    <p>Payment Method: {{ $details['paymentMethod'] }}</p>
    <p>Payment Date: {{ $details['paymentDate'] }}</p>
    <p>Total Payment Fee: RM {{ $details['totalPaymentFee'] }}</p>
    <p>Delviery Price: RM {{ $details['deliveryPrice'] }}</p>
    <p>Delivery Address: {{ $details['deliveryAddress'] }}</p>
    <h2>Product Details:</h2>
    <ul>
        @foreach ($details['productDetails'] as $product)
            <li>
                {{ $product['quantity'] }} x {{ $product['productName'] }} (Color: {{ $product['color'] }}, Size: {{ $product['size'] }})
            </li>
        @endforeach
    </ul>
</body>
</html>