#!/bin/bash
set -e

# MHRIS Deployment Script for Ubuntu Server
# Usage: ./deploy.sh [production|staging]

# Configuration
APP_DIR="/var/www/mhris"
ENV_TYPE=${1:-production}
DOCKER_COMPOSE_FILE="docker-compose.yml"
BACKUP_DIR="/var/backups/mhris"
TIMESTAMP=$(date +%Y%m%d%H%M%S)

# Color codes
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Print header
echo -e "${GREEN}====================================${NC}"
echo -e "${GREEN}     MHRIS Deployment Script       ${NC}"
echo -e "${GREEN}====================================${NC}"
echo -e "${YELLOW}Environment: ${ENV_TYPE}${NC}"
echo -e "${YELLOW}Timestamp: $(date)${NC}"
echo ""

# Check if running as root
if [ "$(id -u)" != "0" ]; then
   echo -e "${RED}This script must be run as root${NC}" 
   exit 1
fi

# Check if Docker and Docker Compose are installed
if ! command -v docker &> /dev/null || ! command -v docker-compose &> /dev/null; then
    echo -e "${YELLOW}Docker or Docker Compose not found. Installing...${NC}"
    apt-get update
    apt-get install -y apt-transport-https ca-certificates curl software-properties-common
    curl -fsSL https://download.docker.com/linux/ubuntu/gpg | apt-key add -
    add-apt-repository "deb [arch=amd64] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable"
    apt-get update
    apt-get install -y docker-ce docker-compose
    systemctl enable docker
    systemctl start docker
    echo -e "${GREEN}Docker and Docker Compose installed successfully${NC}"
fi

# Create necessary directories
mkdir -p ${APP_DIR}
mkdir -p ${BACKUP_DIR}
mkdir -p ${APP_DIR}/docker/nginx/ssl
mkdir -p ${APP_DIR}/docker/nginx/logs
mkdir -p ${APP_DIR}/docker/mysql/init
mkdir -p ${APP_DIR}/storage/logs
mkdir -p ${APP_DIR}/storage/app/public
mkdir -p ${APP_DIR}/storage/framework/cache
mkdir -p ${APP_DIR}/storage/framework/sessions
mkdir -p ${APP_DIR}/storage/framework/views

# Backup existing database if container exists and is running
if docker ps | grep -q "mhris_db"; then
    echo -e "${YELLOW}Backing up database...${NC}"
    mkdir -p ${BACKUP_DIR}/db
    docker exec mhris_db mysqldump -u root -p"$(grep DB_ROOT_PASSWORD ${APP_DIR}/.env.${ENV_TYPE} | cut -d= -f2)" --all-databases > ${BACKUP_DIR}/db/backup_${TIMESTAMP}.sql
    echo -e "${GREEN}Database backup created at ${BACKUP_DIR}/db/backup_${TIMESTAMP}.sql${NC}"
fi

# Backup code if it exists
if [ -d "${APP_DIR}" ] && [ "$(ls -A ${APP_DIR})" ]; then
    echo -e "${YELLOW}Backing up code...${NC}"
    mkdir -p ${BACKUP_DIR}/code
    tar -czf ${BACKUP_DIR}/code/backup_${TIMESTAMP}.tar.gz -C ${APP_DIR} .
    echo -e "${GREEN}Code backup created at ${BACKUP_DIR}/code/backup_${TIMESTAMP}.tar.gz${NC}"
fi

# Copy application files to server
echo -e "${YELLOW}Copying application files...${NC}"
cp -R . ${APP_DIR}/
cd ${APP_DIR}

# Generate SSL certificate if it doesn't exist
if [ ! -f "${APP_DIR}/docker/nginx/ssl/mhris.crt" ]; then
    echo -e "${YELLOW}Generating self-signed SSL certificate...${NC}"
    # For production, you should replace this with proper SSL certificates
    openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
      -keyout ${APP_DIR}/docker/nginx/ssl/mhris.key \
      -out ${APP_DIR}/docker/nginx/ssl/mhris.crt \
      -subj "/C=US/ST=State/L=City/O=Organization/CN=yourdomain.com"
    echo -e "${GREEN}SSL certificate generated${NC}"
    echo -e "${YELLOW}WARNING: In production, replace self-signed certificate with a proper SSL certificate!${NC}"
fi

# Prepare environment file
if [ ! -f "${APP_DIR}/.env.${ENV_TYPE}" ]; then
    if [ -f "${APP_DIR}/docker/.env.${ENV_TYPE}.example" ]; then
        echo -e "${YELLOW}Creating .env.${ENV_TYPE} file from example...${NC}"
        cp ${APP_DIR}/docker/.env.${ENV_TYPE}.example ${APP_DIR}/.env.${ENV_TYPE}
        echo -e "${RED}IMPORTANT: Edit .env.${ENV_TYPE} with actual production values!${NC}"
        echo -e "${RED}Run 'nano ${APP_DIR}/.env.${ENV_TYPE}' to edit the file.${NC}"
        exit 1
    else
        echo -e "${RED}No .env.${ENV_TYPE}.example file found!${NC}"
        exit 1
    fi
fi

# Set proper permissions
echo -e "${YELLOW}Setting file permissions...${NC}"
chown -R www-data:www-data ${APP_DIR}
chmod -R 755 ${APP_DIR}
chmod -R 775 ${APP_DIR}/storage
chmod -R 775 ${APP_DIR}/bootstrap/cache
chmod +x ${APP_DIR}/docker/scripts/entrypoint.sh

# Stop and remove existing containers
echo -e "${YELLOW}Stopping existing containers...${NC}"
docker-compose -f ${DOCKER_COMPOSE_FILE} down || true

# Build and start Docker containers
echo -e "${YELLOW}Building and starting containers...${NC}"
docker-compose -f ${DOCKER_COMPOSE_FILE} build
docker-compose -f ${DOCKER_COMPOSE_FILE} up -d

# Wait for containers to be ready
echo -e "${YELLOW}Waiting for containers to be ready...${NC}"
sleep 10

# Show container status
echo -e "${GREEN}Container status:${NC}"
docker-compose -f ${DOCKER_COMPOSE_FILE} ps

# Final instructions
echo -e "${GREEN}====================================${NC}"
echo -e "${GREEN}     Deployment Completed!         ${NC}"
echo -e "${GREEN}====================================${NC}"
echo -e "${YELLOW}Your MHRIS application should now be accessible at:${NC}"
echo -e "${GREEN}https://yourdomain.com${NC}"
echo ""
echo -e "${YELLOW}Important notes:${NC}"
echo -e "1. Ensure your domain is pointed to this server's IP address"
echo -e "2. Replace the self-signed SSL certificate with a proper one (Let's Encrypt)"
echo -e "3. Ensure your firewall allows traffic on ports 80 and 443"
echo -e "4. Set up regular backups using cron jobs"
echo -e "5. Monitor your application logs in ${APP_DIR}/docker/nginx/logs"
echo ""
echo -e "${YELLOW}To view logs:${NC}"
echo -e "${GREEN}docker-compose -f ${DOCKER_COMPOSE_FILE} logs -f${NC}"
echo "" 