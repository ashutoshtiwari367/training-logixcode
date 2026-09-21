<?php
/**
 * Payment Page
 * registration/payment.php
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

// Generate CSRF token if not exists
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrfToken = $_SESSION['csrf_token'];

// Payment configuration
define('PAYMENT_AMOUNT', REGISTRATION_FEE);     // Global config (699)
define('PAYMENT_CURRENCY', 'INR');

if (!defined('INSTITUTE_NAME')) {
    define('INSTITUTE_NAME', 'Logixcode IT Solution');
}

// Calculate amount in paise for Razorpay (699 * 100 = 69900)
$amountInPaise = PAYMENT_AMOUNT * 100;

/**
 * Create Razorpay Order server-side
 */
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
        throw new Exception('Unable to create payment order gateway. Please try again.');
    }

    $order = json_decode($result, true);
    if (!isset($order['id'])) {
        throw new Exception('Invalid response received from payment gateway.');
    }

    return $order;
}

// Create or retrieve session Razorpay order
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

// Prepare safe JSON variables for frontend JS script
$jsName        = json_encode($registrationData['firstName'] . ' ' . $registrationData['lastName']);
$jsEmail       = json_encode($registrationData['email']);
$jsPhone       = json_encode(str_replace('+91', '', $registrationData['phone']));
$jsProgram     = json_encode('Registration Fee - ' . $registrationData['program']);
$jsInstitute   = json_encode(INSTITUTE_NAME);
$jsOrderId     = json_encode($razorpayOrder ? $razorpayOrder['id'] : '');
$jsCsrfToken   = json_encode($csrfToken);
?>
<!DOCTYPE html>
<html class="scroll-smooth" lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Payment | <?php echo INSTITUTE_NAME; ?></title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="icon" href="https://res.cloudinary.com/de7mh41io/image/upload/v1749888137/logixcode-logo.webp">
    <script id="tailwind-config">
        tailwind.config = {
          darkMode: "class",
          theme: {
            extend: {
              colors: {
                "brand-cyan": "#03c4ce",
                "brand-dark": "#004f54",
                "primary-container": "#03bfd3",
              },
              fontFamily: {
                sans: ['Inter', 'Manrope', 'sans-serif'],
                heading: ['Manrope', 'sans-serif'],
              }
            }
          }
        }
    </script>
