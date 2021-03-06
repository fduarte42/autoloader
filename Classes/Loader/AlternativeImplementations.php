<?php
/**
 * Loading AlternativeImplementations
 *
 * @author Tim Lochmüller
 */

namespace HDNET\Autoloader\Loader;

use HDNET\Autoloader\Loader;
use HDNET\Autoloader\LoaderInterface;
use HDNET\Autoloader\Utility\ClassNamingUtility;
use HDNET\Autoloader\Utility\FileUtility;
use HDNET\Autoloader\Utility\ReflectionUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Loading AlternativeImplementations
 */
class AlternativeImplementations implements LoaderInterface
{

    /**
     * Get all the complex data for the loader.
     * This return value will be cached and stored in the database
     * There is no file monitoring for this cache
     *
     * @param Loader $loader
     * @param int    $type
     *
     * @return array
     */
    public function prepareLoader(Loader $loader, $type)
    {
        $classNames = [];
        $alternativeImpPath = ExtensionManagementUtility::extPath($loader->getExtensionKey()) . 'Classes/AlternativeImplementations/';
        $alternativeClasses = FileUtility::getBaseFilesInDir($alternativeImpPath, 'php');

        foreach ($alternativeClasses as $aic) {
            $aicClass = ClassNamingUtility::getFqnByPath(
                $loader->getVendorName(),
                $loader->getExtensionKey(),
                'AlternativeImplementations/' . $aic
            );

            if (!$loader->isInstantiableClass($aicClass)) {
                continue;
            }

            $classNames[] = [
                'originalName'         => ReflectionUtility::getParentClassName($aicClass),
                'alternativeClassName' => $aicClass,
            ];
        }
        return $classNames;
    }

    /**
     * Run the loading process for the ext_tables.php file
     *
     * @param Loader $loader
     * @param array  $loaderInformation
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
     * @param array  $loaderInformation
     *
     * @return NULL
     */
    public function loadExtensionConfiguration(Loader $loader, array $loaderInformation)
    {
        /** @var \TYPO3\CMS\Extbase\Object\Container\Container $objectManager */
        $objectManager = GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\Container\Container::class);
        foreach ($loaderInformation as $classInformation) {
            $objectManager->registerImplementation($classInformation['originalName'], $classInformation['alternativeClassName']);
        }
        return null;
    }
}
