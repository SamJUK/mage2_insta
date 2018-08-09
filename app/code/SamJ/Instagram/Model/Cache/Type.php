<?php
namespace SamJ\Instagram\Model\Cache;

use Magento\Framework\App\Cache\Type\FrontendPool;
use Magento\Framework\Cache\Frontend\Decorator\TagScope;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * System / Cache Management / Cache type "Custom Cache Tag"
 */
class Type extends TagScope
{
    /** Cache type code unique among all cache types */
    const TYPE_IDENTIFIER = 'samj_instagram';

    /** Cache tag used to distinguish the cache type from all other cache */
    const CACHE_TAG = 'SAMJ_INSTAGRAM';

    const XML_PATH_CACHE_LIFETIME = 'samj_instagram/general/cache_lifetime';

    protected $scopeConfig;

    /**
     * @param FrontendPool $cacheFrontendPool
     */
    public function __construct(FrontendPool $cacheFrontendPool, ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
        parent::__construct($cacheFrontendPool->get(self::TYPE_IDENTIFIER), self::CACHE_TAG);
    }

    public function getCacheLifeTime()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_CACHE_LIFETIME,
            ScopeInterface::SCOPE_STORE
        );
    }
}