# Phase 0 - Audit Plan

**Date:** 2026-05-06
**Branch:** phase-0-strategic-foundation-20250506-v2

## Audit Objectives
- Complete technical inventory of the current codebase.
- Identify all legacy components, security risks, and technical debt.
- Map business logic vs presentation layers.
- Assess current dependencies and compatibility.

## Detailed Audit Steps
1. **Repository Structure Analysis**
   - Full directory tree.
   - File-by-file classification (legacy / partially modern / modern).

2. **Dependency Audit**
   - composer.json review.
   - Outdated / vulnerable packages.
   - PEAR remnants.

3. **Codebase Inventory**
   - PHP version compatibility.
   - Database interactions (PDO status).
   - Routing, Auth, Forms status.

4. **Security & Best Practices**
   - Input validation, SQL injection risks.
   - Session management.
   - File permissions and includes.

5. **Business Logic Preservation**
   - Identify core ISO 9001 / 14001 workflows.

## Deliverables
- Updated `.agents/repo-context.md`
- Risk matrix.
- Prioritized findings.

**Success Metric:** Full audit documented before any code changes.