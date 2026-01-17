# Feature Specifications

This directory contains feature specifications for the ShuleLabs application.

## Files

- **FEATURE_TEMPLATE.md** - Template for creating new feature specs
- **placeholder-spec.md** - Example placeholder spec to validate the pipeline

## Auto-generation

Feature specs can be automatically generated using the AI workflow defined in `.github/workflows/ai-generate-features.yml`.

The workflow:
1. Runs on schedule (weekly) or manual trigger
2. Extracts routes and tests using `scripts/extract-features.sh`
3. Generates specs using `scripts/ai-orchestrator.sh` with OpenAI API
4. Creates a PR with generated specs for review

## Template Format

All specs should follow the format defined in FEATURE_TEMPLATE.md with:
- YAML frontmatter (title, slug, summary, owner, tags, evidence, confidence)
- Description section
- User flows
- Acceptance criteria
- Related code/tests
- Implementation notes
