<?php

declare(strict_types=1);

namespace Tuqan\Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * Legacy Bloat Removal Audit (Stage 5)
 *
 * This test exists to make the bloat removal changes self-documenting and
 * regression-proof in the PR. It asserts that the large attack-surface and
 * size-heavy legacy components have been moved out of the active tree into
 * archive/legacy/ (while preserving functionality via updated call sites).
 *
 * See .agents/STAGE-CHECKLISTS.md Stage 5 and MIGRATION-PLAN.md for context.
 */
final class LegacyBloatAuditTest extends TestCase
{
    private string $basePath;

    protected function setUp(): void
    {
        // __DIR__ = tests/Unit when running via phpunit in the container
        $this->basePath = dirname(__DIR__, 2);
    }

    public function testFckeditorHasBeenArchived(): void
    {
        // Old active location must no longer exist
        $this->assertDirectoryDoesNotExist(
            $this->basePath . '/javascript/FCKeditor',
            'FCKeditor must be archived (was 3.2MB attack surface)'
        );

        // New archive location must exist with the core entry file
        $this->assertFileExists(
            $this->basePath . '/archive/legacy/fckeditor/fckeditor.php',
            'FCKeditor functionality preserved under archive/legacy/'
        );
    }

    public function testImageGraphHasBeenArchived(): void
    {
        $this->assertDirectoryDoesNotExist(
            $this->basePath . '/Image',
            'Image/ (incl. Graph 2.5MB) must be archived'
        );

        $this->assertFileExists(
            $this->basePath . '/archive/legacy/Image/Graph.php',
            'Image_Graph library preserved under archive/ for graficamensajes.php'
        );
    }

    public function testLargeDatabaseBackupHasBeenArchived(): void
    {
        $this->assertFileDoesNotExist(
            $this->basePath . '/scripts/qnova.backup',
            '27MB qnova.backup moved out of active scripts/'
        );

        $this->assertFileExists(
            $this->basePath . '/archive/db-dumps/qnova.backup',
            'Large historical dump preserved for manual restore/audit'
        );
    }

    public function testNoCvsDirectoriesRemain(): void
    {
        // Quick smoke that the 103 ancient CVS metadata dirs were cleaned
        $cvsDirs = glob($this->basePath . '/*/CVS', GLOB_ONLYDIR) ?: [];
        $cvsDirs = array_merge($cvsDirs, glob($this->basePath . '/*/*/CVS', GLOB_ONLYDIR) ?: []);

        $this->assertCount(
            0,
            $cvsDirs,
            'All CVS/ directories (ancient metadata bloat) should have been removed in Stage 5'
        );
    }
}
