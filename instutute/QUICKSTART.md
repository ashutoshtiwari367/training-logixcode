# 🚀 Quick Start Guide

## Complete Setup in 10 Minutes

### Step 1: Upload Files (2 min)
Upload all files to your server via FTP/cPanel File Manager maintaining folder structure.

### Step 2: Install PHPMailer (1 min)
```bash
cd /path/to/project
composer install
```

Or download PHPMailer manually and place in `/vendor/phpmailer/phpmailer/`

### Step 3: Setup Database (2 min)

**Via phpMyAdmin:**
1. Create database: `institute_registration`
2. Import `database.sql`
3. Done!

**Via Command Line:**
```bash
mysql -u root -p
CREATE DATABASE institute_registration;
exit;
mysql -u root -p institute_registration < database.sql
```

### Step 4: Configure Database (1 min)

Edit `config/db.php`:
```php
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('INSTITUTE_NAME', 'Your Institute Name');
```

### Step 5: Configure Email (2 min)

Edit `config/mail.php`:
```php
define('SMTP_USER', 'your-email@gmail.com');
define('SMTP_PASS', 'your-app-password');
```

**Get Gmail App Password:**
Google Account → Security → 2-Step Verification → App Passwords

### Step 6: Configure Payment (2 min)

**Get Razorpay Keys:**
1. Go to https://razorpay.com
2. Sign up (free for testing)
3. Dashboard → Settings → API Keys → Generate Test Key

Edit `registration/payment.php` (lines 17-18):
```php
define('RAZORPAY_KEY_ID', 'rzp_test_xxxxx');
define('RAZORPAY_KEY_SECRET', 'your_secret');
```

Edit `api/verify-payment.php` (lines 58 & 63):
```php
$razorpayKeyId = 'rzp_test_xxxxx';
$razorpaySecret = 'your_secret';
```

### Step 7: Test Everything! (5 min)

1. **Student Registration:**
   - Go to: `https://yourdomain.com/registration/`
   - Fill form
   - Test payment with card: `4111 1111 1111 1111`

2. **Admin Login:**
   - Go to: `https://yourdomain.com/admin/login.php`
   - Email: `admin@institute.com`
   - Password: `Admin@123`
   - ⚠️ Change this password immediately!

3. **Check Email:**
   - Verify confirmation email received

## ✅ You're Done!

System is now live and ready to accept registrations.

---

## 🎯 Test Cards (Razorpay Test Mode)

| Card Number | Result |
|-------------|--------|
| 4111 1111 1111 1111 | Success |
| 4012 8888 8888 1881 | Success |
| 5555 5555 5555 4444 | Success |

Any CVV, any future expiry date.

---

## ⚡ Common Issues & Quick Fixes

### Email not sending?
```php
// Try port 465 with SSL instead of 587
define('SMTP_PORT', 465);
// In mail.php, change:
$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
```

### Payment not working?
- Verify Razorpay keys are correct
- Check you're using TEST keys for testing
- SSL must be enabled for production

### Can't login to admin?
Reset password via SQL:
```sql
UPDATE users SET password_hash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' WHERE email = 'admin@institute.com';
```
(Password will be: Admin@123)

---

## 📱 Access URLs

- **Student Registration:** `https://yourdomain.com/registration/`
- **Admin Panel:** `https://yourdomain.com/admin/login.php`

---

## 🔐 Default Login

**Admin:**
- Email: admin@institute.com
- Password: Admin@123

**⚠️ SECURITY: Change this password immediately after first login!**

---

## 🎨 Customization Quick Links

| What to Change | File | Line |
|----------------|------|------|
| Institute Name | config/mail.php | 17 |
| Payment Amount | registration/payment.php | 18 |
| Programs List | registration/index.php | 128-149 |
| Logo URL | registration/payment.php | 97 |
| Email Template | config/mail.php | 68-130 |

---

## 📞 Need More Help?

See full **README.md** for:
- Detailed configuration
- Security hardening
- Going to production
- Troubleshooting guide
- Advanced customization

---

**Happy Registration! 🎓**
