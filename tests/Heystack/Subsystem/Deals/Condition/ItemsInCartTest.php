<?php
namespace Heystack\Subsystem\Deals\Test;

use Heystack\Subsystem\Deals\Condition\ItemsInCart;
use Heystack\Subsystem\Products\ProductHolder\ProductHolder;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2013-04-25 at 16:49:07.
 */
class ItemsInCartTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Time
     */
    protected $itemsInCartCondition;
    protected $adaptableConfigurationStub;
    protected $purchaseableHolder;
    protected $purchasable;

    protected function setUp()
    {
        $this->adaptableConfigurationStub = $this->getMockBuilder('Heystack\Subsystem\Deals\AdaptableConfiguration')
            ->disableOriginalConstructor()
            ->getMock();

        $this->purchaseableHolder = $this->getMockBuilder('Heystack\Subsystem\Products\ProductHolder\ProductHolder')
            ->disableOriginalConstructor()
            ->getMock();

        $this->purchasable = $this->getMockBuilder('Heystack\Subsystem\Ecommerce\Purchasable\Interfaces\PurchasableInterface')
            ->getMock();

    }

    protected function configureStub($getConfigMap, $hasConfigMap)
    {
        $this->adaptableConfigurationStub->expects($this->any())
            ->method('getConfig')
            ->will(
                $this->returnValueMap($getConfigMap)
            );

        $this->adaptableConfigurationStub->expects($this->any())
            ->method('hasConfig')
            ->will(
                $this->returnValueMap($hasConfigMap)
            );

        $this->itemsInCartCondition = new ItemsInCart($this->purchaseableHolder, $this->adaptableConfigurationStub);
    }

    public function testNoConfiguration()
    {
        $this->setExpectedException('Exception');

        $this->configureStub(
            [
                [

                ]
            ],
            [
                [

                ]
            ]
        );
    }

    public function testConfiguration()
    {
        $this->configureStub(
            [
                [
                    ItemsInCart::ITEM_COUNT_KEY, 1
                ],
                [
                    ItemsInCart::COUNT_BY_PURCHASABLE_QUANTITY_KEY, 1
                ]
            ],
            [
                [
                    ItemsInCart::ITEM_COUNT_KEY, 1
                ],
                [
                    ItemsInCart::COUNT_BY_PURCHASABLE_QUANTITY_KEY, 1
                ]
            ]
        );
    }

    public function testNoCountByPurchasableQuantityConfiguration()
    {
        $this->setExpectedException('Exception');

        $this->configureStub(
            [
                [
                    ItemsInCart::ITEM_COUNT_KEY, 1
                ]
            ],
            [
                [
                    ItemsInCart::ITEM_COUNT_KEY, 1
                ]
            ]
        );
    }

    public function testNoItemCountConfiguration()
    {
        $this->setExpectedException('Exception');

        $this->configureStub(
            [
                [
                    ItemsInCart::COUNT_BY_PURCHASABLE_QUANTITY_KEY, 1
                ]
            ],
            [
                [
                    ItemsInCart::COUNT_BY_PURCHASABLE_QUANTITY_KEY, 1
                ]
            ]
        );
    }

    public function testGetType()
    {
        $this->configureStub(
            [
                [
                    ItemsInCart::ITEM_COUNT_KEY, 1
                ],
                [
                    ItemsInCart::COUNT_BY_PURCHASABLE_QUANTITY_KEY, 1
                ]
            ],
            [
                [
                    ItemsInCart::ITEM_COUNT_KEY, 1
                ],
                [
                    ItemsInCart::COUNT_BY_PURCHASABLE_QUANTITY_KEY, 1
                ]
            ]
        );

        $this->assertEquals($this->itemsInCartCondition->getType(), ItemsInCart::CONDITION_TYPE);
    }

    public function testGetDescription()
    {
        $this->configureStub(
            [
                [
                    ItemsInCart::ITEM_COUNT_KEY, 1
                ],
                [
                    ItemsInCart::COUNT_BY_PURCHASABLE_QUANTITY_KEY, 1
                ]
            ],
            [
                [
                    ItemsInCart::ITEM_COUNT_KEY, 1
                ],
                [
                    ItemsInCart::COUNT_BY_PURCHASABLE_QUANTITY_KEY, 1
                ]
            ]
        );

        $this->itemsInCartCondition->setItemCount(4);

        $this->assertEquals($this->itemsInCartCondition->getDescription(), "Must have a total of " . $this->itemsInCartCondition->getItemCount() . " products (by quantity) in the cart");


    }

    public function testMet()
    {


        $this->markTestIncomplete();
    }

    public function testAlmostMet()
    {


        $this->markTestIncomplete();
    }

}
