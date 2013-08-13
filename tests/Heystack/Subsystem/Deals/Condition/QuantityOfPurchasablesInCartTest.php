<?php
namespace Heystack\Subsystem\Deals\Test;

use Heystack\Subsystem\Deals\Condition\QuantityOfPurchasablesInCart;
use Heystack\Subsystem\Products\ProductHolder\ProductHolder;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2013-04-25 at 16:49:07.
 */
class QuantityOfPurchasablesInCartTest extends \PHPUnit_Framework_TestCase
{

    protected $quantityOfPurchasablesInCartCondition;
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

        $this->quantityOfPurchasablesInCartCondition = new QuantityOfPurchasablesInCart($this->purchaseableHolder, $this->adaptableConfigurationStub);
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
                    QuantityOfPurchasablesInCart::MINIMUM_QUANTITY_KEY, 1
                ],
                [
                    QuantityOfPurchasablesInCart::PURCHASABLE_IDENTIFIERS, array(1)
                ]
            ],
            [
                [
                    QuantityOfPurchasablesInCart::MINIMUM_QUANTITY_KEY, 1
                ],
                [
                    QuantityOfPurchasablesInCart::PURCHASABLE_IDENTIFIERS, array(1)
                ]
            ]
        );
    }

    public function testNoPurchasableIdentifiersConfiguration()
    {
        $this->setExpectedException('Exception');

        $this->configureStub(
            [
                [
                    QuantityOfPurchasablesInCart::MINIMUM_QUANTITY_KEY, 1
                ]
            ],
            [
                [
                    QuantityOfPurchasablesInCart::MINIMUM_QUANTITY_KEY, 1
                ]
            ]
        );
    }

    public function testNoMinimumQuantityConfiguration()
    {
        $this->setExpectedException('Exception');

        $this->configureStub(
            [
                [
                    QuantityOfPurchasablesInCart::PURCHASABLE_IDENTIFIERS, array(1)
                ]
            ],
            [
                [
                    QuantityOfPurchasablesInCart::PURCHASABLE_IDENTIFIERS, array(1)
                ]
            ]
        );
    }

    public function testGetType()
    {
        $this->configureStub(
            [
                [
                    QuantityOfPurchasablesInCart::MINIMUM_QUANTITY_KEY, 1
                ],
                [
                    QuantityOfPurchasablesInCart::PURCHASABLE_IDENTIFIERS, array(1)
                ]
            ],
            [
                [
                    QuantityOfPurchasablesInCart::MINIMUM_QUANTITY_KEY, 1
                ],
                [
                    QuantityOfPurchasablesInCart::PURCHASABLE_IDENTIFIERS, array(1)
                ]
            ]
        );

        $this->assertEquals($this->quantityOfPurchasablesInCartCondition->getType(), QuantityOfPurchasablesInCart::CONDITION_TYPE);
    }

    public function testGetDescription()
    {
        $this->configureStub(
            [
                [
                    QuantityOfPurchasablesInCart::MINIMUM_QUANTITY_KEY, 1
                ],
                [
                    QuantityOfPurchasablesInCart::PURCHASABLE_IDENTIFIERS, array(1)
                ]
            ],
            [
                [
                    QuantityOfPurchasablesInCart::MINIMUM_QUANTITY_KEY, 1
                ],
                [
                    QuantityOfPurchasablesInCart::PURCHASABLE_IDENTIFIERS, array(1)
                ]
            ]
        );

        $this->quantityOfPurchasablesInCartCondition->setMinimumQuantity(4);
        $this->quantityOfPurchasablesInCartCondition->setPurchasableIdentifiers(array(4));

        $this->assertEquals($this->quantityOfPurchasablesInCartCondition->getDescription(), 'Must have at least ' . $this->quantityOfPurchasablesInCartCondition->getMinimumQuantity() . ' of any of the ff: ' . implode($this->quantityOfPurchasablesInCartCondition->getPurchasableIdentifiers(), ','));


    }

    public function testSetDealHandler()
    {
        $this->configureStub(
            [
                [
                    QuantityOfPurchasablesInCart::MINIMUM_QUANTITY_KEY, 1
                ],
                [
                    QuantityOfPurchasablesInCart::PURCHASABLE_IDENTIFIERS, array(1)
                ]
            ],
            [
                [
                    QuantityOfPurchasablesInCart::MINIMUM_QUANTITY_KEY, 1
                ],
                [
                    QuantityOfPurchasablesInCart::PURCHASABLE_IDENTIFIERS, array(1)
                ]
            ]
        );

        $handler = $this->getMockBuilder('Heystack\Subsystem\Deals\DealHandler')
            ->disableOriginalConstructor()
            ->getMock();

        $this->quantityOfPurchasablesInCartCondition->setDealHandler($handler);

        $this->assertAttributeNotEmpty('dealHandler', $this->quantityOfPurchasablesInCartCondition);

    }

    /**
     * @depends testSetDealHandler
     */
    public function testMet()
    {
        $this->testProduct = $this->getMockBuilder('Heystack\Subsystem\Deals\Interfaces\DealPurchasableInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->testProductTwo = $this->getMockBuilder('Heystack\Subsystem\Deals\Interfaces\DealPurchasableInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->testProduct->expects($this->any())
            ->method('getQuantity')
            ->will($this->returnValue(1));

        $this->testProduct->expects($this->any())
            ->method('hasFreeItems')
            ->will($this->returnValue(false));

        $this->testProductTwo->expects($this->any())
            ->method('getQuantity')
            ->will($this->returnValue(6));

        $this->testProductTwo->expects($this->any())
            ->method('hasFreeItems')
            ->will($this->returnValue(true));

        $this->testProductTwo->expects($this->any())
            ->method('getFreeQuantity')
            ->will($this->returnValue(1));

        $this->testProductTwo->expects($this->any())
            ->method('getUnitPrice')
            ->will($this->returnValue(10));

        $this->purchaseableHolder->expects($this->any())
            ->method('getPurchasablesByPrimaryIdentifier')
            ->will($this->returnValue(array($this->testProduct, $this->testProductTwo)));

        $eventService = $this->getMockBuilder('Heystack\Subsystem\Core\EventDispatcher')
            ->disableOriginalConstructor()
            ->getMock();

        $this->purchaseableHolder->expects($this->any())
            ->method('getEventService')
            ->will($this->returnValue($eventService));

        $this->configureStub(
            [
                [
                    QuantityOfPurchasablesInCart::MINIMUM_QUANTITY_KEY, 5
                ],
                [
                    QuantityOfPurchasablesInCart::PURCHASABLE_IDENTIFIERS, array(1)
                ]
            ],
            [
                [
                    QuantityOfPurchasablesInCart::MINIMUM_QUANTITY_KEY, 5
                ],
                [
                    QuantityOfPurchasablesInCart::PURCHASABLE_IDENTIFIERS, array(1)
                ]
            ]
        );

        $handler = $this->getMockBuilder('Heystack\Subsystem\Deals\DealHandler')
            ->disableOriginalConstructor()
            ->getMock();

        $this->quantityOfPurchasablesInCartCondition->setDealHandler($handler);

        $this->assertEquals(1, $this->quantityOfPurchasablesInCartCondition->met());
    }

    /**
     * @depends testSetDealHandler
     */
    public function testMetWithFreeGift()
    {
        $this->testProduct = $this->getMockBuilder('Heystack\Subsystem\Deals\Interfaces\DealPurchasableInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->testProductTwo = $this->getMockBuilder('Heystack\Subsystem\Deals\Interfaces\DealPurchasableInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->testProduct->expects($this->any())
            ->method('getQuantity')
            ->will($this->returnValue(1));

        $this->testProduct->expects($this->any())
            ->method('hasFreeItems')
            ->will($this->returnValue(false));

        $this->testProductTwo->expects($this->any())
            ->method('getQuantity')
            ->will($this->returnValue(5));

        $this->testProductTwo->expects($this->any())
            ->method('hasFreeItems')
            ->will($this->returnValue(true));

        $this->testProductTwo->expects($this->any())
            ->method('getFreeQuantity')
            ->will($this->returnValue(1));

        $this->testProductTwo->expects($this->any())
            ->method('getUnitPrice')
            ->will($this->returnValue(10));

        $this->purchaseableHolder->expects($this->any())
            ->method('getPurchasablesByPrimaryIdentifier')
            ->will($this->returnValue(array($this->testProduct, $this->testProductTwo)));

        $eventService = $this->getMockBuilder('Heystack\Subsystem\Core\EventDispatcher')
            ->disableOriginalConstructor()
            ->getMock();

        $this->purchaseableHolder->expects($this->any())
            ->method('getEventService')
            ->will($this->returnValue($eventService));

        $this->configureStub(
            [
                [
                    QuantityOfPurchasablesInCart::MINIMUM_QUANTITY_KEY, 5
                ],
                [
                    QuantityOfPurchasablesInCart::PURCHASABLE_IDENTIFIERS, array(1)
                ]
            ],
            [
                [
                    QuantityOfPurchasablesInCart::MINIMUM_QUANTITY_KEY, 5
                ],
                [
                    QuantityOfPurchasablesInCart::PURCHASABLE_IDENTIFIERS, array(1)
                ]
            ]
        );

        $handler = $this->getMockBuilder('Heystack\Subsystem\Deals\DealHandler')
            ->disableOriginalConstructor()
            ->getMock();

        $freeGift = $this->getMockBuilder('Heystack\Subsystem\Deals\Result\FreeGift')
            ->disableOriginalConstructor()
            ->getMock();

        $handler->expects($this->any())
            ->method('getResult')
            ->will($this->returnValue($freeGift));

        $this->quantityOfPurchasablesInCartCondition->setDealHandler($handler);

        $this->assertEquals(1, $this->quantityOfPurchasablesInCartCondition->met());
    }


    /**
     * @depends testSetDealHandler
     */
    public function testAlmostMet()
    {
        $this->testProduct = $this->getMockBuilder('Heystack\Subsystem\Deals\Interfaces\DealPurchasableInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->testProductTwo = $this->getMockBuilder('Heystack\Subsystem\Deals\Interfaces\DealPurchasableInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->testProduct->expects($this->any())
            ->method('getQuantity')
            ->will($this->onConsecutiveCalls(1, 1, 1, 1, 1 ,1));

        $this->testProduct->expects($this->any())
            ->method('hasFreeItems')
            ->will($this->returnValue(false));

        $this->testProductTwo->expects($this->any())
            ->method('getQuantity')
            ->will($this->onConsecutiveCalls(1, 1, 5, 5, 5, 5));

        $this->testProductTwo->expects($this->any())
            ->method('hasFreeItems')
            ->will($this->returnValue(true));

        $this->testProductTwo->expects($this->any())
            ->method('getFreeQuantity')
            ->will($this->returnValue(1));

        $this->testProductTwo->expects($this->any())
            ->method('getUnitPrice')
            ->will($this->returnValue(10));

        $this->purchaseableHolder->expects($this->any())
            ->method('getPurchasablesByPrimaryIdentifier')
            ->will($this->returnValue(array($this->testProduct, $this->testProductTwo)));

        $this->purchaseableHolder->expects($this->any())
            ->method('getPurchasables')
            ->will($this->returnValue(array($this->testProduct, $this->testProductTwo)));

        $eventService = $this->getMockBuilder('Heystack\Subsystem\Core\EventDispatcher')
            ->disableOriginalConstructor()
            ->getMock();

        $this->purchaseableHolder->expects($this->any())
            ->method('getEventService')
            ->will($this->returnValue($eventService));

        $this->configureStub(
            [
                [
                    QuantityOfPurchasablesInCart::MINIMUM_QUANTITY_KEY, 5
                ],
                [
                    QuantityOfPurchasablesInCart::PURCHASABLE_IDENTIFIERS, array(1)
                ]
            ],
            [
                [
                    QuantityOfPurchasablesInCart::MINIMUM_QUANTITY_KEY, 5
                ],
                [
                    QuantityOfPurchasablesInCart::PURCHASABLE_IDENTIFIERS, array(1)
                ]
            ]
        );

        $handler = $this->getMockBuilder('Heystack\Subsystem\Deals\DealHandler')
            ->disableOriginalConstructor()
            ->getMock();

        $this->quantityOfPurchasablesInCartCondition->setDealHandler($handler);

        $this->assertTrue($this->quantityOfPurchasablesInCartCondition->almostMet());
    }

}
