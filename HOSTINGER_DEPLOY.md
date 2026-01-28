# HOSTINGER DEPLOYMENT INSTRUCTIONS

## Step 1: Server Configuration on Hostinger

### A. Upload Files
1. Upload all Laravel files to your root directory (e.g., `/home/username/domains/freelacy.actualminds.com/`)
2. Make sure ALL files are uploaded including:
   - `app/`
   - `bootstrap/`
   - `config/`
   - `database/`
   - `public/`
   - `resources/`
   - `routes/`
   - `storage/`
   - `vendor/`
   - `.htaccess` (root)
   - `index.php` (root)
   - `artisan`
   - `composer.json`

### B. Set Document Root
In Hostinger cPanel:
1. Go to **Advanced** → **Website Management**
2. Find your domain: `freelacy.actualminds.com`
3. Click **Manage**
4. Change **Document Root** to: `/domains/freelacy.actualminds.com/public_html/public`
   (or wherever your `/public` folder is located)

### C. Set File Permissions
Connect via SSH or File Manager and run:
```bash
chmod -R 755 storage bootstrap/cache
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### D. Configure Environment File
1. Rename `.env.production` to `.env` on the server
2. Update these values in `.env`:
   ```
   DB_DATABASE=your_hostinger_db_name
   DB_USERNAME=your_hostinger_db_user
   DB_PASSWORD=your_hostinger_db_password
   ```

### E. Install Composer Dependencies (if needed)
If vendor folder is missing, run via SSH:
```bash
composer install --optimize-autoloader --no-dev
```

### F. Run Migrations
```bash
php artisan migrate --force
```

### G. Clear and Cache Config
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

## Step 2: Test the API

Try accessing:
- `https://freelacy.actualminds.com/` - Should redirect or show Laravel page
- `https://freelacy.actualminds.com/api/test` - Test endpoint
- `https://freelacy.actualminds.com/api/auth/login` - Auth endpoint

## Step 3: Update Frontend App

Update your React Native/Flutter app API base URL from:
```
http://localhost/freelancy-backend/public/api
```

To:
```
https://freelacy.actualminds.com/api
```

## Common Issues & Solutions

### 403 Forbidden Error
- **Cause**: Document root not pointing to `/public` folder
- **Solution**: Change document root in Hostinger cPanel to point to `/public`

### 500 Internal Server Error
- **Cause**: Missing .env file or wrong permissions
- **Solution**: 
  - Copy `.env.production` to `.env`
  - Set permissions: `chmod 644 .env`
  - Run: `php artisan config:clear`

### "No application encryption key has been set"
- **Solution**: Run `php artisan key:generate`

### Database Connection Error
- **Solution**: Update DB credentials in `.env` with Hostinger database details

### Routes Not Working
- **Solution**: 
  - Check `.htaccess` exists in `/public` folder
  - Run: `php artisan route:cache`

## Auto-Deploy Setup

If using Git auto-deploy on Hostinger:
1. Go to **Git** in cPanel
2. Add repository URL
3. Set deployment path
4. Add post-deployment script:
```bash
#!/bin/bash
composer install --optimize-autoloader --no-dev
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Database Credentials

Find your Hostinger database credentials:
1. cPanel → **MySQL Databases**
2. Note down:
   - Database name
   - Username
   - Password
   - Host (usually `localhost`)
