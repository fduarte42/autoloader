<?php
/**
 * Loading SoapServer
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
 * Loading SoapServer
 */
class SoapServer implements LoaderInterface
{

    /**
     * Get all the complex data for the loader.
     * This return value will be cached and stored in the database
     * There is no file monitoring for this cache
     *
     * @param Loader $autoLoader
     * @param int $type
     *
     * @return array
     */
    public function prepareLoader(Loader $autoLoader, $type)
    {
        $servicePath = ExtensionManagementUtility::extPath($autoLoader->getExtensionKey()) . 'Classes/Service/Soap/';
        $serviceClasses = FileUtility::getBaseFilesRecursivelyInDir($servicePath, 'php');

        $info = [];
        foreach ($serviceClasses as $service) {
            $serviceClass = ClassNamingUtility::getFqnByPath(
                $autoLoader->getVendorName(),
                $autoLoader->getExtensionKey(),
                'Service/Soap/' . $service
            );
            $info[lcfirst($service)] = $serviceClass;
        }

        return $info;
    }

    /**
     * Run the loading process for the ext_tables.php file
     *
     * @param Loader $autoLoader
     * @param array $loaderInformation
     *
     * @return NULL
     */
    public function loadExtensionTables(Loader $autoLoader, array $loaderInformation)
    {
        foreach ($loaderInformation as $key => $class) {
            $GLOBALS['TYPO3_CONF_VARS']['AUTOLOADER']['Soap'][$key] = $class;
        }
        return null;
    }

    /**
     * Run the loading process for the ext_localconf.php file
     *
     * @param Loader $autoLoader
     * @param array $loaderInformation
     *
     * @return NULL
     */
    public function loadExtensionConfiguration(Loader $autoLoader, array $loaderInformation)
    {
        foreach ($loaderInformation as $key => $class) {
            $GLOBALS['TYPO3_CONF_VARS']['AUTOLOADER']['Soap'][$key] = $class;
        }
        return null;
    }
}
