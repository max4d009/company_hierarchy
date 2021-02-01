<?php

namespace App\Service\UGStat\StatGetStrategy;


use App\Dto\UGStat\Request\GetStatRequestDto;

/**
 * Interface UGStatTypeInterface
 * @package App\Service\UGStat\StatGetStrategy
 */
interface UGStatTypeInterface
{
    /**
     * @return mixed
     */
    public function getName();

    /**
     * @param GetStatRequestDto $getStatRequestDto
     * @return mixed
     */
    public function getStat(GetStatRequestDto $getStatRequestDto);
}
