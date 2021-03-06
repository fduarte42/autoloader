<?php
/**
 * Loading CommandController
 *
 * @author Tim Lochmüller
 */

namespace HDNET\Autoloader\Loader;

use HDNET\Autoloader\Loader;
use HDNET\Autoloader\LoaderInterface;
use HDNET\Autoloader\Utility\ClassNamingUtility;
use HDNET\Autoloader\Utility\FileUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * Loading CommandController
 */
class CommandController implements LoaderInterface
{

    /**
     * Get all the complex data for the loader.
     * This return value will be cached and stored in the database
     * There is no file monitoring for this cache
     *
     * @param Loader $loader
     * @param int $type
     *
     * @return array
     */
    public function prepareLoader(Loader $loader, $type)
    {
        $classNames = [];
        $commandPath = ExtensionManagementUtility::extPath($loader->getExtensionKey()) . 'Classes/Command/';
        $controllers = FileUtility::getBaseFilesInDir($commandPath, 'php');
        foreach ($controllers as $controller) {
            if ($controller === 'AbstractCommandController') {
                continue;
            }

            $className = ClassNamingUtility::getFqnByPath(
                $loader->getVendorName(),
                $loader->getExtensionKey(),
                'Command/' . $controller
            );
            if (!$loader->isInstantiableClass($className)) {
                continue;
            }

            $classNames[] = $className;
        }
        return $classNames;
    }

    /**
     * Run the loading process for the ext_tables.php file
     *
     * @param Loader $loader
     * @param array $loaderInformation
     *
     * @return NULL
     */
    public function loadExtensionTables(Loader $loader, array $loaderInformation)
    {
        return null;
    }

    /**
     * Run the loading process for the ext_localconf.php file
     *
     * @param Loader $loader
     * @param array $loaderInformation
     *
     * @return NULL
     */
    public function loadExtensionConfiguration(Loader $loader, array $loaderInformation)
    {
        foreach ($loaderInformation as $className) {
            $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][] = $className;
        }
        return null;
    }
}
