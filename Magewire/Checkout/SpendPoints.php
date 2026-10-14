<?php declare(strict_types=1);
/**
 * @copyright   Copyright (c) Vendic B.V https://vendic.nl/
 */

namespace Vendic\HyvaCheckoutMirasvitRewards\Magewire\Checkout;

use Magento\Checkout\Model\Session;
use Magewirephp\Magewire\Component;
use Mirasvit\Rewards\Api\RewardsInterface;
use Mirasvit\Rewards\Model\PurchaseFactory;
use Mirasvit\Rewards\Model\ResourceModel\Purchase as PurchaseResource;

class SpendPoints extends Component
{
    /**
     * @var int
     */
    public $spend = 0;

    public function __construct(
        private RewardsInterface $rewards,
        private Session $checkoutSession,
        private PurchaseFactory $purchaseFactory,
        private PurchaseResource $purchaseResource
    ) {
    }

    public function mount(): void
    {
        $this->spend = $this->getSpendPointsByCartId((int)$this->checkoutSession->getQuoteId());
    }

    public function updatingSpend(string $value): int
    {
        $value = is_numeric($value) ? (int)$value : 0;
        $cartId = (int)$this->checkoutSession->getQuoteId();

        $result = $this->rewards->apply($cartId, $value);
        $value = $result === false
            ? 0
            : $this->getSpendPointsByCartId($cartId);

        $this->emit('spend_points_updated');
        $this->emitToRefresh('price-summary.total-segments');
        $this->emitToRefresh('price-summary.rewards.earn-points');
        $this->emitToRefresh('checkout.payment.methods');
        $this->emitToRefresh('checkout.shipping.methods');

        return $value;
    }

    private function getSpendPointsByCartId(int $cartId): int
    {
        $purchase = $this->purchaseFactory->create();
        $this->purchaseResource->load($purchase, $cartId, 'quote_id');
        return (int)$purchase->getSpendPoints();
    }
}
