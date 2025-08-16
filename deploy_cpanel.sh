#!/bin/bash

# 🚀 **cPanel Deployment Script for Laravel Application**
# Usage: ./deploy_cpanel.sh

echo "🚀 Starting cPanel Deployment..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check if running in Laravel directory
if [ ! -f "artisan" ]; then
    echo -e "${RED}❌ Error: Please run this script from Laravel root directory${NC}"
    exit 1
fi

echo -e "${YELLOW}📋 Pre-deployment checklist...${NC}"

# 1. Install production dependencies
echo "📦 Installing production dependencies..."
composer install --optimize-autoloader --no-dev --no-interaction

if [ $? -ne 0 ]; then
    echo -e "${RED}❌ Composer install failed${NC}"
    exit 1
fi

# 2. Build assets (if using Vite/Webpack)
if [ -f "package.json" ]; then
    echo "🔨 Building assets..."
    npm ci --production
    npm run build
fi

# 3. Generate application key
echo "🔑 Generating application key..."
php artisan key:generate --force

# 4. Clear all caches
echo "🧹 Clearing caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 5. Cache for production
echo "⚡ Caching for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Optimize
echo "🚀 Optimizing application..."
php artisan optimize

# 7. Set proper permissions
echo "🔒 Setting file permissions..."
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod 644 .env

# 8. Create deployment package
echo "📦 Creating deployment package..."
DEPLOY_DIR="laravel_deploy_$(date +%Y%m%d_%H%M%S)"
mkdir -p ../$DEPLOY_DIR

# Copy necessary files
cp -r app ../$DEPLOY_DIR/
cp -r bootstrap ../$DEPLOY_DIR/
cp -r config ../$DEPLOY_DIR/
cp -r database ../$DEPLOY_DIR/
cp -r resources ../$DEPLOY_DIR/
cp -r routes ../$DEPLOY_DIR/
cp -r storage ../$DEPLOY_DIR/
cp -r vendor ../$DEPLOY_DIR/
cp .env ../$DEPLOY_DIR/
cp artisan ../$DEPLOY_DIR/
cp composer.json ../$DEPLOY_DIR/
cp composer.lock ../$DEPLOY_DIR/

