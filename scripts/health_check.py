import requests
from bs4 import BeautifulSoup
import re
import urllib.parse
import sys

BASE_URL = "http://127.0.0.1:8000"
LOGIN_URL = f"{BASE_URL}/signin/index"
USERNAME = "testadmin"
PASSWORD = "password"

# Session to hold cookies
session = requests.Session()

def login():
    print(f"[*] Attempting login as {USERNAME}...")
    try:
        # Get the login page first to maybe get a CSRF token if needed (CodeIgniter usually uses one)
        response = session.get(LOGIN_URL)
        soup = BeautifulSoup(response.text, 'html.parser')
        
        # Check for CSRF token
        csrf_token = ""
        csrf_Name = ""
        csrf_input = soup.find('input', dict(name=re.compile('csrf_test_name'))) # Standard CI name
        if csrf_input:
            csrf_token = csrf_input['value']
            csrf_Name = csrf_input['name']
            print(f"[*] Found CSRF token: {csrf_token}")
        
        payload = {
            'username': USERNAME,
            'password': PASSWORD
        }
        if csrf_token:
            payload[csrf_Name] = csrf_token

        # Perform login
        response = session.post(LOGIN_URL, data=payload)
        
        # Check if login was successful. Usually redirects to dashboard.
        if "dashboard" in response.url or "Sign out" in response.text or "Dashboard" in response.text:
            print("[+] Login Successful!")
            return True
        else:
            print("[-] Login Failed. Check credentials or captcha.")
            # Debug: print title
            print(f"Current Page Title: {soup.title.string if soup.title else 'No Title'}")
            return False

    except Exception as e:
        print(f"[-] Login Error: {e}")
        return False

def crawl():
    print(f"\n[*] Starting Scan of {BASE_URL}...")
    
    # Start at dashboard
    dashboard_url = f"{BASE_URL}/dashboard/index"
    response = session.get(dashboard_url)
    
    if response.status_code != 200:
        print(f"[-] Critical: Dashboard returned {response.status_code}")
        return

    soup = BeautifulSoup(response.text, 'html.parser')
    
    # Extract all internal links
    links_to_visit = set()
    external_assets = set()
    internal_assets_missing = set()
    
    # Simple recursive finder for sidebar/menu links only first
    # We look for <a> tags with href starting with BASE_URL or /
    
    for a in soup.find_all('a', href=True):
        href = a['href']
        # Normalize
        if href.startswith(BASE_URL):
            links_to_visit.add(href)
        elif href.startswith('/'):
            links_to_visit.add(BASE_URL + href)
    
    print(f"[*] Found {len(links_to_visit)} links on Dashboard.")
    
    # Crawl each link
    results = []
    
    for link in list(links_to_visit)[:50]: # Limit to 50 for speed in this test
        try:
            r = session.get(link, timeout=5)
            status = r.status_code
            
            # Check for offline violations in the page content
            page_content = r.text
            page_soup = BeautifulSoup(page_content, 'html.parser')
            
            violations = []
            
            # Check for CDN links
            for tag in page_soup.find_all(['script', 'link', 'img'], src=True):
                if is_external(tag.get('src')):
                    violations.append(tag.get('src'))
            for tag in page_soup.find_all(['link'], href=True):
                 if is_external(tag.get('href')):
                    violations.append(tag.get('href'))
                    
            results.append({
                'url': link,
                'status': status,
                'violations': violations
            })
            
            if status == 200:
                 print(f"[OK] {link}")
            else:
                 print(f"[FAIL] {link} ({status})")

        except Exception as e:
            print(f"[ERR] {link} : {e}")
            results.append({'url': link, 'status': 'ERR', 'violations': []})

    # Report
    print("\n" + "="*50)
    print("HEALTH CHECK REPORT")
    print("="*50)
    
    print(f"{'URL':<60} | {'STATUS':<6} | {'OFFLINE VIOLATIONS'}")
    print("-" * 100)
    
    issues_found = 0
    for res in results:
        v_count = len(res['violations'])
        status = res['status']
        if status != 200 or v_count > 0:
            issues_found += 1
            print(f"{res['url']:<60} | {status:<6} | {v_count} found")
            if v_count > 0:
                for v in res['violations'][:3]: # Show first 3
                    print(f"   -> {v}")
                if v_count > 3: print("   -> ...")

    if issues_found == 0:
        print("All scanned links are Healthy and Offline-Ready!")
    else:
        print(f"\nFound issues in {issues_found} pages.")

def is_external(url):
    if not url: return False
    return "http" in url and "127.0.0.1" not in url and "localhost" not in url

if __name__ == "__main__":
    if login():
        crawl()
    else:
        sys.exit(1)
