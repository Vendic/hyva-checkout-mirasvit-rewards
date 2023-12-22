<?php declare(strict_types=1);
/**
 * @copyright   Copyright (c) Vendic B.V https://vendic.nl/
 */

namespace Vendic\HyvaCheckoutMirasvitRewards\Magewire\Checkout\PriceSummary;

use Magento\Checkout\Model\Session;
use Magewirephp\Magewire\Component;

class SpentPoints extends Component
{
    private const SPEND_POINTS = 'spend_points';

    /**
     * @var int
     */
    public $spent = 0;

    public function __construct(
        private Session $checkoutSession,
    ) {
    }

    public function mount(): void
    {
        $this->spent = $this->checkoutSession->getData(self::SPEND_POINTS) ?? 0;
    }

    public function refresh(): void
    {
        $this->spent = $this->checkoutSession->getData(self::SPEND_POINTS) ?? 0;
    }
}
