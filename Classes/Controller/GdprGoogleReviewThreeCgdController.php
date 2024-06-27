<?php

declare(strict_types=1);

namespace GdprExtensionsCom\GdprExtensionsComGrthreec\Controller;


use GdprExtensionsCom\GdprExtensionsComGrthreec\Utility\Helper;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * This file is part of the "gdpr-extensions-com-google_reviewslider" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2023
 */

/**
 * gdprgoogle_reviewsliderController
 */
class GdprGoogleReviewThreeCgdController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{


    /**
     * gdprManagerRepository
     *
     * @var \GdprExtensionsCom\GdprExtensionsComGrthreec\Domain\Repository\GdprManagerRepository
     */

    protected $gdprManagerRepository = null;

    /**
     * ContentObject
     *
     * @var ContentObject
     */
    protected $contentObject = null;

    /**
     * Action initialize
     */
    protected function initializeAction()
    {
        $this->contentObject = $this->configurationManager->getContentObject();

        // intialize the content object
    }

    /**
     * @param \GdprExtensionsCom\GdprExtensionsComGrthreec\Domain\Repository\GdprManagerRepository $gdprManagerRepository
     */
    public function injectGdprManagerRepository(\GdprExtensionsCom\GdprExtensionsComGrthreec\Domain\Repository\GdprManagerRepository $gdprManagerRepository)
    {
        $this->gdprManagerRepository = $gdprManagerRepository;
    }

    /**
     * action index
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function indexAction(): \Psr\Http\Message\ResponseInterface
    {
        $showReviewsUrl = $this->uriBuilder->reset()
            ->uriFor('showReviews');
        $this->view->assign('showReviewsUrl', $showReviewsUrl);
        $this->view->assign('data', $this->contentObject->data);
        $this->view->assign('rootPid', $GLOBALS['TSFE']->site->getRootPageId());
        return $this->htmlResponse();
    }
    public function showReviewsAction()
    {
        $reviewsToFetch = GeneralUtility::_GP('reveiwsToFetch') ?: 6;
        $contentElementUid = $this->configurationManager->getContentObject()->data['uid']; // Example to get current content element UID

        $cacheManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Cache\CacheManager::class);
        $cache = $cacheManager->getCache('GdprExtensionsComGrthreec');

        // Adjusted cache identifier to be more specific and include content element UID
        $cacheIdentifier = 'reviewArray_' . $contentElementUid;
        $cacheTag = 'content_element_' . $contentElementUid; // Cache tag based on content element UID

        $reviewArray = $cache->get($cacheIdentifier);

        if (!$reviewArray) {
            $reviewArray = $this->fetchReviews();
            $cache->set($cacheIdentifier, $reviewArray, [$cacheTag], 3600);
        }
       
        $reviewsSlice = array_slice($reviewArray, 0, (int)$reviewsToFetch);
        if(count($reviewArray) > 0){
            $completed = (count($reviewsSlice) == count($reviewArray)) ? 1 : 0;
        }
        $result = ['fetchedReviews' => $reviewsSlice ,'completed'=>$completed ];
        die(json_encode($result));
        return $this->jsonResponse(json_encode($result));
    }


    public function fetchReviews()
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_gdprextensionscomyoutube_domain_model_gdprmanager');
        $reviews = [];
        $maxResults = $this->contentObject->data['tx_gdprreviewsclient_slider_max_reviews_threecgd'];
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $showAllReviews = $this->contentObject->data['gdpr_show_all_reviews_threecgd'];
        if ($showAllReviews == 1) {
            $maxResults = 2000;
        }

        // .................................................................

        // $selectedLocations = explode(",", $this->contentObject->data['gdpr_business_locations_threecgd']);
       $rootPageId = $GLOBALS['TSFE']->rootLine[0]['uid'];
       
        
        // if (!empty($this->contentObject->data['gdpr_business_locations_threecgd'])) {
            $reviewsQB = $connectionPool->getQueryBuilderForTable('tx_gdprclientreviews_domain_model_reviews');
            $locationsreviewsQB = $connectionPool->getQueryBuilderForTable('gdpr_multilocations');
            $locationNamesList = [];
          
            // foreach ($selectedLocations as $uid) {
                $locationResult = $locationsreviewsQB->select('dashboard_api_key')
                    ->from('gdpr_multilocations')
                    ->where(
                        $locationsreviewsQB->expr()
                            ->eq('root_pid', $rootPageId)
                    )
                    
                    ->executeQuery()
                    ->fetchOne();
                   
                // $locationName = $locationResult->fetchOne();
                // $locationNamesList[] = $locationName;
            // }
            if ($locationResult) {
                $reviews = [];
                // foreach ($locationNamesList as $location) {

                    $reviewsResult = $reviewsQB->select('*')
                        ->from('tx_gdprclientreviews_domain_model_reviews')
                        ->where(
                            $reviewsQB->expr()
                                ->eq('dashboard_api_key', $reviewsQB->createNamedParameter($locationResult)),
                        )
                        ->executeQuery();

                    $reviewsData = $reviewsResult->fetchAllAssociative();

                    $reviews = array_merge($reviews, $reviewsData);
                // }
            }
        // }
        usort($reviews, function ($a, $b) {
            return $b['date_sort'] - $a['date_sort'];
        });
        $currentCount = sizeof($reviews);
        if ($currentCount < $maxResults) {
            $maxResults = $currentCount;
        }
        $holdReviews = $reviews;
        $filteredReveiws = [];
        for ($i = 0; $i < $maxResults; $i++) {
            $filteredReveiws[$i] = $holdReviews[$i];
        }
        return $filteredReveiws;
    }
}
