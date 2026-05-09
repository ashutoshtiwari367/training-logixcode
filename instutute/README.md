# Institute Registration System - Installation & Setup Guide

## 📋 Overview

A complete, production-ready institute registration system with mandatory online payment integration, admin dashboard, and offline registration capabilities.

## 🎯 Features

✅ Multi-section student registration form
✅ Mandatory online payment via Razorpay
✅ Server-side payment verification
✅ Automated email confirmations
✅ Admin & office staff dashboard
✅ Offline registration for office staff
✅ CSV export functionality
✅ Advanced filtering & search
✅ Role-based access control
✅ CSRF protection
✅ SQL injection prevention
✅ Session management

## 📁 Project Structure

```
/
├── registration/
│   ├── index.php              # Student registration form
│   ├── payment.php            # Payment page
│   ├── success.php            # Success confirmation
│   └── process_registration.php
│
├── admin/
│   ├── login.php              # Admin login
│   ├── dashboard.php          # Main dashboard
│   ├── add-registration.php   # Offline registration
│   ├── view-registration.php  # View details
│   ├── export-csv.php         # Export data
│   └── logout.php
│
├── config/
│   ├── db.php                 # Database configuration
│   ├── mail.php               # Email configuration
│   └── auth.php               # Authentication
│
├── api/
│   └── verify-payment.php     # Payment verification
│
├── database.sql               # Database schema
└── composer.json              # Dependencies
```

## 🛠️ Installation Steps

### 1. Server Requirements

- PHP 7.4 or higher
- MySQL 5.7+ or MariaDB 10.2+
- Apache/Nginx web server
- Composer (for PHPMailer)
- SSL certificate (required for payment gateway)

### 2. Upload Files

Upload all files to your server maintaining the folder structure:
- `/registration/` → `/public_html/registration/`
- `/admin/` → `/public_html/admin/`
- `/config/` → `/public_html/config/`
- `/api/` → `/public_html/api/`

### 3. Install Dependencies

```bash
cd /path/to/your/project
composer install
```

This will install PHPMailer automatically.

### 4. Database Setup

**Step 1: Create Database**
```sql
CREATE DATABASE institute_registration;
```

**Step 2: Import Schema**
```bash
mysql -u username -p institute_registration < database.sql
```

Or via phpMyAdmin:
1. Open phpMyAdmin
2. Select 'institute_registration' database
3. Click 'Import'
4. Upload `database.sql`
5. Click 'Go'

**Step 3: Update Database Credentials**

Edit `config/db.php`:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'institute_registration');
define('DB_USER', 'your_database_username');
define('DB_PASS', 'your_database_password');
```

### 5. Email Configuration

Edit `config/mail.php`:

**For Gmail:**
```php
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'your-email@gmail.com');
define('SMTP_PASS', 'your-app-password'); // Use App Password, not regular password
define('SMTP_FROM_EMAIL', 'noreply@institute.com');
define('SMTP_FROM_NAME', 'Your Institute Name');
```

**Gmail App Password Setup:**
1. Go to Google Account → Security
2. Enable 2-Step Verification
3. Generate App Password
4. Use that password in SMTP_PASS

**Update Institute Details:**
```php
define('INSTITUTE_NAME', 'Your Institute Name');
define('INSTITUTE_EMAIL', 'info@institute.com');
define('INSTITUTE_PHONE', '+91-1234567890');
define('INSTITUTE_ADDRESS', 'Your Institute Address');
```

### 6. Payment Gateway Setup (Razorpay)

**Step 1: Create Razorpay Account**
1. Visit https://razorpay.com
2. Sign up for an account
3. Complete KYC verification
4. Go to Settings → API Keys
5. Generate Test/Live Keys

**Step 2: Update Payment Configuration**

Edit `registration/payment.php`:
```php
define('RAZORPAY_KEY_ID', 'rzp_test_xxxxxxxxxxxxx');
define('RAZORPAY_KEY_SECRET', 'your_secret_key');
define('PAYMENT_AMOUNT', 5000); // Amount in INR
```

Edit `api/verify-payment.php` (line 58 and 63):
```php
$razorpayKeyId = 'rzp_test_xxxxxxxxxxxxx';
$razorpaySecret = 'your_secret_key';
```

**Step 3: Test vs Live Mode**
- Test mode: Use test keys (rzp_test_...)
- Live mode: Use live keys (rzp_live_...)

### 7. Security Configuration

**Step 1: Change Default Admin Password**

After installation, login with:
- Email: admin@institute.com
- Password: Admin@123

Then immediately:
1. Create a new admin user
2. Delete the default admin account

**Or update password via SQL:**
```sql
-- Generate new password hash in PHP
<?php echo password_hash('YourNewPassword', PASSWORD_DEFAULT); ?>

