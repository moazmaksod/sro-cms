#!/bin/bash
# ==============================================================================
# SRO-CMS VPS Deployment & Management Script
# ==============================================================================
#
# Description:
#   Automates the installation of Docker and the deployment of SRO-CMS
#   on a clean Ubuntu VPS. This script replaces the development-focused sro.sh.
#
# Usage:
#   chmod +x deploy.sh
#   ./deploy.sh setup
#
# ==============================================================================

# Exit on error
set -e

# --- Colors for Output ---
function print_info { echo -e "\e[34m\U1F6C8 INFO: $1\e[0m"; }
function print_success { echo -e "\e[32m\U2714 SUCCESS: $1\e[0m"; }
function print_warning { echo -e "\e[33m\U26A0 WARNING: $1\e[0m"; }
function print_error { echo -e "\e[31m\U2716 ERROR: $1\e[0m"; exit 1; }

# --- Helper: Check Docker Permissions ---
# If the current user cannot access the docker socket, use sudo.
DOCKER_CMD="docker compose"
if ! docker info > /dev/null 2>&1; then
    DOCKER_CMD="sudo docker compose"
fi

# --- Helper: Run commands inside Docker ---
function docker_exec {
    $DOCKER_CMD exec -T -u www-data app "$@"
}

function docker_exec_root {
    $DOCKER_CMD exec -T -u root app "$@"
}

# ==============================================================================
#                             DEPLOYMENT FUNCTIONS
# ==============================================================================

# --- Step 0: Install Docker & Compose (If missing) ---
function install_system_dependencies() {
    print_info "Checking for Docker installation..."
    
    if ! command -v docker &> /dev/null; then
        print_warning "Docker not found. Installing official Docker Engine..."
        sudo apt-get update
        sudo apt-get install -y ca-certificates curl gnupg lsb-release
        
        sudo mkdir -p /etc/apt/keyrings
        curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg
        
        echo "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
        
        sudo apt-get update
        sudo apt-get install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin
        
        # Add current user to docker group
        sudo usermod -aG docker $USER
        print_success "Docker installed. Note: Permission changes usually require a logout/login."
        
        # Immediately update the DOCKER_CMD to use sudo for this session
        DOCKER_CMD="sudo docker compose"
    fi

    # Ensure Docker service is enabled
    sudo systemctl enable --now docker
}

# --- Step 1: Initialize Environment ---
function init_production_env() {
    if [ ! -f .env ]; then
        print_info "Initializing production .env from example..."
        cp .env.example .env
        print_warning "PLEASE EDIT THE .env FILE MANUALLY NOW TO ADD YOUR CLOUD DB SECRETS."
        print_warning "Run: nano .env"
        exit 1
    fi
}

# --- Step 2: Auto-update Dockerfile for PHP Compatibility ---
function auto_update_dockerfile() {
    local dockerfile=".docker/Dockerfile"
    if [ ! -f "$dockerfile" ]; then return; fi

    print_info "Checking Dockerfile for PHP version compatibility..."
    
    # Update from 8.2 to 8.3 if detected to support latest sqlsrv drivers
    if grep -q "FROM php:8.2-fpm" "$dockerfile"; then
        print_warning "Detected legacy PHP 8.2 in Dockerfile. Updating to PHP 8.3 for driver compatibility..."
        sed -i 's/FROM php:8.2-fpm/FROM php:8.3-fpm/g' "$dockerfile"
        print_success "Dockerfile updated to PHP 8.3."
    fi
}

# --- Step 3: Build & Start Containers ---
function start_production() {
    print_info "Ensuring host directory permissions..."
    # Ensure the current VPS user owns the directory before Docker mounts it
    sudo chown -R $USER:$USER .

    auto_update_dockerfile

    print_info "Building and starting containers in detached mode..."
    # --pull always ensures we get the latest base images (security updates)
    if ! $DOCKER_CMD up -d --build --pull always; then
        print_warning "Standard build failed. Retrying with host networking (bypassing VPS DNS issues)..."
        export DOCKER_BUILDKIT=1
        $DOCKER_CMD build --pull
        $DOCKER_CMD up -d
    fi
    print_success "Containers are running."
}

