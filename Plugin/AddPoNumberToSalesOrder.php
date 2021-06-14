<?php declare(strict_types=1);

namespace Acme\PoNumber\Plugin;

use Acme\PoNumber\Model\ResourceModel\SalesOrder\Collection;
use Acme\PoNumber\Model\ResourceModel\SalesOrder\CollectionFactory;
use Magento\Sales\Api\OrderRepositoryInterface;

class AddPoNumberToSalesOrder
{
    private CollectionFactory $acmeSalesOrderCollectionFactory;

    /**
     * AddPoNumberToSalesOrder constructor.
     * @param CollectionFactory $acmeSalesOrderCollectionFactory
     */
    public function __construct(
        CollectionFactory $acmeSalesOrderCollectionFactory
    ) {
        $this->acmeSalesOrderCollectionFactory = $acmeSalesOrderCollectionFactory;
    }

    /**
     * @param OrderRepositoryInterface $subject
     * @param $result
     * @return mixed
     */
    public function afterGet(
        OrderRepositoryInterface $subject,
        $result
    ) {
        // We must first grab the record from our custom database table by the order id.

        /** @var Collection $acmeSalesOrder */
        $acmeSalesOrderCollection = $this->acmeSalesOrderCollectionFactory->create();
        $acmeSalesOrder = $acmeSalesOrderCollection
            ->addFieldToFilter('order_id', $result->getId())
            ->getFirstItem();

        // Then, we get the extension attributes that are currently assigned to this order.
        $extensionAttributes = $result->getExtensionAttributes();

        // We then call "setData" on the property we want to set, wtih the value from our custom table.
        $extensionAttributes->setData('po_number', $acmeSalesOrder->getData('po_number'));

        // Then, just re-set the extension attributes containing the newly added data...
        $result->setExtensionAttributes($extensionAttributes);

        // ...and finally, return the result.
        return $result;
    }

    /**
     * @param OrderRepositoryInterface $subject
     * @param $result
     * @return mixed
     */
    public function afterGetList(
        OrderRepositoryInterface $subject,
        $result
    ) {
        // We do the same thing here, and can save some time by passing the logic to afterGet.
        foreach ($result->getItems() as $order) {
            $this->afterGet($subject, $order);
        }

        return $result;
    }
}
