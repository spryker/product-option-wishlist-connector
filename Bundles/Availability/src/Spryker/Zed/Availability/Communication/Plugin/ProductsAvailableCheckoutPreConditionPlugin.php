<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Availability\Communication\Plugin;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Availability\Business\AvailabilityFacade;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPreConditionInterface;

/**
 * @method AvailabilityFacade getFacade()
 */
class ProductsAvailableCheckoutPreConditionPlugin extends AbstractPlugin implements CheckoutPreConditionInterface
{

    /**
     * @param QuoteTransfer $quoteTransfer
     * @param CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function checkCondition(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $this->getFacade()->checkoutAvailabilityPrecondition($quoteTransfer, $checkoutResponseTransfer);
    }
}
