# 🚀 **cPanel Deployment Guide for Laravel Application**

## 📋 **Pre-Deployment Checklist**

### **1. Local Environment Setup**
- [ ] Run `composer install --optimize-autoloader --no-dev`
- [ ] Run `npm run build` (if using Vite/Webpack)
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan route:cache`
- [ ] Run `php artisan view:cache`
- [ ] Run `php artisan migrate`
- [ ] Run `php artisan db:seed`

### **2. File Permissions (Local)**
```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod 644 .env
```

## 📁 **File Structure for cPanel**

### **Public Directory (Document Root)**
```
public_html/
├── index.php
├── .htaccess
├── robots.txt
├── web.config (if using IIS)
├── favicon.ico
├── css/
├── js/
└── images/
```

### **Application Directory (Outside Document Root)**
```
laravel_app/
├── app/
├── bootstrap/
├── config/
├── database/
├── resources/
├── routes/
├── storage/
├── vendor/
├── .env
├── artisan
└── composer.json
```

## 🔧 **cPanel Configuration**

### **1. Document Root Setup**
- Set document root to `public_html` folder
- Upload Laravel files to parent directory
- Ensure `.env` file is outside document root

### **2. PHP Configuration**
```ini
upload_max_filesize = 64M
post_max_size = 64M
max_execution_time = 300
max_input_vars = 3000
memory_limit = 256M
```

### **3. Database Configuration**
- Create MySQL database in cPanel
- Update `.env` file with cPanel database credentials
- Ensure database user has proper permissions

## 📤 **Upload Process**

### **Step 1: Prepare Files**
```bash
# Create deployment package
tar -czf laravel-deploy.tar.gz \
  --exclude='.git' \
  --exclude='node_modules' \
  --exclude='storage/logs/*' \
  --exclude='storage/framework/cache/*' \
  --exclude='storage/framework/sessions/*' \
  --exclude='storage/framework/views/*' \
  .
```

### **Step 2: Upload to cPanel**
1. Extract files to `laravel_app/` directory
2. Move `public/` contents to `public_html/`
3. Update `public_html/index.php` paths
4. Set proper file permissions

### **Step 3: Update Paths**
```php
// In public_html/index.php
$autoloadPaths = [
    __DIR__.'/../laravel_app/vendor/autoload.php',
    __DIR__.'/laravel_app/vendor/autoload.php',
];

$bootstrapPaths = [
    __DIR__.'/../laravel_app/bootstrap/app.php',
    __DIR__.'/laravel_app/bootstrap/app.php',
];
```

## 🔒 **Security Settings**

### **1. File Permissions**
```bash
# Directories
chmod 755 laravel_app/storage/
chmod 755 laravel_app/bootstrap/cache/
chmod 755 public_html/

# Files
chmod 644 laravel_app/.env
chmod 644 public_html/.htaccess
```

### **2. .htaccess Security**
```apache
# Block access to sensitive files
<Files ".env">
    Order allow,deny
    Deny from all
</Files>

<Files "composer.json">
    Order allow,deny
    Deny from all
</Files>
```

## 🚨 **Common cPanel Issues & Solutions**

### **1. 500 Internal Server Error**
- **Cause**: Incorrect file paths or permissions
- **Solution**: Check `.htaccess` and file permissions

### **2. White Screen**
- **Cause**: PHP errors or missing dependencies
- **Solution**: Check error logs and run `composer install`

### **3. Database Connection Error**
- **Cause**: Wrong database credentials or host
- **Solution**: Verify `.env` configuration

### **4. Asset Loading Issues**
- **Cause**: Incorrect asset paths
- **Solution**: Update `APP_URL` in `.env`

## 📝 **Post-Deployment Checklist**

- [ ] Test homepage loads
- [ ] Test login/registration
- [ ] Test database operations
- [ ] Test file uploads
- [ ] Check error logs
- [ ] Verify SSL certificate
- [ ] Test mobile responsiveness
- [ ] Check loading speed

## 🔍 **Troubleshooting Commands**

### **Check Error Logs**
```bash
# cPanel error logs
tail -f /home/username/public_html/error_log

# Laravel logs
tail -f laravel_app/storage/logs/laravel.log
```

### **Test Database Connection**
```bash
php artisan tinker --execute="DB::connection()->getPdo(); echo 'Database connected successfully!';"
```

### **Clear All Caches**
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## 📞 **Support Information**

- **cPanel Version**: Check in cPanel dashboard
- **PHP Version**: Check in cPanel PHP Selector
- **MySQL Version**: Check in cPanel MySQL Databases
- **Error Logs**: Check cPanel Error Logs section

---

**⚠️ Important Notes:**
1. Always backup before deployment
2. Test in staging environment first
3. Keep `.env` file secure and outside document root
4. Monitor error logs after deployment
5. Test all critical functionality 