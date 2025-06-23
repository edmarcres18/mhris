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

# MHRIS - Production Deployment Guide

This guide provides detailed instructions for deploying the MHRIS application to an Ubuntu production server using Docker.

## Prerequisites

- Ubuntu Server 20.04 LTS or later
- Root access to the server
- Domain name pointed to your server (for production)
- Basic knowledge of Linux, Docker, and Laravel

## Server Requirements

- Minimum 2GB RAM (4GB recommended)
- 2 CPU cores (4 recommended)
- 20GB disk space (SSD preferred)
- Stable internet connection

## Deployment Steps

### 1. Prepare Your Server

Ensure your server is up-to-date:

```bash
sudo apt update
sudo apt upgrade -y
```

Install required packages:

```bash
sudo apt install -y git curl zip unzip
```

### 2. Clone the Repository

```bash
cd /tmp
git clone https://your-repository-url.git mhris
```

### 3. Run the Deployment Script

Our deployment script automates most of the setup process:

```bash
cd /tmp/mhris
chmod +x deploy.sh
sudo ./deploy.sh production
```

The script will:
- Install Docker and Docker Compose if not present
- Create required directories
- Backup existing data if applicable
- Configure SSL certificates
- Set proper file permissions
- Build and start Docker containers

### 4. Configure Environment Variables

After running the deployment script, you need to configure your environment variables:

```bash
sudo nano /var/www/mhris/.env.production
```

Update the following values:
- `APP_URL`: Your production domain
- `DB_PASSWORD` and `DB_ROOT_PASSWORD`: Strong database passwords
- Email configuration for your production mail server
- Any other application-specific settings

### 5. Restart the Application

After updating your environment variables:

```bash
cd /var/www/mhris
sudo docker-compose down
sudo docker-compose up -d
```

### 6. Set Up SSL with Let's Encrypt (For Production)

Replace the self-signed certificate with a Let's Encrypt certificate:

```bash
sudo apt install -y certbot
sudo certbot certonly --standalone -d yourdomain.com -d www.yourdomain.com
```

Copy the certificates to the Docker volume:

```bash
sudo cp /etc/letsencrypt/live/yourdomain.com/fullchain.pem /var/www/mhris/docker/nginx/ssl/mhris.crt
sudo cp /etc/letsencrypt/live/yourdomain.com/privkey.pem /var/www/mhris/docker/nginx/ssl/mhris.key
```

Restart Nginx:

```bash
sudo docker-compose restart nginx
```

### 7. Set Up Automatic Backups

Create a backup script:

```bash
sudo nano /usr/local/bin/backup-mhris.sh
```

Add the following content:

```bash
#!/bin/bash
TIMESTAMP=$(date +%Y%m%d%H%M%S)
BACKUP_DIR="/var/backups/mhris"
mkdir -p $BACKUP_DIR/db
mkdir -p $BACKUP_DIR/code

# Backup database
docker exec mhris_db mysqldump -u root -p$(grep DB_ROOT_PASSWORD /var/www/mhris/.env.production | cut -d= -f2) --all-databases > $BACKUP_DIR/db/backup_$TIMESTAMP.sql

# Backup code
tar -czf $BACKUP_DIR/code/backup_$TIMESTAMP.tar.gz -C /var/www/mhris .

# Remove backups older than 7 days
find $BACKUP_DIR -type f -name "backup_*" -mtime +7 -delete
```

Make it executable:

```bash
sudo chmod +x /usr/local/bin/backup-mhris.sh
```

Add it to crontab:

```bash
sudo crontab -e
```

Add this line for daily backups at 2 AM:

```
0 2 * * * /usr/local/bin/backup-mhris.sh
```

## Maintenance

### Monitoring Logs

```bash
cd /var/www/mhris
sudo docker-compose logs -f
```

### Updating the Application

1. Pull the latest changes:
```bash
cd /var/www/mhris
git pull origin main
```

2. Redeploy:
```bash
sudo ./deploy.sh production
```

### Database Management

To access the MySQL database directly:

```bash
sudo docker exec -it mhris_db mysql -u root -p
```

## Troubleshooting

### Container not starting

Check logs:
```bash
sudo docker-compose logs app
```

### Database connection issues

Verify environment variables and network configuration:
```bash
sudo docker exec mhris_app php artisan env
```

### Performance issues

Monitor resource usage:
```bash
sudo docker stats
```

## Security Recommendations

1. Enable UFW firewall:
```bash
sudo ufw allow 22
sudo ufw allow 80
sudo ufw allow 443
sudo ufw enable
```

2. Set up fail2ban to prevent brute force attacks
3. Regularly update your server and application
4. Use strong passwords for all services
5. Consider setting up a WAF (Web Application Firewall)

## Support

For additional support, please contact your system administrator or open an issue in the project repository.
