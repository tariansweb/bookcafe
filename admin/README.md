# BookCafe Admin Panel

A comprehensive admin panel for managing the BookCafe website content and operations.

## Features

### 📊 Dashboard
- Overview statistics (books, menu items, events, messages, subscribers)
- Quick action buttons
- Recent activity log
- Real-time data updates

### 📚 Books Management
- Add, edit, and delete books
- Set featured books
- Manage availability and stock
- Search functionality
- Category assignment
- Cover image URLs

### ☕ Menu Items Management
- Add, edit, and delete menu items
- Organize by categories (Coffee, Tea, Pastries, etc.)
- Set featured items
- Manage availability and pricing

### 🎭 Events Management
- Create and manage events
- Set event dates and times
- Add location and descriptions
- View upcoming and past events

### ✉️ Contact Messages
- View all contact form submissions
- Mark messages as read/unread
- Reply via email
- Delete messages

### 👥 Subscribers Management
- View all newsletter subscribers
- Activate/deactivate subscribers
- Export email list
- Delete subscribers

### 🔐 Admin Users Management (Super Admin Only)
- Add new admin users
- Manage user roles (Super Admin, Admin, Editor)
- Activate/deactivate accounts
- Change passwords
- View last login times

## Installation

### 1. Database Setup

Run the admin schema SQL file to create the necessary tables:

```bash
mysql -u username -p database_name < database/admin_schema.sql
```

This will:
- Create `admin_users` table
- Create `admin_activity_log` table
- Create a default admin user
- Add necessary indexes

### 2. Default Admin Credentials

After running the schema, you can log in with:

**Username:** `admin`  
**Password:** `admin123`  
**Email:** `admin@bookcafe.com`

⚠️ **IMPORTANT:** Change this password immediately after first login!

### 3. Access the Admin Panel

Navigate to: `http://yourdomain.com/admin/`

This will redirect to the login page if you're not authenticated.

## User Roles

### Super Admin
- Full access to all features
- Can manage other admin users
- Can view activity logs
- Complete CRUD operations

### Admin
- Can manage content (books, menu, events)
- Can view and respond to messages
- Can manage subscribers
- Cannot manage admin users

### Editor
- Can create and edit content
- Limited delete permissions
- Cannot access user management
- Cannot view activity logs

## Security Features

- Password hashing using PHP's `password_hash()`
- Session-based authentication
- CSRF protection via POST requests
- SQL injection prevention with prepared statements
- XSS protection with input sanitization
- Activity logging for audit trails

## File Structure

```
admin/
├── assets/
│   ├── css/
│   │   └── admin.css          # Admin panel styles
│   └── js/
│       └── admin.js           # Admin panel JavaScript
├── includes/
│   ├── admin_functions.php    # Core admin functions
│   ├── header.php             # Admin header/navigation
│   └── footer.php             # Admin footer
├── index.php                  # Dashboard
├── login.php                  # Login page
├── logout.php                 # Logout handler
├── books.php                  # Books management
├── menu.php                   # Menu items management
├── events.php                 # Events management
├── messages.php               # Contact messages viewer
├── subscribers.php            # Subscribers management
├── admins.php                 # Admin users management
└── README.md                  # This file
```

## Usage

### Adding a New Book

1. Go to **Books** section
2. Click "Add New Book"
3. Fill in the required fields:
   - Title *
   - Author *
   - Price *
   - ISBN (optional)
   - Category (optional)
   - Description (optional)
   - Cover Image URL (optional)
   - Stock quantity
4. Check "Featured Book" to display on homepage
5. Check "Available for Sale" to make it visible
6. Click "Add Book"

### Managing Menu Items

1. Go to **Menu Items** section
2. Click "Add New Item"
3. Select category (Coffee, Tea, Pastries, etc.)
4. Enter name, description, and price
5. Mark as featured if desired
6. Save changes

### Creating Events

