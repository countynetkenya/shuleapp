# GitHub Copilot Instructions for Shuleapp

## Project Overview

Shuleapp is a PHP-based school management application built on CodeIgniter 2/3 framework. The application manages student information, academic records, fees, and various school administrative functions.

## Tech Stack

- **Backend**: PHP 7.4+ with CodeIgniter 2/3 framework
- **Database**: MySQL/MariaDB (relational database)
- **Frontend**: Static HTML/JavaScript/CSS assets
- **Session Management**: CodeIgniter session drivers
- **Dependencies**: Managed via Composer (see `composer.json`)
  - PDF generation: mPDF
  - Spreadsheet: PhpSpreadsheet
  - Payment gateways: Omnipay (PayPal, Stripe)
  - M-Pesa integration
  - QuickBooks SDK

## Directory Structure

### Core Application Files
- **`index.php`**: Application entry point (root level)
- **`mvc/`**: Main application directory (CodeIgniter-style)
  - `controllers/`: Business logic controllers (282+ controllers)
  - `models/`: Database models and data access layer
  - `views/`: HTML templates and view files
  - `config/`: Configuration files (database, routes, etc.)
  - `helpers/`: Custom helper functions
  - `libraries/`: Custom libraries (M-Pesa, etc.)
- **`main/`**: CodeIgniter core files and base libraries
  - `core/`: Framework core files
  - `libraries/`: Core libraries (Session, etc.)
  - `helpers/`: Core helper functions

### Additional Directories
- **`assets/`**: Static frontend assets (CSS, JS, images, third-party libraries)
- **`frontend/`**: Additional frontend resources
- **`docs/`**: Comprehensive documentation
  - `ARCHITECTURE.md`: System architecture overview
  - `adr/`: Architecture Decision Records
  - `features/`: Per-controller feature documentation
  - `runbooks/`: Operational guides
- **`specs/`**: Feature specifications and requirements
- **`tests/`**: Test files (limited test infrastructure currently)
- **`scripts/`**: Utility scripts (e.g., `generate-feature-index.sh`)
- **`config/`**: Environment-specific configurations

## Coding Conventions and Best Practices

### General Rules
- **Minimal changes**: Make the smallest possible changes to achieve the goal. Prefer config-driven behavior over modifying multiple files.
- **No secrets**: Never commit secrets, API keys, or credentials. Use `.env` file (git-ignored) or environment variables.
- **Backwards compatibility**: This is a legacy monolithic application. Prioritize backwards compatibility.
- **CodeIgniter patterns**: Follow CodeIgniter MVC patterns and naming conventions.

### PHP Code Style
- Follow PSR-1 and PSR-2 coding standards where practical
- Use meaningful variable and function names
- Maintain consistency with existing code style in the file you're modifying
- Use PHP type hints where appropriate (when adding new code)

### Database
- Database configuration: `mvc/config/production/database.php` (with environment-aware fallbacks)
- Use CodeIgniter's Query Builder for database operations
- Prefer prepared statements to prevent SQL injection
- Never hardcode database credentials

### Environment Configuration
- Configuration should be environment-aware
- Use `.env` file for local development settings (copy from `.env.example`)
- Environment variables should be loaded via `config/bootstrap_env.php`
- Support for proxy headers (X-Forwarded-Proto, X-Forwarded-Host) for deployment flexibility

### Security
- Always sanitize user input
- Use CodeIgniter's XSS filtering and input validation
- Implement proper authentication and authorization checks
- Follow secure coding practices to prevent SQL injection, XSS, and CSRF attacks
- Never expose sensitive information in error messages or logs

## Testing

### Current Test Infrastructure
- Limited test infrastructure exists in `tests/` directory
- Tests are written as standalone PHP scripts (not PHPUnit-based yet)
- Example: `tests/base_url_test.php` for configuration testing

