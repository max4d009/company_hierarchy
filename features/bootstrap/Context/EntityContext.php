<?php

namespace BehatTest\Context;


use App\Entity\Category;
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

    private const CATEGORY = 'Category';


    public function getEntityMapping(): array
    {
        return [
            self::CATEGORY => Category::class,
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
     * @Then Searched in table for entity :entityName and found the records:
     * @throws EntityNotFoundException
     */
    public function searchedInTableForEntityAndFoundTheRecords(string $entityName, TableNode $table)
    {
        $this->assertByTableAndClassName($entityName, $table, true);
    }


    /**
     * @Then Searched in table for entity :entityName and not found the records:
     * | field | value |
     *
     * @param string    $entityName
     * @param TableNode $table
     *
     * @throws EntityNotFoundException|\Exception
     */
    public function searchedInTableForEntityAndNotFoundTheRecords(string $entityName, TableNode $table): void
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
        $repository = $this->getEntityRepositoryByEntityName($entityName);

        $criteria = [];
        $arrayKeys = [];
        foreach ($table->getColumnsHash() as $keyRow => $rows) {
            foreach ($rows as $key => $value){

                $value = $this->featureStorage->replacePlaceholderString($value);

                // For DateTime
                if (mb_strpos($key, 'date_') === 0 && mb_strpos($value, 'DateTime=') === 0) {
                    $value = new \DateTime(str_replace('DateTime=', '', $value));
                }

                // For Boolean
                if ($value === 'true') {
                    $value = true;
                }
                if ($value === 'false') {
                    $value = false;
                }
                if ($value === 'null') {
                    $value = null;
                }

                // For json fields
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

            // For normal fields
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
                // For array fields
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
     * @Given Set entity namespace as :namespace
     */
    public function setEntityNamespaceAs($namespace)
    {
        $this->featureStorage->set('defaultEntityNamespace', $namespace);
    }

    /**
     * @param string $entityName
     * @return mixed
     * @throws Exception
     */
    private function getEntityByName(string $entityName)
    {
        $namespace = $this->featureStorage->get('defaultEntityNamespace');
        if(!$namespace){
            throw new Exception('(!) Before working with entity by name need Set entity namespace');
        }
        $classEntity = $namespace.  '\\' . $entityName;
        if(!class_exists($classEntity)){
            throw new EntityNotFoundException(sprintf('(!) Class %s Не найден', $classEntity));
        }
        $entity = new $classEntity;
        return new $entity;
    }

    /**
     * @param string $entityName
     * @return ObjectRepository
     * @throws Exception
     */
    private function getEntityRepositoryByEntityName(string $entityName)
    {
        $entity = $this->getEntityByName($entityName);
        $repository = $this->getEm()->getRepository(get_class($entity));
        return $repository;
    }



    /**
     * @Given Table for entity :entityName contains:
     * @throws Exception
     */
    public function tableForEntityContain($entityName, TableNode $tableNode)
    {
        $this->save($tableNode, function () use ($entityName) {
            return ($this->getEntityByName($entityName));
        });
    }

    /**
     * @param TableNode $tableNode
     * @param Closure $closure
     *
     * @throws \Exception
     */
    protected function save(TableNode $tableNode, Closure $closure): void
    {
        foreach ($tableNode->getColumnsHash() as $row) {
            $row = $this->featureStorage->replacePlaceholdersInArrayRecursive($row);

            $object = $closure($row);

            if (!empty($row['storage_key'])) {
                $this->featureStorage->set($row['storage_key'], $object);
                unset($row['storage_key']);
            }

            foreach ($row as $attribute => $value) {
                if($value == 'null'){
                    continue;
                }

                if(!is_object($value)){
                    if (mb_strpos($value, 'DateTime=') === 0) {
                        $value = new DateTime(str_replace('DateTime=', '', $value));
                    } elseif (mb_strpos($value, 'Date=') === 0) {
                        $time = new DateTime(str_replace('Date=', '', $value));
                        $value = $time->setTime(0,0,0,0);
                    }
                }
                $this->accessor->setValue($object, $attribute, $value);
            }
            $this->getEm()->persist($object);
        }
        $this->getEm()->flush();
    }

    /**
     * @When Clear table for entity :entityName
     * @throws Exception
     */
    public function clearTableForEntity($entityName)
    {
        $repository = $this->getEntityRepositoryByEntityName($entityName);
        foreach ($repository->findAll() as $entity){
            $this->em->remove($entity);
        }
        $this->em->flush();
    }


    /**
     * @Given Delete a table row for entity :entityName by id :id
     * @throws Exception
     */
    public function deleteATableRowForEntityById($entityName, $id)
    {
        $id = $this->featureStorage->replacePlaceholderString($id);
        $repository = $this->getEntityRepositoryByEntityName($entityName);
        $entity = $repository->find($id);
        $this->em->remove($entity);
        $this->em->flush();
    }

}
