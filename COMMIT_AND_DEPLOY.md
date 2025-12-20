# Commit and Deploy PostgreSQL Branch

## 📋 Current Status

You are on the **PostgreSQL** branch with all changes ready to commit.

---

## 🔍 Changes Summary

### Modified Files (2):
- ✅ `config/database.php` - PostgreSQL configuration
- ✅ `vite.config.ts` - Fixed CSS manifest issue

### New Files (11):
- ✅ `.env.example` - PostgreSQL template
- ✅ `.gitignore` - Git ignore rules
- ✅ `.prettierignore` - Prettier ignore rules
- ✅ `.prettierrc` - Prettier configuration
- ✅ `EC2_TROUBLESHOOTING.md` - Troubleshooting guide
- ✅ `POSTGRESQL_BRANCH_SUMMARY.md` - Complete summary
- ✅ `PostgreSQL_EC2_SETUP.md` - Setup guide
- ✅ `PostgreSQL_MIGRATION_GUIDE.md` - Migration guide
- ✅ `PostgreSQL_PRODUCTION_STEPS.md` - Deployment steps
- ✅ `README_POSTGRESQL.md` - Branch README
- ✅ `deploy-postgresql-ec2.sh` - Automated deployment script

---

## 🚀 Step 1: Commit Changes

```bash
# Add all changes
git add -A

# Commit with descriptive message
git commit -m "PostgreSQL migration: Configuration and deployment documentation

- Changed default database from SQLite to PostgreSQL
- Updated config/database.php with PostgreSQL optimizations
- Fixed vite.config.ts to include CSS in build manifest
- Added .env.example with PostgreSQL configuration
- Created comprehensive deployment documentation:
  * PostgreSQL_EC2_SETUP.md - Complete setup guide
  * PostgreSQL_PRODUCTION_STEPS.md - Production steps
  * PostgreSQL_MIGRATION_GUIDE.md - Migration from SQLite
  * README_POSTGRESQL.md - Branch-specific README
  * POSTGRESQL_BRANCH_SUMMARY.md - Complete summary
- Added automated deployment script (deploy-postgresql-ec2.sh)
- No changes to models, controllers, routes, or frontend
- All code is database-agnostic using Eloquent ORM"

# Verify commit
git log -1 --stat
```

---

## 📤 Step 2: Push to GitHub

```bash
# Push PostgreSQL branch to remote
git push origin PostgreSQL

# If this is the first push of this branch:
git push -u origin PostgreSQL
```

---

## 🌐 Step 3: Deploy to EC2

### Option A: Automated Deployment (Recommended)

```bash
# 1. SSH into EC2
ssh -i your-key.pem ubuntu@44.212.152.76

# 2. Clone PostgreSQL branch
git clone -b PostgreSQL https://github.com/YOUR_USERNAME/YOUR_REPO.git to-do-list
cd to-do-list

# 3. Run deployment script
chmod +x deploy-postgresql-ec2.sh
./deploy-postgresql-ec2.sh

# Follow the prompts:
# - Database name: todolist_db (or press Enter)
# - Database user: todolist_user (or press Enter)
# - Database password: [enter a strong password]

# 4. Edit .env file
nano .env
# Update:
#   APP_URL=http://44.212.152.76:3002
#   APP_ENV=production
#   APP_DEBUG=false

# 5. Start application
pm2 start "php artisan serve --host=0.0.0.0 --port=3002" --name todo-app
pm2 save
pm2 startup
# Run the command it outputs

# 6. Test
curl http://localhost:3002
```

### Option B: Manual Deployment

See `PostgreSQL_EC2_SETUP.md` for detailed manual steps.

---

## ✅ Step 4: Verify Deployment

### Test Application

1. **Open browser**: `http://44.212.152.76:3002`

2. **Test user registration**:
   - Click "Register"
   - Create a new account
   - Verify redirect to todo lists page

3. **Test todo list creation**:
   - Click "Add Todo List"
   - Create a new list
   - Verify it appears

4. **Test todo creation**:
   - Click "Add Task"
   - Create a new task
   - Verify it appears

5. **Test filtering**:
   - Select "Complete" filter
   - Select "Incomplete" filter
   - Select "All Tasks" filter

6. **Test sorting**:
   - Sort by Due Date
   - Sort by Priority
   - Sort by Created Date

7. **Test editing**:
   - Edit a task
   - Edit a todo list name

8. **Test deletion**:
   - Delete a task
   - Delete a todo list

### Check Logs

