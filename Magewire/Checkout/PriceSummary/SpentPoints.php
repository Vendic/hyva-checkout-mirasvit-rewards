<?php declare(strict_types=1);
/**
 * @copyright   Copyright (c) Vendic B.V https://vendic.nl/
 */

namespace Vendic\HyvaCheckoutMirasvitRewards\Magewire\Checkout\PriceSummary;

use Magento\Checkout\Model\Session;
use Magewirephp\Magewire\Component;
use Mirasvit\Rewards\Model\PurchaseFactory;
use Mirasvit\Rewards\Model\ResourceModel\Purchase as PurchaseResource;

class SpentPoints extends Component
{
    protected $listeners = [
        'spend_points_updated' => 'refresh'
    ];

    /**
     * @var int
     */
    public $spent = 0;

    public function __construct(
        private Session $checkoutSession,
        private PurchaseFactory $purchaseFactory,
        private PurchaseResource $purchaseResource
    ) {
    }

    public function mount(): void
    {
        $this->spent = $this->getSpentPoints();
    }

    public function refresh(): void
    {
        $this->spent = $this->getSpentPoints();
    }

    private function getSpentPoints(): int
    {
        $purchase = $this->purchaseFactory->create();
        $this->purchaseResource->load($purchase, (int)$this->checkoutSession->getQuoteId(), 'quote_id');
        return (int)$purchase->getSpendPoints();
    }
}
