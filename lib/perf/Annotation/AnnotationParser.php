<?php

namespace perf\Annotation;

/**
 *
 *
 */
class AnnotationParser
{

    /**
     *
     *
     * @var ParametersParser
     */
    private $parametersParser;

    /**
     *
     * Temporary property.
     *
     * @var string
     */
    private $annotationString;

    /**
     *
     * Temporary property.
     *
     * @var string
     */
    private $key;

    /**
     *
     * Temporary property.
     *
     * @var {string:string}
     */
    private $parameters = array();

    /**
     *
     * Temporary property.
     *
     * @var string
     */
    private $suffix;

    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->parametersParser = new ParametersParser();
    }

    /**
     *
     *
     * @param ParametersParser $parser
     * @return void
     */
    public function setParametersParser(ParametersParser $parser)
    {
        $this->parametersParser = $parser;
    }

    /**
     *
     *
     * @param string $annotationString
     * @return Annotation
     * @throws \InvalidArgumentException
     */
    public function parse($annotationString)
    {
        $this->init($annotationString);

        $this->parseKey();
        $this->parseParameters();
        $this->parseSuffix();

        return $this->conclude();
    }

    /**
     *
     *
     * @param string $annotationString
     * @return void
     * @throws \InvalidArgumentException
     */
    private function init($annotationString)
    {
        if (!is_string($annotationString)) {
            throw new \InvalidArgumentException('Annotation type is not valid (expected string).');
        }

        if (0 !== strpos($annotationString, '@')) {
            throw new \InvalidArgumentException("Annotation string does not start with '@'.");
        }

        $this->annotationString = $annotationString;
        $this->key              = null;
        $this->parameters       = array();
        $this->suffix           = null;
    }

    /**
     *
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    private function parseKey()
    {
        static $pattern = '/^@([^\(\s]+)/';

        $matches = array();

        if (1 !== preg_match($pattern, $this->annotationString, $matches)) {
            throw new \InvalidArgumentException("Invalid annotation.");
        }

        $this->chopLeft(strlen($matches[0]));

        $this->key = $matches[1];
    }

    /**
     *
     *
     * @return void
     */
    private function parseParameters()
    {
        static $pattern = '#\(([^\)]+)\)#';

        $matches = array();

        if (1 === preg_match($pattern, $this->annotationString, $matches)) {
            $parametersString = $matches[1];

            $this->parameters = $this->parametersParser->parse($parametersString);
        }
    }

    /**
     *
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    private function parseSuffix()
    {
        static $pattern = '#\s+(.+)$#';

        $matches = array();

        if (1 === preg_match($pattern, $this->annotationString, $matches)) {
            $this->suffix = $matches[1];
        }
    }

    /**
     *
     *
     * @param int $length
     * @return void
     */
    private function chopLeft($length)
    {
        $this->annotationString = substr($this->annotationString, $length);
    }

    /**
     *
     *
     * @return Annotation
     */
    private function conclude()
    {
        return new Annotation($this->key, $this->parameters, $this->suffix);
    }
}
