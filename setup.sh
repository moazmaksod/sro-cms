#!/bin/bash
# ==============================================================================
# SRO-CMS Environment Management Script
# ==============================================================================
#
# Description:
#   This script provides a set of commands to automate the setup and daily
#   management of the SRO-CMS Laravel development environment using Docker.
#   It is designed to connect to an external (host) MS SQL Server.
#
# Requirements:
#   - WSL 2 with a Linux distribution (e.g., Ubuntu)
#   - Docker Desktop installed and integrated with WSL 2
#   - Git command-line tool
#   - A correctly configured '.env' file in the project root
#
# Author: Your Name
# Version: 2.3
#
# ==============================================================================

# --- Script Setup ---
# Exit immediately if a command exits with a non-zero status.
set -e

# --- Helper Functions for Colored Output ---
# Provides standardized, color-coded feedback to the user.
function print_info { echo -e "\e[34m\U1F6C8 INFO: $1\e[0m"; }
function print_success { echo -e "\e[32m\U2714 SUCCESS: $1\e[0m"; }
function print_warning { echo -e "\e[33m\U26A0 WARNING: $1\e[0m"; }
function print_error { echo -e "\e[31m\U2716 ERROR: $1\e[0m"; exit 1; }

# --- Docker Command Helpers ---
# Simplifies running commands inside the application container.

# Runs a command as the non-privileged 'www-data' user (for Composer, NPM, Artisan).
function docker_exec {
    docker-compose exec -T -u www-data app "$@"
}

# Runs a command as the privileged 'root' user (for package installation, permissions).
function docker_exec_root {
    docker-compose exec -T -u root app "$@"
}


# ==============================================================================
#                             MAIN SCRIPT FUNCTIONS
# ==============================================================================

# --- Part 0: Prerequisite Checks ---
# Objective: Verify that all required command-line tools are installed.
function check_prerequisites {
    print_info "Checking for prerequisites..."

    # Check for Git, and install it if missing.
    if ! command -v git &> /dev/null; then
        print_warning "Git is not installed. Attempting to install it now..."
        # The user will be prompted for their sudo password here if not recently entered.
        sudo apt-get update && sudo apt-get install -y git
        print_success "Git has been successfully installed."
    fi

    # Check for Docker and Docker Compose. These must be installed manually on Windows.
    if ! command -v docker &> /dev/null || ! command -v docker-compose &> /dev/null; then
        print_error "Docker or Docker Compose is not found. Please ensure Docker Desktop is installed and configured for WSL."
    fi

    print_success "All prerequisites are met."
}

