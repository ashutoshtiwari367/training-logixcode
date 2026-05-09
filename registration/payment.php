<?php
/**
 * Payment Page
 * registration/payment.php
 */

session_start();
require_once __DIR__ . '/../config/db.php';

// Check if registration data exists in session
if (!isset($_SESSION['registration_data'])) {
    header('Location: index.php');
    exit;
}

// Check session timeout (10 minutes)
if (!isset($_SESSION['registration_timestamp']) || (time() - $_SESSION['registration_timestamp']) > 600) {
    unset($_SESSION['registration_data']);
    unset($_SESSION['registration_timestamp']);
    header('Location: index.php?error=session_expired');
    exit;
}

$registrationData = $_SESSION['registration_data'];

// FIX: Generate CSRF token properly (don't overwrite, check if exists)
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrfToken = $_SESSION['csrf_token'];

// Payment configuration
define('PAYMENT_AMOUNT', REGISTRATION_FEE);     // Using global config
define('PAYMENT_CURRENCY', 'INR');

// FIX: INSTITUTE_NAME define karo (ya db.php se import karo)
if (!defined('INSTITUTE_NAME')) {
    define('INSTITUTE_NAME', 'Your Institute Name'); // <-- Apna institute name yahan lagao
}

// Calculate amount in paise (Razorpay requires amount in smallest currency unit)
$amountInPaise = PAYMENT_AMOUNT * 100;

// -----------------------------------------------------------------------
// FIX (CRITICAL): Razorpay Order ID server-side banao
// Without order_id, signature verification hamesha fail hogi
// -----------------------------------------------------------------------
function createRazorpayOrder($amountInPaise) {
    $url  = 'https://api.razorpay.com/v1/orders';
    $data = json_encode([
        'amount'          => $amountInPaise,
        'currency'        => PAYMENT_CURRENCY,
        'receipt'         => 'reg_' . time() . '_' . random_int(1000, 9999),
        'payment_capture' => 1
    ]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_USERPWD,        RAZORPAY_KEY_ID . ':' . RAZORPAY_KEY_SECRET);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST,  'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS,     $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER,     ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_TIMEOUT,        30);

    $result   = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($result === false || $httpCode !== 200) {
        throw new Exception('Unable to create payment order. Please try again.');
    }

    $order = json_decode($result, true);
    if (!isset($order['id'])) {
        throw new Exception('Invalid response from payment gateway.');
    }

    return $order;
}

// Order create karo aur session mein store karo (page reload pe duplicate order na bane)
try {
    if (!isset($_SESSION['razorpay_order']) || $_SESSION['razorpay_order']['amount'] !== $amountInPaise) {
        $razorpayOrder = createRazorpayOrder($amountInPaise);
        $_SESSION['razorpay_order'] = $razorpayOrder;
    } else {
        $razorpayOrder = $_SESSION['razorpay_order'];
    }
} catch (Exception $e) {
    $orderError = $e->getMessage();
    $razorpayOrder = null;
}

