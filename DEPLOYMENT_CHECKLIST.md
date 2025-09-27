# YPT Study App - Deployment Checklist

## Pre-Deployment (Local Testing)
- [ ] All files are created and in correct structure
- [ ] Database schema is ready (database/schema.sql)
- [ ] Configuration template is prepared (config/database.php)
- [ ] All JavaScript functions work locally
- [ ] CSS styling displays correctly
- [ ] Mobile responsiveness is tested

## cPanel Deployment Steps

### 1. Database Setup
- [ ] Login to cPanel
- [ ] Go to "MySQL Databases"
- [ ] Create new database (note the full name with prefix)
- [ ] Create database user
- [ ] Add user to database with "All Privileges"
- [ ] Go to phpMyAdmin
- [ ] Select your database
- [ ] Import `database/schema.sql`
- [ ] Verify tables are created (study_sessions, todos, users, etc.)

### 2. File Upload
- [ ] Open File Manager in cPanel
- [ ] Navigate to public_html (or your domain folder)
- [ ] Upload all project files OR use FTP client
- [ ] Verify file structure is correct:
  ```
  public_html/
  ├── index.php
  ├── .htaccess
  ├── config/database.php
  ├── includes/functions.php
  ├── pages/
  ├── assets/
  ├── api/
  └── database/
  ```

### 3. Configuration
- [ ] Edit `config/database.php` with your actual database credentials:
  ```php
  define('DB_HOST', 'localhost');
  define('DB_NAME', 'username_dbname');  // Replace with actual
  define('DB_USER', 'username_dbuser');  // Replace with actual  
  define('DB_PASS', 'your_password');    // Replace with actual
  ```
- [ ] Save the file
- [ ] Check file permissions (644 for PHP files, 755 for directories)

### 4. Security Setup
- [ ] Verify .htaccess is uploaded and active
- [ ] Test that direct access to /config/ returns 403 Forbidden
- [ ] Test that /database/schema.sql cannot be accessed directly
- [ ] Ensure PHP error reporting is off in production

## Testing Checklist

### 5. Basic Functionality Tests
- [ ] Visit your domain - app loads without errors
- [ ] Theme toggle works (moon/sun icon)
- [ ] Timer displays correctly (default 25:00)
- [ ] Can select different subjects
- [ ] Preset buttons (25m, 45m, 60m, 90m) work
- [ ] Start timer button works
- [ ] Pause timer button works  
- [ ] Reset timer button works
- [ ] Timer countdown updates every second

### 6. To-Do List Tests
- [ ] Can add new tasks
- [ ] Tasks appear in the list
- [ ] Can mark tasks as complete (checkbox)
- [ ] Can delete tasks (trash icon)
- [ ] Tasks persist after page refresh

### 7. Statistics Tests
- [ ] Statistics cards display
- [ ] Complete a timer session
- [ ] Statistics update after session completion
- [ ] Weekly progress bar shows progress
- [ ] Data persists between sessions

### 8. Responsive Design Tests
- [ ] App works on mobile phones
- [ ] App works on tablets
- [ ] Navigation is usable on small screens
- [ ] All buttons are touchable on mobile

### 9. Browser Compatibility Tests
- [ ] Test in Chrome
- [ ] Test in Firefox
- [ ] Test in Safari (if available)
- [ ] Test in Edge

## Performance Optimization

### 10. Speed Tests
- [ ] Page loads quickly (under 3 seconds)
- [ ] CSS and JS files load properly
- [ ] No 404 errors in browser console
- [ ] Images/icons load correctly

### 11. Database Performance
- [ ] Database queries execute quickly
- [ ] No PHP errors in server logs
- [ ] Session data saves correctly
- [ ] Todo operations are responsive

## Post-Deployment

### 12. Monitoring Setup
- [ ] Check server error logs location
- [ ] Set up basic analytics (if needed)
- [ ] Document any custom configurations
- [ ] Note database backup procedures

### 13. User Experience
- [ ] Share with test users for feedback
- [ ] Monitor for any reported issues
- [ ] Collect usage statistics
- [ ] Plan for future phase rollouts

## Troubleshooting Quick Fixes

### Common Issues:
1. **White screen/PHP errors**
   - Check database credentials
   - Verify database exists and is accessible
   - Check PHP error logs

2. **JavaScript not working**
   - Check browser console for errors
   - Verify JS files are uploaded
   - Clear browser cache

3. **Styling issues**
   - Verify CSS file is uploaded
   - Check file permissions
   - Clear browser cache
   - Test on different browsers

4. **Database connection failed**
   - Double-check database name (with username prefix)
   - Verify user has correct permissions
   - Test connection from phpMyAdmin

5. **Features not saving**
   - Check API endpoints are accessible
   - Verify database tables exist
   - Check browser network tab for failed requests

## Success Criteria
- [ ] App loads without errors
- [ ] All Phase 1 features work correctly
- [ ] Mobile responsive design works
- [ ] Data persists correctly
- [ ] No JavaScript errors in console
- [ ] No PHP errors in server logs

## Next Steps After Successful Deployment
- [ ] Document any hosting-specific configurations
- [ ] Plan Phase 2 development timeline
- [ ] Set up regular database backups
- [ ] Consider adding user authentication
- [ ] Gather user feedback for improvements

---

**Note**: Keep this checklist handy and check off items as you complete them. This ensures nothing is missed during deployment.