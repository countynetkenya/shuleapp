# Theming & UI Guidelines

## Critical Architecture Note: AdminLTE Skins

**WARNING**: The theming system in this application has a specific quirk regarding AdminLTE skins.

### The "skin-blue" dependency
*   **Behavior**: The application allows users to select themes (Green, Red, Purple, etc.) via the backend settings.
*   **Implementation**: However, the legacy CSS files for these themes (located in `assets/shuleapp/themes/`) generally contain selectors that target `.skin-blue`, or are copy-pasted from the Blue skin without renaming the top-level class.
*   **Rule**: **DO NOT** change the `<body>` class in `mvc/views/components/page_header.php`. It must remain hardcoded to `skin-blue fuelux` (or include `skin-blue`) for the theme CSS to apply correctly.
    *   ❌ **Incorrect**: `<body class="skin-<?php echo $theme; ?>">`
    *   ✅ **Correct**: `<body class="skin-blue fuelux">`

### Global CSS Overrides
To apply global UI fixes that persist across all themes:
*   **File**: `assets/shuleapp/combined.css`
*   **Usage**: This file is loaded *after* individual theme files, allowing for global overrides.
*   **Active Fixes**:
    *   **Generic Box Styling**: Standard AdminLTE `.box` elements (used in Question Bank, Teacher views, etc.) lack a top border in some custom themes. A global fix enforces `border-top: 3px solid #d2d6de;`.

### Debugging UI Issues
If a page looks "un-styled" or flat (missing borders, shadows):
1.  Check if `combined.css` is loaded.
2.  Verified the `<body>` tag has `skin-blue`.
3.  Inspect if the page uses `.box` (requires global fix) or `.small-box` (Dashboard widgets).
