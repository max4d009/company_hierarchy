<?php

namespace BehatTest\Context;

use App\Entity\Asteroid;
use App\Messenger\Nasa\Handler\UpdateAsteroidHandler;
use App\Messenger\Nasa\Message\UpdateAsteroidMessage;
use Behat\Gherkin\Node\TableNode;
use BehatTest\Storage\FeatureSharedStorage;
use BehatTest\Traits\DoctrineTrait;
use DateTime;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Inflector\Inflector;
use Doctrine\ORM\EntityNotFoundException;
use Closure;
use Doctrine\Persistence\ObjectRepository;
use PHPUnit\Framework\Assert;
use Exception;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * Defines application features from the specific context.
 */
class EntityContext extends AbstractFeatureContext
{
    use DoctrineTrait;

    private const UPDATE_ASTEROID_MESSAGE = 'UpdateAsteroidMessage';
    private const UPDATE_ASTEROID_HANDLER = 'UpdateAsteroidHandler';
    private const ASTEROID = 'Asteroid';

    public function getEntityMapping(): array
    {
        return [
            self::UPDATE_ASTEROID_MESSAGE => UpdateAsteroidMessage::class,
            self::UPDATE_ASTEROID_HANDLER => UpdateAsteroidHandler::class,
            self::ASTEROID => Asteroid::class,
        ];
    }
    /** @var PropertyAccessor */
    protected $accessor;

    public function __construct()
    {
        $this->featureStorage = FeatureSharedStorage::getInstance();
        $this->accessor = PropertyAccess::createPropertyAccessor();

        parent::__construct();
    }

    /**
     * @param int    $count
     * @param string $entityName
     * @param string $field
     * @param string $fieldValue
     *
     * @throws \Exception
     *
     * @Given /^I find ([0-9]+) "([^"]*)" by "([^"]*)"="([^"]*)"$/
     *
     * $entityName mapped in @see AbstractDataContext::getEntityMapping()
     */
    public function iFindCountByField(int $count, string $entityName, string $field, string $fieldValue): void
    {
        $fieldValue = $this->featureStorage->replacePlaceholderString($fieldValue);

        $repository = $this->getRepositoryByEntityName($entityName);

        $result = $repository->findBy([Inflector::camelize($field) => $fieldValue]);

        if (count($result) !== $count) {
            throw new \Exception(sprintf('Expected %s entities, %s found', $count, count($result)));
        }
    }

    /**
     * @Then DB should contain :entityName with fields:
     * | field | value |
     *
     * @param string    $entityName
     * @param TableNode $table
     *
     * @throws EntityNotFoundException|\Exception
     */
    public function dbShouldContainEntity(string $entityName, TableNode $table): void
    {
        if (!$entity = $this->findOneByTableAndClassName($entityName, $table)) {
            throw new EntityNotFoundException(sprintf('%s is not found', $entityName));
        }
    }

    /**
     * @Then DB should contain :entityName list with fields:
     * | field | value |
     *
     * @param string    $entityName
     * @param TableNode $table
     *
     * @throws EntityNotFoundException|\Exception
     */
    public function dbShouldContainListEntity(string $entityName, TableNode $table): void
    {
        $this->assertByTableAndClassName($entityName, $table, true);
    }

    /**
     * @Then DB should not contain :entityName list with fields:
     * | field | value |
     *
     * @param string    $entityName
     * @param TableNode $table
     *
     * @throws EntityNotFoundException|\Exception
     */
    public function dbShouldNotContainListEntity(string $entityName, TableNode $table): void
    {
        $this->assertByTableAndClassName($entityName, $table, false);
    }

    /**
     * @param string $entityName
     * @param TableNode $table
     *
     * @return null|object
     * @throws \Exception
     */
    private function findOneByTableAndClassName(string $entityName, TableNode $table)
    {

        $repository = $this->getRepositoryByEntityName($entityName);

        $criteria = [];
        $arrayKeys = [];
        foreach ($table->getRowsHash() as $key => $value) {
            $value = $this->featureStorage->replacePlaceholderString($value);

            if (mb_strpos($value, 'DateTime=') === 0) {
                $value = new DateTime(str_replace('DateTime=', '', $value));
            } elseif (mb_strpos($value, 'Date=') === 0) {
                $time = new DateTime(str_replace('Date=', '', $value));
                $value = $time->setTime(0,0,0,0);
            }

            if ($value === 'true') {
                $value = true;
            }

            if ($value === 'false') {
                $value = false;
            }

            if(is_string($value) && substr($value, 0, 6) == 'array['){
                $value = str_replace('array[', '', $value);
                $value = str_replace(']', '', $value);
                $value = explode(',', $value);
            }

            if(!is_array($value)){
                $criteria[Inflector::camelize($key)] = $value;
            } else {
                $arrayKeys[Inflector::ucwords($key)] = $value;
            }

        }

        if(!$arrayKeys){
            return $repository->findOneBy($criteria);
        } else {
            $res = $repository->findOneBy($criteria);
            foreach ($arrayKeys as $field => $array){
                if(!method_exists($res, 'get'.$field)){
                    throw new \Exception('findOneByTableAndClassName json field not found');
                }
                $result = call_user_func([$res, 'get'.$field]);
                $diffArr = array_diff($result, $array);

                if($diffArr){
                   Assert::assertEquals($array, $result, "Несовпадение в '$field'");
                }
            }
            return $res;
        }

    }