# Create public directory
mkdir -p ../${DEPLOY_DIR}_public
cp -r public/* ../${DEPLOY_DIR}_public/

# Create .htaccess for public
cat > ../${DEPLOY_DIR}_public/.htaccess << 'EOF'
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Handle X-XSRF-Token Header
    RewriteCond %{HTTP:x-xsrf-token} .
    RewriteRule .* - [E=HTTP_X_XSRF_TOKEN:%{HTTP:X-XSRF-Token}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>

# cPanel Compatibility
<IfModule mod_php.c>
    php_value upload_max_filesize 64M
    php_value post_max_size 64M
    php_value max_execution_time 300
    php_value max_input_vars 3000
    php_value memory_limit 256M
</IfModule>

# Security Headers
<IfModule mod_headers.c>
    Header always set X-Content-Type-Options nosniff
    Header always set X-Frame-Options DENY
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
</IfModule>

# Error Handling
ErrorDocument 500 /index.php
ErrorDocument 404 /index.php
EOF

# Create robots.txt
cat > ../${DEPLOY_DIR}_public/robots.txt << 'EOF'
User-agent: *
Disallow: /storage/
Disallow: /bootstrap/
Disallow: /vendor/
Disallow: /app/
Disallow: /config/
Disallow: /database/
Disallow: /resources/
Disallow: /routes/
Disallow: /tests/
Disallow: /composer.json
Disallow: /composer.lock
Disallow: /package.json
Disallow: /package-lock.json
Disallow: /artisan
Disallow: /webpack.mix.js
Disallow: /vite.config.js
Disallow: /tailwind.config.js
Disallow: /postcss.config.js

# Allow access to public assets
Allow: /css/
Allow: /js/
Allow: /images/
Allow: /favicon.ico
EOF

# Create web.config for IIS
cat > ../${DEPLOY_DIR}_public/web.config << 'EOF'
<?xml version="1.0" encoding="UTF-8"?>
<configuration>
    <system.webServer>
        <rewrite>
            <rules>
                <rule name="Imported Rule 1" stopProcessing="true">
                    <match url="^(.*)/$" ignoreCase="false" />
                    <conditions logicalGrouping="MatchAll">
                        <add input="{REQUEST_FILENAME}" matchType="IsDirectory" ignoreCase="false" negate="true" />
                    </conditions>
                    <action type="Redirect" redirectType="Permanent" url="/{R:1}" />
                </rule>
                <rule name="Imported Rule 2" stopProcessing="true">
                    <match url="^" ignoreCase="false" />
                    <conditions logicalGrouping="MatchAll">
                        <add input="{REQUEST_FILENAME}" matchType="IsDirectory" ignoreCase="false" negate="true" />
                        <add input="{REQUEST_FILENAME}" matchType="IsFile" ignoreCase="false" negate="true" />
                    </conditions>
                    <action type="Rewrite" url="index.php" />
                </rule>
            </rules>
        </rewrite>
        <httpErrors errorMode="Custom" defaultResponseMode="ExecuteURL">
            <remove statusCode="404" subStatusCode="-1" />
            <error statusCode="404" path="/index.php" responseMode="ExecuteURL" />
            <remove statusCode="500" subStatusCode="-1" />
            <error statusCode="500" path="/index.php" responseMode="ExecuteURL" />
        </httpErrors>
        <defaultDocument>
            <files>
                <clear />
                <add value="index.php" />
                <add value="index.html" />
                <add value="index.htm" />
            </files>
        </defaultDocument>
    </system.webServer>
</configuration>
EOF

# Update index.php for cPanel
sed -i '' 's|__DIR__."/../vendor/autoload.php"|__DIR__."/../laravel_app/vendor/autoload.php"|g' ../${DEPLOY_DIR}_public/index.php
sed -i '' 's|__DIR__."/../bootstrap/app.php"|__DIR__."/../laravel_app/bootstrap/app.php"|g' ../${DEPLOY_DIR}_public/index.php

# Create deployment instructions
cat > ../${DEPLOY_DIR}_INSTRUCTIONS.md << 'EOF'
# 🚀 **cPanel Deployment Instructions**

## 📁 **File Structure:**
```
public_html/          # Document Root (Upload ${DEPLOY_DIR}_public contents here)
laravel_app/          # Outside Document Root (Upload ${DEPLOY_DIR} contents here)
```

## 🔧 **Steps:**
1. Upload `${DEPLOY_DIR}_public/` contents to `public_html/`
2. Upload `${DEPLOY_DIR}/` contents to `laravel_app/` (outside document root)
3. Update `.env` file with your cPanel database credentials
4. Run `php artisan migrate` in laravel_app directory
5. Set proper file permissions (755 for dirs, 644 for files)

## ⚠️ **Important:**
- Never put `.env` file in public_html
- Ensure database credentials are correct
- Check error logs if issues occur
EOF

# Create deployment package
cd ..
tar -czf ${DEPLOY_DIR}_package.tar.gz $DEPLOY_DIR ${DEPLOY_DIR}_public ${DEPLOY_DIR}_INSTRUCTIONS.md

echo -e "${GREEN}✅ Deployment package created successfully!${NC}"
echo -e "${YELLOW}📦 Package: ${DEPLOY_DIR}_package.tar.gz${NC}"
echo -e "${YELLOW}📁 Laravel App: ${DEPLOY_DIR}/${NC}"
echo -e "${YELLOW}📁 Public Files: ${DEPLOY_DIR}_public/${NC}"
echo -e "${YELLOW}📋 Instructions: ${DEPLOY_DIR}_INSTRUCTIONS.md${NC}"

echo -e "${GREEN}🚀 Ready for cPanel deployment!${NC}"
echo -e "${YELLOW}💡 Upload public files to document root and Laravel files outside it${NC}" 