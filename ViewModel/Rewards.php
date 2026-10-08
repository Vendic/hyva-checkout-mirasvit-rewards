<?php declare(strict_types=1);
/**
 * @copyright   Copyright (c) Vendic B.V https://vendic.nl/
 */

namespace Vendic\HyvaCheckoutMirasvitRewards\ViewModel;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Mirasvit\Rewards\Helper\Balance;
use Mirasvit\Rewards\Helper\Purchase as PurchaseHelper;

class Rewards implements ArgumentInterface
{
    public function __construct(
        private PurchaseHelper $purchaseHelper,
        private Balance $balance,
        private CheckoutSession $checkoutSession
    ) {
    }

    /**
     * Get the customer's total available reward points balance.
     */
    public function getBalancePoints(): int
    {
        $customerId = (int) $this->checkoutSession->getQuote()->getCustomerId();
        if ($customerId === 0) {
            return 0;
        }

        return (int) $this->balance->getBalancePoints($customerId);
    }

    /**
     * Get the points that will be earned upon purchase.
     */
    public function getEarnPoints(): int
    {
        $purchase = $this->purchaseHelper->getPurchase();
        if (is_bool($purchase)) {
            return 0;
        }

        return (int)$purchase->getEarnPoints();
    }

    public function getMaxSpendPoints(): int
    {
        $purchase = $this->purchaseHelper->getPurchase();
        if (is_bool($purchase)) {
            return 0;
        }

        return (int)$purchase->getMaxPointsNumberToSpent();
    }
}
