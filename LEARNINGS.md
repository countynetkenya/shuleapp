# AI Learnings & Knowledge Base

This file serves as a persistent memory for AI agents working on this project. Agents should check this file **first** before starting complex tasks to avoid repeating past mistakes or relearning known system behaviors.

## 1. Test Credentials & Authentication
Use these accounts for testing. **Do not modify these accounts** unless necessary.

| Role | Username | Password | Expected Dashboard | Notes |
|------|----------|----------|--------------------|-------|
| **Super Admin** | `afyamart@gmail.com` | `drew` | `/admin/index` | Has valid system-wide privileges. |
| **School Admin** | `drewgash@gmail.com` | `drew` | `/dashboard/index` OR `/school/select` | Standard user. Logic redirects to `/school/select` if linked to >1 school, otherwise `/dashboard/index`. |

## 2. System Logic & Workflows

### Login Redirection
Unlike standard MVC apps, `Signin` controller handles redirection based on `usertypeID`:
- **Superadmin (Type 0)**: Redirects strictly to `admin/index`.
- **Others**: Logic checks the number of assigned schools.
    - If `> 1` school: Redirects to `school/select`.
    - If `1` school: Redirects directly to `dashboard/index`.

### Setup Requirements
- When resetting the DB (`scripts/setup_minimal_db.php` or similar), ensure compatibility with these user accounts is maintained or restored.

## 3. Past Mistakes & Fixes
- **CSS Theming**: The `<body>` class in `page_header.php` MUST remain `skin-blue` for legacy theme CSS to work, even if the visual theme is Green/Red. Use `assets/shuleapp/combined.css` for global layout fixes (e.g., adding `.box` borders).
- **Environment**: Always check `config.php` base URL if redirects behave inconsistently.

## 4. Docker & Requirements
- **System Dependencies**: The Dockerfile installs common PHP extensions (`gd`, `mysqli`, `zip`).
- **Composer**: `postCreate.sh` handles `composer install`.
- **Database**: Ensure `mariadb` service is reachable. `postCreate.sh` waits for it.
- **Workflow**: If adding new PHP extensions, update `.devcontainer/Dockerfile`. If adding libraries, use `composer require`.

## 5. Performance & Offline Capability
- **Offline Assets**: 
    - **No CDNs**: Do not use external CDNs (Google Fonts, Highcharts, jQuery, etc.) in views.
    - **Mechanism**: Add new external assets to `scripts/download_offline_assets.sh`, run it to download them to `assets/vendor/`, and reference them locally.
    - **Reasoning**: This ensures the app works offline and eliminates DNS/Connection latency.
- **Service Worker**:
    - `sw.js` in the root implements a "Cache First" strategy for `/assets/`.
    - If you modify core static assets (JS/CSS), you may need to bump `CACHE_NAME` in `sw.js` to force clients to update.
- **Lazy Loading**:
    - **Controllers**: Do not load heavy models (e.g., `mailandsms_m`, `quickbookssettings_m`) in the `__construct` of `Admin_Controller` or `MY_Controller`. Load them only in the specific methods that need them. This significantly improves page load times for the entire app.

## 6. Meta-Instructions for Agents
- **Update This File**: When you solve a complex problem, discover a new "gotcha" (like the login redirect logic), or make a significant architectural decision (like the offline asset strategy), **YOU MUST** update this `LEARNINGS.md` file.
- **Goal**: Help the *next* agent (or your future self) avoid the same research time. Treat this file as the project's long-term memory.

