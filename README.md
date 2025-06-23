# Human Resources Information System (HRIS) 👥

[![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com)
[![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)](https://developer.mozilla.org/en-US/docs/Web/JavaScript)
[![Vue.js](https://img.shields.io/badge/Vue.js-4FC08D?style=for-the-badge&logo=vue.js&logoColor=white)](https://vuejs.org)
[![Docker](https://img.shields.io/badge/Docker-2496ED?style=for-the-badge&logo=docker&logoColor=white)](https://www.docker.com)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg?style=for-the-badge)](https://opensource.org/licenses/MIT)

## 📋 Overview
A comprehensive Human Resources Information System designed to streamline HR operations and enhance organizational efficiency. This solution provides a centralized platform for managing the complete employee lifecycle, from recruitment to retirement.

### 🎯 Key Objectives
- Streamline HR processes and reduce administrative overhead
- Ensure data accuracy and maintain compliance
- Improve employee engagement and satisfaction
- Enable data-driven HR decision making
- Enhance security and confidentiality of employee information

## ⭐ Features

### 👤 Employee Management
- Complete employee profile management
- Document storage and verification
- Employee onboarding and offboarding workflows
- Organization chart and reporting structure

### ⏰ Time & Attendance
- Automated attendance tracking
- Work schedule management
- Overtime calculation and management
- Night premium calculations
- Real-time attendance monitoring

### 📅 Leave Management
- Comprehensive leave policy implementation
- Leave balance tracking
- Leave application and approval workflow
- Holiday calendar management
- Leave reports and analytics

### 💼 Career Portal
- Job posting and application management
- Candidate tracking system
- Interview scheduling
- Recruitment workflow
- Offer letter generation

### 📊 Performance Management
- Task management
- Performance review cycles
- Goal setting and tracking
- Training and development tracking
- Performance analytics

### 💰 Payroll Management
- Automated payroll calculation
- Tax deductions and contributions (SSS, PhilHealth, Pagibig)
- Overtime pay computation
- Night premium pay
- Loan and cash advance management

### 📑 Document Management
- Centralized document repository
- Credential management
- Access control and permissions
- Document expiry notifications
- Digital signature support

### 📈 Reporting & Analytics
- Custom report builder
- Real-time dashboards
- Export capabilities (PDF, Excel, CSV)
- Data visualization
- User activity tracking

### 🔔 Notifications & Communication
- Real-time notifications
- Email alerts
- Internal messaging system
- Push notifications support
- Event-based reminders

## 🛠 Tech Stack
- **Backend Framework:** PHP/Laravel 10.x
- **Database:** MySQL 8.0
- **Frontend:** 
  - HTML5/CSS3
  - JavaScript
  - Bootstrap 5
  - Vue.js 3
- **Real-time Features:**
  - Pusher
  - Laravel Echo
  - WebSockets
- **AI Integration:**
  - OpenAI integration for smart features
- **Development Tools:**
  - Docker
  - Git
  - Composer
  - NPM
  - Vite
- **Testing:** PHPUnit
- **Authentication:** 
  - Laravel Sanctum
  - Social login (Google)
  - Role-based permissions (Spatie)

## ⚙️ Prerequisites
- PHP >= 8.2
- Composer >= 2.0
- Node.js >= 14.x
- NPM >= 6.x
- MySQL >= 5.7
- Docker >= 20.10 (optional)
- Git

## 🚀 Installation

### 💻 Local Setup
1. **Clone the repository**
```bash
git clone [repository-url]
cd hris
```

2. **Install PHP dependencies**
```bash
composer install
```

3. **Install JavaScript dependencies**
```bash
npm install
npm run build
```

4. **Environment Configuration**
```bash
cp .env.example .env
php artisan key:generate
php artisan storage:link
```

5. **Database Configuration**
Update `.env` file with your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hris
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

6. **Database Setup**
```bash
php artisan migrate --seed
```

7. **Start Development Server**
```bash
php artisan serve
```

8. **Run frontend development server (optional)**
```bash
npm run dev
```

### 🐳 Docker Setup
1. **Build and start containers**
```bash
docker-compose up -d --build
```

2. **Container Setup**
```bash
docker-compose exec app bash
composer install
php artisan key:generate
php artisan migrate --seed
```

3. **Frontend build**
```bash
npm install
npm run build
```

## 🌐 Usage
- **Development:** `http://localhost:8000`
- **Production:** Configure your domain with proper SSL certificate
- **Default Admin Credentials:**
  - Email: `admin@example.com`
  - Password: `password`

## 📱 Key Features Usage

### Employee Management
- Add, edit, and view comprehensive employee profiles
- Manage departments, positions, and job levels
- Track employee documents and credentials

### Attendance System
- Clock in/out functionality
- Track attendance history
- Generate attendance reports
- Calculate overtime and night premium pay

### Leave Management
- Apply for leave with approval workflow
- Track leave balances and history
- Generate leave reports
- Holiday calendar integration

### Payroll System
- Automated payroll calculation
- Government contribution management (SSS, PhilHealth, Pagibig)
- Cash advance and loan management
- Payslip generation

## 🧪 Testing
```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit
```

## 🤝 Contributing
We welcome contributions! Please follow these steps:

1. Fork the repository
2. Create your feature branch: `git checkout -b feature/AmazingFeature`
3. Commit your changes: `git commit -m 'Add some AmazingFeature'`
4. Push to the branch: `git push origin feature/AmazingFeature`
5. Open a Pull Request

### Coding Standards
- Follow PSR-12 coding standards
- Write meaningful commit messages
- Add appropriate documentation
- Include unit tests for new features

## 🔒 Security
- For security vulnerabilities, email security@yourdomain.com
- Regular security audits conducted
- Data encryption at rest and in transit
- Role-based access control implementation

## 📄 License
This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 💬 Support
- Technical Support: support@yourdomain.com
- Documentation: [Wiki Link]
- Community Forum: [Forum Link]

## 👏 Acknowledgments
- Laravel Development Team
- Open Source Community
- All Project Contributors

## 📊 Project Status
![GitHub issues](https://img.shields.io/github/issues/yourusername/hris)
![GitHub pull requests](https://img.shields.io/github/issues-pr/yourusername/hris)
![GitHub last commit](https://img.shields.io/github/last-commit/yourusername/hris)

---
Made with ❤️ by MHR-IT
