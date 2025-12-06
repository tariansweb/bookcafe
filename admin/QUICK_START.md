# 🚀 Admin Panel - Quick Start Guide

## ✅ Step 1: Setup Database

### Option A: Using phpMyAdmin (Recommended for XAMPP)

1. **Start XAMPP**
   - Open XAMPP Control Panel
   - Start Apache ✅
   - Start MySQL ✅

2. **Open phpMyAdmin**
   - Go to: `http://localhost/phpmyadmin`
   - Click on `bookcafe_db` database (or create it if it doesn't exist)

3. **Import Admin Schema**
   - Click the "Import" tab
   - Click "Choose File"
   - Select: `D:\localhost\xampp\htdocs\BookCafe\database\admin_schema.sql`
   - Click "Go" at the bottom
   - Wait for "Import has been successfully finished" message ✅

### Option B: Using MySQL Command Line

```bash
cd D:\localhost\xampp\htdocs\BookCafe
mysql -u root -p bookcafe_db < database\admin_schema.sql
```

---

## 🔐 Step 2: Login to Admin Panel

### Access the Login Page

**URL:** `http://localhost/BookCafe/admin/`

Or directly: `http://localhost/BookCafe/admin/login.php`

### Default Credentials

```
Username: admin
Password: admin123
```

### Login Screen Preview

```
┌─────────────────────────────────────┐
│                                     │
│         📚 BookCafe                 │
│          Admin Panel                │
│                                     │
│  ┌───────────────────────────────┐ │
│  │ Username                      │ │
│  │ [admin________________]       │ │
│  │                               │ │
│  │ Password                      │ │
│  │ [admin123_____________]       │ │
│  │                               │ │
│  │     [Login]                   │ │
│  └───────────────────────────────┘ │
│                                     │
│      ← Back to Website              │
│                                     │
└─────────────────────────────────────┘
```

---

## 📊 Step 3: Explore the Dashboard

After login, you'll see:

### Navigation Menu (Left Sidebar)

```
📚 BookCafe
Admin Panel

📊 Dashboard        ← You are here
📚 Books
☕ Menu Items
🎭 Events
✉️ Messages
👥 Subscribers
🔐 Admin Users

───────────────
🌐 View Website
```

### Statistics Cards

```
┌─────────────┐  ┌─────────────┐  ┌─────────────┐
│ 📚          │  │ ☕          │  │ 🎭          │
│ 5 Books     │  │ 12 Menu     │  │ 3 Events    │
│ Available   │  │ Items       │  │ Upcoming    │
└─────────────┘  └─────────────┘  └─────────────┘

┌─────────────┐  ┌─────────────┐  ┌─────────────┐
│ ✉️          │  │ 👥          │  │ 📊          │
│ 0 Unread    │  │ 0 Active    │  │ 1 Today's   │
│ Messages    │  │ Subscribers │  │ Activities  │
└─────────────┘  └─────────────┘  └─────────────┘
```

---

## 🎯 Step 4: Common Tasks

### Add a New Book

1. Click **"Books"** in the sidebar
2. Click **"➕ Add New Book"** button
3. Fill in the form:
   - **Title:** "The Great Gatsby"
   - **Author:** "F. Scott Fitzgerald"
   - **Price:** 14.99
   - **Category:** Select from dropdown
   - **Description:** (optional)
   - **Cover Image URL:** (optional)
   - **Stock:** 10
   - ✅ **Featured Book** (check if you want it on homepage)
   - ✅ **Available for Sale** (check to make it visible)
4. Click **"Add Book"**

### Add a Menu Item

1. Click **"Menu Items"** in the sidebar
2. Click **"➕ Add New Item"**
3. Fill in:
   - **Category:** Select (☕ Coffee, 🍵 Tea, etc.)
   - **Item Name:** "Vanilla Latte"
   - **Description:** "Smooth espresso with vanilla and steamed milk"
   - **Price:** 5.50
   - ✅ **Featured Item** (optional)
   - ✅ **Available** (check)
4. Click **"Add Item"**

### Create an Event

1. Click **"Events"** in the sidebar
2. Click **"➕ Add New Event"**
3. Fill in:
   - **Title:** "Book Launch Party"
   - **Description:** "Join us for an exciting book launch"
   - **Date:** Select date (YYYY-MM-DD)
   - **Time:** Select time (HH:MM)
   - **Location:** "Main Hall"
4. Click **"Add Event"**

### View Messages

1. Click **"Messages"** in the sidebar
2. See all contact form submissions
3. Click **"View"** to read a message
4. Click **"Reply via Email"** to respond
5. Mark as read or delete

### Manage Subscribers

1. Click **"Subscribers"** in the sidebar
2. View all newsletter subscribers
3. Click **"📧 Export Email List"** to download
4. Activate/Deactivate or Delete as needed

---

## 🔒 Step 5: Change Default Password (IMPORTANT!)

1. Click **"Admin Users"** in the sidebar
2. Find the "admin" user in the table
3. Click **"Edit"**
4. In the **"Password"** field, enter your new password
5. Click **"Update Admin User"**
6. ✅ Done! Your password is now secure

---

## 🎨 Admin Panel Features

### ✅ What You Can Do

- **Dashboard**
  - View real-time statistics
  - See recent activities
  - Quick action buttons

- **Books Management**
  - Add/Edit/Delete books
  - Set featured books for homepage
  - Manage stock levels
  - Search functionality
  - Category assignment

- **Menu Management**
  - Add/Edit/Delete menu items
  - Organize by categories
  - Set featured items
  - Control availability

- **Events Management**
  - Create/Edit/Delete events
  - Schedule with date & time
  - Add location details
  - View upcoming events

- **Messages**
  - Read contact submissions
  - Mark as read/unread
  - Reply directly via email
  - Delete old messages

- **Subscribers**
  - View all subscribers
  - Export email list for campaigns
  - Activate/Deactivate
  - Manage newsletter list

- **Admin Users** (Super Admin only)
  - Add new admins
  - Manage user roles
  - Change passwords
  - View login history

---

## 🛠️ Troubleshooting

### Problem: "Database connection failed"

**Solution:**
1. Check XAMPP - MySQL must be running (green status)
2. Open `config/database.php`
3. Verify these settings:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'bookcafe_db');
   define('DB_USER', 'root');        // Default XAMPP user
   define('DB_PASS', '');            // Default XAMPP password (empty)
   ```

### Problem: "Invalid username or password"

**Solution:**
- Use exactly: Username `admin` / Password `admin123`
- Check for typos
- Verify admin_schema.sql was imported successfully
- Check database has `admin_users` table with data

### Problem: "Table doesn't exist" errors

**Solution:**
1. First run: `database/schema.sql` (creates main tables)
2. Then run: `database/admin_schema.sql` (adds admin tables)
3. Verify in phpMyAdmin that tables exist

### Problem: Can't see the admin panel styling

**Solution:**
- Clear browser cache (Ctrl + Shift + Delete)
- Check that `admin/assets/css/admin.css` exists
- Verify file paths are correct

---

## 📱 Access Points

### Main Website
```
http://localhost/BookCafe/
http://localhost/BookCafe/index.php
```

### Admin Panel
```
http://localhost/BookCafe/admin/
http://localhost/BookCafe/admin/login.php
```

### Admin Pages
```
http://localhost/BookCafe/admin/index.php       (Dashboard)
http://localhost/BookCafe/admin/books.php       (Books)
http://localhost/BookCafe/admin/menu.php        (Menu)
http://localhost/BookCafe/admin/events.php      (Events)
http://localhost/BookCafe/admin/messages.php    (Messages)
http://localhost/BookCafe/admin/subscribers.php (Subscribers)
http://localhost/BookCafe/admin/admins.php      (Admin Users)
```

---

## 🎯 Next Steps

1. ✅ Login with default credentials
2. ✅ Change your password
3. ✅ Add your first book
4. ✅ Update menu items
5. ✅ Create an event
6. ✅ Test the contact form on main site
7. ✅ Check messages in admin panel
8. ✅ Explore all features!

---

## 💡 Pro Tips

1. **Backup Regularly** - Export your database from phpMyAdmin
2. **Use Featured Items** - They appear on the homepage
3. **Monitor Activity** - Check the dashboard's recent activity log
4. **Export Subscribers** - Before sending newsletters
5. **Test Everything** - Add test data to see how it looks on the website

---

## 🎉 You're All Set!

Your admin panel is fully functional and ready to use!

**Remember:**
- Default login: `admin` / `admin123`
- Change password after first login
- Access at: `http://localhost/BookCafe/admin/`

Happy managing! 📚☕