# --- Step 4: Application Setup ---
function setup_laravel() {
    print_info "Fixing container workspace and NPM permissions..."
    # Force the internal /var/www/html to be owned by www-data so composer can write
    docker_exec_root chown -R www-data:www-data /var/www/html
    docker_exec_root chmod -R 775 /var/www/html
    
    # Fix NPM cache permissions (common EACCES error)
    docker_exec_root mkdir -p /var/www/.npm
    docker_exec_root chown -R www-data:www-data /var/www/.npm

    print_info "Installing PHP dependencies (Composer)..."
    docker_exec composer install --no-dev --optimize-autoloader
    
    print_info "Installing JS dependencies and building assets..."
    docker_exec npm install
    docker_exec npm run build
    
    print_info "Finalizing Laravel configuration..."
    docker_exec php artisan key:generate --force
    docker_exec php artisan storage:link
    docker_exec php artisan config:cache
    docker_exec php artisan route:cache
    docker_exec php artisan view:cache

    # --- Database Initialization ---
    print_info "Ensuring application database exists on SQL Server..."
    # Load variables from .env to use for the sqlcmd call
    export $(grep -v '^#' .env | xargs)
    DB_PORT=${DB_PORT:-1433}

    # Connect to 'master' to create the target database if it doesn't exist
    docker_exec_root /opt/mssql-tools/bin/sqlcmd -S "${DB_HOST},${DB_PORT}" -U "${DB_USERNAME}" -P "${DB_PASSWORD}" -d master -Q "IF NOT EXISTS (SELECT * FROM sys.databases WHERE name = N'${DB_DATABASE}') CREATE DATABASE [${DB_DATABASE}];"
    print_success "Database check/creation finished."
    
    print_info "Running migrations and seeding..."
    # We use --seed to run the DatabaseSeeder automatically
    docker_exec php artisan migrate --force --seed
    
    print_success "Application is fully configured with initial data."
}

# --- Step 5: Security/Firewall Check ---
function check_firewall() {
    if command -v ufw &> /dev/null; then
        print_info "Checking UFW Firewall..."
        sudo ufw allow 80/tcp
        sudo ufw allow 443/tcp
        sudo ufw allow 8000/tcp
        print_info "UFW ports 80, 443, and 8000 allowed."
    fi
}

# --- Step 6: Configure Auto-start on Boot ---
function enable_autostart() {
    print_info "Configuring Docker to start automatically on system boot..."
    sudo systemctl enable docker.service
    sudo systemctl enable containerd.service
    print_success "System services enabled. Containers will now start on reboot."
}

# ==============================================================================
#                               COMMAND ROUTER
# ==============================================================================

case "$1" in
    setup)
        install_system_dependencies
        init_production_env
        start_production
        setup_laravel
        check_firewall
        enable_autostart
        echo ""
        print_success "---------------------------------------------------------"
        print_success " VPS DEPLOYMENT COMPLETE! "
        print_success "---------------------------------------------------------"
        ;;
    up)
        $DOCKER_CMD up -d
        ;;
    down)
        $DOCKER_CMD down
        ;;
    logs)
        $DOCKER_CMD logs -f
        ;;
    update)
        print_info "Fixing ownership for Git update..."
        sudo chown -R $USER:$USER .
        
        print_info "Updating application code..."
        # Resolve 'dubious ownership' error by adding current directory to safe.directory
        git config --global --add safe.directory $(pwd)
        git pull origin main
        
        start_production
        setup_laravel
        ;;
    seed)
        print_info "Running database seeders..."
        docker_exec php artisan db:seed --force
        print_success "Seeding complete."
        ;;
    fix-dns)
        print_info "Applying permanent Docker DNS fix..."
        echo '{"dns": ["8.8.8.8", "8.8.4.4"]}' | sudo tee /etc/docker/daemon.json
        sudo systemctl restart docker
        print_success "Docker restarted with Google DNS. Try running update again."
        ;;
    *)
        echo "Usage: $0 {setup|up|down|logs|update|seed|fix-dns}"
        echo ""
        echo "  setup  : Full first-time installation on a clean VPS."
        echo "  up     : Start existing containers."
        echo "  down   : Stop containers."
        echo "  logs   : Show real-time container logs."
        echo "  update : Pull latest code from Git and rebuild everything."
        echo "  seed   : Run only the database seeders."
        echo "  fix-dns: Force Docker to use Google DNS for resolution."
        exit 1
        ;;
esac