<?php

namespace Heystack\Deals\Interfaces;

use Heystack\Core\Identifier\IdentifierInterface;
use Heystack\Ecommerce\Purchasable\Interfaces\PurchasableInterface;
use SebastianBergmann\Money\Money;

/**
 *
 * @copyright  Heyday
 * @author Glenn Bautista <glenn@heyday.co.nz>
 * @package Ecommerce-Deals
 */
interface DealPurchasableInterface extends PurchasableInterface
{
    /**
     * @param \Heystack\Core\Identifier\IdentifierInterface $dealIdentifier
     * @param int $quantity
     */
    public function setFreeQuantity(IdentifierInterface $dealIdentifier, $quantity);

    /**
     * @param \Heystack\Core\Identifier\IdentifierInterface $dealIdentifier
     * @param int $quantity
     */
    public function addFreeQuantity(IdentifierInterface $dealIdentifier, $quantity = 1);

    /**
     * @param \Heystack\Core\Identifier\IdentifierInterface $dealIdentifier
     * @param int $quantity
     */
    public function subtractFreeQuantity(IdentifierInterface $dealIdentifier, $quantity = 1);

    /**
     * @param \Heystack\Core\Identifier\IdentifierInterface $dealIdentifier
     * @return bool
     */
    public function hasFreeItems(IdentifierInterface $dealIdentifier = null);

    /**
     * @param \Heystack\Core\Identifier\IdentifierInterface $dealIdentifier
     * @return int
     */
    public function getFreeQuantity(IdentifierInterface $dealIdentifier = null);

    /**
     * @return array
     */
    public function getFreeQuantities();

    /**
     * @return void
     */
    public function removeFreeQuantities();

    /**
     * @param IdentifierInterface $dealIdentifier
     * @param $discountAmount
     * @return mixed
     */
    public function setDealDiscount(IdentifierInterface $dealIdentifier, Money $discountAmount);

    /**
     * @param IdentifierInterface $dealIdentifier
     * @return \SebastianBergmann\Money\Money
     */
    public function getDealDiscount(IdentifierInterface $dealIdentifier = null);

    /**
     * @return array
     */
    public function getDealDiscounts();

    /**
     * @return void
     */
    public function removeDealDiscounts();
    
}