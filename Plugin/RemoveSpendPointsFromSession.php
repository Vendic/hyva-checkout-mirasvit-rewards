<?php declare(strict_types=1);
/**
 * @copyright   Copyright (c) Vendic B.V https://vendic.nl/
 */

namespace Vendic\HyvaCheckoutMirasvitRewards\Plugin;

use Magento\Checkout\Model\Session;
use Magento\Quote\Api\CartManagementInterface;

class RemoveSpendPointsFromSession
{

    private const SPEND_POINTS = 'spend_points';

    public function __construct(private Session $checkoutSession)
    {
    }

    /**
     * After an order is placed, the spent points should be
     * reset to zero on the session so that the old spent points won't
     * be displayed again on the checkout page.
     *
     * @param CartManagementInterface $subject
     * @param int $result
     * @return int
     */
    public function afterPlaceOrder(
        CartManagementInterface $subject,
        $result,
    ) {
        $this->checkoutSession->setData(self::SPEND_POINTS, 0);
        return $result;
    }
}
