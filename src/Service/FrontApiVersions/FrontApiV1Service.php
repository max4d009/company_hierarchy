<?php

namespace App\Service\UGStat\StatGetStrategy;


use App\Dto\UGStat\Request\GetStatRequestDto;
use App\Repository\UGStat\UGStatRepository;
use App\Service\UGStat\StatCacheService;

class ChartStrategy implements UGStatTypeInterface
{
    public const NAME_STRATEGY = 'chart';

    private $globalStatRepository;
    private $statCacheService;

    public function __construct(UGStatRepository $globalStatRepository, StatCacheService $statCacheService)
    {
        $this->globalStatRepository = $globalStatRepository;
        $this->statCacheService = $statCacheService;
    }

    public function getName()
    {
        return self::NAME_STRATEGY;
    }

    /**
     * @param GetStatRequestDto $dto
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Exception
     */
    public function getStat(GetStatRequestDto $dto)
    {
        $countUploads = $this->statCacheService->getChartCache($dto->getMonth(), $dto->getYear(), $dto->getHost(), false, false);
        $countUploadsMarket = $this->statCacheService->getChartCache($dto->getMonth(), $dto->getYear(), $dto->getHost(), true, false);
        $countUploadsFile = $this->statCacheService->getChartCache($dto->getMonth(), $dto->getYear(), $dto->getHost(), false, false);
        $countUploadsUsers = $this->statCacheService->getChartCache($dto->getMonth(), $dto->getYear(), $dto->getHost(), false, true);


        $uploadsOk = [];
        $uploadsVk = [];
        $uploadsOkMarket = [];
        $uploadsVkMarket = [];
        $uploadsFile = [];

        foreach ($countUploads as $countUpload) {
            if($countUpload['direction'] == 1){
                $uploadsVk[] = $countUpload;
            }
            if($countUpload['direction'] == 2){
                $uploadsOk[] = $countUpload;
            }
        }

        foreach ($countUploadsMarket as $countUploadMarket) {
            if($countUploadMarket['direction'] == 1){
                $uploadsVkMarket[] = $countUploadMarket;
            }
            if($countUploadMarket['direction'] == 2){
                $uploadsOkMarket[] = $countUploadMarket;
            }
        }

        foreach ($countUploadsFile as $countUploadFile) {
            if(!in_array($countUploadFile['direction'],[1,2])){
                $uploadsFile[] = $countUploadFile;
            }
        }


        $vKUploadsData = $this->getChartData($uploadsVk, 'uploads', 'itemsCount', 'countUniqueUsers');
        $oKUploadsData = $this->getChartData($uploadsOk,  'uploads', 'itemsCount', 'countUniqueUsers');
        $vKUploadsDataMarket = $this->getChartData($uploadsVkMarket, 'uploads', 'itemsCount', 'countUniqueUsers');
        $oKUploadsDataMarket = $this->getChartData($uploadsOkMarket,  'uploads', 'itemsCount', 'countUniqueUsers');


        $uploadsUsersData = $this->getChartData($countUploadsUsers, 'countUniqueUsers');

        $uploadsFileData = $this->getChartData($uploadsFile, 'uploads', 'itemsCount');

        $allCountUniqueUsers = $this->statCacheService->getCountUniqueUsers($dto->getMonth(), $dto->getYear(), $dto->getHost());

        return [
            'vk_data'=>$this->fillChartDataWithEmptyValues($vKUploadsData, $dto->getMonth(), $dto->getYear(), ['uploads', 'itemsCount', 'countUniqueUsers']),
            'ok_data'=>$this->fillChartDataWithEmptyValues($oKUploadsData, $dto->getMonth(), $dto->getYear(), ['uploads', 'itemsCount', 'countUniqueUsers']),
            'vk_data_market'=>$this->fillChartDataWithEmptyValues($vKUploadsDataMarket, $dto->getMonth(), $dto->getYear(), ['uploads', 'itemsCount', 'countUniqueUsers']),
            'ok_data_market'=>$this->fillChartDataWithEmptyValues($oKUploadsDataMarket, $dto->getMonth(), $dto->getYear(), ['uploads', 'itemsCount', 'countUniqueUsers']),
            'users_data'=>$this->fillChartDataWithEmptyValues($uploadsUsersData, $dto->getMonth(), $dto->getYear(), ['countUniqueUsers']),
            'file_data'=>$this->fillChartDataWithEmptyValues($uploadsFileData, $dto->getMonth(), $dto->getYear(), ['uploads', 'itemsCount']),
            'all_count_unique_users'=>$allCountUniqueUsers
        ];
    }


    /**
     * @param array $result
     * @param string $field
     * @param string|null $field2
     * @param string|null $field3
     * @return array
     */
    private function getChartData(array $result,  string $field, string $field2 = null, string $field3 = null)
    {
        $resArr = [];

        foreach ($result as $key)
        {
            settype($key[$field], 'float');
            if($field2) settype($key[$field2], 'float');
            if($field3) settype($key[$field3], 'float');

            $key['day'] = str_pad($key['day'],2,0,STR_PAD_LEFT);
            $key['month'] = str_pad($key['month'],2,0,STR_PAD_LEFT);

            $tmpArr = [];
            $tmpArr['date'] = $key['day'].'.'.$key['month'].'.'.($key['year']-2000);
            $tmpArr[$field] = $key[$field];

            if($field2)
                $tmpArr[$field2] = $key[$field2];
            if($field3)
                $tmpArr[$field3] = $key[$field3];

            $resArr[] = $tmpArr;
        }

        return $resArr;
    }


    /**
     * Заполнить данные для графика пустыми значениями, если данные не за все дни
     * @param $chartData
     * @param $month
     * @param $year
     * @param $emptyFields
     * @return array
     * @throws \Exception
     */
    private function fillChartDataWithEmptyValues($chartData, $month, $year, $emptyFields)
    {
        if(empty($chartData)){
            return [];
        }
        if(strlen($month) != 2 or strlen($year) != 4){
            throw new \Exception('bad month or year in fillChartDataWithEmptyValues');
        }
        $date = \DateTime::createFromFormat('d.m.Y', "01.$month.$year");

        $m = str_pad($date->format('m'),2,0,STR_PAD_LEFT);
        $y = $date->format('y');

        $days = idate("t", $date->getTimestamp());
        $daysArr = [];
        for($d=1;$d<=$days;$d++){
            $ds = str_pad($d,2,0,STR_PAD_LEFT);
            $tmpArr = [];
            $tmpArr['date'] = "$ds.$m.$y";
            foreach ($emptyFields as $emptyField){
                $tmpArr[$emptyField] = 0;
            }
            $daysArr[] = $tmpArr;
        }

        foreach ($daysArr as $key=>$dayArr){
            foreach ($chartData as $chartPoint){
                if($chartPoint['date'] == $dayArr['date']){
                    $daysArr[$key] = $chartPoint;
                }
            }
        }

        return $daysArr;
    }
}


