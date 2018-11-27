<?php

namespace Zanox\Api\Adapter\Soap;

/**
 * Api Constants Enum Definitions for the SOAP interface
 *
 * Supported Version: PHP >= 5.1.0
 *
 * @author      Stefan Misch (stefan.misch@zanox.com)
 *
 * @see         http://wiki.zanox.com/en/Web_Services
 * @see         http://apps.zanox.com
 *
 * @package     ApiClient
 * @version     2011-03-01
 * @copyright   Copyright (c) 2007-2011 zanox.de AG
 */
class Constants
{

    /**
     * applicationTypeEnum
     */
    const WIDGET = 'widget';
    const SAAS = 'saas';
    const SOFTWARE = 'software';

    /**
     * profileTypeEnum
     */
    const PUBLISHER = 'publisher';
    const ADVERTISER = 'advertiser';

    /**
     * programStatusEnum
     */
    const ACTIVE = 'active';
    const INACTIVE = 'inactive';

    /**
     * programApplicationStatusEnum
     */
    const OPEN = 'open';
    const CONFIRMED = 'confirmed';
    const REJECTED = 'rejected';
    const DEFERRED = 'deferred';
    const WAITING = 'waiting';
    const BLOCKED = 'blocked';
    const TERMINATED = 'terminated';
    const CANCELED = 'canceled';
    const CALLED = 'called';
    const DECLINED = 'declined';
    const DELETED = 'deleted';

    /**
     * admediaPurposeEnum
     */
    const START_PAGE = 'startPage';
    const PRODUCT_DEEPLINK = 'productDeeplink';
    const CATEGORY_DEEPLINK = 'categoryDeeplink';
    const SEARCH_DEEPLINK = 'searchDeeplink';

    /**
     * adspaceTypeEnum
     */
    const WEBSITE = 'website';
    const EMAIL = 'email';
    const SEARCH_ENGINE = 'searchengine';

    /**
     * adspaceScopeEnum
     */
    const PRIV = 'private';
    const BUSINESS = 'business';

    /**
     * reviewStateEnum
     */
    #const CONFIRMED = 'confirmed';
    #const OPEN = 'open';
    #const REJECTED = 'rejected';
    const APPROVED = 'approved';

    /**
     * admediaTypeEnum
     */
    const HTML = 'html';
    const SCRIPT = 'script';
    const LOOKAT_MEDIA = 'lookatMedia';
    const IMAGE = 'image';
    const IMAGE_TEXT = 'imageText';
    const TEXT = 'text';

    /**
     * searchTypeEnum
     */
    const CONTEXTUAL = 'contextual';
    const PHRASE = 'phrase';

    /**
     * partnerShipEnum
     */
    const DIRECT = 'direct';
    const INDIRECT = 'indirect';

    /**
     * dateTypeEnum
     */
    const CLICK_DATE = 'clickDate';
    const TRACKING_DATE = 'trackingDate';
    const MODIFIED_DATE = 'modifiedDate';
    const REVIEW_STATE_CHANGED_DATE = 'reviewStateChangedDate';

    /**
     * groupByEnum
     */
    const CURRENCY = 'currency';
    const ADMEDIUM = 'admedium';
    const PROGRAM = 'program';
    const ADSPACE = 'adspace';
    const LINK_FORMAT = 'linkFormat';
    const REVIEW_STATE = 'reviewState';
    const TRACKING_CATEGORY = 'trackingCategory';
    const MONTH = 'month';
    const DAY = 'day';
    const YEAR = 'year';
    const DAY_OF_WEEK = 'dayOfWeek';
    const APPLICATION = 'application';
    const MEDIA_SLOT = 'mediaSlot';

    /**
     * incentiveTypeEnum
     */
    const COUPONS = 'coupons';
    const SAMPLES = 'samples';
    const BARGAINS = 'bargains';
    const FREE_PRODUCTS = 'freeProducts';
    const NO_SHIPPING_COSTS = 'noShippingCosts';
    const LOTTERIES = 'lotteries';


    /**
     * roleTypeEnum
     */

    const DEVELOPER = 'developer';
    const CUSTOMER = 'customer';
    const TESTER = 'tester';

    /**
     * settingTypeEnum
     */

    const BOOLEAN = 'boolean';
    const COLOR = 'color';
    const NUMBER = 'number';
    const STRING = 'string';
    const DATE = 'date';

    /**
     * connectStatusTypeEnum
     */

    #const ACTIVE = 'active';
    #const INACTIVE = 'inactive';


    /**
     * mediaSlotStatusEnum
     */
    #const ACTIVE = 'active';
    #const DELETED = 'deleted';

    /**
     * transactionTypeEnum
     */
    const LEADS = 'leads';
    const SALES = 'sales';

}