// FIX: JS mein inject karne ke liye JSON encode use karo (XSS prevent hogi)
$jsName        = json_encode($registrationData['firstName'] . ' ' . $registrationData['lastName']);
$jsEmail       = json_encode($registrationData['email']);
$jsPhone       = json_encode(str_replace('+91', '', $registrationData['phone']));
$jsProgram     = json_encode('Registration Fee - ' . $registrationData['program']);
$jsInstitute   = json_encode(INSTITUTE_NAME);
$jsOrderId     = json_encode($razorpayOrder ? $razorpayOrder['id'] : '');
$jsCsrfToken   = json_encode($csrfToken);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - Student Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 0;
        }
        .payment-container {
            max-width: 700px;
            margin: 0 auto;
        }
        .payment-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            padding: 40px;
        }
        .payment-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .payment-header h1 {
            color: #333;
            font-weight: 700;
        }
        .summary-box {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 30px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #dee2e6;
        }
        .summary-row:last-child {
            border-bottom: none;
            font-weight: bold;
            font-size: 1.2rem;
            color: #667eea;
        }
        .btn-pay {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 15px 50px;
            font-size: 1.1rem;
            font-weight: 600;
            width: 100%;
        }
        .btn-pay:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        .security-badge {
            text-align: center;
            margin-top: 20px;
            color: #6c757d;
            font-size: 0.9rem;
        }
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <div class="payment-card">
            <div class="payment-header">
                <h1><i class="bi bi-credit-card-fill text-primary"></i> Complete Payment</h1>
                <p class="text-muted">Review your details and proceed to payment</p>
            </div>

            <?php if (isset($orderError)): ?>
            <!-- Order creation error show karo -->
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <?php echo htmlspecialchars($orderError); ?>
                <br><a href="payment.php" class="alert-link">Please try again</a>
            </div>
            <?php endif; ?>

            <!-- Registration Summary -->
            <div class="summary-box">
                <h5 class="mb-3"><i class="bi bi-person-circle"></i> Registration Summary</h5>
                <div class="summary-row">
                    <span>Student Name:</span>
                    <strong><?php echo htmlspecialchars($registrationData['firstName'] . ' ' . $registrationData['lastName']); ?></strong>
                </div>
                <div class="summary-row">
                    <span>Email:</span>
                    <span><?php echo htmlspecialchars($registrationData['email']); ?></span>
                </div>
                <div class="summary-row">
                    <span>Phone:</span>
                    <span><?php echo htmlspecialchars($registrationData['phone']); ?></span>
                </div>
                <div class="summary-row">
                    <span>Program:</span>
                    <strong><?php echo htmlspecialchars($registrationData['program']); ?></strong>
                </div>
            </div>

            <!-- Payment Summary -->
            <div class="summary-box">
                <h5 class="mb-3"><i class="bi bi-wallet-fill"></i> Payment Details</h5>
                <div class="summary-row">
                    <span>Registration Fee:</span>
                    <span>₹<?php echo number_format(PAYMENT_AMOUNT, 2); ?></span>
                </div>
                <div class="summary-row">
                    <span>Total Amount:</span>
                    <span>₹<?php echo number_format(PAYMENT_AMOUNT, 2); ?></span>
                </div>
            </div>

            <!-- Payment Button (disable karo agar order create nahi hua) -->
            <button type="button" class="btn btn-primary btn-lg btn-pay" id="payButton"
                <?php echo (!$razorpayOrder) ? 'disabled' : ''; ?>>
                <i class="bi bi-shield-lock-fill"></i> Pay ₹<?php echo number_format(PAYMENT_AMOUNT, 2); ?> Securely
            </button>

            <div class="security-badge">
                <i class="bi bi-shield-check"></i> Secure payment powered by Razorpay
                <br>
                <small>Your payment information is encrypted and secure</small>
            </div>

            <div class="back-link">
                <!-- FIX: Back jaane se session clear karo taaki duplicate issue na ho -->
                <a href="index.php?clear=1" class="text-decoration-none">
                    <i class="bi bi-arrow-left"></i> Back to Registration Form
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        // FIX: Saari PHP values json_encode se safe inject ki hain (XSS proof)
        const RZP_KEY    = <?php echo json_encode(RAZORPAY_KEY_ID); ?>;
        const RZP_AMOUNT = <?php echo json_encode($amountInPaise); ?>;
        const RZP_ORDER  = <?php echo $jsOrderId; ?>;       // CRITICAL: Server-generated order ID
        const RZP_NAME   = <?php echo $jsInstitute; ?>;
        const RZP_DESC   = <?php echo $jsProgram; ?>;
        const RZP_EMAIL  = <?php echo $jsEmail; ?>;
        const RZP_PHONE  = <?php echo $jsPhone; ?>;
        const RZP_PNAME  = <?php echo $jsName; ?>;
        const CSRF_TOKEN = <?php echo $jsCsrfToken; ?>;

        document.getElementById('payButton').addEventListener('click', function () {
            const button = this;
            button.disabled = true;
            button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Initiating Payment...';

            const options = {
                "key":         RZP_KEY,
                "amount":      RZP_AMOUNT,
                "currency":    "<?php echo PAYMENT_CURRENCY; ?>",
                "order_id":    RZP_ORDER,   // FIX: order_id ab server se aa raha hai
                "name":        RZP_NAME,
                "description": RZP_DESC,
                "image":       "https://yourdomain.com/logo.png", // Apna logo URL lagao
                "handler": function (response) {
                    // Payment successful, verify on server
                    verifyPayment(response);
                },
                "prefill": {
                    "name":    RZP_PNAME,
                    "email":   RZP_EMAIL,
                    "contact": RZP_PHONE
                },
                "theme": {
                    "color": "#667eea"
                },
                "modal": {
                    "ondismiss": function () {
                        button.disabled = false;
                        button.innerHTML = '<i class="bi bi-shield-lock-fill"></i> Pay ₹<?php echo number_format(PAYMENT_AMOUNT, 2); ?> Securely';
                    }
                }
            };

            const razorpay = new Razorpay(options);

            // FIX: Payment failure bhi handle karo
            razorpay.on('payment.failed', function (response) {
                alert('Payment failed: ' + response.error.description + '\nPlease try again.');
                button.disabled = false;
                button.innerHTML = '<i class="bi bi-shield-lock-fill"></i> Pay ₹<?php echo number_format(PAYMENT_AMOUNT, 2); ?> Securely';
            });

            razorpay.open();
        });

        function verifyPayment(paymentResponse) {
            const formData = new FormData();
            formData.append('razorpay_payment_id', paymentResponse.razorpay_payment_id);
            formData.append('razorpay_order_id',   paymentResponse.razorpay_order_id);   // ab ye blank nahi hoga
            formData.append('razorpay_signature',  paymentResponse.razorpay_signature);  // ab ye blank nahi hoga
            formData.append('csrf_token',           CSRF_TOKEN);

            fetch('../api/verify-payment.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Server error: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    window.location.href = 'success.php?id=' + data.registration_id;
                } else {
                    alert('Payment verification failed: ' + data.message);
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred during payment verification. Please contact support.');
                location.reload();
            });
        }
    </script>
</body>
</html>
