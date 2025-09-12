# 🚀 **DEPLOYMENT & MAINTENANCE GUIDE**

## **📋 OVERVIEW**

Panduan lengkap untuk deployment, monitoring, dan maintenance sistem Naeelah Firm dalam production environment.

---

## **🏗️ PRODUCTION DEPLOYMENT**

### **1. Server Requirements**

#### **Minimum System Requirements**
```bash
# Server Specifications
- CPU: 2 cores minimum, 4 cores recommended
- RAM: 4GB minimum, 8GB recommended
- Storage: 50GB SSD minimum, 100GB recommended
- PHP: 8.2 or higher
- MySQL: 8.0 or higher
- Nginx/Apache: Latest stable version
- Node.js: 18.x or higher (for asset compilation)
- Composer: 2.x
```

#### **PHP Extensions Required**
```bash
# Essential PHP Extensions
php8.2-fpm
php8.2-mysql
php8.2-xml
php8.2-mbstring
php8.2-curl
php8.2-zip
php8.2-gd
php8.2-intl
php8.2-bcmath
php8.2-redis (optional, for caching)
```

### **2. Environment Configuration**

#### **Production .env Template**
```env
# Application
APP_NAME="Naeelah Firm"
APP_ENV=production
APP_KEY=base64:your-32-character-secret-key
APP_DEBUG=false
APP_URL=https://your-domain.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=naeelah_firm_prod
DB_USERNAME=naeelah_user
DB_PASSWORD=secure_password_here

# Cache & Session
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@your-domain.com
MAIL_FROM_NAME="Naeelah Firm"

# External APIs
WEATHER_API_KEY=your-tomorrow-io-api-key
WEATHER_API_PROVIDER=tomorrow.io

# Security
SPATIE_PERMISSION_TEAMS=true
SESSION_LIFETIME=120
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=strict

# Logging
LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

# File Storage
FILESYSTEM_DISK=public
```

### **3. Deployment Script**

#### **Automated Deployment Script**
```bash
#!/bin/bash
# deploy.sh - Production Deployment Script

set -e

echo "🚀 Starting Naeelah Firm Deployment..."

# Configuration
PROJECT_DIR="/var/www/naeelah-firm"
BACKUP_DIR="/var/backups/naeelah-firm"
PHP_VERSION="8.2"

# Create backup
echo "📦 Creating backup..."
mkdir -p $BACKUP_DIR
tar -czf $BACKUP_DIR/backup-$(date +%Y%m%d-%H%M%S).tar.gz $PROJECT_DIR

# Pull latest code
echo "📥 Pulling latest code..."
cd $PROJECT_DIR
git pull origin main

# Install/Update dependencies
echo "📚 Installing dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction
npm ci --production

# Build assets
echo "🎨 Building assets..."
npm run build

# Clear and optimize caches
echo "🧹 Optimizing application..."
php artisan down --message="System maintenance in progress"

php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force

# Optimize for production
echo "⚡ Optimizing for production..."
php artisan optimize
php artisan storage:link

# Set proper permissions
echo "🔐 Setting permissions..."
chown -R www-data:www-data $PROJECT_DIR
chmod -R 755 $PROJECT_DIR
chmod -R 775 $PROJECT_DIR/storage
chmod -R 775 $PROJECT_DIR/bootstrap/cache

# Restart services
echo "🔄 Restarting services..."
systemctl reload nginx
systemctl restart php$PHP_VERSION-fpm

# Bring application back up
php artisan up

echo "✅ Deployment completed successfully!"

# Health check
echo "🏥 Running health check..."
curl -f http://localhost/health || echo "⚠️ Health check failed"

echo "🎉 Naeelah Firm is now live!"
```

### **4. Nginx Configuration**

#### **Production Nginx Config**
```nginx
# /etc/nginx/sites-available/naeelah-firm
server {
    listen 80;
    server_name your-domain.com www.your-domain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name your-domain.com www.your-domain.com;
    root /var/www/naeelah-firm/public;
    index index.php index.html;

    # SSL Configuration
    ssl_certificate /etc/letsencrypt/live/your-domain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/your-domain.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-RSA-AES256-GCM-SHA512:DHE-RSA-AES256-GCM-SHA512;
    ssl_prefer_server_ciphers off;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'" always;

    # Gzip Compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_proxied expired no-cache no-store private auth;
    gzip_types text/plain text/css text/xml text/javascript application/x-javascript application/xml+rss;

    # File Upload Limits
    client_max_body_size 100M;

    # Static Files Caching
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|pdf)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }

    # Laravel Application
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # PHP Processing
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
        
        # Security
        fastcgi_param HTTP_PROXY "";
        fastcgi_read_timeout 300;
        fastcgi_buffer_size 128k;
        fastcgi_buffers 4 256k;
        fastcgi_busy_buffers_size 256k;
    }

    # Deny access to sensitive files
    location ~ /\. {
        deny all;
    }

    location ~ /\.env {
        deny all;
    }

    # API Rate Limiting
    location /api/ {
        limit_req zone=api burst=20 nodelay;
        try_files $uri $uri/ /index.php?$query_string;
    }

    # Logging
    access_log /var/log/nginx/naeelah-firm.access.log;
    error_log /var/log/nginx/naeelah-firm.error.log;
}

# Rate Limiting Configuration
http {
    limit_req_zone $binary_remote_addr zone=api:10m rate=10r/s;
}
```

