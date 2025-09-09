# SRIJAYA Manufacturing Management System

[![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC.svg)](https://tailwindcss.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

A comprehensive manufacturing management system built with Laravel 12, designed to streamline order management, production tracking, inventory control, and financial reporting for manufacturing businesses.

## 📋 Table of Contents

- [Features](#-features)
- [Technology Stack](#-technology-stack)
- [System Architecture](#-system-architecture)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Database Schema](#-database-schema)
- [User Roles & Permissions](#-user-roles--permissions)
- [API Documentation](#-api-documentation)
- [Usage Guide](#-usage-guide)
- [Development](#-development)
- [Testing](#-testing)
- [Deployment](#-deployment)
- [Contributing](#-contributing)
- [License](#-license)

## ✨ Features

### 🏭 Core Manufacturing Features
- **Order Management**: Complete order lifecycle from creation to delivery
- **Product Management**: Support for both fixed products and custom orders
- **Bill of Materials (BOM)**: Detailed material tracking and management
- **Production Tracking**: Real-time status updates and progress monitoring
- **Inventory Management**: Stock tracking with automatic updates

### 💰 Financial Management
- **Cost Tracking**: Material costs, production costs, and overhead tracking
- **Invoice Generation**: Automated PDF invoice generation with customization
- **Payment Tracking**: Multiple payment methods and status tracking
- **Financial Reporting**: Comprehensive reports with charts and analytics
- **Profit/Loss Analysis**: Detailed financial insights per order and period

### 📊 Reporting & Analytics
- **Dashboard Analytics**: Real-time KPIs and performance metrics
- **Order Reports**: Detailed order analysis and tracking
- **Financial Reports**: Revenue, expenses, and profitability analysis
- **Customer Reports**: Customer behavior and order history
- **Export Functionality**: Excel/PDF export capabilities

### 👥 User Management
- **Role-Based Access Control**: Admin, Owner, Staff, and Finance roles
- **User Authentication**: Secure login with Laravel Breeze
- **Profile Management**: User profile customization
- **Activity Logging**: Comprehensive audit trails

## 🛠 Technology Stack

### Backend
- **Laravel 12.x**: PHP framework
- **PHP 8.2+**: Programming language
- **MySQL**: Database management system
- **Laravel Breeze**: Authentication scaffolding

### Frontend
- **Blade Templates**: Server-side templating
- **Tailwind CSS 3.x**: Utility-first CSS framework
- **Alpine.js**: Lightweight JavaScript framework
- **Chart.js**: Data visualization
- **Heroicons**: Icon library

### Additional Packages
- **Laravel DomPDF**: PDF generation for invoices
- **Maatwebsite Excel**: Excel import/export functionality
- **Laravel Pint**: Code style fixer
- **PHPUnit**: Testing framework

## 🏗 System Architecture

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   Presentation  │    │    Business     │    │      Data      │
│      Layer      │    │     Logic       │    │     Layer      │
├─────────────────┤    ├─────────────────┤    ├─────────────────┤
│ • Blade Views   │◄──►│ • Controllers   │◄──►│ • Models        │
│ • Components    │    │ • Middleware    │    │ • Migrations    │
│ • Assets        │    │ • Services      │    │ • Seeders       │
└─────────────────┘    └─────────────────┘    └─────────────────┘
```

### Key Components
- **Controllers**: Handle HTTP requests and business logic
- **Models**: Eloquent ORM models with relationships
- **Views**: Blade templates with component-based architecture
- **Middleware**: Role-based access control and authentication
- **Services**: Business logic abstraction (where applicable)

## 🚀 Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js & NPM
- MySQL 5.7+ or MariaDB 10.3+
- Web server (Apache/Nginx)

### Step 1: Clone Repository
```bash
git clone https://github.com/your-username/idefu-manufacturing-laravel.git
cd idefu-manufacturing-laravel
```

### Step 2: Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### Step 3: Environment Setup
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 4: Database Configuration
Update your `.env` file with database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=idefu_manufacturing
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Step 5: Database Migration
```bash
# Run migrations
php artisan migrate

# Seed initial data (optional)
php artisan db:seed
```

### Step 6: Storage Setup
```bash
# Create storage link
php artisan storage:link
```

### Step 7: Build Assets
```bash
# Build for development
npm run dev

# Build for production
npm run build
```

### Step 8: Start Development Server
```bash
php artisan serve
```

## ⚙️ Configuration

### Environment Variables
Key configuration options in `.env`:

```env
# Application
APP_NAME="IDEFU Manufacturing"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=idefu_manufacturing
DB_USERNAME=root
DB_PASSWORD=

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### File Storage
- **Public Storage**: `storage/app/public` (linked to `public/storage`)
- **Private Storage**: `storage/app/private`
- **Custom Products**: `storage/app/public/custom-products`
- **Receipt Photos**: `storage/app/public/receipts`

## 🗄 Database Schema

### Core Tables

#### Users
```sql
- id (Primary Key)
- name (String)
- email (Unique)
- password (Hashed)
- role (Enum: admin, owner, staff, finance)
- email_verified_at (Timestamp)
- created_at, updated_at
```

#### Customers
```sql
- id (Primary Key)
- name (String)
- phone (String)
- address (Text)
- created_at, updated_at
```

#### Products
```sql
- id (Primary Key)
- name (Unique String)
- description (Text)
- image (String)
- stock (Integer)
- model (String)
- wood_type (String)
- details (Text)
- product_category (String)
- bom_master (JSON)
- created_at, updated_at
```

#### Orders
```sql
- id (Primary Key)
- order_number (Unique String)
- customer_id (Foreign Key)
- product_id (Foreign Key, Nullable)
- product_type (Enum: tetap, custom)
- product_name (String)
- product_specification (Text)
- image (String)
- order_date (Date)
- deadline (Date, Nullable)
- quantity (Integer)
- status (String)
- total_price (Decimal 15,2)
- created_at, updated_at
```

#### Invoices
```sql
- id (Primary Key)
- order_id (Foreign Key)
- invoice_number (Unique String)
- invoice_date (Date)
- due_date (Date)
- subtotal (Decimal 15,2)
- tax_amount (Decimal 15,2)
- total_amount (Decimal 15,2)
- status (Enum: Unpaid, Sent, Paid, Overdue, Cancelled)
- notes (Text)
- [Additional flexible fields for customization]
- created_at, updated_at
```

### Supporting Tables
- **Purchases**: Material purchases tracking
- **Production Costs**: Production cost tracking
- **Incomes**: Payment and income tracking

## 👤 User Roles & Permissions

### Role Hierarchy
1. **Owner**: Full system access
2. **Admin**: Administrative access
3. **Finance**: Financial operations
4. **Staff**: Basic operations

### Permission Matrix

| Feature | Owner | Admin | Finance | Staff |
|---------|-------|-------|---------|-------|
| Dashboard | ✅ | ✅ | ✅ | ✅ |
| Order Management | ✅ | ✅ | ❌ | ✅ |
| Product Management | ✅ | ✅ | ❌ | ✅ |
| Customer Management | ✅ | ✅ | ❌ | ✅ |
| Invoice Generation | ✅ | ✅ | ✅ | ✅ |
| Financial Reports | ✅ | ✅ | ❌ | ❌ |
| User Management | ✅ | ✅ | ❌ | ❌ |
| System Settings | ✅ | ✅ | ❌ | ❌ |

## 📚 API Documentation

### Authentication Routes
```php
POST /login          # User login
POST /logout         # User logout
GET  /verify-email   # Email verification
```

### Core Resource Routes
```php
# Orders
GET    /orders              # List orders
POST   /orders              # Create order
GET    /orders/{id}         # Show order
PATCH  /orders/{id}/status  # Update status
PATCH  /orders/{id}/price   # Update price

# Products
GET    /products            # List products
POST   /products            # Create product
PATCH  /products/{id}/stock # Update stock

# Customers
GET    /customers           # List customers
POST   /customers           # Create customer

# Invoices
POST   /orders/{id}/generate-invoice  # Generate invoice
GET    /invoices/{id}                 # Show invoice
GET    /invoices/{id}/download        # Download PDF
```

### Report Routes
```php
GET /reports              # Reports dashboard
GET /reports/orders       # Order reports
GET /reports/invoices     # Invoice reports
GET /reports/financial    # Financial reports
GET /reports/customers    # Customer reports
GET /reports/export       # Export reports
```

## 📖 Usage Guide

### Creating an Order
1. Navigate to **Orders** → **Create New Order**
2. Select customer and product type (Fixed/Custom)
3. Fill in order details and specifications
4. Upload custom product image (if applicable)
5. Set quantity and deadline
6. Save order

### Managing Production
1. Go to order details page
2. Add BOM items and material requirements
3. Record material purchases
4. Track production costs
5. Update order status as production progresses

### Generating Invoices
1. Ensure order is completed and priced
2. Navigate to order details
3. Click **Generate Invoice**
4. Customize invoice details
5. Download or send invoice

### Viewing Reports
1. Access **Reports** section (Owner/Admin only)
2. Select report type and date range
3. View analytics and charts
4. Export data if needed

## 🛠 Development

### Development Environment Setup
```bash
# Install development dependencies
composer install --dev

# Start development server with all services
composer run dev

# This runs concurrently:
# - PHP development server
# - Queue worker
# - Log monitoring
# - Vite asset building
```

### Code Style
```bash
# Fix code style issues
./vendor/bin/pint

# Check code style
./vendor/bin/pint --test
```

### Database Operations
```bash
# Create new migration
php artisan make:migration create_table_name

# Create new model with migration
php artisan make:model ModelName -m

# Create new controller
php artisan make:controller ControllerName

# Rollback last migration
php artisan migrate:rollback

# Fresh migration with seeding
php artisan migrate:fresh --seed
```

### Asset Management
```bash
# Watch for changes (development)
npm run dev

# Build for production
npm run build

# Install new packages
npm install package-name
```

## 🧪 Testing

### Running Tests
```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test --filter=OrderTest

# Run with coverage
php artisan test --coverage
```

### Test Structure
```
tests/
├── Feature/          # Feature tests
│   ├── Auth/        # Authentication tests
│   └── ProfileTest.php
├── Unit/            # Unit tests
└── TestCase.php     # Base test case
```

## 🚀 Deployment

### Production Checklist
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure production database
- [ ] Set up SSL certificate
- [ ] Configure web server (Apache/Nginx)
- [ ] Set up file storage permissions
- [ ] Configure email settings
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan route:cache`
- [ ] Run `php artisan view:cache`
- [ ] Build production assets: `npm run build`

### Environment-Specific Configuration
```bash
# Production optimizations
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Clear caches (if needed)
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Docker Deployment (Optional)
```dockerfile
# Example Dockerfile
FROM php:8.2-fpm
# ... additional configuration
```

## 🤝 Contributing

### Development Workflow
1. Fork the repository
2. Create a feature branch: `git checkout -b feature/amazing-feature`
3. Make your changes
4. Run tests: `php artisan test`
5. Fix code style: `./vendor/bin/pint`
6. Commit changes: `git commit -m 'Add amazing feature'`
7. Push to branch: `git push origin feature/amazing-feature`
8. Open a Pull Request

### Code Standards
- Follow PSR-12 coding standards
- Write comprehensive tests
- Document complex functionality
- Use meaningful commit messages
- Keep functions small and focused

### Pull Request Guidelines
- Provide clear description of changes
- Include relevant tests
- Update documentation if needed
- Ensure all tests pass
- Follow the existing code style

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 📞 Support

For support and questions:
- Create an issue on GitHub
- Contact the development team
- Check the documentation wiki

## 🔄 Changelog

### Version 1.0.0
- Initial release
- Core manufacturing management features
- User authentication and role management
- Order and product management
- Invoice generation and financial tracking
- Comprehensive reporting system

---

**Built with ❤️ using Laravel 12**