```bash
# Laravel logs
tail -f storage/logs/laravel.log

# PM2 logs
pm2 logs todo-app

# PostgreSQL logs
sudo journalctl -u postgresql -n 50
```

### Check Database

```bash
# Connect to database
sudo -u postgres psql -d todolist_db

# Check tables
\dt

# Check data
SELECT COUNT(*) FROM users;
SELECT COUNT(*) FROM todo_lists;
SELECT COUNT(*) FROM todos;

# Exit
\q
```

---

## 🔧 Step 5: Post-Deployment Tasks

### Set Up Automated Backups

```bash
# Create backup script
cat > /home/ubuntu/backup-db.sh << 'EOF'
#!/bin/bash
BACKUP_DIR="/home/ubuntu/backups"
mkdir -p $BACKUP_DIR
sudo -u postgres pg_dump todolist_db > $BACKUP_DIR/todolist_$(date +%Y%m%d_%H%M%S).sql
find $BACKUP_DIR -name "todolist_*.sql" -mtime +7 -delete
EOF

chmod +x /home/ubuntu/backup-db.sh

# Test backup
./backup-db.sh
ls -lh /home/ubuntu/backups/

# Add to crontab (daily at 2 AM)
crontab -e
# Add this line:
0 2 * * * /home/ubuntu/backup-db.sh
```

### Monitor Application

```bash
# Check PM2 status
pm2 status

# Monitor logs in real-time
pm2 logs todo-app --lines 100

# Check resource usage
pm2 monit
```

### Security Hardening

```bash
# Ensure PostgreSQL is not exposed
sudo ufw status
# Port 5432 should NOT be open to public

# Only port 3002 should be open
sudo ufw allow 3002/tcp

# Check PostgreSQL access
sudo nano /etc/postgresql/15/main/pg_hba.conf
# Should only allow local connections
```

---

## 📊 Monitoring and Maintenance

### Daily Checks

```bash
# Check application status
pm2 status

# Check disk space
df -h

# Check PostgreSQL status
sudo systemctl status postgresql

# Check recent logs
tail -n 50 storage/logs/laravel.log
```

### Weekly Tasks

```bash
# Update system packages
sudo apt update && sudo apt upgrade -y

# Verify backups exist
ls -lh /home/ubuntu/backups/

# Check database size
sudo -u postgres psql -c "SELECT pg_size_pretty(pg_database_size('todolist_db'));"
```

### Monthly Tasks

```bash
# Review and clean old logs
find storage/logs -name "*.log" -mtime +30 -delete

# Optimize database
sudo -u postgres psql -d todolist_db -c "VACUUM ANALYZE;"

# Review security updates
sudo apt list --upgradable
```

---

## 🐛 Troubleshooting

### Application Not Starting

```bash
# Check logs
tail -f storage/logs/laravel.log
pm2 logs todo-app

# Clear caches
php artisan config:clear
php artisan cache:clear

# Restart
pm2 restart todo-app
```

### Database Connection Issues

```bash
# Test connection
php artisan tinker
>>> DB::connection()->getPdo();

# Check PostgreSQL
sudo systemctl status postgresql
sudo systemctl restart postgresql

# Check credentials in .env
cat .env | grep DB_
```

### Assets Not Loading

```bash
# Rebuild assets
npm run build

# Check manifest exists
ls -la public/build/manifest.json

# Clear view cache
php artisan view:clear
```

---

## 📚 Documentation Reference

| File | Purpose |
|------|---------|
| `POSTGRESQL_BRANCH_SUMMARY.md` | Complete change summary |
| `PostgreSQL_EC2_SETUP.md` | Full setup guide |
| `PostgreSQL_PRODUCTION_STEPS.md` | Deployment steps |
| `PostgreSQL_MIGRATION_GUIDE.md` | Migration details |
| `README_POSTGRESQL.md` | Branch README |
| `EC2_TROUBLESHOOTING.md` | Troubleshooting |
| `COMMIT_AND_DEPLOY.md` | This file |

---

## ✅ Final Checklist

- [ ] All changes committed to PostgreSQL branch
- [ ] Branch pushed to GitHub
- [ ] EC2 server has PostgreSQL installed
- [ ] Database and user created
- [ ] Application deployed to EC2
- [ ] `.env` configured correctly
- [ ] Migrations run successfully
- [ ] Application accessible at `http://44.212.152.76:3002`
- [ ] All features tested and working
- [ ] Automated backups configured
- [ ] Monitoring set up
- [ ] Security hardened

---

## 🎉 Success!

Your To-Do List application is now running on PostgreSQL in production on EC2!

**Access**: `http://44.212.152.76:3002`


