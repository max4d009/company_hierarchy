<?php

namespace App\Service\UGStat;

use App\Service\UGStat\StatGetStrategy\UGStatTypeInterface;


/**
 * Class UGStatTypeContext
 */
class StatGetContext
{

    /**
     * @var array
     */
    private $strategies = array();


    /**
     * @param UGStatTypeInterface $strategy
     */
    public function addStrategy(UGStatTypeInterface $strategy)
    {
        $this->strategies[] = $strategy;
    }


    /**
     * @param $strategyName
     * @return null|UGStatTypeInterface $strategy
     */
    public function getStrategy($strategyName)
    {
        foreach ($this->strategies as $strategy)
        {
            if ($strategy->getName() == $strategyName)
            {
                return $strategy;
            }
        }
        return null;
    }

    /**
     * @return UGStatTypeInterface[]
     */
    public function getStrategies()
    {
        return $this->strategies;
    }


    /**
     * @param $strategies
     */
    public function setStrategies($strategies)
    {
        $this->strategies = $strategies;
    }

    /**
     * @return UGStatTypeInterface[]
     */
    public function getStrategyList()
    {
        return $this->strategies;
    }

    /**
     * @return string
     */
    public function getStrategyListString()
    {
        $strategyNameList = [];
        foreach ($this->strategies as $strategy){
            $strategyNameList[] = $strategy->getName();
        }
        return implode(',',  $strategyNameList);
    }

}


