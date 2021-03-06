<?php

namespace Heystack\Deals\Events;

class ConditionEventTest extends \PHPUnit_Framework_TestCase
{

    protected $event;
    protected $dealHandler;
    protected $state;
    protected $eventDispatcher;

    protected function setUp()
    {

        $this->state = $this->getMockBuilder('Heystack\Core\State\State')
            ->disableOriginalConstructor()
            ->getMock();

        $this->eventDispatcher = $this->getMockBuilder('Heystack\Core\EventDispatcher')
            ->disableOriginalConstructor()
            ->getMock();

        $this->dealHandler = $this->getMockBuilder('Heystack\Deals\DealHandler')
            ->setConstructorArgs([$this->state, $this->eventDispatcher, 'testDeal', null])
            ->getMock();

        $this->event = new ConditionEvent($this->dealHandler);

    }

    public function testIsNotInitiallyEmpty()
    {
        $this->assertAttributeNotEmpty('deal', $this->event);

    }

    public function testGetDeal()
    {
        $this->assertEquals($this->event->getDealHandler(), $this->dealHandler);
    }

    public function testGetDispatcher()
    {
        $this->assertEquals($this->event->getDispatcher(), NULL);
    }

}
