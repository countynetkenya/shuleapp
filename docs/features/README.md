# Feature Documentation

This directory contains per-feature documentation, organized by controller name. Each file
maps to a controller under `mvc/controllers/` and should explain the feature's purpose,
primary routes, and any configuration or data dependencies.

## How to update

1. Identify the controller in `mvc/controllers/` that owns the feature behavior.
2. Update the matching `docs/features/<Controller>.md` with:
   - Purpose and user-facing summary
   - Key routes/screens
   - Configuration or permission requirements
   - Data models or tables touched
   - Edge cases and known limitations
3. Re-run `scripts/generate-feature-index.sh` after adding new controllers to keep
   `docs/FEATURE_INDEX.md` and `docs/FEATURE_CATALOG.md` in sync.
