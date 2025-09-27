# 🔐 Social Authentication Setup Guide

## 🎯 **GOOGLE SIGN-IN SETUP**

### **Step 1: Google Cloud Console Setup**

1. **Visit Google Cloud Console:**
   - Go to: https://console.cloud.google.com/
   - Sign in with your Google account

2. **Create or Select Project:**
   - Click "Select a project" → "New Project"
   - Project name: "YPT Study App"
   - Click "Create"

3. **Enable Google+ API:**
   - Go to "APIs & Services" → "Library"
   - Search for "Google+ API"
   - Click on it and press "Enable"

4. **Create OAuth 2.0 Credentials:**
   - Go to "APIs & Services" → "Credentials"
   - Click "Create Credentials" → "OAuth 2.0 Client ID"
   - If prompted, configure OAuth consent screen first:
     - User Type: External
     - App name: "YPT Study App"
     - User support email: your email
     - Developer contact: your email
   - Application type: "Web application"
   - Name: "YPT Study App Web Client"
   - Authorized redirect URIs: `https://yourdomain.com/api/auth/google-callback.php`

5. **Copy Your Credentials:**
   - Client ID: `xxxxxxxxx.apps.googleusercontent.com`
   - Client Secret: `xxxxxxxxxxxxxxxxx`

### **Step 2: Update Configuration**

Edit `/workspaces/web-app-study/config/oauth.php`:

```php
define('GOOGLE_CLIENT_ID', 'your_actual_client_id.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', 'your_actual_client_secret');
define('GOOGLE_REDIRECT_URI', 'https://yourdomain.com/api/auth/google-callback.php');
```

---

## 🐙 **GITHUB SIGN-IN SETUP**

### **Step 1: GitHub OAuth App Setup**

1. **Go to GitHub Settings:**
   - Visit: https://github.com/settings/developers
   - Click "New OAuth App"

2. **Fill Application Details:**
   - Application name: "YPT Study App"
   - Homepage URL: `https://yourdomain.com`
   - Authorization callback URL: `https://yourdomain.com/api/auth/github-callback.php`
   - Click "Register application"

3. **Copy Your Credentials:**
   - Client ID: `xxxxxxxxxxxxxxxxxx`
   - Client Secret: `xxxxxxxxxxxxxxxxxx`

### **Step 2: Update Configuration**

Edit `/workspaces/web-app-study/config/oauth.php`:

```php
define('GITHUB_CLIENT_ID', 'your_github_client_id');
define('GITHUB_CLIENT_SECRET', 'your_github_client_secret');
define('GITHUB_REDIRECT_URI', 'https://yourdomain.com/api/auth/github-callback.php');
```

---

## 📱 **FACEBOOK SIGN-IN SETUP (Optional)**

### **Step 1: Facebook Developers Setup**

1. **Go to Facebook Developers:**
   - Visit: https://developers.facebook.com/
   - Click "My Apps" → "Create App"

2. **Create App:**
   - App Type: "Consumer"
   - App Name: "YPT Study App"
   - Contact Email: your email

3. **Add Facebook Login:**
   - In dashboard, click "Add a Product"
   - Select "Facebook Login"
   - Choose "Web"

4. **Configure Settings:**
   - Valid OAuth Redirect URIs: `https://yourdomain.com/api/auth/facebook-callback.php`

5. **Copy Your Credentials:**
   - App ID: `xxxxxxxxxxxxxxxxx`
   - App Secret: `xxxxxxxxxxxxxxxxx`

---

## 🚀 **TESTING SOCIAL LOGIN**

### **Step 1: Upload Files to cPanel**
- Upload all files including the new OAuth files
- Make sure `config/oauth.php` has your real credentials

### **Step 2: Test Login**
1. Go to your website's login page
2. Click "Continue with Google" or "Continue with GitHub"
3. Complete OAuth flow
4. Should automatically create account and log you in

### **Step 3: Verify Database**
Check that new users appear in your `users` table with:
- `oauth_provider` = 'google' or 'github'
- `oauth_id` = their provider ID
- `password` = empty (since they use OAuth)

---

## ⚠️ **IMPORTANT SECURITY NOTES**

1. **HTTPS Required:**
   - OAuth providers require HTTPS in production
   - Use SSL certificate on your domain

2. **Domain Verification:**
   - Make sure redirect URIs exactly match your domain
   - No trailing slashes unless specified

3. **Environment Variables:**
   - In production, consider using environment variables for secrets
   - Never commit real credentials to version control

---

## 🎉 **WHAT USERS GET**

With social login enabled, users can:
- ✅ Sign up instantly with Google/GitHub
- ✅ No password to remember
- ✅ Automatic profile info (name, email, photo)
- ✅ Faster registration process
- ✅ More secure authentication

Your login/register pages already have the buttons - they'll work once you configure the OAuth credentials!