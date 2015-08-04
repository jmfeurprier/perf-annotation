<?php

namespace perf\Annotation;

/**
 *
 *
 */
class ParametersParser
{

    /**
     *
     * Temporary property.
     *
     * @var string
     */
    private $parametersString;

    /**
     *
     * Temporary property.
     *
     * @var string
     */
    private $parameterName;

    /**
     *
     *
     * @param string $parametersString
     * @return {string:string}
     * @throws \RuntimeException
     */
    public function parse($parametersString)
    {
        $this->parametersString = $parametersString;
        $parsedParameters       = array();

        while (true) {
            $this->name();
            $this->equals();
            $this->doubleQuote();
            $this->value();
            $this->doubleQuote();

            $parsedParameters[$this->parameterName] = $this->parameterValue;

            if ($this->nothingMoreToParse()) {
                break;
            }

            $this->comma();
        }

        return $parsedParameters;
    }

    /**
     *
     *
     * @return void
     */
    private function name()
    {
        $this->parameterName = $this->expectRegex('|^([a-zA-Z][a-zA-Z\d]*)\=|');
    }

    /**
     *
     *
     * @return void
     */
    private function equals()
    {
        $this->expectString('=');
    }

    /**
     *
     *
     * @return void
     */
    private function doubleQuote()
    {
        $this->expectString('"');
    }

    /**
     *
     *
     * @return void
     */
    private function value()
    {
        // @todo Take escaped double-quotes (\") into account.

        $this->parameterValue = $this->expectRegex('|^([^"]+)|');
    }

    /**
     *
     *
     * @return void
     */
    private function comma()
    {
        $this->expectRegex('|^(\s*,\s*)|');
    }

    /**
     *
     *
     * @param string $pattern
     * @return string
     */
    private function expectRegex($pattern)
    {
        $matches = array();

        if (1 !== preg_match($pattern, $this->parametersString, $matches)) {
            $message = "Parameters annotation '{$this->parametersString}' "
                     . "does not match expected pattern '{$pattern}'.";

            throw new \RuntimeException($message);
        }

        $match       = $matches[1];
        $matchLength = strlen($match);

        $this->chopLeft($matchLength);

        return $match;
    }

    /**
     *
     *
     * @param string $expectedString
     * @return void
     */
    private function expectString($expectedString)
    {
        if (0 !== strpos($this->parametersString, $expectedString)) {
            $message = "Parameters annotation '{$this->parametersString}' "
                     . "does not match expected string '{$expectedString}'.";

            throw new \RuntimeException($message);
        }

        $stringLength = strlen($expectedString);

        $this->chopLeft($stringLength);
    }

    /**
     *
     *
     * @param int $length
     * @return void
     */
    private function chopLeft($length)
    {
        $this->parametersString = substr($this->parametersString, $length);
    }

    /**
     *
     *
     * @return bool
     */
    private function nothingMoreToParse()
    {
        return (strlen($this->parametersString) < 1);
    }
}
