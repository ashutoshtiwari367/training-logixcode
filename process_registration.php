<?php
/**
 * Registration Success Page
 * registration/success.php
 */

session_start();
require_once __DIR__ . '/../config/db.php';

// Check if registration was completed
if (!isset($_SESSION['completed_registration_id'])) {
    header('Location: index.php');
    exit;
}

$registrationId = $_SESSION['completed_registration_id'];
$paymentId = $_SESSION['completed_payment_id'] ?? 'N/A';

// Fetch registration details
try {
    $stmt = $pdo->prepare("
        SELECT r.*, p.payment_gateway_id, p.amount, p.status as payment_status 
        FROM registrations r 
        LEFT JOIN payments p ON r.registration_id = p.registration_id 
        WHERE r.registration_id = ?
    ");
    $stmt->execute([$registrationId]);
    $registration = $stmt->fetch();

    if (!$registration) {
        throw new Exception('Registration not found');
    }
} catch (Exception $e) {
    die('Error loading registration details');
}

// Clear session data
unset($_SESSION['completed_registration_id']);
unset($_SESSION['completed_payment_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Successful</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 0;
        }
        .success-container {
            max-width: 800px;
            margin: 0 auto;
        }
        .success-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            padding: 40px;
        }
        .success-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .success-icon {
            font-size: 80px;
            color: #28a745;
            margin-bottom: 20px;
        }
        .success-header h1 {
            color: #28a745;
            font-weight: 700;
        }
        .registration-details {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 30px;
            margin: 30px 0;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #dee2e6;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 600;
            color: #495057;
        }
        .detail-value {
            color: #212529;
            text-align: right;
        }
        .highlight-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            margin: 20px 0;
        }
        .highlight-box h2 {
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
        }
        .highlight-box p {
            margin: 5px 0 0 0;
            opacity: 0.9;
        }
        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }
        .btn-print, .btn-home {
            padding: 12px 30px;
            font-weight: 600;
        }
        .alert-info {
            background: #e7f3ff;
            border-left: 4px solid #0d6efd;
        }
        @media print {
            .action-buttons, .alert-info {
                display: none;
            }
            body {
                background: white;
            }
        }
    </style>
</head>
<body>
    <div class="success-container">
        <div class="success-card">
            <div class="success-header">
                <div class="success-icon">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <h1>Registration Successful!</h1>
                <p class="text-muted">Your application has been submitted and payment confirmed</p>
            </div>

            <div class="alert alert-info">
                <i class="bi bi-info-circle-fill"></i>
                <strong>Important:</strong> A confirmation email has been sent to <strong><?php echo htmlspecialchars($registration['email']); ?></strong>. 
                Please save this page or take a screenshot for your records.
            </div>

            <div class="highlight-box">
                <p>Your Registration ID</p>
                <h2><?php echo htmlspecialchars($registrationId); ?></h2>
            </div>

            <div class="registration-details">
                <h4 class="mb-4"><i class="bi bi-person-circle"></i> Student Information</h4>
                
                <div class="detail-row">
                    <span class="detail-label">Full Name:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($registration['first_name'] . ' ' . $registration['last_name']); ?></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Email:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($registration['email']); ?></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Phone:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($registration['phone']); ?></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Date of Birth:</span>
                    <span class="detail-value"><?php echo date('d F Y', strtotime($registration['dob'])); ?></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Program:</span>
                    <span class="detail-value"><strong><?php echo htmlspecialchars($registration['program']); ?></strong></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Qualification:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($registration['qualification']); ?></span>
                </div>
            </div>

            <div class="registration-details">
                <h4 class="mb-4"><i class="bi bi-credit-card-fill"></i> Payment Information</h4>
                
                <div class="detail-row">
                    <span class="detail-label">Payment Status:</span>
                    <span class="detail-value">
                        <span class="badge bg-success">
                            <i class="bi bi-check-circle"></i> <?php echo htmlspecialchars($registration['payment_status']); ?>
                        </span>
                    </span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Payment ID:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($registration['payment_gateway_id']); ?></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Amount Paid:</span>
                    <span class="detail-value"><strong>₹<?php echo number_format($registration['amount'], 2); ?></strong></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Payment Mode:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($registration['payment_mode']); ?></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Registration Date:</span>
                    <span class="detail-value"><?php echo date('d F Y, h:i A', strtotime($registration['created_at'])); ?></span>
                </div>
            </div>

            <div class="alert alert-success">
                <h5><i class="bi bi-envelope-check-fill"></i> Next Steps:</h5>
                <ul class="mb-0">
                    <li>You will receive a confirmation email shortly</li>
                    <li>Keep your Registration ID safe for future reference</li>
                    <li>Further instructions will be sent to your email</li>
                    <li>For any queries, contact us with your Registration ID</li>
                </ul>
            </div>

            <div class="action-buttons">
                <button onclick="window.print()" class="btn btn-primary btn-print">
                    <i class="bi bi-printer-fill"></i> Print this Page
                </button>
                <a href="../index.php" class="btn btn-outline-secondary btn-home">
                    <i class="bi bi-house-fill"></i> Go to Home
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
