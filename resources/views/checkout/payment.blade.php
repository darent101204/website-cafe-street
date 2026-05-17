<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete QRIS Payment — Coffee Street</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,500&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #FF902A;
            --primary-hover: #e57e20;
            --dark-bg: #1C1C1C;
            --card-bg: #2F2F2F;
            --text-gold: #FF902A;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--dark-bg);
            color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            overflow-x: hidden;
        }

        /* Glassmorphism details */
        .payment-card {
            background-color: var(--card-bg);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 24px;
            padding: 40px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
            position: relative;
            z-index: 2;
        }

        .brand-logo {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 1.8rem;
            color: #ffffff;
            letter-spacing: 0.5px;
        }

        .brand-logo span {
            color: var(--primary-color);
        }

        /* Loading / Scanning Pulse Animation */
        .qris-pulse-wrapper {
            position: relative;
            width: 110px;
            height: 110px;
            margin: 0 auto;
        }

        .qris-icon-bg {
            position: absolute;
            top: 10px;
            left: 10px;
            width: 90px;
            height: 90px;
            background: rgba(255, 144, 42, 0.1);
            border: 2px solid var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.2rem;
            color: var(--primary-color);
            z-index: 3;
            animation: pulse-heart 2s infinite ease-in-out;
        }

        .pulse-wave {
            position: absolute;
            top: 0;
            left: 0;
            width: 110px;
            height: 110px;
            border: 2px solid rgba(255, 144, 42, 0.3);
            border-radius: 50%;
            animation: pulse-out 2s infinite linear;
            opacity: 0;
        }

        @keyframes pulse-heart {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        @keyframes pulse-out {
            0% { transform: scale(0.8); opacity: 0; }
            50% { opacity: 0.6; }
            100% { transform: scale(1.2); opacity: 0; }
        }

        .btn-pay-now {
            background-color: var(--primary-color);
            color: #ffffff;
            font-weight: 600;
            border: none;
            padding: 14px 28px;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(255, 144, 42, 0.3);
        }

        .btn-pay-now:hover {
            background-color: var(--primary-hover);
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 144, 42, 0.4);
        }

        .btn-outline-custom {
            background-color: transparent;
            border: 2px solid rgba(255, 255, 255, 0.1);
            color: #adb5bd;
            font-weight: 500;
            padding: 12px 24px;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .btn-outline-custom:hover {
            border-color: rgba(255, 255, 255, 0.25);
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.05);
        }

        /* Order Info Detail Block */
        .order-summary-box {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.04);
            border-radius: 16px;
            padding: 20px;
            margin: 25px 0;
        }

        .divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.08);
            margin: 15px 0;
        }

        /* Custom Scrollbar for Midtrans Frame */
        iframe {
            border-radius: 12px !important;
        }
    </style>
</head>
<body>

    <div class="payment-card text-center">
        <!-- Logo -->
        <div class="brand-logo mb-4">
            Coffee<span>Street</span>
        </div>

        <!-- Scanning Animation -->
        <div class="qris-pulse-wrapper mb-4">
            <div class="qris-icon-bg">
                <i class="fa-solid fa-qrcode"></i>
            </div>
            <div class="pulse-wave"></div>
        </div>

        <!-- Instructions -->
        <h4 class="fw-bold mb-2">QRIS Payment Gateway</h4>
        <p class="small text-secondary px-3">
            A Midtrans secure billing checkout popup has been launched. Please scan the QRIS using GoPay, ShopeePay, DANA, OVO, LinkAja, or any other bank app.
        </p>

        <!-- Order Summary -->
        <div class="order-summary-box text-start">
            <div class="d-flex justify-content-between">
                <span class="text-secondary small">Order ID</span>
                <span class="fw-medium text-white small">#{{ $order->id }}</span>
            </div>
            <div class="d-flex justify-content-between mt-1">
                <span class="text-secondary small">Midtrans Reference</span>
                <span class="fw-medium text-white small">{{ $order->midtrans_order_id }}</span>
            </div>
            <div class="divider"></div>
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-secondary font-weight-bold">Total Amount</span>
                <span class="fs-5 fw-bold text-gold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Actions -->
        <div class="d-grid gap-3">
            <button id="pay-button" class="btn btn-pay-now">
                <i class="fa-solid fa-expand me-2"></i> Re-open Payment QRIS
            </button>
            
            <a href="{{ route('order.track', $order->tracking_token) }}" class="btn btn-outline-custom">
                <i class="fa-solid fa-rotate me-2"></i> Check Payment Status
            </a>
            
            <a href="{{ route('home') }}" class="btn btn-link text-secondary text-decoration-none small mt-2">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to Main Menu
            </a>
        </div>
    </div>

    <!-- Midtrans Snap JS Sandbox Library -->
    <script type="text/javascript" 
            src="https://app.sandbox.midtrans.com/snap/snap.js" 
            data-client-key="{{ config('midtrans.client_key') }}">
    </script>
    
    <script type="text/javascript">
        // Function to launch Midtrans Snap
        function triggerPayment() {
            snap.pay('{{ $order->snap_token }}', {
                onSuccess: function(result) {
                    console.log('Payment Success:', result);
                    window.location.href = '{{ route("order.track", $order->tracking_token) }}';
                },
                onPending: function(result) {
                    console.log('Payment Pending:', result);
                    window.location.href = '{{ route("order.track", $order->tracking_token) }}';
                },
                onError: function(result) {
                    console.error('Payment Error:', result);
                    window.location.href = '{{ route("order.track", $order->tracking_token) }}';
                },
                onClose: function() {
                    console.log('User closed payment popup.');
                }
            });
        }

        // Auto-open on page load
        window.onload = function() {
            triggerPayment();
        };

        // Attach action to Re-open button
        document.getElementById('pay-button').onclick = function() {
            triggerPayment();
        };
    </script>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
