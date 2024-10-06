<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            text-align: center;
        }

        h1 {
            color: #4CAF50;
            margin-bottom: 20px;
        }

        p {
            color: #333;
            font-size: 18px;
            margin: 5px 0;
        }

        .success-image {
            width: 150px;
            height: auto;
            margin: 20px 0;
        }

        .container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 90%;
            max-width: 600px;
        }

        .payment-image {
            width: 200px;
            height: auto;
            margin: 20px 0;
        }

        footer {
            margin-top: 20px;
            font-size: 14px;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="{{ asset('images/Person sending money using phone flat vector illustration.jpg') }}" alt="Payment Success" class="success-image">
        <h1>Payment Successful!</h1>
        <p>Thank you for your payment.</p>
        <p>Your invoice ID is: <strong>{{ $invoiceId }}</strong></p>
        <p>Your payment ID is: <strong>{{ $paymentId }}</strong></p>
    </div>
    <footer>
        &copy; {{ date('Y') }} Yoo Store. All rights reserved.
    </footer>
</body>
</html>