---

## **📊 MONITORING & LOGGING**

### **1. Application Monitoring**

#### **Health Check Endpoint**
```php
// routes/web.php
Route::get('/health', [HealthController::class, 'check'])
    ->name('health.check')
    ->middleware('throttle:60,1');

// app/Http/Controllers/HealthController.php
class HealthController extends Controller
{
    public function check()
    {
        $checks = [
            'app' => $this->checkApplication(),
            'database' => $this->checkDatabase(),
            'cache' => $this->checkCache(),
            'storage' => $this->checkStorage(),
            'queue' => $this->checkQueue(),
            'external_apis' => $this->checkExternalApis()
        ];

        $status = collect($checks)->every(fn($check) => $check['status'] === 'ok') ? 'healthy' : 'unhealthy';

        return response()->json([
            'status' => $status,
            'timestamp' => now()->toISOString(),
            'checks' => $checks,
            'version' => config('app.version', '1.0.0'),
            'environment' => app()->environment()
        ], $status === 'healthy' ? 200 : 503);
    }

    private function checkApplication()
    {
        return [
            'status' => 'ok',
            'message' => 'Application is running',
            'uptime' => $this->getUptime(),
            'memory_usage' => round(memory_get_usage(true) / 1024 / 1024, 2) . 'MB'
        ];
    }

    private function checkDatabase()
    {
        try {
            $startTime = microtime(true);
            DB::connection()->getPdo();
            $responseTime = round((microtime(true) - $startTime) * 1000, 2);

            return [
                'status' => 'ok',
                'message' => 'Database connection successful',
                'response_time' => $responseTime . 'ms'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Database connection failed: ' . $e->getMessage()
            ];
        }
    }
}
```

### **2. Log Management**

#### **Custom Log Channels**
```php
// config/logging.php
'channels' => [
    'stack' => [
        'driver' => 'stack',
        'channels' => ['single', 'slack'],
        'ignore_exceptions' => false,
    ],

    'api' => [
        'driver' => 'daily',
        'path' => storage_path('logs/api.log'),
        'level' => env('LOG_LEVEL', 'debug'),
        'days' => 30,
    ],

    'security' => [
        'driver' => 'daily',
        'path' => storage_path('logs/security.log'),
        'level' => 'warning',
        'days' => 90,
    ],

    'performance' => [
        'driver' => 'daily',
        'path' => storage_path('logs/performance.log'),
        'level' => 'info',
        'days' => 14,
    ],

    'slack' => [
        'driver' => 'slack',
        'url' => env('LOG_SLACK_WEBHOOK_URL'),
        'username' => 'Naeelah Firm',
        'emoji' => ':boom:',
        'level' => env('LOG_LEVEL', 'critical'),
    ],
];
```

#### **Performance Monitoring Middleware**
```php
// app/Http/Middleware/PerformanceMonitor.php
class PerformanceMonitor
{
    public function handle(Request $request, Closure $next)
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage(true);

        $response = $next($request);

        $endTime = microtime(true);
        $endMemory = memory_get_usage(true);

        $duration = ($endTime - $startTime) * 1000; // Convert to milliseconds
        $memoryUsed = $endMemory - $startMemory;

        // Log slow requests (> 2 seconds)
        if ($duration > 2000) {
            Log::channel('performance')->warning('Slow request detected', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'duration' => round($duration, 2) . 'ms',
                'memory_used' => round($memoryUsed / 1024 / 1024, 2) . 'MB',
                'user_id' => auth()->id(),
                'firm_id' => session('current_firm_id')
            ]);
        }

        // Add performance headers
        $response->headers->set('X-Response-Time', round($duration, 2) . 'ms');
        $response->headers->set('X-Memory-Usage', round($endMemory / 1024 / 1024, 2) . 'MB');

        return $response;
    }
}
```

### **3. Database Monitoring**

#### **Query Performance Monitoring**
```php
// app/Providers/AppServiceProvider.php
public function boot()
{
    if (app()->environment('production')) {
        DB::listen(function ($query) {
            if ($query->time > 1000) { // Log queries > 1 second
                Log::channel('performance')->warning('Slow query detected', [
                    'sql' => $query->sql,
                    'bindings' => $query->bindings,
                    'time' => $query->time . 'ms',
                    'connection' => $query->connectionName
                ]);
            }
        });
    }
}
```

