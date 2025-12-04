# 📚☕ BookCafe

> Where Stories Meet Coffee

A beautiful, modern website for a literary café — combining the warmth of great coffee with the magic of books. Built with PHP, MySQL (AWS RDS), JavaScript, HTML, and CSS.

![BookCafe Preview](https://via.placeholder.com/800x400/2C1810/F5F0E8?text=BookCafe+Preview)

## ✨ Features

- **Responsive Design** - Looks great on all devices
- **Modern UI/UX** - Warm, literary-inspired aesthetic with smooth animations
- **Dynamic Content** - Menu items, books, and events loaded from database
- **Newsletter System** - Email subscription functionality
- **Contact Form** - Message submission with validation
- **AWS RDS Ready** - Configured for MySQL on Amazon RDS

## 🚀 Quick Start

### Prerequisites

- PHP 7.4 or higher
- MySQL 5.7+ or MariaDB 10.3+
- Web server (Apache/Nginx) or PHP built-in server
- AWS RDS instance (or local MySQL)

### Installation

1. **Clone or download** this repository to your web server directory:
   ```bash
   git clone https://github.com/yourusername/bookcafe.git
   cd bookcafe
   ```

2. **Configure the database** connection in `config/database.php`:
   ```php
   define('DB_HOST', 'your-rds-endpoint.region.rds.amazonaws.com');
   define('DB_NAME', 'bookcafe_db');
   define('DB_USER', 'your_username');
   define('DB_PASS', 'your_password');
   ```

3. **Create the database** by running the SQL schema:
   ```bash
   mysql -h your-rds-endpoint -u your_username -p < database/schema.sql
   ```
   Or import `database/schema.sql` using phpMyAdmin or MySQL Workbench.

4. **Start the development server**:
   ```bash
   php -S localhost:8000
   ```

5. **Open your browser** and visit `http://localhost:8000`

## 📁 Project Structure

```
BookCafe/
├── assets/
│   ├── css/
│   │   └── style.css          # Main stylesheet
│   ├── js/
│   │   └── main.js            # JavaScript functionality
│   └── images/
│       └── favicon.svg        # Site favicon
├── config/
│   ├── config.php             # Global configuration
│   └── database.php           # Database connection
├── database/
│   └── schema.sql             # Database schema & sample data
├── includes/
│   ├── header.php             # Page header template
│   ├── footer.php             # Page footer template
│   └── functions.php          # Helper functions
├── index.php                  # Homepage
├── menu.php                   # Menu page
├── books.php                  # Books collection page
├── events.php                 # Events page
├── contact.php                # Contact page
├── subscribe.php              # Newsletter subscription endpoint
└── README.md                  # This file
```

## 🎨 Design System

### Color Palette

| Color | Hex | Usage |
|-------|-----|-------|
| Espresso | `#2C1810` | Primary text, headers |
| Mocha | `#4A3428` | Secondary elements |
| Latte | `#8B7355` | Muted text |
| Cream | `#F5F0E8` | Cards, surfaces |
| Parchment | `#FAF7F2` | Background |
| Gold | `#C9A86C` | Accents, highlights |
| Rust | `#A65D3F` | Secondary accent |
| Sage | `#7A8B6E` | Success states |

### Typography

- **Display Font**: Cormorant Garamond (headings)
- **Body Font**: Outfit (body text)

## 🔧 Configuration

### Site Settings (`config/config.php`)

```php
define('SITE_NAME', 'BookCafe');
define('SITE_TAGLINE', 'Where Stories Meet Coffee');
define('SITE_URL', 'http://localhost/BookCafe');
```

### AWS RDS Connection

For production with AWS RDS:

1. Create an RDS MySQL instance in your AWS console
2. Configure security groups to allow your server's IP
3. Update `config/database.php` with your RDS endpoint
4. For SSL connections, set the SSL certificate path:
   ```php
   PDO::MYSQL_ATTR_SSL_CA => '/path/to/rds-ca-cert.pem'
   ```

## 📱 Pages

| Page | Description |
|------|-------------|
| **Home** | Hero section, featured menu items, books, and upcoming events |
| **Menu** | Full menu with categories (Coffee, Tea, Pastries, Light Bites) |
| **Books** | Browsable book collection with category filtering |
| **Events** | Upcoming events with RSVP functionality |
| **Contact** | Contact form and location information |

## 🛠️ Development

### Adding New Menu Items

Insert into the database:
```sql
INSERT INTO menu_items (category_id, name, description, price, featured) 
VALUES (1, 'New Coffee', 'Description here', 5.50, TRUE);
```

### Adding New Books

```sql
INSERT INTO books (title, author, category_id, description, price, featured) 
VALUES ('Book Title', 'Author Name', 1, 'Description', 19.99, TRUE);
```

### Creating Events

```sql
INSERT INTO events (title, description, event_date, event_time, location) 
VALUES ('Event Name', 'Event description', '2024-12-25', '18:00:00', 'Main Hall');
```

## 🌐 Deployment

### Apache

Create a `.htaccess` file:
```apache
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Optional: Remove .php extension
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME}.php -f
RewriteRule ^(.*)$ $1.php [L]
```

### Nginx

```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}

location ~ \.php$ {
    fastcgi_pass unix:/var/run/php/php-fpm.sock;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    include fastcgi_params;
}
```

## 🔒 Security Recommendations

1. **Use HTTPS** in production
2. **Update credentials** - Never use default database passwords
3. **Configure error reporting** - Disable display_errors in production
4. **Input validation** - All user inputs are sanitized
5. **Prepared statements** - All database queries use PDO prepared statements

## 📄 License

This project is open source and available under the [MIT License](LICENSE).

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

---

Made with ☕ and 📚 by BookCafe Team

