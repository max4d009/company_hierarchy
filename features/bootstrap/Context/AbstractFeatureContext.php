<?php

namespace BehatTest\Context;

use App\Entity\Asteroid;
use Behat\Behat\Context\Context;
use BehatTest\Models\RequestModel;
use BehatTest\Storage\FeatureSharedStorage;
use BehatTest\Traits\DoctrineTrait;
use Doctrine\DBAL\Driver\Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;


abstract class AbstractFeatureContext implements Context
{
    use DoctrineTrait;

    /** @var RequestModel */
    protected $request;

    /** @var Response|null */
    protected $response;

    /** @var FeatureSharedStorage */
    protected $featureStorage;

    /** @var Client $client */
    protected $client;



    public function __construct()
    {
        $this->featureStorage = FeatureSharedStorage::getInstance();
        $this->request = RequestModel::getInstance();
    }

    /**
     * @BeforeScenario
     * @throws Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function beforeScenario(): void
    {
        $this->request = RequestModel::getInstance();
        $connection = $this->getEm()->getConnection();
        // todo: need refactor
        $this->getEm()->createQuery('DELETE FROM '. Asteroid::class)->execute();
        $statement = $connection->prepare("DELETE FROM messenger_messages");
        $statement->execute();

    }


    /**
     * @BeforeSuite
     */
    public static function beforeSuite(): void
    {
        $logDir = __DIR__.'/../../../var/log/';
        if (file_exists($logDir))
            foreach (glob($logDir.'*') as $file)
                unlink($file);
    }

}
