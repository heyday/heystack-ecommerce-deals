<?php
namespace Heystack\Subsystem\Deals\Test;

use Heystack\Subsystem\Deals\Result\RelativePrice;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2013-04-25 at 16:49:39.
 */
class RelativePriceTest extends \PHPUnit_Framework_TestCase
{
    const PRIMARY_IDENTIFIER = 'Identifier123';
    const PERCENTAGE_DISCOUNT = 15;
    const OLD_PRICE = 120;
    const EXPECTED_DISCOUNT = 18;
    const PURCHASABLE_QUANTITY = 1;

    /**
     * @var RelativePrice
     */
    protected $relativePriceResult;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $adaptableConfigurationStub = $this->getMockBuilder('Heystack\Subsystem\Deals\AdaptableConfiguration')
            ->disableOriginalConstructor()
            ->getMock();

        $adaptableConfigurationStub->expects($this->any())
            ->method('getConfig')
            ->will(
                $this->returnValueMap(
                    [
                        ['purchasable_identifier', self::PRIMARY_IDENTIFIER],
                        ['value', self::PERCENTAGE_DISCOUNT]
                    ]
                )
            );

        $adaptableConfigurationStub->expects($this->any())
            ->method('hasConfig')
            ->will(
                $this->returnValueMap(
                    [
                        ['purchasable_identifier', true],
                        ['value', true]
                    ]
                )
            );

        $purchasableStub = $this->getMockBuilder('Heystack\Subsystem\Ecommerce\Purchasable\Interfaces\PurchasableInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $purchasableStub->expects($this->any())
            ->method('getPrice')
            ->will(
                $this->returnValue(self::OLD_PRICE)
            );

        $purchasableStub->expects($this->any())
            ->method('getQuantity')
            ->will(
                $this->returnValue(self::PURCHASABLE_QUANTITY)
            );


        $purchasableHolderStub = $this->getMockBuilder('Heystack\Subsystem\Ecommerce\Purchasable\Interfaces\PurchasableHolderInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $purchasableHolderStub->expects($this->any())
            ->method('getPurchasablesByPrimaryIdentifier')
            ->will(
                $this->returnValue(array($purchasableStub))
            );

        $eventDispatcherStub = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->relativePriceResult = new RelativePrice($eventDispatcherStub, $purchasableHolderStub, $adaptableConfigurationStub);
    }

    /**
     * @covers Heystack\Subsystem\Deals\Result\RelativePrice::getDescription
     */
    public function testGetDescription()
    {
        $this->assertEquals('Percentage Discount: ' . self::PERCENTAGE_DISCOUNT, $this->relativePriceResult->getDescription());
    }

    /**
     * @covers Heystack\Subsystem\Deals\Result\RelativePrice::process
     */
    public function testProcess()
    {
        $dealHandlerStub = $this->getMockBuilder('Heystack\Subsystem\Deals\DealHandler')
            ->disableOriginalConstructor()
            ->getMock();

        $this->assertEquals(self::EXPECTED_DISCOUNT, $this->relativePriceResult->process($dealHandlerStub));
    }
}
