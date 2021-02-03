<?php

namespace App\Service\FrontApiVersions;


use App\FrontApi\Exception\FrontApiException;

class FrontApiContext
{
    const V1 = 'v1';

    /**
     * @return array
     */
    public static function getVersionList()
    {
        return [
            self::V1
        ];
    }

    /**
     * @var FrontApiInterface[]
     */
    private array $apiServiceVersions = [];


    /**
     * @param FrontApiInterface $apiService
     */
    public function addApiVersion(FrontApiInterface $apiService)
    {
        $this->apiServiceVersions[] = $apiService;
    }


    /**
     * @param string $frontApiVersion
     * @return FrontApiInterface|null
     * @throws \Exception
     */
    public function getApiService(string $frontApiVersion)
    {
        if(!in_array($frontApiVersion, self::getVersionList())){
            throw new FrontApiException('Wrong api version');
        }

        foreach ($this->apiServiceVersions as $service) {

            if ($service->getApiVersion() == $frontApiVersion) {
                return $service;
            }
        }
        return null;
    }

    /**
     * @return FrontApiInterface[]
     */
    public function getStrategies()
    {
        return $this->apiServiceVersions;
    }

}


