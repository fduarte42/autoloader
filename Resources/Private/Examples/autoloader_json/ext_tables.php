<?php
/**
 * General ext_tables file and also an example for your own extension
 *
 * @category Extension
 * @package  AutoloaderSoap
 * @author   Tim Lochmüller
 * @author   Tito Duarte
 */

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

\HDNET\Autoloader\Loader::extTables('HDNET', 'autoloader_json', ['JsonServer']);
