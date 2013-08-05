<?php

namespace Heystack\Subsystem\Deals\Interfaces;

use Heystack\Subsystem\Ecommerce\Transaction\Interfaces\TransactionModifierInterface;

/**
 *
 * @copyright  Heyday
 * @author Glenn Bautista <glenn@heyday.co.nz>
 * @package Ecommerce-Deals
 */
interface DealHandlerInterface extends TransactionModifierInterface
{
    /**
     * @param ResultInterface $result
     * @return mixed
     */
    public function setResult(ResultInterface $result);

    /**
     * @param ConditionInterface $condition
     * @return mixed
     */
    public function addCondition(ConditionInterface $condition);

    /**
     * @return mixed
     */
    public function updateTotal();

    /**
     * @param $type
     * @return mixed
     */
    public function getPromotionalMessage($type);

    /**
     * @return array
     */
    public function getConditions();

    /**
     * Returns the number of times that each condition was met more than once
     * @return int
     */
    public function getConditionsMetCount();

    /**
     * @return \Heystack\Subsystem\Deals\Interfaces\ResultInterface
     */
    public function getResult();

    /**
     * Returns whether the deal is almost completed based on the conditions it has
     * @return boolean
     */
    public function almostMet();
}
