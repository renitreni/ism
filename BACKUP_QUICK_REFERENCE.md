<!-- ISM Backup System - Quick Reference Guide -->
<!-- This can be displayed as a modal or embedded in the backup page -->

# ISM Backup & Restore - Quick Start Guide

## 3 Ways to Backup

### 1️⃣ By Category (Easiest)
**When to use:** Regular, organized backups
- Select related tables grouped by business function
- Examples: All Sales Orders, All Products, All Vendors
- ✅ Fast, organized, recommended for most users

**How:**
1. Tab: "By Category" 
2. Check categories you want
3. Click "Backup Selected"
4. Done! File downloads

---

### 2️⃣ By Table (Most Control)
**When to use:** Selective backups, troubleshooting, specific data export
- Pick exactly which tables to backup
- Examples: Just `products` table, Just `customers` and `vendors`
- ✅ Granular control, lightweight files

**How:**
1. Tab: "By Table"
2. Check tables you want  
3. Click "Backup Selected Tables"
4. Done! File downloads

---

### 3️⃣ Full Backup (Complete)
**When to use:** Complete archive, disaster recovery, monthly backup
- Everything: All tables, all data, all relationships
- ✅ Complete safety net, full database snapshot

**How:**
1. Tab: "Full Backup"
2. Click "Create Full Backup"
3. Confirm in dialog
4. Wait (may take several minutes)
5. Done! File downloads

---

## Backup Categories Reference

| Icon | Category | Tables | Best For |
|------|----------|--------|----------|
| 🛒 | **Purchase Orders** | PO, Details, Summaries | Vendor purchases |
| 📄 | **Sales Orders** | SO, Details, Summaries, Forms | Customer sales |
| 📦 | **Products** | Categories, Products, Supplies | Product catalog |
| 🚚 | **Vendors** | Vendors | Supplier info |
| 👥 | **Customers** | Customers | Customer info |
| 💵 | **Expenses** | Expenses | Cost tracking |
| 🔧 | **Job Orders** | Jobs, Statuses, Products | Job tracking |
| ↩️ | **Returns** | Returns, Statuses, Details | Product returns |
| ⚙️ | **Settings** | Users, Preferences, Config | System setup |

---

## Restore Data

### Restore from Uploaded File
1. Go to **Restore** tab
2. Upload your `.json` backup file
3. Choose what to restore (full or specific category)
4. Confirm ⚠️ **This overwrites existing data!**
5. Done!

### Restore from Server
1. Go to **Restore** tab
2. Choose backup from server dropdown
3. Choose what to restore
4. Confirm ⚠️ **This overwrites existing data!**
5. Done!

---

## Download & Delete Files

### Download
- Go to **Backup Files** tab
- Click ⬇️ button next to file
- Save to your computer or cloud storage

### Delete
- Go to **Backup Files** tab
- Click 🗑️ button next to file
- Confirm deletion
- ⚠️ Cannot be undone!

---

## Important Notes

✅ **Good Practices:**
- Backup regularly (weekly or more)
- Download and store backups offsite
- Keep at least one recent full backup
- Label backups with dates
- Test restore occasionally

⚠️ **Warnings:**
- Restoring OVERWRITES existing data
- Always confirm before restoring
- Backup before major changes
- Don't delete your only backup
- Backup files are JSON (can be edited but not recommended)

📅 **Automatic Backups:**
- Daily: 2:00 AM
- Weekly: Sundays 3:00 AM
- Monthly: 1st of month 4:00 AM
- (Always available in Backup Files tab)

---

## Troubleshooting

**Backup taking too long?**
- Large databases may take minutes
- ✅ Normal! Don't close browser
- Backup runs in background

**File size seems wrong?**
- Check you selected right tables/categories
- Large databases = larger files
- ✅ Size depends on data, not table count

**Restore not working?**
- Verify backup file is valid JSON
- Check server disk space
- Try different category first
- See full guide for more help

**Can't find backup?**
- Check Backup Files tab
- Filter/search by date
- Manual backups ALWAYS saved
- Check History tab for logs

---

## File Storage

📁 **Server Location:** `storage/app/backups/`
- Auto-cleaned: Old scheduled backups deleted
- Manual backups: Kept indefinitely  
- Stored as JSON (text format)

💾 **Download & Store:**
- Save to computer
- Cloud storage (Google Drive, Dropbox)
- External drives
- Organized by month/year

---

## Support & Help

📖 **Full Guide:** See `BACKUP_GUIDE.md` for complete documentation
📊 **History Tab:** View all backup/restore operations
🕐 **Timestamps:** All files show creation date/time
💬 **Contact:** Ask system administrator if stuck

---

## Quick Stats

| Metric | Value |
|--------|-------|
| Backup Speed | ~1K records/sec |
| File Format | JSON |
| Relationships | Preserved |
| Scheduled | Daily + Weekly + Monthly |
| Download To | Computer or Cloud |
| Max Backup Size | Unlimited |
| Retention | Manual backups: indefinite |

---

**Need Help?** Check BACKUP_GUIDE.md for complete documentation or contact your administrator.
