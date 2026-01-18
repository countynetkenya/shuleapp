# Performance Optimization Guide

## Architecture
- **Lazy Loading**: Avoid loading heavy libraries (SMS, Email) in `__construct` of base controllers (`Admin_Controller`). Load them only in the methods that use them.
- **Database**: 
  - Ensure all `WHERE` clauses use indexed columns.
  - Use `EXPLAIN` on slow queries.
- **Caching**:
  - Enable OpCache in `php.ini`.
  - Use CodeIgniter's `db->cache_on()` for read-heavy, write-rare queries.

## Frontend
- **Assets**: 
  - Minify CSS and JS files for production.
  - Use a CDN for static assets if possible.
- **Images**: optimize images before upload.

## AI Optimization
- Agents can run `scripts/analyze_slow_logs.sh` (future) to identify bottlenecks.
