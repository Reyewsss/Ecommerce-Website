# E-commerce Website

A complete e-commerce website built with PHP, MySQL, HTML, CSS, and JavaScript.

## Features

- **Frontend:**
  - Responsive design
  - Product catalog with categories
  - Shopping cart functionality
  - User authentication (login/register)
  - Product search and filtering
  - Newsletter subscription
  - Contact form

- **Backend:**
  - Admin dashboard
  - Product management
  - Order management
  - User management
  - Category management
  - Newsletter management
  - Settings management

- **Database:**
  - MySQL database with complete schema
  - User authentication and authorization
  - Product catalog with categories
  - Shopping cart and orders
  - Newsletter subscribers
  - Contact messages

## Installation

1. **Prerequisites:**
   - PHP 7.4 or higher
   - MySQL 5.7 or higher
   - Apache/Nginx web server

2. **Database Setup:**
   - Create a new MySQL database
   - Import the schema from `database/schema.sql`
   - Update database credentials in `config/config.php`

3. **Configuration:**
   - Update site settings in `config/config.php`
   - Set proper file permissions for upload directories
   - Configure email settings for notifications

4. **Admin Access:**
   - Default admin credentials:
     - Email: admin@cosmeticsstore.com
     - Password: admin123
   - Change default credentials after first login

## File Structure

```
Cosmetics Website/
├── admin/                  # Admin panel
│   ├── css/               # Admin styles
│   ├── js/                # Admin scripts
│   └── includes/          # Admin includes
├── api/                   # API endpoints
├── assets/                # Frontend assets
│   ├── css/              # Stylesheets
│   ├── js/               # JavaScript files
│   ├── images/           # Images
│   └── fonts/            # Fonts
├── config/                # Configuration files
├── database/              # Database schema
├── includes/              # Shared includes
├── uploads/               # Upload directories
├── user/                  # User account pages
└── vendor/                # Third-party libraries
```

## Usage

### Frontend
- Visit the website homepage
- Browse products by category
- Add products to cart
- Register/login for checkout
- Complete purchase process

### Admin Panel
- Access admin panel at `/admin/`
- Manage products, categories, orders
- View customer information
- Process orders and manage inventory

## API Endpoints

- `api/auth.php` - User authentication
- `api/cart.php` - Shopping cart operations
- `api/products.php` - Product data
- `api/newsletter.php` - Newsletter subscription
- `api/contact.php` - Contact form submission

## Security Features

- Password hashing
- SQL injection prevention
- Session management
- Input validation and sanitization
- Role-based access control

## Customization

### Styling
- Modify `assets/css/style.css` for main styles
- Update `assets/css/responsive.css` for mobile styles
- Admin styles in `admin/css/admin.css`

### Functionality
- Add new features by creating new PHP files
- Extend API endpoints in the `api/` directory
- Add new database tables as needed

## Support

For support and customization, refer to the documentation in the `docs/` directory.
