# 🚀 Production Configuration Guide

## 📝 Files to Update After Upload:

### 1. **config/database.php**
```php
// Update with your live database credentials
$host = 'localhost';                    // Usually localhost
$dbname = 'xynx4483_ypt_study_app';    // Your production database name
$username = 'xynx4483_ypt_user';       // Your production database username  
$password = 'YOUR_PRODUCTION_DB_PASSWORD'; // Your production database password
```

### 2. **config/oauth.php**
```php
// Update redirect URLs for production
'redirect_uri' => 'https://xynotoky.online/api/auth/google-callback.php',
```

### 3. **Google OAuth Console Settings**
- Go to: https://console.developers.google.com/
- Update Authorized Redirect URIs:
  - `https://xynotoky.online/api/auth/google-callback.php`

## 🔧 Production Checklist:

- [ ] Upload all files to server
- [ ] Update database credentials in config/database.php
- [ ] Update Google OAuth redirect URI
- [ ] Update Google Console settings
- [ ] Test landing page loads
- [ ] Test authentication flow
- [ ] Test dashboard access
- [ ] Verify CSS/JS loading properly

## 🌐 Your Live URLs:
- **Landing Page**: https://xynotoky.online/
- **Dashboard**: https://xynotoky.online/index.php?page=dashboard
- **Login**: https://xynotoky.online/pages/login.php

## 📧 Support:
If you need help with any configuration, check:
1. cPanel error logs
2. Browser developer console
3. Database connection test