-- Update in database
UPDATE users SET password_hash = '$2y$10$your_new_hash_here' WHERE email = 'admin@institute.com';
```

### 8. File Permissions

Set correct permissions:
```bash
chmod 755 /path/to/registration
chmod 755 /path/to/admin
chmod 644 /path/to/config/*.php
chmod 600 /path/to/config/db.php  # Most restrictive for DB config
```

### 9. .htaccess Configuration (Apache)

Create `.htaccess` in root directory:
```apache
# Prevent directory listing
Options -Indexes

# Protect config files
<FilesMatch "^(db|mail|auth)\.php$">
    Order Allow,Deny
    Deny from all
</FilesMatch>

# Enable HTTPS redirect (after SSL setup)
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

### 10. SSL Certificate Setup

**Required for Payment Gateway**

Option 1: Let's Encrypt (Free)
```bash
sudo certbot --apache -d yourdomain.com -d www.yourdomain.com
```

Option 2: Purchase from SSL provider
- Follow provider's installation guide

### 11. Testing

**Test Mode Checklist:**

1. **Student Registration:**
   - Go to: https://yourdomain.com/registration/
   - Fill form completely
   - Test payment with Razorpay test cards:
     - Card: 4111 1111 1111 1111
     - CVV: Any 3 digits
     - Expiry: Any future date

2. **Admin Login:**
   - Go to: https://yourdomain.com/admin/login.php
   - Login with admin credentials
   - Verify dashboard displays data

3. **Email Testing:**
   - Complete a registration
   - Check if email arrives
   - Verify all details are correct

4. **Offline Registration:**
   - Login as admin/office
   - Add offline registration
   - Verify in dashboard

## 🔧 Configuration Options

### Payment Amount

Edit in `registration/index.php` and `payment.php`:
```php
define('PAYMENT_AMOUNT', 5000); // Change to your amount
```

### Session Timeout

Edit `config/auth.php`:
```php
function checkSessionTimeout($timeout = 1800) // 1800 = 30 minutes
```

### Email Template Customization

Edit `config/mail.php`:
- Modify `getEmailTemplate()` function for HTML email
- Modify `getEmailPlainText()` function for plain text

## 🎨 Customization

### Logo
Update in `registration/payment.php`:
```javascript
"image": "https://yourdomain.com/logo.png"
```

### Colors
Update CSS in respective files:
```css
background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
```

### Programs List
Edit form `<select>` options in:
- `registration/index.php`
- `admin/add-registration.php`

## 🔐 Security Best Practices

1. ✅ Use HTTPS only (enforced via .htaccess)
2. ✅ Change default admin password immediately
3. ✅ Keep PHP and MySQL updated
4. ✅ Regular database backups
5. ✅ Use strong passwords for database
6. ✅ Limit failed login attempts (add feature if needed)
7. ✅ Regular security audits
8. ✅ Monitor error logs

## 📊 Database Backup

**Automated Backup (Recommended):**
```bash
# Add to crontab (daily at 2 AM)
0 2 * * * mysqldump -u username -p'password' institute_registration > /backups/db_$(date +\%Y\%m\%d).sql
```

**Manual Backup:**
```bash
mysqldump -u username -p institute_registration > backup.sql
```

## 🐛 Troubleshooting

### Email Not Sending
- Verify SMTP credentials
- Check if port 587 is open
- Try port 465 with SSL
- Check spam folder
- Enable "Less secure apps" or use App Password for Gmail

### Payment Verification Failed
- Verify Razorpay keys are correct
- Check if webhook URL is accessible
- Verify SSL certificate is valid
- Check error logs in `/api/verify-payment.php`

### Database Connection Error
- Verify credentials in `config/db.php`
- Check if MySQL service is running
- Verify user has proper permissions
- Check if database exists

### Session Expired Too Quickly
- Increase timeout in `config/auth.php`
- Check PHP session configuration
- Verify session storage permissions

## 📞 Support & Maintenance

### Log Files
- PHP Error Log: `/var/log/php_errors.log`
- Apache Error Log: `/var/log/apache2/error.log`
- MySQL Error Log: `/var/log/mysql/error.log`

### Monitoring
- Monitor failed login attempts
- Check email delivery rates
- Monitor payment success rates
- Regular database health checks

## 📝 Admin User Management

### Add New Admin User (SQL)
```sql
INSERT INTO users (name, email, password_hash, role, created_at) 
VALUES (
    'Admin Name', 
    'admin@example.com',
    '$2y$10$password_hash_here',
    'admin',
    NOW()
);
```

### Add Office Staff (SQL)
```sql
INSERT INTO users (name, email, password_hash, role, created_at) 
VALUES (
    'Staff Name', 
    'staff@example.com',
    '$2y$10$password_hash_here',
    'office',
    NOW()
);
```

Generate password hash:
```php
<?php
echo password_hash('your_password', PASSWORD_DEFAULT);
?>
```

## 🚀 Going Live

### Pre-Launch Checklist

- [ ] Test all features thoroughly
- [ ] Switch Razorpay to Live mode
- [ ] Update payment keys to live keys
- [ ] Configure production email settings
- [ ] Enable HTTPS and force redirect
- [ ] Change all default passwords
- [ ] Set up database backups
- [ ] Configure error logging
- [ ] Test email delivery
- [ ] Complete test transaction
- [ ] Set up monitoring
- [ ] Prepare support documentation

## 📄 License & Credits

Created for production use in educational institutes.

---

## 🆘 Need Help?

1. Check error logs first
2. Verify all configuration files
3. Test with minimal setup
4. Contact your hosting provider for server issues

## System Requirements Summary

| Component | Requirement |
|-----------|-------------|
| PHP | 7.4+ |
| MySQL | 5.7+ |
| Web Server | Apache 2.4+ / Nginx |
| SSL | Required |
| Composer | Latest |
| Storage | 100MB minimum |

---

**Installation Complete!** 

Access your system at:
- Student Portal: `https://yourdomain.com/registration/`
- Admin Panel: `https://yourdomain.com/admin/login.php`
