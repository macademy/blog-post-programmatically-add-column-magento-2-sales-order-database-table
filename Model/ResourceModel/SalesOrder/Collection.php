<?php declare(strict_types=1);

namespace Acme\PoNumber\Model\ResourceModel\SalesOrder;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Acme\PoNumber\Model\SalesOrder;

class Collection extends AbstractCollection
{
    protected function _construct(): void
    {
        $this->_init(SalesOrder::class, \Acme\PoNumber\Model\ResourceModel\SalesOrder::class);
    }
}
