# ISM Backup & Restore System - User Guide

## Overview

The ISM Backup & Restore System allows you to create backups of your database in multiple ways:
- **By Category** - Backup related tables grouped by business function
- **By Table** - Backup individual database tables for granular control
- **Full Backup** - Complete backup of all data

All backups are stored as JSON files in `storage/app/backups/` and can be downloaded for offsite storage or restored at any time.

---

## Table of Contents

1. [Accessing the Backup System](#accessing-the-backup-system)
2. [Backup Methods](#backup-methods)
   - [Category-Based Backup](#category-based-backup)
   - [Table-Based Backup](#table-based-backup)
   - [Full Database Backup](#full-database-backup)
3. [Download Backups](#download-backups)
4. [Restore from Backup](#restore-from-backup)
5. [Managing Backup Files](#managing-backup-files)
6. [Understanding Backup Files](#understanding-backup-files)
7. [Scheduled Backups](#scheduled-backups)
8. [Common Tasks](#common-tasks)
9. [Troubleshooting](#troubleshooting)

---

## Accessing the Backup System

1. **Login** to your ISM application with administrator credentials
2. Click the **Backup & Restore** link (look for the database icon in the admin menu)
3. You'll see the main backup interface with three tabs:
   - **Backup** - Create new backups
   - **Restore** - Restore from backups
   - **Backup Files** - View and manage backup files
   - **History** - View backup/restore operation history

---

## Backup Methods

### Category-Based Backup

**Best for:** Regular backups of specific business areas; organized by function

#### Available Categories:

| Category | Icon | Tables Included | Use Case |
|----------|------|-----------------|----------|
| **Purchase Orders (P.O)** | 🛒 | purchase_infos, product_details, summaries | Backup all purchase order data |
| **Sales Orders (S.O)** | 📄 | sales_orders, product_details, summaries, order_forms | Backup all sales order data |
| **Products & Categories** | 📦 | categories, products, supplies, supply_history, galleries, tags, taggables | Backup product catalog |
| **Vendors** | 🚚 | vendors | Backup vendor information |
| **Customers** | 👥 | customers | Backup customer data |
| **Expenses** | 💵 | expenses | Backup expense records |
| **Job Orders** | 🔧 | job_orders, job_order_statuses, job_order_products | Backup job order data |
| **Product Returns** | ↩️ | product_returns, return_statuses, product_details | Backup return information |
| **System Settings** | ⚙️ | users, preferences, print_settings, payment_methods, price_lists, audit_logs | Backup system configuration |

#### Steps:

1. Navigate to **Backup** tab
2. Click on **"By Category"** sub-tab
3. **Select Categories:**
   - Check boxes next to the categories you want to backup
   - Use "Select All" button to select all categories at once
   - Use "Clear" button to deselect all categories
4. Click **"Backup Selected"** button
5. System will create the backup file automatically
6. When complete, you'll see a success message with the filename

**Example Scenarios:**
- Backup only Sales Orders: Check "Sales Orders (S.O)" and click "Backup Selected"
- Backup Products and Vendors: Check both categories and click "Backup Selected"
- Monthly data backup: Select all categories and create a full category backup

---

### Table-Based Backup

**Best for:** Selective data export, troubleshooting, or recovering specific table data

#### Available Tables:

All database tables are available for individual backup:
- `categories` - Product categories
- `products` - Product information
- `supplies` - Supply information
- `supply_history` - Supply history records
- `galleries` - Product galleries/images
- `tags` - Tag definitions
- `taggables` - Tag relationships
- `vendors` - Vendor data
- `customers` - Customer data
- `expenses` - Expense records
- `job_orders` - Job orders
- `job_order_statuses` - Job order status records
- `job_order_products` - Job order products
- `product_returns` - Product return records
- `return_statuses` - Return status records
- `product_details` - Product detail records
- `sales_orders` - Sales order records
- `summaries` - Summary records
- `order_forms` - Order form records
- `purchase_infos` - Purchase information
- `users` - User accounts
- `preferences` - System preferences
- `print_settings` - Print configuration
- `payment_methods` - Payment methods
- `price_lists` - Price lists
- `audit_logs` - Audit logs

#### Steps:

1. Navigate to **Backup** tab
2. Click on **"By Table"** sub-tab
3. **Select Tables:**
   - Check boxes next to the tables you want to backup
   - Use "Select All" button to select all tables
   - Use "Clear" button to deselect all
   - Scroll to see all available tables
4. Review your selection (e.g., "5 of 27 tables selected")
5. Click **"Backup Selected Tables"** button
6. System will create the backup file with the selected tables
7. Success message will show with the filename

**Example Scenarios:**
- Backup only Product Data: Select `categories`, `products`, `supplies`, `galleries` and click "Backup Selected Tables"
- Backup Vendor Data: Select only `vendors` table
- Backup Specific Orders: Select `sales_orders` and `purchase_infos` tables
- Troubleshooting: Backup specific tables to investigate data issues

---

### Full Database Backup

**Best for:** Complete system backup; disaster recovery; archival

#### Steps:

1. Navigate to **Backup** tab
2. Click on **"Full Backup"** sub-tab
3. Review the information:
   - This backs up ALL tables and ALL data
   - Including all relationships and foreign keys
   - Scheduled backups run automatically
4. Click **"Create Full Backup"** button
5. A confirmation dialog will appear - click **"Yes, backup all!"**
6. System will create a complete database backup (may take a few minutes for large databases)
7. Success message will show with the filename

**Important Notes:**
- Full backups may take longer depending on database size
- Automated scheduled backups run daily, weekly, and monthly
- Always keep at least one recent full backup for disaster recovery

---

## Download Backups

Backups can be downloaded from your server to store offsite or on your local computer.

#### Steps:

1. Navigate to **"Backup Files"** tab
2. You'll see a list of all backup files with:
   - Filename (e.g., `backup_sales_orders_2024-04-08_143022.json`)
   - File size (e.g., 2.5 MB)
   - Creation date
3. Click the **Download** button (blue ⬇️ icon) for the backup you want
4. The file will download to your Downloads folder (or configured location)
5. You can now store this file securely

**Storage Tips:**
- Store backups in cloud storage (Google Drive, Dropbox, etc.)
- Keep copies on external drives
- Organize by date and category
- Verify file integrity after download

---

## Restore from Backup

Restore data from a previously created backup. **⚠️ WARNING: Restoring will overwrite existing data!**

### Restore from Uploaded File

Use this to restore a backup file you've downloaded at a later date.

#### Steps:

1. Navigate to **"Restore"** tab
2. Go to **"Restore from Upload"** section
3. **Select Backup File:**
   - Click "Choose backup file..." button
   - Select a `.json` backup file from your computer
   - Wait for the file to load (meta information will preview)
4. **Select Restore Type:**
   - "Full Restore (All Data)" - Restores entire backup
   - Or select a specific category to restore only category data
5. Review warnings - **restoring will replace existing data**
6. Click **"Restore Data"** button
7. Confirm the action in the dialog
8. System will restore the data (may take several minutes)
9. Success message shows tables restored

**Example Scenarios:**
- Recover from accidental deletion: Upload backup and restore specific category
- Restore after data corruption: Upload full backup and do full restore
- Revert to previous state: Upload old backup and restore selected categories

### Restore from Server Backup

Use this to restore from backup files already stored on your server.

#### Steps:

1. Navigate to **"Restore"** tab
2. Go to **"Restore from Server Backup"** section
3. **Select Server Backup:**
   - Click the dropdown "-- Select backup file --"
   - Choose a backup from the list with file size and date info
4. **Select Restore Type:**
   - "Full Restore (All Data)" - Restores entire backup
   - Or select a specific category to restore only category data
5. Review warnings - **restoring will replace existing data**
6. Click **"Restore Data"** button
7. Confirm the action in the dialog
8. System will restore the data
9. Success message shows operation completed

**Safety Features:**
- Backup history logged with user information
- Can view what was restored in History tab
- Previous backups always available for re-restore

---

## Managing Backup Files

### View Backup Files

1. Navigate to **"Backup Files"** tab
2. See all available backups with:
   - File number (index)
   - Filename with backup type and timestamp
   - File size (formatted as B, KB, MB, GB)
   - Creation date and time

### Download a Backup

1. In **"Backup Files"** tab, find the backup you want
2. Click the **Download** button (blue ⬇️ icon)
3. File downloads to your computer
4. Store safely for long-term archival

### Delete a Backup

1. In **"Backup Files"** tab, find the backup you want to delete
2. Click the **Delete** button (red 🗑️ icon)
3. Confirm deletion in the dialog - **this cannot be undone**
4. File is permanently removed from server

**Warning:** Be careful deleting backups - always keep at least one recent backup!

### Backup File Details

Each backup file name follows this pattern:
```
backup_{type}_{YYYY-MM-DD}_{HHMMSS}.json
```

Examples:
- `backup_sales_orders_2024-04-08_143022.json` - Category backup
- `backup_products__2024-04-08_094515.json` - Table selection backup
- `backup_all_2024-04-08_021500.json` - Full database backup

**File Contents:**
- JSON format with metadata and data tables
- Metadata includes: type, category/tables, timestamp, app version
- Tables contain array of records with all columns
- Preserves all relationships and foreign keys

---

## Understanding Backup Files

### Backup File Structure

```json
{
  "meta": {
    "type": "multi_category",
    "categories": ["sales_orders", "products_categories"],
    "label": "Selected Categories Backup",
    "created_at": "2024-04-08T14:30:22Z",
    "app": "ISM Backup System",
    "version": "1.0"
  },
  "tables": {
    "sales_orders": [
      { "id": 1, "order_number": "SO-001", ... },
      { "id": 2, "order_number": "SO-002", ... }
    ],
    "products": [
      { "id": 1, "name": "Widget", ... }
    ]
  }
}
```

### Backup Types

1. **multi_category** - Multiple categories selected
   - Contains related tables from selected categories
   - Example: Sales Orders + Products categories

2. **multi_table** - Individual tables selected
   - Contains only the selected tables
   - Example: Just customers and vendors tables

3. **full** - Complete database backup
   - Contains all tables
   - Contains all categories

4. **category** - Single category backup (if implemented)
   - Contains one category's tables

---

## Scheduled Backups

The system automatically creates backups on a schedule:

| Frequency | Schedule | Type |
|-----------|----------|------|
| **Daily** | 2:00 AM | Automated backup |
| **Weekly** | Sunday 3:00 AM | Automated backup |
| **Monthly** | 1st of month 4:00 AM | Automated backup |

### Key Points:

- Scheduled backups run automatically (no user action required)
- All tables included in scheduled backups
- Backups stored in `storage/app/backups/`
- Old scheduled backups auto-cleanup (keeps 30 most recent)
- Can be viewed and downloaded from Backup Files tab

### Scheduled Backup Files

Scheduled backup filenames follow pattern:
```
backup_scheduled_{YYYY-MM-DD}_{HHMMSS}.json
```

---

## Common Tasks

### Task 1: Regular Backup of Sales Data

**Goal:** Create a backup of all sales-related information every week

**Steps:**
1. Go to Backup tab → "By Category"
2. Select "Sales Orders (S.O)" and "Customers"
3. Click "Backup Selected"
4. Note the filename or click Download to save offsite
5. Repeat weekly

### Task 2: Export Specific Product List

**Goal:** Export only product and category tables for analysis

**Steps:**
1. Go to Backup tab → "By Table"
2. Select: `products`, `categories`, `supplies`
3. Click "Backup Selected Tables"
4. Download the file
5. Share or analyze the JSON data

### Task 3: Create Monthly Archive

**Goal:** Create complete monthly backup for archival

**Steps:**
1. Go to Backup tab → "Full Backup"
2. Click "Create Full Backup"
3. Wait for completion
4. In Backup Files tab, Download the backup
5. Rename and store in archive folder with date
6. Example: `backup_full_2024-04_april.json`

### Task 4: Restore Lost Vendor Data

**Goal:** Recover deleted vendor records from a previous backup

**Steps:**
1. Go to Restore tab
2. Select "Restore from Server Backup" (or upload if you have external copy)
3. Choose the backup file from before the deletion
4. Select Restore Type: "Vendors" category
5. Confirm restore
6. System restores vendor data as of that backup

### Task 5: Backup Before Major Update

**Goal:** Create safety backup before applying system updates

**Steps:**
1. Before starting any update: Go to Backup tab
2. Create Full Backup (or select critical categories)
3. Download the backup file to external storage
4. Keep the filename/location noted
5. Proceed with updates
6. If issues occur, restore from the backup

---

## Troubleshooting

### Q: Where are backup files stored?

**A:** Backup files are stored in: `storage/app/backups/`
- Access via File Manager or FTP
- Never move files manually - use Backup Files UI
- Backups are automatically organized and cleanup old files

### Q: How large should backup files be?

**A:** File size depends on data volume:
- Small database: 1-5 MB
- Medium database: 5-50 MB
- Large database: 50-500 MB+
- Extremely large: 500MB+ (full database with years of data)

If a backup seems too small, verify:
- Correct tables/categories were backed up
- Database has recent data
- No errors occurred during backup

### Q: How long does a backup take?

**A:** Timing depends on database size:
- Small category (10K records): < 1 second
- Large category (1M records): 5-30 seconds
- Full database backup: 1-5 minutes (or longer for very large databases)
- Size of data > Table count

**During backup:**
- UI shows "Creating backup..." message
- Don't close the tab or navigate away
- Large backups may timeout in browser - watch for errors

### Q: Can I backup while others are using the system?

**A:** Yes! Backups use read-only queries and don't lock tables.
- Other users can continue working
- Backups capture data as of the moment started
- No performance impact

### Q: What if restore fails?

**A:** If restore fails:
1. Check error message in REST modal
2. Verify backup file is valid JSON
3. Try restoring to a different category first
4. Check server disk space
5. Review History tab for details

**Recovery:**
- Restore failures do not corrupt data
- Original data remains intact
- Previous backup is still available
- Try restore again or contact support

### Q: How do I know if a backup succeeded?

**A:** Successful backup shows:
- ✅ Green "Success" message
- Filename displayed
- New backup appears in Files tab
- Entry in History tab marked "success"
- No error messages displayed

Check History tab to see all backup operations with status.

### Q: Can I backup while a restore is running?

**A:** No, block one backup/restore operation at a time.
- Wait for current operation to complete
- System prevents multiple concurrent operations
- "Please wait..." message if already processing

### Q: How often should I backup?

**A:** Recommended backup frequency:
- **Daily:** Critical business systems
- **Weekly:** Standard systems
- **Monthly:** Archive + compliance
- **Before major changes:** Always backup before updates

The system includes automatic daily, weekly, and monthly backups in addition to manual backups.

### Q: How long are backups retained?

**A:** Server-side retention:
- Automated scheduled backups: 30 most recent kept
- Manual backups: No automatic deletion (disk space permitting)
- Download important backups to external storage

**Best Practice:** Download and archive important backups monthly.

### Q: Can I edit a backup file manually?

**A:** Not recommended, but technically possible:
- Backups are valid JSON files
- Can be opened in text editor
- Can be parsed with any JSON tool
- **Do not manually edit before restore** - risks data corruption

For specific modifications, better to:
1. Restore to test database
2. Make changes there
3. Create new backup
4. Restore to production

### Q: What tables are in each category?

**A:** See Category table in "Category-Based Backup" section above for complete breakdown.

---

## Support & Help

**Questions?**
- Check this guide (search by keywords)
- Review History tab for past operations
- Contact system administrator
- Check server error logs in `storage/logs/`

**Common File Paths:**
- Backup files: `storage/app/backups/`
- Error logs: `storage/logs/backup.log`
- App logs: `storage/logs/laravel.log`

---

## Summary

| Task | Method | Time | Size |
|------|--------|------|------|
| Quick backup | By Category | Seconds | Small |
| Selective export | By Table | Seconds | Small |
| Full backup | Full Backup | Minutes | Large |
| Restore | From File/Server | Minutes | Large |
| Schedule | Automatic | Scheduled | Auto |

---

**Document Version:** 1.0
**Last Updated:** April 8, 2024
**System:** ISM Backup & Restore v1.0

