<?php

namespace BehatTest\Models;

use BehatTest\Storage\FeatureSharedStorage;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RequestModel
 *
 * @package BehatTest\Models
 */
class RequestModel
{
    /** @var string */
    private $host = '127.0.0.1:8000';

    /** @var string */
    private $scheme = 'http';

    /** @var string */
    private $url;

    /** @var string */
    private $method;

    /** @var array */
    private $parameters = [];

    /** @var array */
    private $cookies = [];

    /** @var array */
    private $files = [];

    /** @var array */
    private $headers = [];

    /** @var string */
    private $content;

    /** @var Request|null */
    private $lastRequest;

    /** @var self */
    private static $instance = null;
    /**
     * @return self
     */
    public static function getInstance(): self
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * @return FeatureSharedStorage
     */
    private function getStorage(): FeatureSharedStorage
    {
        return FeatureSharedStorage::getInstance();
    }

    /**
     * @return string
     */
    public function getHost(): ?string
    {
        return $this->host;
    }

    /**
     * @param string $host
     *
     * @return RequestModel
     */
    public function setHost(string $host): self
    {
        $this->host = $host;

        return $this;
    }

    /**
     * @return string
     */
    public function getScheme(): ?string
    {
        return $this->scheme;
    }

    /**
     * @param string $scheme
     *
     * @return RequestModel
     */
    public function setScheme(string $scheme): self
    {
        $this->scheme = $scheme;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return RequestModel
     */
    public function setUrl(string $url): self
    {
//        $this->url = $this->getStorage()->replacePlaceholderString($url);
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getMethod(): ?string
    {
        return $this->method;
    }

    /**
     * @param string $method
     *
     * @return RequestModel
     */
    public function setMethod(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @return array
     */
    public function getParameters(): ?array
    {
        return $this->parameters;
    }

    /**
     * @param array $parameters
     *
     * @return RequestModel
     */
    public function setParameters(array $parameters): self
    {
        $this->parameters = $this->getStorage()->replacePlaceholdersInArrayRecursive($parameters);
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * @return array
     */
    public function getCookies(): array
    {
        return $this->cookies;
    }

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return RequestModel
     */
    public function setCookie(string $key, $value): self
    {
        $this->cookies[$key] = $value;

        return $this;
    }

//    /**
//     * @param Response $response
//     */
//    public function populateCookiesFromResponse(Response $response): void
//    {
//        /** @var Cookie $cookie */
//        foreach ($response->headers->getCookies() as $cookie) {
//            if ($cookie->getExpiresTime() != 0 && $cookie->getExpiresTime() < time() + 10) {
//                $this->removeCookie($cookie->getName());
//            } else {
//                $this->setCookie($cookie->getName(), $cookie->getValue());
//            }
//        }
//    }

    /**
     * @param string $key
     *
     * @return RequestModel
     */
    public function removeCookie(string $key): self
    {
        unset($this->cookies[$key]);

        return $this;
    }

    /**
     * @return array
     */
    public function getFiles(): array
    {
        return $this->files;
    }

//    /**
//     * @param string $keyPath
//     * @param UploadedFile $file
//     *
//     * @return RequestModel
//     *
//     * @throws TypeError
//     */
//    public function setFile(string $keyPath, UploadedFile $file): self
//    {
//        $accessor = PropertyAccess::createPropertyAccessor();
//
//        $keyPath = preg_replace("/^(\w+)(\[|$)/", '[$1]$2', $keyPath, 1);
//
//        $accessor->setValue($this->files, $keyPath, $file);
//
//        return $this;
//    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param string $key
     * @param mixed$value
     *
     * @return RequestModel
     */
    public function setHeader(string $key, $value): self
    {
        $this->headers[$key] = $value;

        return $this;
    }

    /**
     * @param string $key
     *
     * @return RequestModel
     */
    public function removeHeader(string $key): self
    {
        unset($this->headers[$key]);

        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string $content
     *
     * @return RequestModel
     */
    public function setContent(string $content): self
    {

        $this->content = $this->getStorage()->replacePlaceholderString($content);

        return $this;
    }

    /**
     * @return Request
     */
    public function getLastRequest(): Request
    {
        if (!$this->lastRequest) {
            throw new RuntimeException('Request not created');
        }

        return $this->lastRequest;
    }

    /**
     * @return string
     */
    private function getPreparedUrl(): string
    {
        $path = $this->getUrl();

        if (parse_url($path, PHP_URL_HOST)) {
            return $path;
        }

        return sprintf('%s://%s%s', $this->getScheme(), $this->getHost(), $path);
    }

    /**
     * @return Request
     */
    public function createRequest(): Request
    {
        $this->lastRequest = Request::create(
            $this->getPreparedUrl(),
            $this->getMethod(),
            $this->getParameters(),
            $this->getCookies(),
            $this->getFiles(),
            [],
            $this->getContent()
        );

        $this->lastRequest->headers->add($this->getHeaders());

        return $this->lastRequest;
    }

}
