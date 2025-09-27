# YPT Study App - Installation Guide

## Overview
This is a PHP-based study application that helps students manage their study time, track progress, and maintain focus. It's designed to work perfectly with cPanel hosting providers like Rumahweb.

## Features (Phase 1 - MVP)
- ⏰ Study Timer with customizable durations
- ✅ To-do List with task management
- 📊 Study Statistics and progress tracking
- 🌙 Dark/Light theme toggle
- 📱 Responsive design for mobile and desktop
- 💾 Local storage for theme preferences

## Prerequisites
- cPanel hosting account (like Rumahweb)
- PHP 7.4+ (most cPanel hosts support this)
- MySQL database
- Web browser with JavaScript enabled

## Installation Steps

### 1. Database Setup
1. Log into your cPanel
2. Go to "MySQL Databases" or "phpMyAdmin"
3. Create a new database (e.g., `your_username_study_app`)
4. Create a database user and assign it to the database
5. Import the database schema:
   - Open phpMyAdmin
   - Select your database
   - Go to "SQL" tab
   - Copy and paste the contents of `database/schema.sql`
   - Click "Go" to execute

### 2. Configure Database Connection
1. Open `config/database.php`
2. Update the database credentials:
```php
define('DB_HOST', 'localhost');  // Usually localhost on cPanel
define('DB_NAME', 'your_database_name');  // Your database name
define('DB_USER', 'your_db_username');    // Your database user
define('DB_PASS', 'your_db_password');    // Your database password
```

### 3. Upload Files
1. Use cPanel File Manager or FTP client (like FileZilla)
2. Upload all files to your domain's public_html folder
3. Ensure the following structure:
```
public_html/
├── index.php
├── config/
├── includes/
├── pages/
├── assets/
├── api/
└── database/
```

### 4. Set Permissions
1. Ensure PHP files have 644 permissions
2. Ensure directories have 755 permissions
3. The web server should be able to read all files

### 5. Test Installation
1. Visit your website (e.g., `https://yourdomain.com`)
2. You should see the YPT Study App interface
3. Test the timer functionality
4. Try adding a to-do item
5. Check if theme toggle works

## File Structure Explanation

```
/
├── index.php              # Main application entry point
├── config/
│   └── database.php       # Database configuration
├── includes/
│   └── functions.php      # Utility functions
├── pages/
│   ├── dashboard.php      # Main dashboard page
│   └── coming-soon.php    # Placeholder for future features
├── assets/
│   ├── css/
│   │   └── style.css      # Main stylesheet
│   └── js/
│       ├── theme.js       # Theme management
│       ├── timer.js       # Study timer functionality
│       ├── todo.js        # To-do list management
│       ├── stats.js       # Statistics handling
│       └── main.js        # General app functionality
├── api/
│   ├── save-session.php   # Save study sessions
│   ├── add-todo.php       # Add new tasks
│   ├── toggle-todo.php    # Mark tasks complete/incomplete
│   ├── delete-todo.php    # Delete tasks
│   ├── get-stats.php      # Get study statistics
│   └── get-detailed-stats.php # Get detailed statistics
└── database/
    └── schema.sql         # Database structure
```

## Usage Instructions

### Study Timer
1. Select your subject from the dropdown
2. Choose a preset time (25m, 45m, 60m, 90m) or use default 25 minutes
3. Click "Start" to begin studying
4. Use "Pause" to temporarily stop the timer
5. Use "Reset" to restart the timer
6. The timer will automatically save your session when completed

### To-Do List
1. Type your task in the input field
2. Click "Add" or press Enter
3. Click the checkbox to mark tasks as complete
4. Click the trash icon to delete tasks

### Statistics
- View today's study time and session count
- See weekly progress toward your 10-hour goal
- Track your consistency with session statistics

### Theme Toggle
- Click the moon/sun icon in the top-right corner
- Theme preference is saved locally

### Keyboard Shortcuts
- `Ctrl/Cmd + Space`: Start/pause timer
- `Ctrl/Cmd + R`: Reset timer (when focused on app)
- `Ctrl/Cmd + T`: Toggle theme
- `Escape`: Close modals

## Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Check your database credentials in `config/database.php`
   - Ensure the database exists and user has proper permissions
   - Verify your hosting provider's database settings

2. **Timer Not Working**
   - Check browser console for JavaScript errors
   - Ensure JavaScript is enabled
   - Try refreshing the page

3. **To-Do Items Not Saving**
   - Check database connection
   - Verify API endpoints are accessible
   - Check browser console for errors

4. **Styling Issues**
   - Clear browser cache
   - Check if CSS file is loading properly
   - Verify file permissions

### Browser Compatibility
- Chrome 70+
- Firefox 65+
- Safari 12+
- Edge 79+

## Customization

### Changing Colors
Edit the CSS variables in `assets/css/style.css`:
```css
:root {
    --primary-color: #6366f1;  /* Change this for different accent color */
    --success-color: #10b981;
    --danger-color: #ef4444;
    /* ... */
}
```

### Adding New Subjects
Modify the select options in `pages/dashboard.php`:
```html
<select id="subject-input" class="form-control">
    <option value="Math">Math</option>
    <option value="Science">Science</option>
    <!-- Add your subjects here -->
</select>
```

### Changing Timer Presets
Modify the preset buttons in `pages/dashboard.php`:
```html
<button class="preset-btn" data-minutes="25">25m</button>
<button class="preset-btn" data-minutes="45">45m</button>
<!-- Add or modify presets here -->
```

## Security Notes

1. The app uses session-based guest users for Phase 1
2. All user inputs are sanitized
3. Database queries use prepared statements
4. CSRF protection should be added in future versions
5. For production, consider adding rate limiting

## Future Phases

### Phase 2 (Planned)
- User registration and authentication
- Focus mode with website blocking
- Break tracking with different types
- D-Day countdown for exams
- Enhanced subject categorization

### Phase 3 (Planned)
- Study groups and social features
- Gamification with points and badges
- Flashcards and quiz system
- File upload for notes

### Phase 4 (Planned)
- AI tutor integration
- Marketplace for study materials
- Advanced analytics
- Offline mode capability

## Support

If you encounter issues:
1. Check the browser console for errors
2. Verify all files are uploaded correctly
3. Ensure database is properly configured
4. Check file permissions

## Contributing

This is a personal study app project. Feel free to modify and enhance it for your needs!

## License

This project is for educational purposes. Feel free to use and modify as needed.
stuuy appp