</head>
<body class="bg-slate-50 text-slate-900 font-sans antialiased min-h-screen flex flex-col pt-20">

    <!-- Header Navbar -->
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>

    <!-- Main Content -->
    <main class="flex-grow py-10 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl mx-auto">
            
            <!-- Payment Header Card -->
            <div class="bg-white rounded-3xl p-8 shadow-xl border border-slate-100 mb-8 text-center relative overflow-hidden">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-[#03c4ce]/10 text-[#03c4ce] mb-4">
                    <span class="material-symbols-outlined text-3xl">lock</span>
                </div>
                <h1 class="text-3xl font-extrabold text-slate-900 font-heading mb-2">
                    Complete Registration Payment
                </h1>
                <p class="text-slate-600 text-sm">
                    Review your application summary below and proceed to secure checkout.
                </p>
            </div>

            <?php if (isset($orderError)): ?>
            <!-- Order Creation Error Alert -->
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 flex items-center gap-3">
                <span class="material-symbols-outlined text-rose-500">warning</span>
                <div>
                    <strong>Payment Gateway Alert:</strong> <?php echo htmlspecialchars($orderError); ?>
                    <br><a href="payment.php" class="underline font-semibold hover:text-rose-900">Click here to retry</a>
                </div>
            </div>
            <?php endif; ?>

            <!-- Main Payment Summary Card -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-xl border border-slate-100 space-y-8">
                
                <!-- Registration Summary -->
                <div>
                    <div class="flex items-center gap-2 pb-3 mb-4 border-b border-slate-100">
                        <span class="material-symbols-outlined text-[#03c4ce]">account_circle</span>
                        <h3 class="text-lg font-bold text-slate-900 font-heading">Applicant Summary</h3>
                    </div>
                    <div class="bg-slate-50 rounded-2xl p-4 sm:p-5 space-y-3 text-sm">
                        <div class="flex justify-between items-center pb-2 border-b border-slate-200/60">
                            <span class="text-slate-500">Student Name:</span>
                            <span class="font-bold text-slate-900"><?php echo htmlspecialchars($registrationData['firstName'] . ' ' . $registrationData['lastName']); ?></span>
                        </div>
                        <div class="flex justify-between items-center pb-2 border-b border-slate-200/60">
                            <span class="text-slate-500">Email Address:</span>
                            <span class="font-semibold text-slate-800"><?php echo htmlspecialchars($registrationData['email']); ?></span>
                        </div>
                        <div class="flex justify-between items-center pb-2 border-b border-slate-200/60">
                            <span class="text-slate-500">Mobile Number:</span>
                            <span class="font-semibold text-slate-800"><?php echo htmlspecialchars($registrationData['phone']); ?></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-500">Selected Program:</span>
                            <span class="font-bold text-[#004f54]"><?php echo htmlspecialchars($registrationData['program']); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Fee Breakdown -->
                <div>
                    <div class="flex items-center gap-2 pb-3 mb-4 border-b border-slate-100">
                        <span class="material-symbols-outlined text-[#03c4ce]">payments</span>
                        <h3 class="text-lg font-bold text-slate-900 font-heading">Fee Details</h3>
                    </div>
                    <div class="bg-slate-50 rounded-2xl p-5 space-y-3 text-sm">
                        <div class="flex justify-between items-center pb-3 border-b border-slate-200/60">
                            <span class="text-slate-600">Application Registration Fee</span>
                            <span class="font-semibold text-slate-800">₹<?php echo number_format(PAYMENT_AMOUNT, 2); ?></span>
                        </div>
                        <div class="flex justify-between items-center pt-1 text-base font-extrabold text-slate-900">
                            <span>Total Payable Amount</span>
                            <span class="text-2xl text-[#03c4ce]">₹<?php echo number_format(PAYMENT_AMOUNT, 2); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Payment Action -->
                <div class="space-y-4 pt-2">
                    <button type="button" id="payButton"
                            <?php echo (!$razorpayOrder) ? 'disabled' : ''; ?>
                            class="w-full py-4 px-8 rounded-2xl bg-[#03c4ce] hover:bg-[#02aab3] disabled:bg-slate-300 disabled:cursor-not-allowed text-white font-extrabold text-lg shadow-xl shadow-[#03c4ce]/25 transition-all duration-300 flex items-center justify-center gap-3">
                        <span class="material-symbols-outlined text-2xl">verified_user</span>
                        <span>Pay ₹<?php echo number_format(PAYMENT_AMOUNT, 2); ?> Securely</span>
                    </button>

                    <!-- Security Badge -->
                    <div class="text-center text-xs text-slate-500 space-y-1">
                        <div class="flex items-center justify-center gap-1 font-semibold text-slate-600">
                            <span class="material-symbols-outlined text-sm text-emerald-600">shield</span>
                            <span>Encrypted 256-Bit SSL Payment via Razorpay</span>
                        </div>
                        <p>UPI, Credit/Debit Cards, NetBanking & Mobile Wallets Supported</p>
                    </div>

                    <!-- Navigation Link -->
                    <div class="text-center pt-2">
                        <a href="index.php?clear=1" class="text-sm font-semibold text-slate-600 hover:text-[#03c4ce] inline-flex items-center gap-1 transition-colors">
                            <span class="material-symbols-outlined text-base">arrow_back</span>
                            <span>Edit Registration Details</span>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <!-- Footer -->
    <?php require_once __DIR__ . '/../includes/footer.php'; ?>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        const RZP_KEY    = <?php echo json_encode(RAZORPAY_KEY_ID); ?>;
        const RZP_AMOUNT = <?php echo json_encode($amountInPaise); ?>;
        const RZP_ORDER  = <?php echo $jsOrderId; ?>;
        const RZP_NAME   = <?php echo $jsInstitute; ?>;
        const RZP_DESC   = <?php echo $jsProgram; ?>;
        const RZP_EMAIL  = <?php echo $jsEmail; ?>;
        const RZP_PHONE  = <?php echo $jsPhone; ?>;
        const RZP_PNAME  = <?php echo $jsName; ?>;
        const CSRF_TOKEN = <?php echo $jsCsrfToken; ?>;

        document.getElementById('payButton').addEventListener('click', function () {
            const button = this;
            const originalHTML = button.innerHTML;
            button.disabled = true;
            button.innerHTML = `
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Initiating Payment...
            `;

            const options = {
                "key":         RZP_KEY,
                "amount":      RZP_AMOUNT,
                "currency":    "<?php echo PAYMENT_CURRENCY; ?>",
                "order_id":    RZP_ORDER,
                "name":        RZP_NAME,
                "description": RZP_DESC,
                "image":       "https://res.cloudinary.com/de7mh41io/image/upload/v1749888137/logixcode-logo.webp",
                "handler": function (response) {
                    verifyPayment(response);
                },
                "prefill": {
                    "name":    RZP_PNAME,
                    "email":   RZP_EMAIL,
                    "contact": RZP_PHONE
                },
                "theme": {
                    "color": "#03c4ce"
                },
                "modal": {
                    "ondismiss": function () {
                        button.disabled = false;
                        button.innerHTML = originalHTML;
                    }
                }
            };

            const razorpay = new Razorpay(options);

            razorpay.on('payment.failed', function (response) {
                alert('Payment failed: ' + (response.error ? response.error.description : 'Transaction error') + '\nPlease try again.');
                button.disabled = false;
                button.innerHTML = originalHTML;
            });

            razorpay.open();
        });

        function verifyPayment(paymentResponse) {
            const formData = new FormData();
            formData.append('razorpay_payment_id', paymentResponse.razorpay_payment_id);
            formData.append('razorpay_order_id',   paymentResponse.razorpay_order_id);
            formData.append('razorpay_signature',  paymentResponse.razorpay_signature);
            formData.append('csrf_token',           CSRF_TOKEN);

            fetch('../api/verify-payment.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Server returned HTTP ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    window.location.href = 'success.php?id=' + data.registration_id;
                } else {
                    alert('Payment verification error: ' + (data.message || 'Verification failed'));
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Verification Error:', error);
                alert('An error occurred during payment verification. Please contact support if payment was deducted.');
                location.reload();
            });
        }
    </script>
</body>
</html>
