<?php

namespace App\Service\FrontApiVersions;


class FrontApiContext
{
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
        if(!in_array($frontApiVersion, FrontApiVersionsEnum::getVersionList())){
            throw new \Exception('wrong api version');
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


