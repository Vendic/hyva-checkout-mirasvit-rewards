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
    private const SPEND_POINTS = 'spend_points';

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
        $this->spend = $this->checkoutSession->getData(self::SPEND_POINTS) ?? 0;
    }

    public function updatingSpend(string $value): int
    {
        $value = is_numeric($value) ? (int)$value : 0;
        $cartId = (int)$this->checkoutSession->getQuoteId();

        $result = $this->rewards->apply($cartId, $value);
        $value = $result === false
            ? 0
            : $this->getSpendPointsByCartId($cartId);

        $this->checkoutSession->setData(self::SPEND_POINTS, $value);

        /**
         * @see https://hyva-themes.slack.com/archives/C04R7U5SZDL/p1703258520674129
         * $this->emitToRefresh('price-summary.total-segments');
         * $this->emitToRefresh('price-summary.rewards.spent-points');
         */

        $this->emit('spend_points_updated');
        $this->emit('payment_method_selected');

        return $value;
    }

    private function getSpendPointsByCartId(int $cartId): int
    {
        $purchase = $this->purchaseFactory->create();
        $this->purchaseResource->load($purchase, $cartId, 'quote_id');
        return (int)$purchase->getSpendPoints();
    }
}
