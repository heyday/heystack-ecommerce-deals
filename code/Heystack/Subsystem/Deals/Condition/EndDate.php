<?php

namespace Heystack\Subsystem\Deals\Condition;

use Heystack\Subsystem\Deals\Interfaces\AdaptableConfigurationInterface;
use Heystack\Subsystem\Deals\Interfaces\ConditionAlmostMetInterface;
use Heystack\Subsystem\Deals\Interfaces\ConditionInterface;

/**
 * Determine whether the current date is less than the end date
 *
 * @copyright  Heyday
 * @author Stevie Mayhew <stevie@heyday.co.nz>
 * @package Ecommerce-Deals
 */
class EndDate implements ConditionInterface, ConditionAlmostMetInterface
{
    const CONDITION_TYPE = 'EndDate';
    const END_KEY = 'end';

    /**
     * @var string
     */
    public static $time_format = 'd-m-Y';
    /**
     * @var int
     */
    protected $endDate;
    /**
     * @var
     */
    protected $currentTime;
    /**
     * @param AdaptableConfigurationInterface $configuration
     * @throws \Exception if the configuration does not have a 'start' value
     */
    public function __construct(AdaptableConfigurationInterface $configuration)
    {

        if ($configuration->hasConfig(self::END_KEY)) {

            $this->endDate = strtotime($configuration->getConfig(self::END_KEY));

        } else {

            throw new \Exception('EndTimeCondition requires a end date');

        }

        // Set up a default currentTime, but allow the value to be overridden through a setter.
        $this->currentDate = time();

    }
    /**
     * @return string that indicates the type of condition this class is implementing
     */
    public function getType()
    {
        return self::CONDITION_TYPE;
    }
    /**
     * @return int
     */
    public function met()
    {
        return $this->currentDate < $this->endDate;
    }

    public function almostMet()
    {
        return $this->met();
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Valid if current date less than: ' . date(self::$time_format, $this->endDate);
    }

    /**
     * @param mixed $currentTime
     */
    public function setCurrentTime($currentTime)
    {
        $this->currentTime = $currentTime;
    }

    /**
     * @return mixed
     */
    public function getCurrentTime()
    {
        return $this->currentTime;
    }
}
