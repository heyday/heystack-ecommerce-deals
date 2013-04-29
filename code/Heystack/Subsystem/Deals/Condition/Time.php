<?php

namespace Heystack\Subsystem\Deals\Condition;

use Heystack\Subsystem\Deals\Interfaces\ConditionInterface;
use Heystack\Subsystem\Deals\Interfaces\AdaptableConfigurationInterface;

/**
 *
 * @copyright  Heyday
 * @author Glenn Bautista <glenn@heyday.co.nz>
 * @package Ecommerce-Deals
 */
class Time implements ConditionInterface
{
    /**
     * @var string
     */
    public static $time_format = 'd-m-Y';
    /**
     * @var int
     */
    protected $startTime;
    /**
     * @var int
     */
    protected $endTime;
    /**
     * @var
     */
    protected $currentTime;
    /**
     * @param AdaptableConfigurationInterface $configuration
     */
    public function __construct(AdaptableConfigurationInterface $configuration)
    {

        if ($configuration->hasConfig('start')) {

            $this->startTime = strtotime($configuration->getConfig('start'));

        } else {

            throw new \Exception('Time Condition needs a start time configuration');

        }

        if ($configuration->hasConfig('end')) {

            $this->endTime = strtotime($configuration->getConfig('end'));

        }
    }
    /**
     * @param array $data
     * @return bool
     */
    public function met(array $data = null)
    {
        if (!is_null($data) && is_array($data) && isset($data['Time'])) {
            $this->currentTime = $data['Time'];
        } else {
            $this->currentTime = time();
        }

        if ($this->startTime && $this->endTime) {
            return ($this->currentTime > $this->startTime) && ($this->currentTime < $this->endTime);
        }

        if ($this->startTime && !$this->endTime) {
            return ($this->currentTime > $this->startTime);
        }

        if ($this->endTime && !$this->startTime) {
            return ($this->currentTime < $this->endTime);
        }

        return false;
    }
    /**
     * @return string
     */
    public function getDescription()
    {
        $description = array();

        if ($this->startTime) {
            $description[] = 'From: ' . date(self::$time_format, $this->startTime);
        }
        if ($this->endTime) {
            $description[] = 'To: ' . date(self::$time_format, $this->endTime);
        }

        return implode('; ', $description);
    }
}
