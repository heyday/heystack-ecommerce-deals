<?php

namespace Heystack\Deals\Events;

use Heystack\Deals\Interfaces\DealHandlerInterface;
use Heystack\Deals\Traits\HasDealHandlerTrait;
use Symfony\Component\EventDispatcher\Event;

/**
 * @author  Stevie Mayhew <stevie@heyday.co.nz>
 * @package Heystack\Deals
 */
class ConditionEvent extends Event
{
    use HasDealHandlerTrait;

    /**
     * @param \Heystack\Deals\Interfaces\DealHandlerInterface $dealHandler
     */
    public function __construct(DealHandlerInterface $dealHandler)
    {
        $this->dealHandler = $dealHandler;
    }
}
