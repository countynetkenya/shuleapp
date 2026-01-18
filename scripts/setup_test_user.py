import hashlib
import subprocess
import time

def generate_hash(password, key):
    return hashlib.sha512((password + key).encode()).hexdigest()

key = '8bc8ae426d4354c8df0488e2d7f1a9de'
password = 'password'
hashed_password = generate_hash(password, key)

print(f"Hash: {hashed_password}")

# SQL to insert user
# We need to match the columns from the previous `SELECT *` output.
# Columns: systemadminID, name, dob, sex, religion, email, phone, address, jod, photo, username, password, usertypeID, create_date, modify_date, create_userID, create_username, create_usertype, active, systemadminextra1, systemadminextra2, schoolID, selected_schoolID

sql = f"""
INSERT INTO systemadmin (
    name, dob, sex, religion, email, phone, address, jod, photo, username, password, usertypeID, create_date, modify_date, create_userID, create_username, create_usertype, active
) VALUES (
    'Test Admin', '1990-01-01', 'Male', 'Other', 'testadmin@example.com', '123456789', 'Address', '2023-01-01', 'default.png', 'testadmin', '{hashed_password}', 1, NOW(), NOW(), 1, 'admin', 'Admin', 1
);
"""

# Run mysql command
cmd = ["mysql", "-u", "root", "shuleapp", "-e", sql]
try:
    subprocess.run(cmd, check=True)
    print("Test user inserted successfully.")
except subprocess.CalledProcessError as e:
    # If duplicate, that's fine, we might have run this before or username matches
    print(f"Error inserting user (might already exist): {e}")