1. Go to **Events** section
2. Click "Add New Event"
3. Enter event details:
   - Title
   - Description
   - Date and time
   - Location
4. Click "Add Event"

### Viewing Messages

1. Go to **Messages** section
2. Unread messages appear in bold
3. Click "View" to read a message
4. Reply via email or mark as read
5. Delete when no longer needed

### Managing Subscribers

1. Go to **Subscribers** section
2. View all newsletter subscribers
3. Activate/deactivate as needed
4. Export email list for campaigns
5. Delete unsubscribed users

### Managing Admin Users (Super Admin Only)

1. Go to **Admin Users** section
2. Click "Add New Admin"
3. Enter username, email, and password
4. Select role (Super Admin, Admin, or Editor)
5. Set account as active
6. Click "Add Admin User"

## Activity Logging

All admin actions are logged in the `admin_activity_log` table:
- Login/logout events
- Content creation
- Content updates
- Content deletion
- User management actions

View recent activity on the Dashboard.

## API Functions

### Authentication

```php
// Check if admin is logged in
isAdminLoggedIn(): bool

// Require login (redirect if not authenticated)
requireAdminLogin(): void

// Get current admin data
getCurrentAdmin(): ?array

// Authenticate admin
authenticateAdmin(string $username, string $password): array

// Logout admin
logoutAdmin(): void
```

### Dashboard

```php
// Get dashboard statistics
getDashboardStats(): array

// Get recent activities
getRecentActivities(int $limit = 10): array
```

### Content Management

```php
// Get all books with pagination
getAllBooksAdmin(int $page = 1, int $perPage = 20, ?string $search = null): array

// Get all contact messages
getAllContactMessages(int $page = 1, int $perPage = 20): array

// Get all subscribers
getAllSubscribers(int $page = 1, int $perPage = 50): array

// Mark message as read
markMessageAsRead(int $messageId): bool
```

### Activity Logging

```php
// Log admin activity
logAdminActivity(
    int $adminId, 
    string $action, 
    ?string $entityType = null, 
    ?int $entityId = null, 
    ?string $details = null
): void
```

## Customization

### Changing Colors

Edit `admin/assets/css/admin.css` and modify CSS variables:

```css
:root {
    --admin-sidebar-width: 260px;
    --admin-primary: #2c3e50;
    --admin-secondary: #34495e;
    --admin-accent: #3498db;
    --admin-success: #27ae60;
    --admin-danger: #e74c3c;
    --admin-warning: #f39c12;
}
```

### Adding New Features

1. Create a new PHP file in the `admin/` directory
2. Include the admin functions: `require_once __DIR__ . '/includes/admin_functions.php';`
3. Call `requireAdminLogin()` to protect the page
4. Use the standard header and footer includes
5. Add a navigation link in `admin/includes/header.php`

## Troubleshooting

### Cannot Login
- Verify database connection in `config/database.php`
- Check that `admin_users` table exists
- Confirm password is correct (default: `admin123`)
- Clear browser cookies/cache

### Database Errors
- Ensure all tables from `admin_schema.sql` are created
- Check database user has proper permissions
- Verify foreign key constraints

### Session Issues
- Check PHP session configuration
- Ensure cookies are enabled in browser
- Verify session directory is writable

### Permission Denied
- Check user role (some features require Super Admin)
- Verify account is active in database
- Check `admin_users.is_active` field

## Best Practices

1. **Change Default Password** - Immediately after installation
2. **Use HTTPS** - Always use SSL/TLS in production
3. **Regular Backups** - Backup database regularly
4. **Limit Admin Access** - Only create necessary admin accounts
5. **Monitor Activity** - Review activity logs regularly
6. **Update Passwords** - Change passwords periodically
7. **Delete Unused Accounts** - Remove inactive admin users

## Support

For issues or questions:
- Check the main project README
- Review the code comments
- Check the activity logs for errors
- Verify database schema is up to date

## License

Part of the BookCafe project. All rights reserved.

