<?php declare(strict_types=1);

namespace Wordless\Tests\Unit;

use PHPUnit\Framework\ExpectationFailedException;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToGetCurrentWorkingDirectory;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Tests\WordlessTestCase;

class ProjectPathHelperTest extends WordlessTestCase
{
    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testAppPath()
    {
        $this->assertPathTo('app');
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testAssetsPath()
    {
        $this->assertPathTo('assets');
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testCachePath()
    {
        $this->assertPathTo('cache');
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testCommandsPath()
    {
        $this->assertPathTo('app/Commands', 'commands');
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testConfigPath()
    {
        $this->assertPathTo('config');
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testControllersPath()
    {
        $this->assertPathTo('app/Controllers', 'controllers');
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testCustomPostTypesPath()
    {
        $this->assertPathTo('app/CustomPostTypes', 'customPostTypes');
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testCustomTaxonomiesPath()
    {
        $this->assertPathTo('app/CustomTaxonomies', 'customTaxonomies');
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testDistPath()
    {
        $this->assertPathTo('dist');
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testDockerPath()
    {
        $this->assertPathTo('docker');
    }

    /**
     * @return void
     * @throws PathNotFoundException
     */
    public function testInvalidPath()
    {
        $this->expectException(PathNotFoundException::class);

        ProjectPath::root(Str::uuid());
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testListenersPath()
    {
        $this->assertPathTo('app/Listeners', 'listeners');
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testMigrationsPath()
    {
        $this->assertPathTo('migrations');
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testPath()
    {
        try {
            $path = ProjectPath::path(__DIR__);
        } catch (PathNotFoundException $exception) {
            $path = $exception->path;
        }

        $this->assertTrue(Str::beginsWith($path, DIRECTORY_SEPARATOR));
        $this->assertFalse(Str::endsWith($path, DIRECTORY_SEPARATOR));
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testProvidersPath()
    {
        $this->assertPathTo('app/Providers', 'providers');
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testPublicPath()
    {
        $this->assertPathTo('public');
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws PathNotFoundException
     */
    public function testRealpath()
    {
        $this->assertTrue(is_link(ProjectPath::realpath(
            realpath(__DIR__ . '/../..') . DIRECTORY_SEPARATOR . 'config'
        )));
        $this->assertTrue(is_dir(ProjectPath::realpath(
            realpath(__DIR__ . '/../..') . DIRECTORY_SEPARATOR . 'docker'
        )));
        $this->assertTrue(is_file(ProjectPath::realpath(
            realpath(__DIR__ . '/../..') . DIRECTORY_SEPARATOR . 'composer.json'
        )));
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws PathNotFoundException
     * @throws FailedToGetCurrentWorkingDirectory
     */
    public function testRelativeToPath(): void
    {
        $this->assertEquals(
            '../../docker',
            ProjectPath::relativeTo(ProjectPath::docker(), __DIR__)
        );
        $this->assertEquals(
            'StrHelperTest/Traits/BooleanTests.php',
            ProjectPath::relativeTo(
                __DIR__ . '/StrHelperTest/Traits/BooleanTests.php',
                __DIR__
            )
        );
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testRootToPath(): void
    {
        $this->assertPathTo('', 'root');
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testSchedulersPath()
    {
        $this->assertPathTo('app/Schedulers', 'schedulers');
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testScriptsPath()
    {
        $this->assertPathTo('app/Scripts', 'scripts');
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testSrcPath(): void
    {
        $this->assertPathTo('src');
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testSrcApplicationPath(): void
    {
        $this->assertPathTo('src/Application', 'srcApplication');
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testStubsPath(): void
    {
        $this->assertPathTo('stubs');
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testStylesPath(): void
    {
        $this->assertPathTo('app/Styles', 'styles');
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testThemePath(): void
    {
        $this->assertPathTo('wp/wp-content/themes/wordless', 'theme');
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testVendorPath(): void
    {
        $this->assertPathTo('vendor');
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testVendorPackageRootPath(): void
    {
        $this->assertPathTo('', 'vendorPackageRoot');
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testWpPath(): void
    {
        $this->assertPathTo('wp');
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testWpCorePath(): void
    {
        $this->assertPathTo('wp/wp-core', 'wpCore');
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testWpContentPath(): void
    {
        $this->assertPathTo('wp/wp-content', 'wpContent');
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testWpMustUsePluginsPath(): void
    {
        $this->assertPathTo('wp/wp-content/mu-plugins', 'wpMustUsePlugins');
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testWpPluginsPath(): void
    {
        $this->assertPathTo('wp/wp-content/plugins', 'wpPlugins');
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testWpThemesPath(): void
    {
        $this->assertPathTo('wp/wp-content/themes', 'wpThemes');
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testWpUploadsPath(): void
    {
        $this->assertPathTo('wp/wp-content/uploads', 'wpUploads');
    }

    /**
     * @param string $expected_relative_path_from_root
     * @param string|null $project_path_method_name
     * @return void
     * @throws ExpectationFailedException
     */
    private function assertPathTo(
        string  $expected_relative_path_from_root,
        ?string $project_path_method_name = null
    ): void
    {
        $expected_absolute_path_from_root = ROOT_PROJECT_PATH
            . DIRECTORY_SEPARATOR
            . $expected_relative_path_from_root;
        $project_path_method_name ??= $expected_relative_path_from_root;

        try {
            $absolute_path_to_assert = ProjectPath::$project_path_method_name();
            $expected_absolute_path_from_root = ProjectPath::realpath($expected_absolute_path_from_root);
        } catch (PathNotFoundException $exception) {
            $absolute_path_to_assert = $exception->path;
        }

        $this->assertEquals($expected_absolute_path_from_root, $absolute_path_to_assert);
    }
}
