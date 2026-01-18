#!/bin/bash
# Scripts to import a SQL dump into the local dev database

DB_HOST=${DB_HOST:-127.0.0.1}
DB_USER=${DB_USER:-shuleapp}
DB_PASS=${DB_PASS:-shuleapp}
DB_NAME=${DB_NAME:-shuleapp}

# Find the first .sql file in the root directory
SQL_FILE=$(find . -maxdepth 1 -name "*.sql" | head -n 1)

if [ -z "$SQL_FILE" ]; then
    echo "Error: No .sql file found in the root directory."
    echo "Please upload your 'shuleapp_backup.sql' file to the workspace root."
    exit 1
fi

echo "Found file: $SQL_FILE"
echo "Importing into database '$DB_NAME' on '$DB_HOST'..."

# Import
mariadb -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" < "$SQL_FILE"

if [ $? -eq 0 ]; then
    echo "✅ Import successful!"
    echo "You can now log in at http://localhost:8000/signin/index"
else
    echo "❌ Import failed."
fi