### Testing Approach
- When modifying code, add tests if they fit the existing pattern in `tests/`
- Manual testing may be required for many features
- If adding significant new functionality, consider adding corresponding test files
- Run existing tests before and after changes to ensure no regressions

### Running Tests
```bash
# Run individual test files
php tests/base_url_test.php
php tests/test_student_autoload.php
```

## Documentation Requirements

### When to Update Documentation
- **Always update** `docs/` when making significant changes
- Create ADRs in `docs/adr/` for architectural decisions (use existing ADRs as templates)
- Update or create feature docs in `docs/features/` for controller changes
- Run `scripts/generate-feature-index.sh` to refresh `docs/FEATURE_INDEX.md` after adding or modifying features

### Documentation Style
- Use clear, concise Markdown
- Include code examples where helpful
- Document the "why" not just the "what"
- Keep `ARCHITECTURE.md` up-to-date with structural changes

## Development Workflow

### Standard Flow
1. **Spec first**: Create or update a spec in `specs/` for the feature/bug
2. **Generate index**: Run `scripts/generate-feature-index.sh` to update feature catalog
3. **Make changes**: Implement in `mvc/` or `main/` as appropriate
4. **Test**: Add or update tests in `tests/` and run manual checks
5. **Document**: Update relevant documentation
6. **Commit**: Use clear, descriptive commit messages with `AGENT:` prefix for AI-generated changes

### Git Practices
- Write clear, descriptive commit messages
- For AI agent changes, prefix with `AGENT:` (e.g., "AGENT: Add student fee calculation feature")
- Keep commits focused and atomic

## Key Features and Modules

### Core Modules
- Student management and enrollment
- Academic records and grading
- Fee management and payment processing
- Staff and teacher management
- Attendance tracking
- Timetable management
- Library management
- Inventory and assets

### Payment Integration
- M-Pesa payment gateway (`mvc/libraries/Mpesa/`)
- Stripe and PayPal via Omnipay
- QuickBooks integration for accounting

### Reporting
- PDF generation using mPDF
- Excel exports using PhpSpreadsheet
- Custom report builders per module

## Common Pitfalls to Avoid

1. **Don't break existing functionality**: This is a production system with 282+ controllers. Be extremely careful with global changes.
2. **Don't remove working code**: Only modify code when absolutely necessary or fixing security vulnerabilities.
3. **Don't add unnecessary dependencies**: Use existing libraries when possible.
4. **Don't ignore error handling**: Always handle errors gracefully with user-friendly messages.
5. **Don't hardcode values**: Use configuration files for any environment-specific or changeable values.

## Resources

- **Main documentation**: See `docs/ARCHITECTURE.md` for system overview
- **Agent guidelines**: See `AGENTS.md` for AI agent-specific rules
- **ADRs**: Check `docs/adr/` for architectural decision history
- **Feature catalog**: See `docs/FEATURE_CATALOG.md` and `docs/FEATURE_INDEX.md`
- **Runbooks**: See `docs/runbooks/` for deployment and operational guides

## Special Considerations

### Legacy Codebase
- This is a mature application with significant legacy code
- Modernize incrementally, not through large rewrites
- Test thoroughly when touching core functionality
- Respect existing patterns even if they're not modern best practices

### Kenyan Context
- Timezone: Africa/Nairobi (set in `index.php`)
- Currency: Kenyan Shillings (KES)
- M-Pesa is the primary mobile payment method
- School system follows Kenyan curriculum structure

### Performance
- The application serves multiple schools with potentially large datasets
- Consider query performance when modifying database operations
- Use pagination for large result sets
- Cache appropriately but avoid stale data in critical areas

## Questions or Clarifications

When uncertain about:
- **Architecture decisions**: Check `docs/adr/` or ask for guidance
- **Feature implementation**: Review existing similar controllers in `mvc/controllers/`
- **Configuration**: Check `mvc/config/` and `.env.example`
- **Business logic**: Consult feature docs in `docs/features/` or specs in `specs/`
