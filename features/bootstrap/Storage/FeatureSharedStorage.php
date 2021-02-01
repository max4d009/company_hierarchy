<?php

namespace BehatTest\Storage;

use RuntimeException;
use Symfony\Component\PropertyAccess\PropertyAccess;


final class FeatureSharedStorage
{
    const FIND_ALL_PLACEHOLDERS_PATTERN = '/{{(.*?)}}/';
    const IS_A_PLACEHOLDER_PATTERN = '/^{{(.*)}}$/';

    /** @var array */
    private $storage = [];

    /** @var self */
    private static $instance = null;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    /**
     * @return self
     */
    public function clear(): self
    {
        $this->storage = [];

        return $this;
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        return $this->storage;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key)
    {
        return $this->storage[$key] ?? null;
    }

    /**
     * @param string $variablePath
     *
     * @return mixed
     */
    public function getByPath(string $variablePath)
    {
        $objectNodeList = explode('.', $variablePath, 2);
        $accessor = PropertyAccess::createPropertyAccessor();

        $object = $this->get($objectNodeList[0]);
        if (count($objectNodeList) === 1) {
            return $this->get($variablePath);
        }

        if (!$accessor->isReadable($object, $objectNodeList[1])) {
            throw new RuntimeException('Not found entity property for placeholder path ' . $objectNodeList[1]);
        }

        return $accessor->getValue($object, $objectNodeList[1]);
    }

    /**
     * @param string $variablePath
     *
     * @return string
     */
    public function getStringByPath(string $variablePath): string
    {
        $variable = $this->getByPath($variablePath);
        if (is_object($variable) || is_array($variable)) {
            throw new RuntimeException(sprintf('Storage variable for path "%s" not a string', $variablePath));
        }

        return (string) $variable;
    }

    /**
     * @param string $key
     *
     * @param mixed $value
     *
     * @return self
     */
    public function set(string $key, $value): self
    {
        $this->storage[$key] = $value;

        return $this;
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public function replacePlaceholderString(string $string): string
    {
        foreach ($this->findStringPlaceholders($string) as $placeholder => $variablePath) {
            $string = str_replace($placeholder, $this->getStringByPath($variablePath), $string);
        }

        return $string;
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public function replacePlaceholder(string $string)
    {
        if (is_string($string) && preg_match(self::IS_A_PLACEHOLDER_PATTERN, $string)) {
            return $this->getByPath(str_replace(['{{', '}}'], '', $string));
        }

        return $string;
    }

    /**
     * @param array $parameters
     *
     * @return array
     */
    public function replacePlaceholdersInArrayRecursive(array $parameters): array
    {
        foreach ($parameters as $key => $value) {
            if (is_array($value)) {
                $parameters[$key] = $this->replacePlaceholdersInArrayRecursive($value);
            } elseif (is_string($value) && preg_match(self::IS_A_PLACEHOLDER_PATTERN, $value)) {
                $parameters[$key] = $this->getByPath(str_replace(['{{', '}}'], '', $value));
            } elseif (!(is_object($value) || is_bool($value))) {
                $parameters[$key] = $this->replacePlaceholderString((string)$value);
            }
        }

        return $parameters;
    }

    /**
     * @param string $string
     *
     * @return array
     */
    public function findStringPlaceholders(string $string): array
    {
        $placeholderList = [];

        preg_match_all(self::FIND_ALL_PLACEHOLDERS_PATTERN, $string, $matches);

        foreach ($matches[0] as $key => $placeholder) {
            $placeholderList[$placeholder] = $matches[1][$key];
        }

        return $placeholderList;
    }

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
}
