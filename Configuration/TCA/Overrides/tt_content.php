<?php
defined('TYPO3') || die();

$frontendLanguageFilePrefix = 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:';
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'GdprExtensionsComGrthreec',
    'gdprgooglethreecgrid', 
    'Google Reviews 3-Col Grid'
);


$fields = [
    'gdpr_button_shape_threecgd' => [
        'onChange' => 'reload',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                ['Round', '1'],
                ['Square', '2'],
            ],
        ],
    ],
    // 'gdpr_business_locations_threecgd' => [
    //     'config' => [
    //         'type' => 'select',
    //         'renderType' => 'selectMultipleSideBySide',
    //         'itemsProcFunc' => 'GdprExtensionsCom\GdprExtensionsComGrthreec\Utility\ProcessSliderItems->getLocationsforRoodPid',
    //     ],
    // ],
    'gdpr_background_color_threecgd' => [
        'config' => [
            'type' => 'input',
            'renderType' => 'colorpicker',
        ],
    ],
    'gdpr_color_of_text_threecgd' => [
        'config' => [
            'type' => 'input',
            'renderType' => 'colorpicker',
        ],
    ],
    'gdpr_button_color_threecgd' => [
        'config' => [
            'type' => 'input',
            'renderType' => 'colorpicker',
        ],
    ],
    'gdpr_button_text_color_threecgd' => [
        'config' => [
            'type' => 'input',
            'renderType' => 'colorpicker',
        ],
    ],
    'tx_gdprreviewsclient_slider_max_reviews_threecgd' => [
        'displayCond' => 'FIELD:gdpr_show_all_reviews_threecgd:=:0',
        'config' => [
            'type' => 'input',
            'size' => 10,
            'eval' => 'trim,int',
            'range' => [
                'lower' => 1,
                'upper' => 100,
            ],
            'default' => 4,
            'slider' => [
                'step' => 1,
                'width' => 200,
            ],
        ],
    ],

    'gdpr_show_all_reviews_threecgd' => [
        'onChange' => 'reload',
        'config' => [
            'type' => 'check',
            'renderType' => 'checkboxToggle',
            'items' => [
                [
                    0 => '',
                    1 => '',
                ],
            ],
        ],
    ],
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $fields);

$GLOBALS['TCA']['tt_content']['types']['gdprextensionscomgrthreec_gdprgooglethreecgrid'] = [
    'showitem' => '
                --palette--;' . $frontendLanguageFilePrefix . 'palette.general;general,
                 gdpr_color_of_text_threecgd; Text Color,
                 gdpr_button_color_threecgd ; Button Color,
                 gdpr_button_text_color_threecgd ; Button Text Color,
                 gdpr_background_color_threecgd; Background Color,
                 tx_gdprreviewsclient_slider_max_reviews_threecgd; Max. number of reviews,
                 gdpr_button_shape_threecgd ; Button Shape,
                 gdpr_show_all_reviews_threecgd; Show All Reviews,

                --div--;' . $frontendLanguageFilePrefix . 'tabs.appearance,
                --palette--;' . $frontendLanguageFilePrefix . 'palette.frames;frames,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                --palette--;;language,
                --div--;' . $frontendLanguageFilePrefix . 'tabs.access,
                hidden;' . $frontendLanguageFilePrefix . 'field.default.hidden,
                --palette--;' . $frontendLanguageFilePrefix . 'palette.access;access,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
        ',
];
