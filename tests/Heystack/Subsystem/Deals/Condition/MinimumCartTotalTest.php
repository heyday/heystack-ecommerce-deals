<?php
namespace Heystack\Deals\Test;

use Heystack\Deals\Condition\MinimumCartTotal;
use Heystack\Purchable\ProductHolder\PurchasableHolder;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2013-04-25 at 16:49:07.
 */
class MinimumCartTotalTest extends \PHPUnit_Framework_TestCase
{

    protected $minimumCartCondition;
    protected $adaptableConfigurationStub;
    protected $purchaseableHolder;
    protected $currencyService;

    protected function setUp()
    {
        $this->adaptableConfigurationStub = $this->getMockBuilder('Heystack\Deals\AdaptableConfiguration')
            ->disableOriginalConstructor()
            ->getMock();

        $this->purchaseableHolder = $this->getMockBuilder('Heystack\Products\ProductHolder\ProductHolder')
            ->disableOriginalConstructor()
            ->getMock();

        $this->currencyService = $this->getMockBuilder('Heystack\Ecommerce\Currency\CurrencyService')
            ->disableOriginalConstructor()
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

        $this->minimumCartCondition = new MinimumCartTotal($this->purchaseableHolder, $this->currencyService, $this->adaptableConfigurationStub);
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
                    MinimumCartTotal::AMOUNTS_KEY, ["NZ" => 10]
                ]
            ],
            [
                [
                    MinimumCartTotal::AMOUNTS_KEY, ["NZ" => 10]
                ]
            ]
        );
    }


    public function testGetType()
    {
        $this->configureStub(
            [
                [
                    MinimumCartTotal::AMOUNTS_KEY, ["NZ" => 10]
                ]
            ],
            [
                [
                    MinimumCartTotal::AMOUNTS_KEY, ["NZ" => 10]
                ]
            ]
        );

        $this->assertEquals($this->minimumCartCondition->getType(), MinimumCartTotal::CONDITION_TYPE);
    }

    public function testGetDescription()
    {

        $this->configureStub(
            [
                [
                    MinimumCartTotal::AMOUNTS_KEY, ["NZ" => 10]
                ]
            ],
            [
                [
                    MinimumCartTotal::AMOUNTS_KEY, ["NZ" => 10]
                ]
            ]
        );

        $this->assertEquals($this->minimumCartCondition->getDescription(), "The Transaction sub total must be greater than or equal to -  NZ : 10");


    }

    public function testGetAmounts() {

        $this->configureStub(
            [
                [
                    MinimumCartTotal::AMOUNTS_KEY, ["NZ" => 10]
                ]
            ],
            [
                [
                    MinimumCartTotal::AMOUNTS_KEY, ["NZ" => 10]
                ]
            ]
        );

        $this->minimumCartCondition->setAmounts(["NZ" => 10]);

        $this->assertEquals($this->minimumCartCondition->getAmounts(), ["NZ" => 10]);
    }

    private function setBasicStubs()
    {
        $this->currencyService->expects($this->any())
            ->method('getActiveCurrencyCode')
            ->will($this->returnValue('NZ'));

        $this->testProduct = $this->getMockBuilder('Heystack\Deals\Interfaces\DealPurchasableInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->testProductTwo = $this->getMockBuilder('Heystack\Deals\Interfaces\DealPurchasableInterface')
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
            ->method('getPurchasables')
            ->will($this->returnValue([$this->testProduct, $this->testProductTwo]));

        $eventService = $this->getMockBuilder('Heystack\Core\EventDispatcher')
            ->disableOriginalConstructor()
            ->getMock();

        $this->purchaseableHolder->expects($this->any())
            ->method('getEventService')
            ->will($this->returnValue($eventService));
    }

    public function testMet()
    {
        $this->setBasicStubs();

        $this->purchaseableHolder->expects($this->any())
            ->method('getTotal')
            ->will($this->returnValue(25));

        $this->configureStub(
            [
                [
                    MinimumCartTotal::AMOUNTS_KEY, ["NZ" => 10]
                ]
            ],
            [
                [
                    MinimumCartTotal::AMOUNTS_KEY, ["NZ" => 10]
                ]
            ]
        );

        $this->assertTrue($this->minimumCartCondition->met());

    }

    public function testNotMet()
    {
        $this->setBasicStubs();

        $this->configureStub(
            [
                [
                    MinimumCartTotal::AMOUNTS_KEY, ["NZ" => 10]
                ]
            ],
            [
                [
                    MinimumCartTotal::AMOUNTS_KEY, ["NZ" => 10]
                ]
            ]
        );
        $this->purchaseableHolder->expects($this->any())
            ->method('getTotal')
            ->will($this->returnValue(15));

        $this->assertFalse($this->minimumCartCondition->met());
    }

    public function testAlreadyMetAlmostMet()
    {
        $this->configureStub(
            [
                [
                    MinimumCartTotal::AMOUNTS_KEY, ["NZ" => 10]
                ]
            ],
            [
                [
                    MinimumCartTotal::AMOUNTS_KEY, ["NZ" => 10]
                ]
            ]
        );

        $this->setBasicStubs();

        $this->purchaseableHolder->expects($this->any())
            ->method('getTotal')
            ->will($this->returnValue(10000));

        $this->assertFalse($this->minimumCartCondition->almostMet());

    }

    public function testAlmostMet()
    {
        $this->configureStub(
            [
                [
                    MinimumCartTotal::AMOUNTS_KEY, ["NZ" => 10]
                ]
            ],
            [
                [
                    MinimumCartTotal::AMOUNTS_KEY, ["NZ" => 10]
                ]
            ]
        );

        $this->setBasicStubs();
        $this->purchaseableHolder->expects($this->any())
            ->method('getEventService')
            ->will($this->returnValue(false));

        $this->purchaseableHolder->expects($this->any())
            ->method('getTotal')
            ->will($this->onConsecutiveCalls(5, 20, 5));

        $this->assertTrue($this->minimumCartCondition->almostMet());

    }

    public function testNotAlmostMet()
    {
        $this->configureStub(
            [
                [
                    MinimumCartTotal::AMOUNTS_KEY, ["NZ" => 10]
                ]
            ],
            [
                [
                    MinimumCartTotal::AMOUNTS_KEY, ["NZ" => 10]
                ]
            ]
        );

        $this->setBasicStubs();
        $this->purchaseableHolder->expects($this->any())
            ->method('getEventService')
            ->will($this->returnValue(false));

        $this->purchaseableHolder->expects($this->any())
            ->method('getTotal')
            ->will($this->returnValue(0));

        $this->assertFalse($this->minimumCartCondition->almostMet());
    }

}
