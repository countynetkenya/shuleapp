import urllib.request
import urllib.parse
import http.cookiejar
import time
import sys

# Configuration
BASE_URL = "http://localhost:8000"
LOGIN_URL = f"{BASE_URL}/signin/index"
DASHBOARD_URL = f"{BASE_URL}/dashboard/index"
USERNAME = "admin"  # Replace with actual credential if known or ask user. 
# Based on context, DB user is 'shuleapp', but app login path unknown. 
# Wait, I can't login without credentials.
# However, I can measure the login page load time itself.
# If the user is already logged in (in browser), the dashboard is slow.
# If I can't login via script, I can only test public pages.

def benchmark_url(opener, url, name):
    print(f"Testing {name} ({url})...")
    start_time = time.time()
    try:
        response = opener.open(url)
        content = response.read()
        end_time = time.time()
        duration = end_time - start_time
        print(f"✅ {name}: Loaded in {duration:.2f} seconds. Status: {response.getcode()}")
        return duration
    except Exception as e:
        end_time = time.time()
        duration = end_time - start_time
        print(f"❌ {name}: Failed after {duration:.2f} seconds. Error: {e}")
        return duration

def main():
    cj = http.cookiejar.CookieJar()
    opener = urllib.request.build_opener(urllib.request.HTTPCookieProcessor(cj))
    
    # 1. Test Login Page (Public)
    time_login_page = benchmark_url(opener, LOGIN_URL, "Login Page")

    # 2. Attempt Login (If we had creds)
    # For now, just report the static page speed.
    
    print("\n--- Summary ---")
    print(f"Login Page Load Time: {time_login_page:.2f}s")
    
    if time_login_page > 2.0:
        print("⚠️  Page load is slow (> 2s).")
    else:
        print("🚀 Page load is fast.")

if __name__ == "__main__":
    main()
