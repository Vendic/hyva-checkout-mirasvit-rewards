<?php declare(strict_types=1);
/**
 * @copyright   Copyright (c) Vendic B.V https://vendic.nl/
 */

namespace Vendic\HyvaCheckoutMirasvitRewards\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Mirasvit\Rewards\Model\Config;

class CheckoutConfig implements ArgumentInterface
{

    public function __construct(private Config $config)
    {
    }

    public function getPointUnitName(): string
    {
        return $this->config->getGeneralPointUnitName();
    }

    public function getCheckoutNotification(): string
    {
        return $this->config->getDisplayOptionsCheckoutNotification();
    }
}
