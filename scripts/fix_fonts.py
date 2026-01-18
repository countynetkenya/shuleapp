
import os

file_path = 'mvc/views/certificate_template/print_preview.php'
replacements = {
    'greatvibes': 'greatvibes-v4-woff2.woff2',
    'allertastencil': 'allertastencil-v7-woff2.woff2',
    'firasansextracondensed': 'firasansextracondensed-v1-woff2.woff2',
    'limelight': 'limelight-v7-woff2.woff2',
    'michroma': 'michroma-v7-woff2.woff2',
    'prostoone': 'prostoone-v5-woff2.woff2'
}

with open(file_path, 'r') as f:
    content = f.read()

import re

# Regex to find these URLs
# Pattern: url(https://fonts.gstatic.com/s/FAMILY/...)
# We extract FAMILY and check if it's in our map.
# If so, replace the whole url(...) block or just the URL.

def replace_match(match):
    url = match.group(0)
    # Check which font family matches
    for font_key, filename in replacements.items():
        if f"/s/{font_key}/" in url:
            new_url = f"<?=base_url('assets/vendor/google-fonts/{filename}')?>"
            return f"url({new_url})"
    return url # No change if not found (shouldn't happen if we map correct keys)

# Pattern matches url(https://fonts.gstatic.com/s/...)
# Captures the whole url(...) content inside is easiest if we just match the https string?
# The file has: url(https://...)
pattern = r"url\(https://fonts\.gstatic\.com/s/[^)]+\)"

new_content = re.sub(pattern, replace_match, content)

with open(file_path, 'w') as f:
    f.write(new_content)

print(f"Updated {file_path}")