# --- Part 1: Interactive Environment Initialization ---
# Objective: Create and interactively configure the .env file if one doesn't exist.
function init_env {
    if [ -f .env ]; then
        print_warning ".env file already exists. Skipping interactive setup."
        print_info "Using existing .env file for the setup."
        return
    fi

    print_info "Creating and configuring a new .env file..."
    cp .env.example .env

    # Helper function to reliably update a key-value pair in the .env file.
    # It uses a non-standard delimiter to avoid conflicts with special characters in values.
    function update_env {
        local key=$1
        local value=$2
        # Escape special characters for sed: backslash, forward slash, ampersand
        local escaped_value=$(printf '%s\n' "$value" | sed -e 's/[\/&]/\\&/g')
        sed -i -e "s/^\($key\s*=\s*\).*\$/\1$escaped_value/" .env
    }

    # --- Start Interactive Configuration ---
    print_info "Please provide the following details to configure your environment:"
    echo "---------------------------------------------------------"

    # General Application Settings
    read -p "Enter application name [default: SRO-CMS]: " APP_NAME
    update_env "APP_NAME" "${APP_NAME:-SRO-CMS}"

    read -p "Enter environment (local/production) [default: local]: " APP_ENV
    APP_ENV=${APP_ENV:-local}
    update_env "APP_ENV" "$APP_ENV"

    if [[ "$APP_ENV" == "local" ]]; then
        DEFAULT_DEBUG="true"
        DEFAULT_DEBUGBAR="true"
    else
        DEFAULT_DEBUG="false"
        DEFAULT_DEBUGBAR="false"
    fi

    read -p "Enable APP_DEBUG (true/false) [default: $DEFAULT_DEBUG]: " APP_DEBUG
    update_env "APP_DEBUG" "${APP_DEBUG:-$DEFAULT_DEBUG}"

    read -p "Enable DEBUGBAR_ENABLED (true/false) [default: $DEFAULT_DEBUGBAR]: " DEBUGBAR_ENABLED
    update_env "DEBUGBAR_ENABLED" "${DEBUGBAR_ENABLED:-$DEFAULT_DEBUGBAR}"

    # Silkroad Specific Settings
    while true; do
        read -p "Enter Silkroad server version (iSRO/vSRO) [default: vSRO]: " SRO_VERSION
        SRO_VERSION=${SRO_VERSION:-vSRO}
        if [[ "$SRO_VERSION" == "iSRO" || "$SRO_VERSION" == "vSRO" ]]; then
            break
        else
            print_warning "Invalid input. Please enter 'iSRO' or 'vSRO'."
        fi
    done
    update_env "SRO_VERSION" "$SRO_VERSION"

    # Database Settings
    echo "---------------------------------------------------------"
    print_info "Now, let's configure the database connection."
    print_info "(Hint: For a local DB on Windows, use 'host.docker.internal')"

    read -p "Enter DB Host/IP [default: host.docker.internal]: " DB_HOST
    update_env "DB_HOST" "${DB_HOST:-host.docker.internal}"

    read -p "Enter DB Port [default: 1433]: " DB_PORT
    update_env "DB_PORT" "${DB_PORT:-1433}"

    read -p "Enter Website DB Name [default: ISRO_CMS]: " DB_DATABASE
    update_env "DB_DATABASE" "${DB_DATABASE:-ISRO_CMS}"

    # Conditional database names based on SRO_VERSION
    if [[ "$SRO_VERSION" == "iSRO" ]]; then
        read -p "Enter Portal DB Name [default: GB_JoymaxPortal]: " DB_DATABASE_PORTAL
        update_env "DB_DATABASE_PORTAL" "${DB_DATABASE_PORTAL:-GB_JoymaxPortal}"

        read -p "Enter Account DB Name [default: SILKROAD_R_ACCOUNT]: " DB_DATABASE_ACCOUNT
        update_env "DB_DATABASE_ACCOUNT" "${DB_DATABASE_ACCOUNT:-SILKROAD_R_ACCOUNT}"

        read -p "Enter Shard DB Name [default: SILKROAD_R_SHARD]: " DB_DATABASE_SHARD
        update_env "DB_DATABASE_SHARD" "${DB_DATABASE_SHARD:-SILKROAD_R_SHARD}"

        read -p "Enter Log DB Name [default: SILKROAD_R_SHARD_LOG]: " DB_DATABASE_LOG
        update_env "DB_DATABASE_LOG" "${DB_DATABASE_LOG:-SILKROAD_R_SHARD_LOG}"
    else # vSRO
        # For vSRO, the portal DB is not used, so we set it to empty.
        update_env "DB_DATABASE_PORTAL" ""

        read -p "Enter Account DB Name [default: SRO_VT_ACCOUNT]: " DB_DATABASE_ACCOUNT
        update_env "DB_DATABASE_ACCOUNT" "${DB_DATABASE_ACCOUNT:-SRO_VT_ACCOUNT}"

        read -p "Enter Shard DB Name [default: SRO_VT_SHARD]: " DB_DATABASE_SHARD
        update_env "DB_DATABASE_SHARD" "${DB_DATABASE_SHARD:-SRO_VT_SHARD}"

        read -p "Enter Log DB Name [default: SRO_VT_SHARDLOG]: " DB_DATABASE_LOG
        update_env "DB_DATABASE_LOG" "${DB_DATABASE_LOG:-SRO_VT_SHARDLOG}"
    fi

    read -p "Enter DB Username [default: sa]: " DB_USERNAME
    update_env "DB_USERNAME" "${DB_USERNAME:-sa}"

    read -sp "Enter DB Password: " DB_PASSWORD
    echo "" # Add a newline after the silent password prompt
    update_env "DB_PASSWORD" "$DB_PASSWORD"

    print_success ".env file has been configured successfully."
    echo "---------------------------------------------------------"
}

# --- Part 2: Infrastructure Management ---
# Objective: Start the Docker containers defined in docker-compose.yml.
function start_env {
    print_info "Starting Docker containers... (This may take a few minutes on the first run)"
    docker-compose up -d --build
    print_success "Containers are up and running."
}

# Objective: Stop and remove the Docker containers.
function stop_env {
    print_info "Stopping Docker containers..."
    docker-compose down "$@"
    print_success "Containers have been stopped."
}

# --- Part 3: Dependency Installation ---
# Objective: Install all Composer and NPM packages.
function install_deps {
    print_info "Ensuring correct permissions for all project directories..."
    # Grant ownership of the home directory to the 'www-data' user to prevent NPM cache errors.
    docker_exec_root chown -R www-data:www-data /var/www
    # Grant broad read/write/execute permissions to the project directory to prevent file access errors from the container.
    docker_exec_root chmod -R ugo+rw /var/www/html

    print_info "Installing Composer (PHP) dependencies..."
    docker_exec composer install --no-interaction --no-progress --prefer-dist
    print_success "Composer dependencies installed."

    print_info "Installing NPM (JavaScript) dependencies..."
    docker_exec npm install
    print_success "NPM dependencies installed."
}

