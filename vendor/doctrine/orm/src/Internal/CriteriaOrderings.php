<?php

declare(strict_types=1);

namespace Doctrine\ORM\Internal;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Order;

use function array_map;
use function class_exists;
use function method_exists;
use function strtoupper;

trait CriteriaOrderings
{
    /** @return array<string, string> */
    private static function getCriteriaOrderings(Criteria $criteria): array
    {
        if (! method_exists(Criteria::class, 'orderings')) {
            // @phpstan-ignore method.deprecated
            return $criteria->getOrderings();
        }

        return array_map(
            static function (Order $order): string {
                return $order->value;
            },
            $criteria->orderings()
        );
    }

    /**
     * @param array<string, string> $orderings
     *
     * @return array<string, string>|array<string, Order>
     */
    private static function mapToOrderEnumIfAvailable(array $orderings): array
    {
        if (! class_exists(Order::class)) {
            return $orderings;
        }

        return array_map(
            static function (string $order): Order {
                return Order::from(strtoupper($order));
            },
            $orderings
        );
    }
}
