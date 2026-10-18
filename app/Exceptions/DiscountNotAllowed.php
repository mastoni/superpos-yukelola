<?php

namespace App\Exceptions;

use App\Actions\Sales\CompleteSale;
use RuntimeException;

/**
 * Thrown by {@see CompleteSale} when a discount is
 * applied by a user who lacks the `sales.discount` permission.
 */
class DiscountNotAllowed extends RuntimeException
{
    public function __construct()
    {
        parent::__construct(__('sales.errors.discount_not_allowed'));
    }
}