---

## **🔧 MAINTENANCE PROCEDURES**

### **1. Regular Maintenance Tasks**

#### **Daily Maintenance Script**
```bash
#!/bin/bash
# daily-maintenance.sh

echo "🔧 Starting daily maintenance..."

# Clear expired sessions
php artisan session:gc

# Clear old logs (keep 30 days)
find /var/www/naeelah-firm/storage/logs -name "*.log" -mtime +30 -delete

# Optimize database
php artisan db:optimize

# Clear temporary files
php artisan cache:prune-stale-tags
php artisan view:clear

# Backup database
mysqldump -u backup_user -p naeelah_firm_prod | gzip > /var/backups/db/naeelah-firm-$(date +%Y%m%d).sql.gz

# Keep only 7 days of database backups
find /var/backups/db -name "naeelah-firm-*.sql.gz" -mtime +7 -delete

echo "✅ Daily maintenance completed"
```

#### **Weekly Maintenance Script**
```bash
#!/bin/bash
# weekly-maintenance.sh

echo "🔧 Starting weekly maintenance..."

# Update composer dependencies (security updates only)
composer update --with-dependencies --prefer-stable --no-dev

# Clear all caches
php artisan optimize:clear
php artisan optimize

# Run database integrity checks
php artisan db:check-integrity

# Generate system health report
php artisan system:health-report

# Update SSL certificates if needed
certbot renew --quiet

echo "✅ Weekly maintenance completed"
```

### **2. Backup Procedures**

#### **Comprehensive Backup Script**
```bash
#!/bin/bash
# backup.sh - Full System Backup

BACKUP_DATE=$(date +%Y%m%d-%H%M%S)
BACKUP_DIR="/var/backups/naeelah-firm"
PROJECT_DIR="/var/www/naeelah-firm"

echo "📦 Starting full system backup..."

# Create backup directory
mkdir -p $BACKUP_DIR/$BACKUP_DATE

# Database backup
echo "🗄️ Backing up database..."
mysqldump -u backup_user -p naeelah_firm_prod | gzip > $BACKUP_DIR/$BACKUP_DATE/database.sql.gz

# Application files backup
echo "📁 Backing up application files..."
tar -czf $BACKUP_DIR/$BACKUP_DATE/application.tar.gz \
    --exclude='node_modules' \
    --exclude='vendor' \
    --exclude='storage/logs' \
    --exclude='storage/framework/cache' \
    --exclude='storage/framework/sessions' \
    --exclude='storage/framework/views' \
    $PROJECT_DIR

# Storage files backup
echo "💾 Backing up storage files..."
tar -czf $BACKUP_DIR/$BACKUP_DATE/storage.tar.gz $PROJECT_DIR/storage/app/public

# Configuration backup
echo "⚙️ Backing up configuration..."
cp $PROJECT_DIR/.env $BACKUP_DIR/$BACKUP_DATE/
cp -r /etc/nginx/sites-available/naeelah-firm $BACKUP_DIR/$BACKUP_DATE/nginx.conf

# Create backup manifest
echo "📋 Creating backup manifest..."
cat > $BACKUP_DIR/$BACKUP_DATE/manifest.txt << EOF
Backup Date: $BACKUP_DATE
Database: database.sql.gz
Application: application.tar.gz
Storage: storage.tar.gz
Environment: .env
Nginx Config: nginx.conf
EOF

# Cleanup old backups (keep 30 days)
find $BACKUP_DIR -type d -name "20*" -mtime +30 -exec rm -rf {} \;

echo "✅ Backup completed: $BACKUP_DIR/$BACKUP_DATE"
```

### **3. Security Maintenance**

#### **Security Audit Script**
```bash
#!/bin/bash
# security-audit.sh

echo "🔒 Starting security audit..."

# Check file permissions
echo "📋 Checking file permissions..."
find /var/www/naeelah-firm -type f -perm /o+w -exec ls -la {} \;

# Check for suspicious files
echo "🔍 Scanning for suspicious files..."
find /var/www/naeelah-firm -name "*.php" -exec grep -l "eval\|base64_decode\|shell_exec" {} \;

# Check SSL certificate expiry
echo "🔐 Checking SSL certificate..."
openssl x509 -in /etc/letsencrypt/live/your-domain.com/cert.pem -text -noout | grep "Not After"

# Check for failed login attempts
echo "🚨 Checking failed login attempts..."
grep "authentication failure" /var/log/auth.log | tail -10

# Update system packages
echo "📦 Checking for security updates..."
apt list --upgradable | grep -i security

echo "✅ Security audit completed"
```

