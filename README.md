# 📱 Mobile POS System

A lightweight, mobile-friendly Point of Sale (POS) system built with Laravel. Designed for small retail shops to manage products, create invoices, and handle sales efficiently from any device on a local network.

![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php&logoColor=white)
![Livewire](https://img.shields.io/badge/Livewire-3-FB70A9?style=flat-square&logo=livewire&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-3.4-06B6D4?style=flat-square&logo=tailwindcss&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)

---

## 📋 About The Project

Mobile POS is a complete point-of-sale solution designed for small retail businesses. It enables quick invoice creation, inventory management, customer tracking, and sales reporting—all accessible from any device connected to your local network.

### Key Highlights
- **Fast Billing**: Create invoices in under 1 minute
- **Mobile-First**: Fully responsive design works on phones, tablets, and desktops
- **Offline-Ready**: Runs entirely on localhost—no internet required
- **Print Support**: Professional invoice printing with QR codes
- **Role-Based Access**: Admin and staff permission levels

---

## 🛠️ Technologies Used

### Backend
| Technology | Version | Purpose |
|------------|---------|---------|
| PHP | 8.2+ | Server-side language |
| Laravel | 12.x | PHP Framework |
| Laravel Jetstream | 5.4 | Authentication scaffolding |
| Laravel Livewire | 3.x | Dynamic UI components |
| Laravel Sanctum | 4.x | API authentication |

### Frontend
| Technology | Version | Purpose |
|------------|---------|---------|
| Tailwind CSS | 3.4 | Utility-first CSS framework |
| Alpine.js | 3.x | Lightweight JS framework (via Livewire) |
| Chart.js | 4.5 | Dashboard charts & analytics |
| Vite | 7.x | Frontend build tool |

### Database
| Technology | Purpose |
|------------|---------|
| MySQL / SQLite | Primary database |

### Additional Packages
| Package | Purpose |
|---------|---------|
| simplesoftwareio/simple-qrcode | QR code generation for invoices |

---

## ✨ Technical Features

### Product Management
- Add, edit, and delete products
- Stock quantity tracking with automatic deduction
- Product categorization
- Cost and price management
- Stock logging for inventory audits

### Billing & Invoicing
- Fast invoice creation workflow
- Cash and card payment support
- Warranty period selection (0, 3, or 6 months)
- One-click invoice printing
- QR code on invoices for verification
- Invoice history and search

### Customer Management
- Customer database with contact details
- Quick customer creation during checkout
- Customer purchase history

### Dashboard & Analytics
- KPI dashboard with monthly/yearly views
- Sales trends and comparisons
- Category-wise breakdown
- Revenue and profit tracking

### User Management
- Secure authentication (Laravel Jetstream)
- Role-based access control (Admin/Staff)
- User activity tracking on invoices

### Shop Settings
- Configurable shop name and contact info
- Customizable invoice headers

---

## 📦 Requirements

Before installation, ensure your system meets these requirements:

### System Requirements
- **PHP** >= 8.2
- **Composer** >= 2.x
- **Node.js** >= 18.x
- **NPM** >= 9.x

### PHP Extensions
- BCMath
- Ctype
- Fileinfo
- JSON
- Mbstring
- OpenSSL
- PDO
- Tokenizer
- XML

### Database
- MySQL 8.0+ **OR** SQLite 3.x

### Recommended
- [Laragon](https://laragon.org/) (Windows) - Easy local development environment
- [XAMPP](https://www.apachefriends.org/) or [MAMP](https://www.mamp.info/) as alternatives

---

## 🚀 Installation

### 1. Clone the Repository

```bash
git clone https://github.com/your-username/mobile-pos.git
cd mobile-pos
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node Dependencies

```bash
npm install
```

### 4. Environment Setup

Copy the example environment file:

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

### 5. Database Configuration

#### Option A: MySQL (Recommended for Production)

Edit your `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mobile_pos
DB_USERNAME=root
DB_PASSWORD=your_password
```

Create the database:

```sql
CREATE DATABASE mobile_pos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

#### Option B: SQLite (Quick Setup)

Edit your `.env` file:

```env
DB_CONNECTION=sqlite
```

Create the SQLite database file:

```bash
touch database/database.sqlite
```

### 6. Run Migrations

```bash
php artisan migrate
```

### 7. Seed the Database (Optional)

Create an admin user and sample data:

```bash
php artisan db:seed
```

### 8. Build Frontend Assets

For development:
```bash
npm run dev
```

For production:
```bash
npm run build
```

### 9. Start the Server

```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

---

## ⚡ Quick Setup (One Command)

If you prefer a quick setup, run:

```bash
composer setup
```

This will:
- Install PHP dependencies
- Copy `.env.example` to `.env`
- Generate application key
- Run migrations
- Install NPM packages
- Build frontend assets

---

## 🔧 Environment Variables

Key environment variables to configure:

| Variable | Description | Default |
|----------|-------------|---------|
| `APP_NAME` | Application name | Laravel |
| `APP_ENV` | Environment (local/production) | local |
| `APP_DEBUG` | Debug mode | true |
| `APP_URL` | Application URL | http://localhost |
| `DB_CONNECTION` | Database driver | sqlite |
| `DB_DATABASE` | Database name | laravel |
| `DB_USERNAME` | Database username | root |
| `DB_PASSWORD` | Database password | - |

---

## 📱 Accessing from Mobile Devices

To access the POS from phones/tablets on your local network:

1. Find your computer's local IP address:
   - Windows: `ipconfig` → Look for IPv4 Address
   - Mac/Linux: `ifconfig` or `ip addr`

2. Start the server on all interfaces:
   ```bash
   php artisan serve --host=0.0.0.0
   ```

3. Access from any device on the same network:
   ```
   http://YOUR_LOCAL_IP:8000
   ```

---

## 📁 Project Structure

```
mobile-pos/
├── app/
│   ├── Http/Controllers/    # HTTP Controllers
│   ├── Livewire/            # Livewire Components
│   ├── Models/              # Eloquent Models
│   └── Services/            # Business Logic Services
├── database/
│   ├── migrations/          # Database Migrations
│   └── seeders/             # Database Seeders
├── resources/
│   ├── views/               # Blade Templates
│   └── css/                 # Stylesheets
├── routes/
│   └── web.php              # Web Routes
└── public/                  # Public Assets
```

---

## 🗺️ Application Routes

| Route | Description |
|-------|-------------|
| `/dashboard` | KPI Dashboard with analytics |
| `/products` | Product management |
| `/customers` | Customer management |
| `/invoice/create` | Create new invoice |
| `/invoices` | Invoice history |
| `/settings` | Shop settings |
| `/staff` | Staff management (Admin only) |

---

## 🔐 Default Credentials

After running seeders, use these credentials:

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@example.com | password |

> ⚠️ **Important**: Change default passwords immediately in production!

---

## 🧪 Running Tests

```bash
php artisan test
```

Or with coverage:

```bash
php artisan test --coverage
```

---

## 📝 License

This project is open-sourced software licensed under the [MIT License](https://opensource.org/licenses/MIT).

---

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

---

## 📞 Support

If you encounter any issues or have questions, please [open an issue](https://github.com/your-username/mobile-pos/issues) on GitHub.
