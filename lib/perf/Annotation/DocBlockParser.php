<?php

namespace perf\Annotation;

/**
 *
 *
 */
class DocBlockParser
{

    /**
     *
     *
     * @var AnnotationParser
     */
    private $annotationParser;

    /**
     *
     * Temporary property.
     *
     * @var string
     */
    private $docBlock;

    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->annotationParser = new AnnotationParser();
    }

    /**
     *
     *
     * @param AnnotationParser $parser
     * @return void
     */
    public function setAnnotationParser(AnnotationParser $parser)
    {
        $this->annotationParser = $parser;
    }

    /**
     *
     *
     * @param string $docBlock
     * @return Annotation[]
     * @throws \InvalidArgumentException
     */
    public function parse($docBlock)
    {
        $this->init($docBlock);

        $lines = preg_split('/[\r\n|\r|\n]/', $docBlock, null, \PREG_SPLIT_NO_EMPTY);

        $annotatedLines = array();

        foreach ($lines as $line) {
            $line = ltrim($line, "\t *");

            if (0 !== strpos($line, '@')) {
                continue;
            }

            $annotatedLines[] = $line;
        }

        if (count($annotatedLines) < 1) {
            return array();
        }

        $annotations = array();

        foreach ($annotatedLines as $annotatedLine) {
            $annotations[] = $this->annotationParser->parse($annotatedLine);
        }

        return $annotations;
    }

    /**
     *
     *
     * @param string $docBlock
     * @return void
     * @throws \InvalidArgumentException
     */
    private function init($docBlock)
    {
        if (!is_string($docBlock)) {
            throw new \InvalidArgumentException('Doc block type is not valid (expected string).');
        }

        $this->docBlock = $docBlock;
    }
}