### **4. Performance Optimization**

#### **Performance Tuning Script**
```bash
#!/bin/bash
# performance-tune.sh

echo "⚡ Starting performance optimization..."

# Optimize MySQL
echo "🗄️ Optimizing MySQL..."
mysql -u root -p -e "OPTIMIZE TABLE cases, users, quotations, receipts, bills, vouchers;"

# Clear and rebuild caches
echo "🧹 Rebuilding caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Optimize Composer autoloader
echo "📚 Optimizing autoloader..."
composer dump-autoload --optimize --classmap-authoritative

# Clear opcache
echo "🚀 Clearing OPcache..."
php -r "opcache_reset();"

# Restart services for optimal performance
echo "🔄 Restarting services..."
systemctl restart php8.2-fpm
systemctl reload nginx
systemctl restart redis-server

echo "✅ Performance optimization completed"
```

---

## **🚨 TROUBLESHOOTING GUIDE**

### **1. Common Issues & Solutions**

#### **Database Connection Issues**
```bash
# Check MySQL status
systemctl status mysql

# Check database connectivity
mysql -u naeelah_user -p -h 127.0.0.1 -e "SELECT 1;"

# Check Laravel database connection
php artisan tinker
>>> DB::connection()->getPdo();
```

#### **Permission Issues**
```bash
# Fix Laravel permissions
sudo chown -R www-data:www-data /var/www/naeelah-firm
sudo chmod -R 755 /var/www/naeelah-firm
sudo chmod -R 775 /var/www/naeelah-firm/storage
sudo chmod -R 775 /var/www/naeelah-firm/bootstrap/cache
```

#### **Cache Issues**
```bash
# Clear all caches
php artisan optimize:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Rebuild caches
php artisan optimize
```

### **2. Emergency Procedures**

#### **Emergency Rollback Script**
```bash
#!/bin/bash
# emergency-rollback.sh

BACKUP_DATE=$1

if [ -z "$BACKUP_DATE" ]; then
    echo "Usage: ./emergency-rollback.sh BACKUP_DATE"
    echo "Available backups:"
    ls -la /var/backups/naeelah-firm/
    exit 1
fi

echo "🚨 Starting emergency rollback to $BACKUP_DATE..."

# Put application in maintenance mode
php artisan down --message="Emergency maintenance in progress"

# Restore database
echo "🗄️ Restoring database..."
gunzip < /var/backups/naeelah-firm/$BACKUP_DATE/database.sql.gz | mysql -u root -p naeelah_firm_prod

# Restore application files
echo "📁 Restoring application files..."
cd /var/www
tar -xzf /var/backups/naeelah-firm/$BACKUP_DATE/application.tar.gz

# Restore storage files
echo "💾 Restoring storage files..."
tar -xzf /var/backups/naeelah-firm/$BACKUP_DATE/storage.tar.gz -C /var/www/naeelah-firm/

# Restore environment configuration
echo "⚙️ Restoring configuration..."
cp /var/backups/naeelah-firm/$BACKUP_DATE/.env /var/www/naeelah-firm/

# Set permissions
chown -R www-data:www-data /var/www/naeelah-firm
chmod -R 755 /var/www/naeelah-firm
chmod -R 775 /var/www/naeelah-firm/storage

# Clear caches
php artisan optimize:clear
php artisan optimize

# Bring application back up
php artisan up

echo "✅ Emergency rollback completed"
```

---

## **📈 SCALING CONSIDERATIONS**

### **1. Horizontal Scaling**

#### **Load Balancer Configuration**
```nginx
# /etc/nginx/conf.d/load-balancer.conf
upstream naeelah_firm_backend {
    least_conn;
    server 10.0.1.10:80 weight=3 max_fails=3 fail_timeout=30s;
    server 10.0.1.11:80 weight=3 max_fails=3 fail_timeout=30s;
    server 10.0.1.12:80 weight=2 max_fails=3 fail_timeout=30s backup;
}

server {
    listen 80;
    server_name your-domain.com;

    location / {
        proxy_pass http://naeelah_firm_backend;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        
        # Session affinity
        ip_hash;
    }
}
```

### **2. Database Scaling**

#### **Read Replica Configuration**
```php
// config/database.php
'mysql' => [
    'read' => [
        'host' => [
            '192.168.1.1',
            '192.168.1.2',
        ],
    ],
    'write' => [
        'host' => [
            '192.168.1.3',
        ],
    ],
    'sticky' => true,
    'driver' => 'mysql',
    'database' => 'naeelah_firm_prod',
    'username' => 'naeelah_user',
    'password' => 'secure_password',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
],
```

---

**Panduan deployment dan maintenance ini memastikan sistem Naeelah Firm berjalan dengan optimal, aman, dan reliable dalam production environment.**
