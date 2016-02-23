<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionWishlistConnector\Communication\Plugin;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Wishlist\Dependency\PreSavePluginInterface;

/**
 * @method \Spryker\Zed\ProductOptionWishlistConnector\Communication\ProductOptionWishlistConnectorCommunicationFactory getFactory()
 */
class PreSaveGroupKeyProductOptionPlugin extends AbstractPlugin implements PreSavePluginInterface
{

    /**
     * @param \ArrayObject $items
     *
     * @return void
     */
    public function trigger(\ArrayObject $items)
    {
        foreach ($items as $item) {
            $item->setGroupKey($this->buildGroupKey($item));
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $item
     *
     * @return string
     */
    protected function buildGroupKey(ItemTransfer $item)
    {
        $currentGroupKey = $item->getGroupKey();
        if (empty($item->getProductOptions())) {
            return $currentGroupKey;
        }

        $sortedProductOptions = $this->sortOptions((array)$item->getProductOptions());
        $optionGroupKey = $this->combineOptionParts($sortedProductOptions);

        if (empty($optionGroupKey)) {
            return $currentGroupKey;
        }

        return !empty($currentGroupKey) ? $currentGroupKey . '-' . $optionGroupKey : $optionGroupKey;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionTransfer[] $options
     *
     * @return array
     */
    protected function sortOptions(array $options)
    {
        usort(
            $options,
            function (ProductOptionTransfer $productOptionLeft, ProductOptionTransfer $productOptionRight) {
                return ($productOptionLeft->getIdOptionValueUsage() < $productOptionRight->getIdOptionValueUsage()) ? -1 : 1;
            }
        );

        return $options;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionTransfer[] $sortedProductOptions
     *
     * @return string
     */
    protected function combineOptionParts(array $sortedProductOptions)
    {
        $groupKeyPart = [];
        foreach ($sortedProductOptions as $option) {
            if (empty($option->getIdOptionValueUsage())) {
                continue;
            }
            $groupKeyPart[] = $option->getIdOptionValueUsage();
        }

        return implode('-', $groupKeyPart);
    }

}
