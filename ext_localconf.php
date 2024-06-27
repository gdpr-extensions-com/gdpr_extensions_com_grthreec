<?php
defined('TYPO3') || die();

(static function() {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'GdprExtensionsComGrthreec',
        'gdprgooglethreecgrid',
        [
            \GdprExtensionsCom\GdprExtensionsComGrthreec\Controller\GdprGoogleReviewThreeCgdController::class => 'index , showReviews'
        ],
        // non-cacheable actions
        [
            \GdprExtensionsCom\GdprExtensionsComGrthreec\Controller\GdprGoogleReviewThreeCgdController::class => 'showReviews',
            \GdprExtensionsCom\GdprExtensionsComGrthreec\Controller\GdprManagerController::class => 'create, update, delete'
        ],
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );

    // register plugin for cookie widget
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'GdprExtensionsComGrthreec',
        'gdprcookiewidget',
        [
            \GdprExtensionsCom\GdprExtensionsComGrthreec\Controller\GdprCookieWidgetController::class => 'index'
        ],
        // non-cacheable actions
        [],
    );

    // wizards
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        'mod {
            wizards.newContentElement.wizardItems.plugins {
                elements {
                    gdprcookiewidget {
                        iconIdentifier = gdpr_extensions_com_grthreec-plugin-gdprcookiewidget
                        title = cookie
                        description = LLL:EXT:gdpr_extensions_com_grthreec/Resources/Private/Language/locallang_db.xlf:tx_gdpr_extensions_com_grthreec_gdprcookiewidget.description
                        tt_content_defValues {
                            CType = list
                            list_type = gdprextensionscomgrthreec_gdprcookiewidget
                        }
                    }
                }
                show = *
            }
       }'
    );
        // wizards
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
            'mod.wizards.newContentElement.wizardItems {
                   gdpr.header = LLL:EXT:tx_gdpr_extensions_com_grthreec/Resources/Private/Language/locallang_db.xlf:tx_gdpr_extensions_com_grthreec.name.tab
            }'
        );
    
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        'mod {
            wizards.newContentElement.wizardItems.gdpr {
                elements {
                    gdprgooglethreecgrid {
                        iconIdentifier = gdpr_extensions_com_grthreec-plugin-gdprgoogletwocgrid-dark
                        title = LLL:EXT:gdpr_extensions_com_grthreec/Resources/Private/Language/locallang_db.xlf:tx_gdpr_extensions_com_grthreec_ggdprgoogletwocgrid.name
                        description = LLL:EXT:gdpr_extensions_com_grthreec/Resources/Private/Language/locallang_db.xlf:tx_gdpr_extensions_com_grthreec_ggdprgoogletwocgrid.description
                        tt_content_defValues {
                            CType = gdprextensionscomgrthreec_gdprgooglethreecgrid
                        }
                    }
                }
                show = *
            }
       }'
    );
    // $registeredTasks = $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'];
    // $alreadyRegistered = 0;
    // foreach($registeredTasks as $registeredTask){

    //     if(isset($registeredTask['extension']) && (strpos($registeredTask['extension'], 'gdpr_extensions_com_gr')!== false || strpos($registeredTask['extension'], 'GdprExtensionsComGr') !== false)){
    //         $alreadyRegistered +=1;
    //     }
        
    // }
 
    // if(!$alreadyRegistered){
    // }
    
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\GdprExtensionsCom\GdprExtensionsComGrthreec\Commands\SyncReviewsTask::class] = [
        'extension' => 'gdpr_extensions_com_grthreec',
        'title' => 'LLL:EXT:gdpr_extensions_com_grthreec/Resources/Private/Language/locallang.xlf:tx_gdpr_extensions_com_grthreec_schedular_title',
        'description' => 'LLL:EXT:gdpr_extensions_com_grthreec/Resources/Private/Language/locallang.xlf:tx_gdpr_extensions_com_grthreec_schedular_description',
        'additionalFields' => \GdprExtensionsCom\GdprExtensionsComGrthreec\Commands\SyncReviewsTask::class,
    ];
})();
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = \GdprExtensionsCom\GdprExtensionsComGrthreec\Hooks\DataHandlerHook::class;
if (!isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['GdprExtensionsComGrthreec'])) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['GdprExtensionsComGrthreec'] = [
        'frontend' => \TYPO3\CMS\Core\Cache\Frontend\VariableFrontend::class,
        'backend' => \TYPO3\CMS\Core\Cache\Backend\FileBackend::class,
        'groups' => ['all', 'GdprExtensionsComGrthreec'],
        'options' => [
            'defaultLifetime' => 3600, // Cache lifetime in seconds
        ],
    ];
}