    /**
     * @param string $entityName
     * @param TableNode $table
     *
     * @param bool $mustContain
     * @return void
     * @throws EntityNotFoundException
     * @throws Exception
     */
    private function assertByTableAndClassName(string $entityName, TableNode $table, bool $mustContain)
    {
        $repository = $this->getRepositoryByEntityName($entityName);

        $criteria = [];
        $arrayKeys = [];
        foreach ($table->getColumnsHash() as $keyRow => $rows) {
            foreach ($rows as $key => $value){


                $value = $this->featureStorage->replacePlaceholderString($value);

                if (mb_strpos($key, 'date_') === 0 && mb_strpos($value, 'DateTime=') === 0) {
                    $value = new \DateTime(str_replace('DateTime=', '', $value));
                }

                if ($value === 'true') {
                    $value = true;
                }

                if ($value === 'false') {
                    $value = false;
                }

                if(is_string($value) && substr($value, 0, 6) == 'array['){
                    $value = str_replace('array[', '', $value);
                    $value = str_replace(']', '', $value);
                    $value = explode(',', $value);
                }


                if(!is_array($value)){
                    $criteria[$key] = $value;
                } else {
                    $arrayKeys[$key] = $value;
                }
            }

            if(!$arrayKeys){
                $res =  $repository->findOneBy($criteria);
                if($mustContain){
                    if(!$res){
                        throw new EntityNotFoundException(sprintf('%s Не найден', $entityName));
                    }
                } else {
                    if($res){
                        throw new EntityNotFoundException(sprintf('%s Запись существует, хотя не должна', $entityName));
                    }
                }
            } else {
                $res = $repository->findOneBy($criteria);
                foreach ($arrayKeys as $field => $array){
                    if(!method_exists($res, 'get'.$field)){
                        throw new \Exception('findOneByTableAndClassName json field not found');
                    }
                    $result = call_user_func([$res, 'get'.$field]) ?? [];
                    $diffArr = array_diff($array, $result);

                    if($diffArr){
                        Assert::assertEquals($array, $result, "Несовпадение в '$field'");
                    }
                }
                if($mustContain){
                    if(!$res){
                        throw new EntityNotFoundException(sprintf('%s Не найден', $entityName));
                    }
                } else {
                    if($res){
                        throw new EntityNotFoundException(sprintf('%s Запись существует, хотя не должна', $entityName));
                    }
                }


            }
        }
    }

    /**
     * @param string $name
     *
     * @return ObjectRepository
     */
    private function getRepositoryByEntityName(string $name): ObjectRepository
    {
        return $this->getEm()->getRepository($this->getEntityMapping()[$name]);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    private function getEntityClassByName(string $name): string
    {
        return $this->getEntityMapping()[$name];
    }


    /**
     * @Given Load default Fixtures :fixtureList
     */
    public function loadDefaultFixtures($fixtureList)
    {
        $fixtureList = explode(',', $fixtureList);
        $purger = new ORMPurger();
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_DELETE);
        $executor = new ORMExecutor($this->getEm(), $purger);
        $loader = new Loader();

        foreach ($fixtureList as $fixture){
            $className = "BehatTest\\ORM\\".trim($fixture);
            $loader->addFixture(new $className);
        }
        $executor->execute($loader->getFixtures());
    }


    private function tableNodeFieldArrNormalize(&$value)
    {
        $value = str_replace('[', '', $value);
        $value = str_replace(']', '', $value);
        $value = explode(',', $value);
        foreach ($value as $key => $v){
            $value[$key] = trim($v);
        }
    }

    /**
     * @Given Asteroid table has contain:
     * @throws \Exception
     */
    public function asteroidTableHasContain(TableNode $node)
    {
        $this->save($node, function (array &$row) {
            return (new Asteroid());
        });
    }


    /**
     * @param TableNode $node
     * @param Closure $closure
     *
     * @throws \Exception
     */
    protected function save(TableNode $node, Closure $closure): void
    {
        foreach ($node->getColumnsHash() as $row) {
            $row = $this->featureStorage->replacePlaceholdersInArrayRecursive($row);

            $object = $closure($row);

            if (!empty($row['storage_key'])) {
                $this->featureStorage->set($row['storage_key'], $object);
                unset($row['storage_key']);
            }

            foreach ($row as $attribute => $value) {

                if (mb_strpos($value, 'DateTime=') === 0) {
                    $value = new DateTime(str_replace('DateTime=', '', $value));
                } elseif (mb_strpos($value, 'Date=') === 0) {
                    $time = new DateTime(str_replace('Date=', '', $value));
                    $value = $time->setTime(0,0,0,0);
                }

                $this->accessor->setValue($object, $attribute, $value);
            }

            $this->getEm()->persist($object);
        }

        $this->getEm()->flush();
    }
}
