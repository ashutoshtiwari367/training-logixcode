# 📋 Configuration Checklist

Use this checklist to ensure everything is configured correctly before going live.

## ✅ Pre-Installation

- [ ] PHP 7.4+ installed
- [ ] MySQL 5.7+ installed
- [ ] Composer installed
- [ ] SSL certificate ready (required for payment)
- [ ] Razorpay account created
- [ ] Email account ready (Gmail recommended)

---

## ✅ File Upload

- [ ] All files uploaded to server
- [ ] Folder structure maintained:
  - [ ] /registration/
  - [ ] /admin/
  - [ ] /config/
  - [ ] /api/
- [ ] composer.json uploaded
- [ ] .htaccess uploaded to root

---

## ✅ Dependencies

- [ ] Run `composer install`
- [ ] PHPMailer installed in /vendor/
- [ ] Autoload files generated

---

## ✅ Database Configuration

- [ ] Database created: `institute_registration`
- [ ] database.sql imported successfully
- [ ] Default admin user created
- [ ] Database credentials updated in `config/db.php`:
  - [ ] DB_HOST
  - [ ] DB_NAME
  - [ ] DB_USER
  - [ ] DB_PASS

---

## ✅ Email Configuration (config/mail.php)

- [ ] SMTP_HOST configured
- [ ] SMTP_PORT set (587 or 465)
- [ ] SMTP_USER (email address)
- [ ] SMTP_PASS (app password for Gmail)
- [ ] SMTP_FROM_EMAIL
- [ ] SMTP_FROM_NAME
- [ ] INSTITUTE_NAME updated
- [ ] INSTITUTE_EMAIL updated
- [ ] INSTITUTE_PHONE updated
- [ ] INSTITUTE_ADDRESS updated
- [ ] Test email sent successfully

---

## ✅ Payment Gateway (Razorpay)

### registration/payment.php
- [ ] RAZORPAY_KEY_ID (line 17)
- [ ] RAZORPAY_KEY_SECRET (line 18)
- [ ] PAYMENT_AMOUNT (line 19)
- [ ] Logo URL updated (line 97)

### api/verify-payment.php
- [ ] RAZORPAY_KEY_ID (line 58)
- [ ] RAZORPAY_KEY_SECRET (line 63)

### Test Mode
- [ ] Using test keys (rzp_test_...)
- [ ] Test transaction completed
- [ ] Payment verification working

### Production Mode (when ready)
- [ ] Switch to live keys (rzp_live_...)
- [ ] Complete Razorpay KYC
- [ ] Activate live account

---

## ✅ Security Configuration

- [ ] Default admin password changed
- [ ] Strong database password set
- [ ] .htaccess configured
- [ ] File permissions set correctly:
  - [ ] 755 for directories
  - [ ] 644 for PHP files
  - [ ] 600 for config/db.php
- [ ] HTTPS enabled and forced
- [ ] Error display turned OFF in production
- [ ] Error logging enabled

---

## ✅ Testing

### Student Registration Flow
- [ ] Registration form loads correctly
- [ ] All fields validate properly
- [ ] Form submits successfully
- [ ] Redirects to payment page
- [ ] Payment page loads with correct amount
- [ ] Test payment completes successfully
- [ ] Payment verification works
- [ ] Data saved to database correctly
- [ ] Registration ID generated
- [ ] Success page displays correct info
- [ ] Confirmation email received
- [ ] Email contains all correct details

### Admin Panel
- [ ] Login page accessible
- [ ] Login works with credentials
- [ ] Dashboard loads correctly
- [ ] All statistics display
- [ ] Registration list shows data
- [ ] Filters work correctly
- [ ] Search functionality works
- [ ] View details modal opens
- [ ] Export CSV works
- [ ] CSV contains all data correctly

### Office Registration
- [ ] Add registration page accessible
- [ ] All fields work correctly
- [ ] Offline registration saves
- [ ] Appears in dashboard with OFFLINE badge
- [ ] Confirmation email sent

### Email System
- [ ] Registration confirmation email received
- [ ] Email template displays correctly
- [ ] All placeholders replaced with actual data
- [ ] Links work correctly
- [ ] Email arrives within 1 minute
- [ ] Not landing in spam folder

---

## ✅ Customization

- [ ] Institute name updated everywhere
- [ ] Logo URL configured
- [ ] Color scheme adjusted (if needed)
- [ ] Programs list updated
- [ ] Payment amount set correctly
- [ ] Email templates customized
- [ ] Success message customized

---

## ✅ Performance & Optimization

- [ ] PHP OpCache enabled
- [ ] MySQL query cache enabled
- [ ] GZIP compression enabled (.htaccess)
- [ ] Browser caching enabled (.htaccess)
- [ ] Static files compressed
- [ ] Database indexed properly

---

## ✅ Backup & Recovery

- [ ] Database backup configured
- [ ] Automated backup script set up (cron)
- [ ] Backup tested and verified
- [ ] Recovery procedure documented
- [ ] Off-site backup location configured

---

## ✅ Monitoring & Logging

- [ ] Error logging enabled
- [ ] Log files location verified
- [ ] Log rotation configured
- [ ] Error monitoring set up
- [ ] Payment success rate tracking
- [ ] Email delivery monitoring

---

## ✅ Documentation

- [ ] README.md reviewed
- [ ] QUICKSTART.md reviewed
- [ ] Admin credentials documented securely
- [ ] API keys stored securely
- [ ] Support contact information ready
- [ ] User guide prepared (if needed)

---

## ✅ Pre-Launch Final Checks

- [ ] All test registrations deleted
- [ ] Production database clean
- [ ] Test mode disabled
- [ ] Live payment keys active
- [ ] SSL certificate valid
- [ ] All URLs updated to production domain
- [ ] Error messages user-friendly
- [ ] Loading indicators working
- [ ] Mobile responsive verified
- [ ] Cross-browser tested (Chrome, Firefox, Safari)
- [ ] Payment gateway verified
- [ ] Terms & conditions page ready (if needed)
- [ ] Privacy policy page ready (if needed)

---

## ✅ Post-Launch

- [ ] Monitor first few registrations closely
- [ ] Verify all emails sending correctly
- [ ] Check payment success rate
- [ ] Monitor error logs
- [ ] User feedback collected
- [ ] Support system ready
- [ ] Backup verified
- [ ] Admin team trained

---

## 🚨 Emergency Contacts

**Hosting Support:** _________________
**Payment Gateway Support:** Razorpay - support@razorpay.com
**Email Provider Support:** _________________
**Developer Contact:** _________________

---

## 📊 Important Metrics to Track

- Total Registrations: _________________
- Payment Success Rate: ________%
- Email Delivery Rate: ________%
- Average Registration Time: ________ minutes
- Peak Traffic Hours: _________________

---

## 🔐 Credentials Record (KEEP SECURE)

**Database:**
- Host: _________________
- Database: _________________
- Username: _________________
- Password: _________________ (ENCRYPTED)

**Admin Panel:**
- URL: _________________
- Email: _________________
- Password: _________________ (ENCRYPTED)

**Razorpay:**
- Account Email: _________________
- Key ID: _________________
- Key Secret: _________________ (ENCRYPTED)

**Email:**
- SMTP User: _________________
- SMTP Pass: _________________ (ENCRYPTED)

---

**Date Completed:** _________________
**Completed By:** _________________
**Signature:** _________________

---

## ✅ All Done?

If all items are checked, your system is ready to go live! 🚀

**Final Step:** Make one complete test registration from start to finish, including payment and email verification.

**Then:** Announce your registration system is live! 🎉
