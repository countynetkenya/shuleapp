# ARCHITECTURE

Overview

- Type: PHP monolith (CodeIgniter-style MVC + custom folders)
- Frontend: static assets under `assets/` and `frontend/`
- Backend: PHP controllers and models in `mvc/` and `main/`
- DB: relational (MySQL/MariaDB expected) configured under `mvc/config/production/database.php`
- Routing: `mvc/config/routes.php` and `index.php` at project root
- Sessions/Caching: CodeIgniter session drivers present (`main/libraries/Session`)
- Notes: Many CodeIgniter core files are present in `main/` indicating CI v2/v3 style structure.

Key locations

- Application entry: `index.php`
- Config: `mvc/config/*.php`, `main/core/Config.php`
- Controllers: `mvc/controllers/`
- Models: `mvc/models/`
- Views: `mvc/views/`
- Helpers: `main/helpers/` and `mvc/helpers/`

Next steps

- Run automated inventory (controllers, routes, DB config) using `scripts/generate-feature-index.sh`.
- Perform environment portability audit and produce `docs/architecture/environment-configuration.md`.
