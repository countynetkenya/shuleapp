#!/bin/bash
set -e

# Create directories
mkdir -p assets/vendor/jquery
mkdir -p assets/vendor/bootstrap/css
mkdir -p assets/vendor/bootstrap/js
mkdir -p assets/vendor/bootstrap-multiselect/css
mkdir -p assets/vendor/bootstrap-multiselect/js
mkdir -p assets/vendor/shim
mkdir -p assets/vendor/google-fonts

# Download jQuery
echo "Downloading jQuery..."
curl -L -o assets/vendor/jquery/jquery.min.js "https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"

# Download Bootstrap 3.3.7
echo "Downloading Bootstrap..."
curl -L -o assets/vendor/bootstrap/css/bootstrap.min.css "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
curl -L -o assets/vendor/bootstrap/js/bootstrap.min.js "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"

# Download Bootstrap Multiselect
echo "Downloading Bootstrap Multiselect..."
curl -L -o assets/vendor/bootstrap-multiselect/css/bootstrap-multiselect.css "https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css"
curl -L -o assets/vendor/bootstrap-multiselect/js/bootstrap-multiselect.js "https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"

# Download Shims
echo "Downloading Shims..."
curl -L -o assets/vendor/shim/html5shiv.min.js "https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"
curl -L -o assets/vendor/shim/respond.min.js "https://oss.maxcdn.com/respond/1.4.2/respond.min.js"

# Download Google Fonts (WOFF2)
echo "Downloading Google Fonts..."
# Great Vibes
curl -L -o assets/vendor/google-fonts/greatvibes-v4-woff2.woff2 "https://fonts.gstatic.com/s/greatvibes/v4/6q1c0ofG6NKsEhAc2eh-3Y4P5ICox8Kq3LLUNMylGO4.woff2"

# Allerta Stencil
curl -L -o assets/vendor/google-fonts/allertastencil-v7-woff2.woff2 "https://fonts.gstatic.com/s/allertastencil/v7/CdSZfRtHbQrBohqmzSdDYHyjZGU_SYMIAZWjSGDHnGA.woff2"

# Fira Sans Extra Condensed
curl -L -o assets/vendor/google-fonts/firasansextracondensed-v1-woff2.woff2 "https://fonts.gstatic.com/s/firasansextracondensed/v1/wg_5XrW_o1_ZfuCbAkBfGb36aABA35U5KbBGmAqRMh0SbZyiE6aTiPyL3F1wza7H.woff2"

# Limelight
curl -L -o assets/vendor/google-fonts/limelight-v7-woff2.woff2 "https://fonts.gstatic.com/s/limelight/v7/kD_2YDkzv1rorNqQ2oFK5ltXRa8TVwTICgirnJhmVJw.woff2"

# Michroma
curl -L -o assets/vendor/google-fonts/michroma-v7-woff2.woff2 "https://fonts.gstatic.com/s/michroma/v7/-4P7knfa8IRSEOi-sKrsivesZW2xOQ-xsNqO47m55DA.woff2"

# Prosto One
curl -L -o assets/vendor/google-fonts/prostoone-v5-woff2.woff2 "https://fonts.gstatic.com/s/prostoone/v5/mTFYjVXEgUAP8V1WIJc9cCEAvth_LlrfE80CYdSH47w.woff2"

echo "Download complete."
