<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        /* Global Styling */
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        
        /* Container */
        .invoice-container {
            background-color: #fff;
            padding: 20px;
            margin: 20px auto;
            max-width: 700px;
            border: 2px solid #000;
            border-radius: 8px;
        }
        
        /* Header */
        .invoice-header {
            text-align: center;
            color: #f1c40f;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        
        .invoice-header h1 {
            font-size: 32px;
            color: #000;
        }
        
        /* Order Information */
        .order-info, .product-info {
            display: flex;
            justify-content: space-between;
            background-color: #000;
            color: #f1c40f;
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
        }
        
        .order-info p, .product-info p {
            margin: 5px 0;
        }
        
        /* Product Table */
        .product-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        .product-table th, .product-table td {
            padding: 12px;
            border: 1px solid #333;
            text-align: left;
        }
        
        .product-table th {
            background-color: #000;
            color: #f1c40f;
        }
        
        .product-table td {
            background-color: #fff;
        }
        
        .total {
            font-weight: bold;
            text-align: right;
            padding-right: 20px;
        }
        
        .footer {
            text-align: center;
            font-size: 12px;
            color: #666;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <h1>Yoostore Invoice</h1>
            <p>Invoice #{{ $order->invoice_id }}</p>
        </div>
        
        <!-- Order Information -->
        <div class="order-info">
            <div>
                <p><strong>User:</strong> {{ $order->user->name }}</p>
                <p><strong>Address:</strong> {{ $order->address->street }}</p>
            </div>
            <div>
                <p><strong>Order Date:</strong> {{ $order->created_at->format('d-m-Y') }}</p>
                <p><strong>Total Price:</strong> ${{ $order->total_price }}</p>
            </div>
        </div>
        
        <!-- Product Table -->
        <h2 style="color: #000;">Product Details</h2>
        <table class="product-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Size</th>
                    <th>Price</th>
                    <th>Color</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->orderProducts as $orderProduct)
                    <tr>
                        <td>{{ $orderProduct->product->name }}</td>
                        <td>{{ $orderProduct->quantity }}</td>
                        <td>{{ $orderProduct->size }}</td>
                        <td>${{ $orderProduct->productDetail->price ?? $orderProduct->productDetail->typeprice }}</td>
                        <td>{{ $orderProduct->productDetail->color ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <!-- Total Price -->
        <p class="total">Total Price: ${{ $order->total_price }}</p>
        
        <!-- Footer -->
        <div class="footer">
            <p>Thank you for shopping with Yoostore! Contact us at support@yoostore.com for any questions regarding your order.</p>
        </div>
    </div>
</body>
</html>
