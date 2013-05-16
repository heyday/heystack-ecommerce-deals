<?php
namespace Heystack\Subsystem\Deals\Test;

use Heystack\Subsystem\Core\Storage\Backends\SilverStripeOrm\Backend;
use Heystack\Subsystem\Deals\DealHandler;
use Heystack\Subsystem\Ecommerce\Transaction\TransactionModifierTypes;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2013-04-25 at 16:49:12.
 */
class DealHandlerTest extends \PHPUnit_Framework_TestCase
{
    const DEAL_ID = 'deal123';
    const INITIAL_TOTAL = 500;
    const FINAL_TOTAL = 200;
    const PROMOTIONAl_MESSAGE = 'This is a test promotional message';
    const CONDITION_DESCRIPTION = 'This is a test condition description';
    const RESULT_DESCRIPTION = 'This is a test result description';
    const PARENT_REFERENCE = 'Test parent reference';

    /**
     * @var DealHandler
     */
    protected $dealHandler;

    protected $condition;

    protected $result;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $stateServiceStub = $this->getMockBuilder('Heystack\Subsystem\Core\State\State')
            ->disableOriginalConstructor()
            ->getMock();

        $stateServiceStub->expects($this->any())
        ->method('getByKey')
        ->will(
            $this->returnValue(array(
                    DealHandler::TOTAL_KEY => self::INITIAL_TOTAL
                ))
        );

        $eventDispatcherStub = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->dealHandler = new DealHandler($stateServiceStub, $eventDispatcherStub, self::DEAL_ID, self::PROMOTIONAl_MESSAGE);

        $this->condition = $this->getMockBuilder('Heystack\Subsystem\Deals\Interfaces\ConditionInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->condition->expects($this->any())
            ->method('met')
            ->will(
                $this->returnValue(true)
            );

        $this->condition->expects($this->any())
            ->method('getDescription')
            ->will(
                $this->returnValue(self::CONDITION_DESCRIPTION)
            );

        $this->result = $this->getMockBuilder('Heystack\Subsystem\Deals\Interfaces\ResultInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->result->expects($this->any())
            ->method('process')
            ->will(
                $this->returnValue(self::FINAL_TOTAL)
            );

        $this->result->expects($this->any())
            ->method('getDescription')
            ->will(
                $this->returnValue(self::RESULT_DESCRIPTION)
            );

        $this->dealHandler->setResult($this->result);

    }

    /**
     * @covers Heystack\Subsystem\Deals\DealHandler::getPromotionalMessage
     */
    public function testGetPromotionalMessage()
    {
        $this->assertEquals(self::PROMOTIONAl_MESSAGE, $this->dealHandler->getPromotionalMessage());
    }

    /**
     * @covers Heystack\Subsystem\Deals\DealHandler::addCondition
     */
    public function testAddCondition()
    {
        $this->dealHandler->addCondition($this->condition);

        $conditions = $this->dealHandler->getConditions();

        $this->assertSame($this->condition, reset($conditions));
    }

    /**
     * @covers Heystack\Subsystem\Deals\DealHandler::getIdentifier
     */
    public function testGetIdentifier()
    {
        $this->assertInstanceOf('Heystack\Subsystem\Core\Identifier\Identifier', $this->dealHandler->getIdentifier());
    }

    /**
     * @covers Heystack\Subsystem\Deals\DealHandler::getTotal
     */
    public function testGetTotal()
    {
        $this->assertEquals(self::INITIAL_TOTAL, $this->dealHandler->getTotal());

        $this->dealHandler->updateTotal();

        $this->assertEquals(self::FINAL_TOTAL, $this->dealHandler->getTotal());
    }

    /**
     * @covers Heystack\Subsystem\Deals\DealHandler::conditionsMet
     */
    public function testConditionsMet()
    {
        $this->dealHandler->addCondition($this->condition);

        $this->assertTrue($this->dealHandler->conditionsMet());
    }

    /**
     * @covers Heystack\Subsystem\Deals\DealHandler::getType
     */
    public function testGetType()
    {
        $this->assertEquals(TransactionModifierTypes::DEDUCTIBLE, $this->dealHandler->getType());
    }

    /**
     * @covers Heystack\Subsystem\Deals\DealHandler::getStorableData
     */
    public function testGetStorableData()
    {
        $conditionDescription = self::CONDITION_DESCRIPTION;
        $resultDescription = self::RESULT_DESCRIPTION;
        $description = <<<DESCRIPTION
Conditions:
$conditionDescription
Result:
$resultDescription
DESCRIPTION;

        $expectedInitialResult = array(
            'id' => 'Deal',
            'parent' => true,
            'flat' => array(
                'Total' => self::INITIAL_TOTAL,
                'Description' => $description,
                'ParentID' => self::PARENT_REFERENCE
            )
        );

        $expectedFinalResult = array(
            'id' => 'Deal',
            'parent' => true,
            'flat' => array(
                'Total' => self::FINAL_TOTAL,
                'Description' => $description,
                'ParentID' => self::PARENT_REFERENCE
            )
        );

        $this->dealHandler->addCondition($this->condition);
        $this->dealHandler->setParentReference(self::PARENT_REFERENCE);

        $this->assertEquals($expectedInitialResult, $this->dealHandler->getStorableData());

        $this->dealHandler->updateTotal();

        $this->assertEquals($expectedFinalResult, $this->dealHandler->getStorableData());
    }

    /**
     * @covers Heystack\Subsystem\Deals\DealHandler::getStorableIdentifier
     */
    public function testGetStorableIdentifier()
    {
        $this->assertEquals(DealHandler::IDENTIFIER . self::DEAL_ID, $this->dealHandler->getStorableIdentifier());
    }

    /**
     * @covers Heystack\Subsystem\Deals\DealHandler::getSchemaName
     */
    public function testGetSchemaName()
    {
        $this->assertEquals('Deal', $this->dealHandler->getSchemaName());
    }

    /**
     * @covers Heystack\Subsystem\Deals\DealHandler::getStorableBackendIdentifiers
     */
    public function testGetStorableBackendIdentifiers()
    {
        $this->assertEquals([Backend::IDENTIFIER], $this->dealHandler->getStorableBackendIdentifiers());
    }

    /**
     * @covers Heystack\Subsystem\Deals\DealHandler::getParentReference
     */
    public function testGetParentReference()
    {
        $this->dealHandler->setParentReference(self::PARENT_REFERENCE);

        $this->assertEquals(self::PARENT_REFERENCE, $this->dealHandler->getParentReference());
    }

}
