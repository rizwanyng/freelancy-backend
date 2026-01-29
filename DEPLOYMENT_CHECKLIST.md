# 🚀 QUICK DEPLOYMENT CHECKLIST

## ✅ What's Been Done Locally:
1. ✅ Created root `.htaccess` to redirect to `/public` folder
2. ✅ Created root `index.php` for public folder redirect
3. ✅ Created `.env.production` template with production settings
4. ✅ Fixed `leads` table migration (UUID foreign key)
5. ✅ Pushed changes to GitHub

## 🔧 WHAT TO DO ON HOSTINGER NOW:

### Step 1: Check Auto-Deploy (1 minute)
1. Log into Hostinger cPanel
2. Go to **Git Version Control**
3. Verify latest commit has been deployed
4. If not auto-deployed, click **Pull** or **Update**

### Step 2: Critical - Set Document Root (2 minutes)
This is THE MOST IMPORTANT step to fix 403 error!

1. Go to **Domains** → **Manage**
2. Find: `freelacy.actualminds.com`
3. Click **Edit** or **Manage**
4. Find **Document Root** field
5. Change it to end with `/public`
   - Example: `/domains/freelacy.actualminds.com/public_html/public`
   - Or: `/home/username/domains/freelacy.actualminds.com/public`
6. Click **Save**

### Step 3: Environment Configuration (3 minutes)
Via File Manager or SSH:

1. Rename `.env.production` to `.env`
2. Edit `.env` and update:
   ```
   DB_DATABASE=u123456_dbname  (your Hostinger DB name)
   DB_USERNAME=u123456_user     (your Hostinger DB user)
   DB_PASSWORD=your_password    (your Hostinger DB password)
   ```
3. Find DB credentials in: cPanel → **MySQL Databases**

### Step 4: Set Permissions (2 minutes)
Via SSH terminal or File Manager:

```bash
cd /home/your-username/domains/freelacy.actualminds.com/
chmod -R 755 storage bootstrap/cache
chmod 644 .env
```

Or in File Manager:
- Right-click `storage` folder → Permissions → 755
- Right-click `bootstrap/cache` → Permissions → 755
- Right-click `.env` → Permissions → 644

### Step 5: Run Database Setup (2 minutes)
Via SSH (Terminal in cPanel):

```bash
cd /home/your-username/domains/freelacy.actualminds.com/
php artisan migrate --force
php artisan config:cache
php artisan route:cache
```

### Step 6: Test the API (1 minute)
Open in browser:
- https://freelacy.actualminds.com/api/test

Should return:
```json
{"message":"Backend is working!","status":"OK"}
```

If you see this, backend is READY! ✅

---

## 🔍 TROUBLESHOOTING

### Still 403 Error?
**Document Root is wrong!** 
- Make sure it points to `/public` folder
- After changing, wait 2-3 minutes for changes to propagate

### 500 Error?
1. Check `.env` file exists
2. Run: `php artisan config:clear`
3. Check file permissions on `storage` folder

### Can't find SSH Terminal?
Use **File Manager** instead:
- Upload files via File Manager
- Set permissions in File Manager (right-click → Permissions)
- For artisan commands, use **Cron Jobs** with this command:
  ```
  cd /home/username/domains/freelacy.actualminds.com && php artisan migrate --force
  ```

### Database Connection Error?
1. Go to cPanel → **MySQL Databases**
2. Verify database exists
3. Verify user has permissions on the database
4. Use exact names in `.env` (case-sensitive)

---

## 📱 AFTER BACKEND IS WORKING:

### Update Your Mobile App API URL:

**Find where you have:**
```javascript
const API_URL = "http://localhost/freelancy-backend/public/api";
// or
const API_URL = "http://192.168.x.x/freelancy-backend/public/api";
```

**Change to:**
```javascript
const API_URL = "https://freelacy.actualminds.com/api";
```

**Common files to update:**
- `src/config/api.js`
- `src/services/api.js`
- `src/constants/config.js`
- `.env` file in your app
- `app.json` or `app.config.js`

---

## 🎯 QUICK TEST CHECKLIST:

- [ ] Backend deployed to Hostinger
- [ ] Document Root set to `/public`
- [ ] .env file configured with DB credentials
- [ ] Permissions set correctly
- [ ] Migrations run successfully
- [ ] https://freelacy.actualminds.com/api/test returns success
- [ ] https://freelacy.actualminds.com/api/user returns 401 (means auth works)
- [ ] Mobile app API_URL updated
- [ ] Login working from mobile app

---

**Need Help?** Check the detailed guide in `HOSTINGER_DEPLOY.md`
