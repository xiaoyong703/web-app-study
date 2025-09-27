#!/bin/bash

# YPT Study App - Quick Setup Script
# This script helps automate the basic setup process

echo "🎓 YPT Study App Setup Script"
echo "============================="
echo ""

# Check if we're in the right directory
if [ ! -f "index.php" ]; then
    echo "❌ Error: Please run this script from the root directory of the YPT Study App"
    exit 1
fi

echo "📁 Setting up file permissions..."

# Set proper permissions for directories
find . -type d -exec chmod 755 {} \;

# Set proper permissions for PHP files
find . -name "*.php" -exec chmod 644 {} \;

# Set proper permissions for other files
find . -name "*.css" -exec chmod 644 {} \;
find . -name "*.js" -exec chmod 644 {} \;
find . -name "*.sql" -exec chmod 644 {} \;
find . -name "*.md" -exec chmod 644 {} \;

echo "✅ File permissions set successfully"
echo ""

echo "🔧 Checking PHP version..."
php_version=$(php -v 2>/dev/null | head -n 1 | awk '{print $2}' | cut -d. -f1,2)

if [ $? -eq 0 ]; then
    echo "✅ PHP version: $php_version (Available)"
    
    # Check if PHP version is 7.4 or higher
    if [ "$(echo "$php_version >= 7.4" | bc 2>/dev/null)" -eq 1 ]; then
        echo "✅ PHP version is compatible"
    else
        echo "⚠️  Warning: PHP 7.4+ recommended (current: $php_version)"
    fi
else
    echo "⚠️  PHP not found in PATH (this is normal for shared hosting)"
fi

echo ""

echo "📋 Next Steps:"
echo "1. Create a MySQL database in your cPanel"
echo "2. Import the database schema from 'database/schema.sql'"
echo "3. Update database credentials in 'config/database.php'"
echo "4. Upload all files to your web hosting account"
echo "5. Test the application by visiting your website"
echo ""

echo "📚 Configuration Example:"
echo "========================"
echo "In config/database.php, update these values:"
echo "define('DB_HOST', 'localhost');"
echo "define('DB_NAME', 'your_database_name');"
echo "define('DB_USER', 'your_database_user');"
echo "define('DB_PASS', 'your_database_password');"
echo ""

echo "🌐 For cPanel hosting (like Rumahweb):"
echo "- Database name format: username_dbname"
echo "- Username format: username_dbuser"
echo "- Host is usually 'localhost'"
echo ""

echo "✨ Setup script completed!"
echo "📖 Check README.md for detailed installation instructions"
echo ""

# Create a simple installation checklist
cat > INSTALLATION_CHECKLIST.txt << 'EOF'
YPT Study App Installation Checklist
====================================

□ 1. Create MySQL database in cPanel
□ 2. Create database user and assign to database  
□ 3. Import database/schema.sql into your database
□ 4. Update config/database.php with your credentials
□ 5. Upload all files to public_html (or domain folder)
□ 6. Test website - should show YPT Study App interface
□ 7. Test timer functionality 
□ 8. Test adding a to-do item
□ 9. Test theme toggle (moon/sun icon)
□ 10. Check that statistics update after using timer

Troubleshooting:
- If database errors: check config/database.php credentials
- If JavaScript errors: check browser console, clear cache
- If styling issues: verify CSS file loads, check permissions

Support: See README.md for detailed troubleshooting guide
EOF

echo "📝 Created INSTALLATION_CHECKLIST.txt for your reference"
echo ""
echo "Happy studying! 🚀"