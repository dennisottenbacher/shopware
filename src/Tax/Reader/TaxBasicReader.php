<?php declare(strict_types=1);

namespace Shopware\Tax\Reader;

use Doctrine\DBAL\Connection;
use Shopware\Api\Read\BasicReaderInterface;
use Shopware\Context\Struct\TranslationContext;
use Shopware\Framework\Struct\SortArrayByKeysTrait;
use Shopware\Tax\Factory\TaxBasicFactory;
use Shopware\Tax\Struct\TaxBasicCollection;
use Shopware\Tax\Struct\TaxBasicStruct;

class TaxBasicReader implements BasicReaderInterface
{
    use SortArrayByKeysTrait;

    /**
     * @var TaxBasicFactory
     */
    private $factory;

    public function __construct(
        TaxBasicFactory $factory
    ) {
        $this->factory = $factory;
    }

    public function readBasic(array $uuids, TranslationContext $context): TaxBasicCollection
    {
        if (empty($uuids)) {
            return new TaxBasicCollection();
        }

        $taxesCollection = $this->read($uuids, $context);

        return $taxesCollection;
    }

    private function read(array $uuids, TranslationContext $context): TaxBasicCollection
    {
        $query = $this->factory->createQuery($context);

        $query->andWhere('tax.uuid IN (:ids)');
        $query->setParameter('ids', $uuids, Connection::PARAM_STR_ARRAY);

        $rows = $query->execute()->fetchAll(\PDO::FETCH_ASSOC);
        $structs = [];
        foreach ($rows as $row) {
            $struct = $this->factory->hydrate($row, new TaxBasicStruct(), $query->getSelection(), $context);
            $structs[$struct->getUuid()] = $struct;
        }

        return new TaxBasicCollection(
            $this->sortIndexedArrayByKeys($uuids, $structs)
        );
    }
}