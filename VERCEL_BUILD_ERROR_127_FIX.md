# 🔧 Vercel Build Error 127 - FIXED

## ❌ Error Message

```
Command "npm run build && composer install --optimize-autoloader --no-dev" exited with 127
```

---

## 🔍 Root Cause

**Error Code 127** = "Command not found"

The issue: Vercel's **Node.js build environment** doesn't have PHP or Composer installed. The build command was trying to run `composer install`, but Composer isn't available during the build phase.

---

## ✅ Solution

**Remove `composer install` from the build command.**

The `vercel-php` runtime automatically handles Composer dependencies when the PHP function is deployed. You only need to build the frontend assets (Vite).

### **Before (❌ Broken):**
```json
{
    "buildCommand": "npm run build && composer install --optimize-autoloader --no-dev",
    ...
}
```

### **After (✅ Fixed):**
```json
{
    "buildCommand": "npm run build",
    ...
}
```

---

## 📝 Updated `vercel.json`

```json
{
    "$schema": "https://openapi.vercel.sh/vercel.json",
    "buildCommand": "npm run build",
    "outputDirectory": "public",
    "framework": null,
    "functions": {
        "api/index.php": {
            "runtime": "vercel-php@0.7.4",
            "maxDuration": 10
        }
    },
    "routes": [
        {
            "src": "/build/(.*)",
            "dest": "/build/$1"
        },
        {
            "src": "/(.*)",
            "dest": "/api/index.php"
        }
    ],
    "env": {
        "APP_ENV": "production",
        "APP_DEBUG": "false",
        "LOG_CHANNEL": "stderr",
        "SESSION_DRIVER": "cookie",
        "CACHE_STORE": "array",
        "VIEW_COMPILED_PATH": "/tmp/views"
    }
}
```

---

## 🚀 How Vercel Handles PHP Dependencies

### **Build Phase (Node.js environment):**
1. ✅ Runs `npm install`
2. ✅ Runs `npm run build` (Vite builds frontend assets)
3. ✅ Outputs to `public/` directory

### **Deployment Phase (PHP runtime):**
1. ✅ `vercel-php@0.7.4` runtime is activated
2. ✅ Composer dependencies are automatically installed
3. ✅ PHP function (`api/index.php`) is deployed

**You don't need to manually run `composer install` in the build command!**

---

## 🎯 Next Steps

1. ✅ **Commit the fix:**
   ```bash
   git add vercel.json
   git commit -m "Fix Vercel build error 127 - remove composer from build command"
   git push origin PostgreSQL
   ```

2. ✅ **Verify Vercel environment variables are set:**
   - Go to: **Vercel Dashboard → Project → Settings → Environment Variables**
   - Make sure these are set:
     ```
     APP_KEY=base64:vLZgJwgMnUgoi3QGFLr9NuXK+Il9W2oTdxEq9aK4WCQ=
     DB_CONNECTION=pgsql
     DB_HOST=44.212.152.76
     DB_PORT=5432
     DB_DATABASE=todolist_db
     DB_USERNAME=todolist_user
     DB_PASSWORD=todotest12345
     ```

3. ✅ **Deploy:**
   ```bash
   git push origin PostgreSQL
   ```
   Or trigger a redeploy in Vercel Dashboard.

4. ✅ **Run migrations on PostgreSQL:**
   ```bash
   # From your local machine (with .env pointing to EC2 PostgreSQL)
   php artisan migrate --force
   ```

---

## ✅ Verification

After deployment, check:

- [ ] Build succeeds (no error 127)
- [ ] Vite assets are built (`public/build/` directory)
- [ ] PHP function deploys successfully
- [ ] App loads in browser
- [ ] Can connect to PostgreSQL database
- [ ] CRUD operations work

---

## 🐛 If You Still Get Errors

### **Error: "Class 'PDO' not found"**
- The `vercel-php` runtime includes PDO by default
- Check that `DB_CONNECTION=pgsql` is set in Vercel environment variables

### **Error: "could not find driver"**
- Make sure `DB_CONNECTION=pgsql` (not `DB_CONNECTION=postgres`)
- The `vercel-php` runtime includes the `pdo_pgsql` extension

### **Error: "Connection refused"**
- Verify PostgreSQL is running on EC2: `sudo systemctl status postgresql`
- Check EC2 Security Group allows port 5432
- Test connection: `psql -h 44.212.152.76 -U todolist_user -d todolist_db`

### **Error: "Target class [view] does not exist"**
- This should already be fixed by the bootstrap cache clearing in `api/index.php`
- If it persists, check that `VIEW_COMPILED_PATH=/tmp/views` is set in Vercel env vars

---

## 📚 Related Documentation

- **PostgreSQL Setup**: See `POSTGRESQL_EC2_SETUP_GUIDE.md`
- **Troubleshooting**: See `POSTGRESQL_TROUBLESHOOTING.md`
- **Quick Commands**: See `POSTGRESQL_QUICK_COMMANDS.md`
- **Previous Vercel Fixes**: See `VERCEL_ERROR_TARGET_CLASS_VIEW_FIX.md`

---

## 🎉 Summary

| Issue | Solution |
|-------|----------|
| **Error Code** | 127 (Command not found) |
| **Root Cause** | Composer not available in Node.js build environment |
| **Fix** | Remove `composer install` from `buildCommand` |
| **Why It Works** | `vercel-php` runtime handles Composer automatically |
| **Files Changed** | `vercel.json` (1 line) |

**Your build should now succeed!** 🚀


