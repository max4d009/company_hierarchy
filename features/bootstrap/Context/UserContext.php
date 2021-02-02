<?php


namespace BehatTest\Context;


use App\Entity\User;
use BehatTest\Models\RequestModel;
use BehatTest\Storage\FeatureSharedStorage;
use BehatTest\Traits\ContainerTrait;
use BehatTest\Traits\DoctrineTrait;

class UserContext extends AbstractFeatureContext
{
    use DoctrineTrait;
    use ContainerTrait;

    public function __construct()
    {
        $this->featureStorage = FeatureSharedStorage::getInstance();
        $this->request = RequestModel::getInstance();
        parent::__construct();
    }

    /**
     * @Given User remember as :storageKey
     */
    public function userRememberAs($storageKey)
    {
        $user = new User();
        $user->setApiToken('test');
        $user->setEmail("$storageKey@test.ru");
        $user->setPassword($storageKey);
        $this->em->persist($user);
        $this->em->flush();
        $this->featureStorage->set($storageKey, $user);
    }

    /**
     * @Given Auth with user :user
     */
    public function authWithUser($userKey)
    {
        $user = $this->featureStorage->get($userKey);
        $this->request->setHeader('X-AUTH-TOKEN', $user->getApiToken());
    }

}