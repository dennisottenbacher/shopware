<?php declare(strict_types=1);

namespace Shopware\Api\Listing\Event\ListingFacetTranslation;

use Shopware\Api\Listing\Collection\ListingFacetTranslationBasicCollection;
use Shopware\Context\Struct\TranslationContext;
use Shopware\Framework\Event\NestedEvent;

class ListingFacetTranslationBasicLoadedEvent extends NestedEvent
{
    public const NAME = 'listing_facet_translation.basic.loaded';

    /**
     * @var TranslationContext
     */
    protected $context;

    /**
     * @var ListingFacetTranslationBasicCollection
     */
    protected $listingFacetTranslations;

    public function __construct(ListingFacetTranslationBasicCollection $listingFacetTranslations, TranslationContext $context)
    {
        $this->context = $context;
        $this->listingFacetTranslations = $listingFacetTranslations;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function getContext(): TranslationContext
    {
        return $this->context;
    }

    public function getListingFacetTranslations(): ListingFacetTranslationBasicCollection
    {
        return $this->listingFacetTranslations;
    }
}