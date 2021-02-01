<?php

namespace App\NasaApi\Dto\Request;

use App\Enum\CrudActionEnum;
use App\NasaApi\Exception\NasaApiException;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Context\ExecutionContextInterface;


/**
 * Class BaseDto
 */
abstract class BaseRequestDto
{
    protected $denormalizer;

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
     * Получаеи на вход Request и возвращает массив параметров из него. Производит дополнительные обработки.
     * @param Request $request
     * @param bool $postBody
     * @return array
     * @throws NasaApiException
     */
    protected function getOptions(Request $request, $postBody = true)
    {
        // Если POST запрос
        if($postBody) {
            // Получить параметры из запроса
            $params = json_decode($request->getContent(),true);
            // Положить их в список на обновление. Т.к. в POST запрсое обычно данные которые нужно обновить
            $this->setUpdateList($params);
        } else {
            // Получить параметры из запроса
            $params = array_merge($request->request->all(), $request->query->all());
        }
        return $params;
    }


    /**
     * Вызывыется из Конвертера параметров src/ParamConverter/RequestToDtoConverter
     * Заполняет DTO из реквеста.
     * @param Request $request
     * @throws NasaApiException
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \ReflectionException
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function resolveByRequest(Request $request)
    {
        if($request->getMethod() == CrudActionEnum::POST or $request->getMethod() == CrudActionEnum::PUT){
            if($request->getContent() == ''){
                throw new NasaApiException('Request body raw is null', 400, 'Need Json Body RAW');
            }
            if(!$this->isJSON($request->getContent())){
                throw new NasaApiException('Request body raw is bad json', 400, 'Check Json Body RAW');
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
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    protected function resolveByParams(array $params)
    {
        // todo: need refactor
       $classMetadataFactory = new ClassMetaDataFactory(
           new AnnotationLoader(new AnnotationReader())
        );
        $metadataAwareNameConverter = new MetadataAwareNameConverter($classMetadataFactory);
        $normalizers = [new GetSetMethodNormalizer($classMetadataFactory, $metadataAwareNameConverter)];

        $serializer = new Serializer($normalizers);
        $requestDto = $serializer->denormalize($params, get_class($this));


        $fromObject = $this->getPublicMethods($requestDto,'get');
        $toObject = $this->getPublicMethods($this,'set');

        foreach ($fromObject as $nameFrom => $from){
            foreach ($toObject as $nameTo => $to){
                if($nameFrom == $nameTo){
                    $this->{'set'.$nameTo}($requestDto->{'get'.$nameTo}());
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

    /**
     * @param array $updateList
     */
    public function setUpdateList(array $updateList): void
    {
        $this->updateList = $updateList;
    }
}
