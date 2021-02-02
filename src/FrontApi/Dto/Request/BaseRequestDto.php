<?php

namespace App\FrontApi\Dto\Request;

use App\FrontApi\Exception\FrontApiException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Context\ExecutionContextInterface;


/**
 * Class BaseDto
 */
abstract class BaseRequestDto
{

    public function __construct()
    {
    }

    /**
     * @param ExecutionContextInterface $context
     * @return bool
     */
    public function validate(ExecutionContextInterface $context)
    {
        return true;
    }

    /**
     * @param $string
     * @return bool
     */
    protected function isJSON($string)
    {
        return ((is_string($string) && (is_object(json_decode($string)) || is_array(json_decode($string))))) ? true : false;
    }

    /**
     * @param Request $request
     * @param bool $postBody
     * @return array
     */
    protected function getOptions(Request $request, $postBody = true)
    {
        if($postBody) {
            $params = json_decode($request->getContent(),true);
        } else {
            $params = array_merge($request->request->all(), $request->query->all());
        }
        return $params;
    }


    /**
     * @param Request $request
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \ReflectionException
     * @throws FrontApiException
     */
    public function resolveByRequest(Request $request)
    {
        if($request->getMethod() == 'POST' or $request->getMethod() == 'PUT'){
            if($request->getContent() == ''){
                throw new FrontApiException('Request body raw is null');
            }
            if(!$this->isJSON($request->getContent())){
                throw new FrontApiException('Request body raw is bad json');
            }
            $params = $this->getOptions($request, true);
        } else {
            $params = $this->getOptions($request, false);
        }
        if($params){
            $this->resolveByParams($params);
        }
    }


    /**
     * Непосредственно заполянет ДТО
     * @param array $params
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \ReflectionException
     */
    protected function resolveByParams(array $params)
    {
        $toObject = $this->getPublicMethods($this,'set');
        foreach ($params as $key => $value){
            foreach ($toObject as $nameTo => $to){
                if(ucfirst($key) == $nameTo){
                    $this->{'set'.$nameTo}($value);
                }
            }
        }
    }

    /**
     * Получить все публичные геттеры/сеттеры
     * @param string $type
     * @return array
     * @throws \ReflectionException
     */
    public function getPublicMethods($class, $type = 'get')
    {
        $refSource = new \ReflectionClass(get_class($class));
        $allMethodsSource = $refSource->getMethods();

        $publicMethods = array();
        for ($methodNum=0; $methodNum < count($allMethodsSource); $methodNum++) {
            $sub = substr( $allMethodsSource[$methodNum]->getName(),0,3);
            if($sub == $type){
                $sub_ = substr( $allMethodsSource[$methodNum]->getName(),3);
                if($allMethodsSource[$methodNum]->isPublic() ) {
                    $publicMethods[$sub_] = $allMethodsSource[$methodNum];
                }
            }
        }
        return $publicMethods;
    }

}