# --- Part 4: Application Finalization ---
# Objective: Run all necessary commands to make the Laravel application operational.
function finalize_app {
    # Step 4.1: Create the database on the external server
    print_info "Connecting to external SQL server to ensure database exists..."
    if [ ! -f .env ]; then
        print_error ".env file not found. Cannot read database credentials."
    fi

    # Load all variables from the .env file into the current shell session.
    export $(grep -v '^#' .env | xargs)

    # Validate that essential DB variables are set in the .env file.
    if [ -z "${DB_DATABASE}" ] || [ -z "${DB_HOST}" ] || [ -z "${DB_USERNAME}" ] || [ -z "${DB_PASSWORD}" ]; then
        print_error "One or more required database variables (DB_DATABASE, DB_HOST, DB_USERNAME, DB_PASSWORD) are missing from your .env file."
    fi

    # Use the standard MS SQL port 1433 if not explicitly set in the .env file.
    DB_PORT=${DB_PORT:-1433}

    print_info "Checking for database '${DB_DATABASE}' on host '${DB_HOST}'..."
    # This command connects to the 'master' database and runs a SQL query to create the application database if it doesn't already exist.
    docker_exec_root /opt/mssql-tools/bin/sqlcmd -S "${DB_HOST},${DB_PORT}" -U "${DB_USERNAME}" -P "${DB_PASSWORD}" -d master -Q "IF NOT EXISTS (SELECT * FROM sys.databases WHERE name = N'${DB_DATABASE}') CREATE DATABASE [${DB_DATABASE}];"
    print_success "Database check/creation task complete."

    # Step 4.2: Run core Laravel setup commands
    print_info "Running final Laravel setup commands..."
    docker_exec php artisan config:clear
    docker_exec php artisan key:generate
    docker_exec php artisan storage:link

    # Step 4.3: Set up the database schema and initial data
    print_info "Running database migrations to build the schema..."
    docker_exec php artisan migrate --force
    print_success "Migrations completed."

    print_info "Seeding the database with initial data..."
    docker_exec php artisan db:seed --force
    print_success "Database seeding completed."

    # Step 4.4: Compile frontend assets for production
    print_info "Compiling frontend assets (CSS/JS)..."
    docker_exec npm run build
    print_success "Frontend assets compiled."
}

# ==============================================================================
#                               COMMAND ROUTER
# ==============================================================================
# This section interprets the command you provide (e.g., 'setup', 'up')
# and calls the appropriate function.

case "$1" in
    # --- Full Setup Command ---
    setup)
        check_prerequisites
        init_env
        start_env
        install_deps
        finalize_app
        echo ""
        print_success "---------------------------------------------------------"
        print_success " SRO-CMS Environment Setup Complete! "
        print_success "---------------------------------------------------------"
        print_info "Your application is now running at: http://localhost:8000"
        ;;

    # --- Daily Development Commands ---
    up)
        start_env
        ;;
    down)
        stop_env
        ;;
    install)
        install_deps
        ;;
    ssh)
        docker-compose exec -u www-data app bash
        ;;

    # --- Utility Commands ---
    clean)
        print_warning "This will stop containers and delete the dependency volumes (vendor, node_modules)."
        read -p "Are you sure? (y/n) " -n 1 -r
        echo
        if [[ $REPLY =~ ^[Yy]$ ]]; then
            # The '-v' flag tells docker-compose to remove named volumes.
            stop_env "-v"
        fi
        ;;
    artisan)
        shift # Removes 'artisan' from the list of arguments.
        docker_exec php artisan "$@" # Passes all subsequent arguments to the artisan command.
        ;;
    clear)
        print_info "Clearing all Laravel caches (config, route, view, etc.)..."
        docker_exec php artisan optimize:clear
        print_success "All caches cleared."
        ;;

    # --- Help and Default Case ---
    *)
        echo "Usage: $0 {command}"
        echo ""
        echo "Available Commands:"
        echo "  setup          : Runs the full first-time setup for the project."
        echo "  up             : Starts the Docker containers."
        echo "  down           : Stops the Docker containers."
        echo "  install        : Installs/updates composer and npm dependencies."
        echo "  clean          : Stops containers and removes dependency volumes."
        echo "  artisan [cmd]  : Runs an artisan command inside the app container."
        echo "  clear          : Clears all Laravel caches (config, route, view, etc.)."
        echo "  ssh            : Opens a bash shell inside the app container."
        exit 1
        ;;
esac

