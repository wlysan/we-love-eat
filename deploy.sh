#!/bin/bash
# Simple deployment script for NutriMenu

# Configure these variables for your environment
REMOTE_USER="username"
REMOTE_HOST="example.com"
REMOTE_PATH="/var/www/nutrimenu"
APP_URL="https://nutrimenu.example.com/"

# Colors for better readability
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${YELLOW}Starting deployment of NutriMenu...${NC}"

# Create a temporary deployment directory
echo "Creating temporary deployment directory..."
DEPLOY_DIR=$(mktemp -d)
cp -r ./* $DEPLOY_DIR/

# Update application.php with production settings
echo "Updating configuration for production..."
cat > $DEPLOY_DIR/src/config/application.php << EOF
<?php
/**
 * Application deployment configuration
 * This file is included by config.php and overrides default settings
 */

// The below settings should be modified for each deployment environment
define('APP_URL', '$APP_URL');

// Security settings
define('ENABLE_DEBUG', false);
define('SECURE_COOKIES', true);
define('SESSION_LIFETIME', 86400); // 24 hours in seconds

// File upload settings
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif']);

// Email settings
define('SMTP_HOST', '');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', '');
define('SMTP_PASSWORD', '');
define('SMTP_FROM', 'no-reply@example.com');
define('SMTP_FROM_NAME', 'NutriMenu');

// Error handling
if (ENABLE_DEBUG) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_WARNING);
}
EOF

# Create database directory if it doesn't exist
mkdir -p $DEPLOY_DIR/database

# Set correct permissions
echo "Setting file permissions..."
find $DEPLOY_DIR -type d -exec chmod 755 {} \;
find $DEPLOY_DIR -type f -exec chmod 644 {} \;
chmod 777 $DEPLOY_DIR/database
chmod 777 $DEPLOY_DIR/public/uploads

# Remove development and temporary files
echo "Removing development files..."
rm -f $DEPLOY_DIR/deploy.sh
rm -rf $DEPLOY_DIR/.git*

# Create a deployment package
echo "Creating deployment package..."
PACKAGE_FILE="nutrimenu_$(date +%Y%m%d%H%M%S).tar.gz"
tar -czf $PACKAGE_FILE -C $DEPLOY_DIR .
rm -rf $DEPLOY_DIR

# Deploy to server
echo -e "${YELLOW}Deploying to $REMOTE_HOST...${NC}"
if [ -z "$REMOTE_HOST" ] || [ "$REMOTE_HOST" = "example.com" ]; then
    echo -e "${RED}Deployment aborted. Please configure REMOTE_HOST in deploy.sh${NC}"
    exit 1
fi

echo "Creating remote directories if they don't exist..."
ssh $REMOTE_USER@$REMOTE_HOST "mkdir -p $REMOTE_PATH"

echo "Uploading package..."
scp $PACKAGE_FILE $REMOTE_USER@$REMOTE_HOST:/tmp/

echo "Extracting package on server..."
ssh $REMOTE_USER@$REMOTE_HOST "tar -xzf /tmp/$PACKAGE_FILE -C $REMOTE_PATH && rm /tmp/$PACKAGE_FILE && chmod -R 755 $REMOTE_PATH && chmod 777 $REMOTE_PATH/database $REMOTE_PATH/public/uploads"

# Clean up
rm $PACKAGE_FILE

echo -e "${GREEN}Deployment completed successfully!${NC}"
echo "The application is now available at $APP_URL"