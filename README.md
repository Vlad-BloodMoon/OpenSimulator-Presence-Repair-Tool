# OpenSimulator Presence Repair Tool

This small utility automatically cleans up "ghosted" avatar sessions from OpenSimulator grids — especially helpful when users crash or disconnect improperly, and their status remains stuck as online in the database.

## 🛠️ What It Does

- Scans the `Presence` table in the Robust database for invalid session entries (`RegionID = 00000000-0000-0000-0000-000000000000`)
- Removes these entries
- Finds the corresponding user in `GridUser` (even HyperGrid users)
- Sets their `Online` status to `False`

## 📂 Files Included

- `repair_ghosts.php`: The main script to repair ghost avatars
- `repair_ghosts.log`: The auto-managed log file (rotated to keep only the last 100 entries)
- `.htaccess`: Prevents public access and listing of files in the `/presence` folder

## ✅ Requirements

- PHP 7.4 or higher
- MySQL/MariaDB (OpenSimulator Robust DB)
- Web server (Apache, nginx etc.) — if used via web
- Write access to the log file

## ⚙️ Usage

### Manual CLI (recommended):

```bash
php repair_ghosts.php
```

### Automatic via cron (example: run every hour):

```bash
0 * * * * /usr/bin/php /var/www/BloodMoon/presence/repair_ghosts.php >> /dev/null 2>&1
```

### Log Rotation

The script keeps only the **last 100 lines** in `repair_ghosts.log` to avoid uncontrolled growth.

## 🔐 Security

`.htaccess` prevents file listing and blocks direct access to `repair_ghosts.log`.

---

🧡 Feel free to fork or contribute — many OpenSim grid owners face this issue.
