# Admin Section Setup Guide

## Quick Start

Your BookCafe website now has a complete admin panel! Follow these steps to get started.

## 🚀 Setup Steps

### Step 1: Configure Database Connection

1. Open `config/database.php`
2. Update the database credentials:

```php
define('DB_HOST', 'localhost');  // or your database host
define('DB_NAME', 'bookcafe_db');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
```

### Step 2: Run Database Schema

Run the admin schema to create necessary tables:

**Option A: Using phpMyAdmin**
1. Open phpMyAdmin
2. Select your `bookcafe_db` database
3. Click "Import" tab
4. Choose file: `database/admin_schema.sql`
5. Click "Go"

**Option B: Using MySQL Command Line**
```bash
mysql -u your_username -p bookcafe_db < database/admin_schema.sql
```

**Option C: Using XAMPP**
1. Start Apache and MySQL in XAMPP Control Panel
2. Go to http://localhost/phpmyadmin
3. Select `bookcafe_db` database
4. Import `database/admin_schema.sql`

### Step 3: Access Admin Panel

Navigate to: **http://localhost/BookCafe/admin/**

Or if deployed: **http://yourdomain.com/admin/**

### Step 4: Login

Use the default credentials:
- **Username:** `admin`
- **Password:** `admin123`

⚠️ **IMPORTANT:** Change this password immediately after first login!

### Step 5: Change Default Password

1. After logging in, go to **Admin Users** section
2. Click "Edit" on the admin user
3. Enter a new strong password
4. Click "Update Admin User"

## 📋 What You Can Do

### Dashboard
- View statistics at a glance
- See recent activity
- Quick action buttons

### Books Management
✅ Add new books  
✅ Edit existing books  
✅ Delete books  
✅ Set featured books  
✅ Manage stock and pricing  
✅ Search books  

### Menu Items Management
✅ Add coffee, tea, pastries, etc.  
✅ Edit menu items  
✅ Delete items  
✅ Set featured items  
✅ Manage availability  

### Events Management
✅ Create events  
✅ Set dates and times  
✅ Add locations  
✅ Edit/delete events  

### Contact Messages
✅ View all messages  
✅ Mark as read/unread  
✅ Reply via email  
✅ Delete messages  

### Newsletter Subscribers
✅ View all subscribers  
✅ Activate/deactivate  
✅ Export email list  
✅ Delete subscribers  

### Admin Users (Super Admin Only)
✅ Add new admins  
✅ Manage roles  
✅ Activate/deactivate accounts  
✅ Change passwords  

## 👥 User Roles

### Super Admin
- Full access to everything
- Can manage other admin users
- Perfect for: Site owners

### Admin
- Can manage all content
- Can view messages and subscribers
- Cannot manage admin users
- Perfect for: Managers

### Editor
- Can create and edit content
- Limited delete permissions
- Perfect for: Content creators

## 🔒 Security Features

✅ Secure password hashing  
✅ Session-based authentication  
✅ SQL injection protection  
✅ XSS protection  
✅ Activity logging  
✅ Role-based access control  

## 📁 File Structure

```
BookCafe/
├── admin/                      # 🆕 Admin Panel
│   ├── assets/
│   │   ├── css/admin.css      # Admin styles
│   │   └── js/admin.js        # Admin JavaScript
│   ├── includes/
│   │   ├── admin_functions.php # Core functions
│   │   ├── header.php         # Admin header
│   │   └── footer.php         # Admin footer
│   ├── index.php              # Dashboard
│   ├── login.php              # Login page
│   ├── books.php              # Books management
│   ├── menu.php               # Menu management
│   ├── events.php             # Events management
│   ├── messages.php           # Messages viewer
│   ├── subscribers.php        # Subscribers management
│   └── admins.php             # Admin users management
├── database/
│   └── admin_schema.sql       # 🆕 Admin tables schema
└── ... (other files)
```

## ⚙️ Customization

### Change Admin Panel Colors

Edit `admin/assets/css/admin.css`:

```css
:root {
    --admin-primary: #2c3e50;    /* Main color */
    --admin-accent: #3498db;     /* Accent color */
    --admin-success: #27ae60;    /* Success color */
}
```

### Add New Admin User

1. Go to Admin Users section (requires Super Admin)
2. Click "Add New Admin"
3. Fill in details
4. Select role
5. Save

### Export Subscriber Emails

1. Go to Subscribers section
2. Click "Export Email List"
3. Opens as text file with one email per line
4. Use for email campaigns

## 🐛 Troubleshooting

### "Database connection failed"
- Check `config/database.php` credentials
- Ensure MySQL is running (XAMPP Control Panel)
- Verify database exists

### "Invalid username or password"
- Use default: `admin` / `admin123`
- Check caps lock is off
- Clear browser cookies

### "Access denied" error
- Check user role permissions
- Some features require Super Admin
- Verify account is active

### Tables don't exist
- Run `database/admin_schema.sql` first
- Ensure it ran successfully
- Check for SQL errors in log

### Can't see any data
- Add data through admin panel
- Or run `database/schema.sql` for sample data
- Check database connection

## 📊 Activity Monitoring

All admin actions are logged:
- User logins/logouts
- Content creation
- Content updates
- Content deletion

View on Dashboard under "Recent Activity"

## 🎯 Best Practices

1. ✅ Change default password immediately
2. ✅ Use strong passwords (8+ chars, mixed case, numbers, symbols)
3. ✅ Only create necessary admin accounts
4. ✅ Use appropriate roles (don't make everyone Super Admin)
5. ✅ Regularly review activity logs
6. ✅ Delete inactive admin accounts
7. ✅ Keep regular database backups
8. ✅ Use HTTPS in production

## 🚀 Next Steps

1. ✅ Complete database setup
2. ✅ Login to admin panel
3. ✅ Change default password
4. ✅ Add your books
5. ✅ Update menu items
6. ✅ Create events
7. ✅ Check contact messages
8. ✅ Review subscribers

## 📞 Quick Reference

**Admin URL:** `/admin/`  
**Default User:** `admin`  
**Default Pass:** `admin123`  
**Database Tables:** `admin_users`, `admin_activity_log`  
**Min PHP Version:** 7.4+  
**Database:** MySQL 5.7+ / MariaDB 10.2+  

## Need Help?

- Check `admin/README.md` for detailed documentation
- Review `includes/admin_functions.php` for available functions
- Check browser console for JavaScript errors
- Review PHP error logs

---

**Your admin panel is ready! 🎉**

Start by logging in and exploring the features. Don't forget to change the default password!

