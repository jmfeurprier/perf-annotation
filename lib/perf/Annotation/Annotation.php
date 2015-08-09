<?php

namespace perf\Annotation;

/**
 *
 *
 */
class Annotation
{

    /**
     *
     *
     * @var string
     */
    private $key;

    /**
     *
     *
     * @var {string:string}
     */
    private $parameters = array();

    /**
     *
     *
     * @var null|string
     */
    private $suffix;

    /**
     *
     *
     * @param string $key
     * @param {string:string} $parameters
     * @param null|string $suffix
     * @return void
     * @throws \InvalidArgumentException
     */
    public function __construct($key, array $parameters, $suffix)
    {
        if (!is_string($key)) {
            throw new \InvalidArgumentException("Provided key is not a string.");
        }

        foreach ($parameters as $name => $value) {
            if (!is_string($name)) {
                throw new \InvalidArgumentException("Parameters list contains non-string keys.");
            }

            if (!is_string($value)) {
                throw new \InvalidArgumentException("Parameters list contains non-string values.");
            }
        }

        if ((null !== $suffix) && !is_string($suffix)) {
            throw new \InvalidArgumentException("Provided suffix is neither null nor a string.");
        }

        $this->key        = $key;
        $this->parameters = $parameters;
        $this->suffix     = $suffix;
    }

    /**
     *
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     *
     *
     * @return {string:string}
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     *
     *
     * @param string $name
     * @return string
     * @throws \DomainException
     */
    public function getParameter($name)
    {
        if ($this->hasParameter($name)) {
            return $this->parameters[$name];
        }

        throw new \DomainException("Parameter '{$name}' not found.");
    }

    /**
     *
     *
     * @param string $name
     * @return bool
     */
    public function hasParameter($name)
    {
        return array_key_exists($name, $this->parameters);
    }

    /**
     *
     *
     * @return bool
     */
    public function getSuffix()
    {
        if ($this->hasSuffix()) {
            return $this->suffix;
        }

        throw new \RuntimeException('No suffix set.');
    }

    /**
     *
     *
     * @return bool
     */
    public function hasSuffix()
    {
        return (null !== $this->suffix);
    }
}
