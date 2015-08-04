<?php

namespace perf\Annotation;

/**
 *
 */
class ParametersParserTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     */
    protected function setUp()
    {
        $this->parametersParser = new ParametersParser();
    }

    /**
     *
     */
    public function testWithEmptyParameterStringWillThrowException()
    {
        $parametersString = '';

        $this->setExpectedException("\\RuntimeException");

        $this->parametersParser->parse($parametersString);
    }

    /**
     *
     */
    public function testWithInvalidParameterNameWillThrowException()
    {
        $parametersString = '123="foo"';

        $this->setExpectedException("\\RuntimeException");

        $this->parametersParser->parse($parametersString);
    }

    /**
     *
     */
    public function testWithoutEqualsWillThrowException()
    {
        $parametersString = 'foo"123"';

        $this->setExpectedException("\\RuntimeException");

        $this->parametersParser->parse($parametersString);
    }

    /**
     *
     */
    public function testWithoutStartingDoubleQuoteWillThrowException()
    {
        $parametersString = 'foo=123"';

        $this->setExpectedException("\\RuntimeException");

        $this->parametersParser->parse($parametersString);
    }

    /**
     *
     */
    public function testWithoutEndingDoubleQuoteWillThrowException()
    {
        $parametersString = 'foo="123';

        $this->setExpectedException("\\RuntimeException");

        $this->parametersParser->parse($parametersString);
    }

    /**
     *
     */
    public function testWithoutDoubleQuotesWillThrowException()
    {
        $parametersString = 'foo=123';

        $this->setExpectedException("\\RuntimeException");

        $this->parametersParser->parse($parametersString);
    }

    /**
     *
     */
    public function testWithSingleParameterWillReturnExpected()
    {
        $parametersString = 'foo="123"';

        $result = $this->parametersParser->parse($parametersString);

        $expected = array(
            'foo' => '123',
        );

        $this->assertSame($expected, $result);
    }

    /**
     *
     */
    public function dataProviderManyValidParameters()
    {
        $expectedParameters = array(
            'foo' => '123',
            'bar' => 'baz',
        );

        return array(
            array('foo="123",bar="baz"', $expectedParameters),
            array('foo="123" ,bar="baz"', $expectedParameters),
            array('foo="123", bar="baz"', $expectedParameters),
            array('foo="123" , bar="baz"', $expectedParameters),
            array('foo="123",    bar="baz"', $expectedParameters),
        );
    }

    /**
     *
     * @dataProvider dataProviderManyValidParameters
     */
    public function testWithManyParameterWillReturnExpected($parametersString, $expectedParameters)
    {
        $result = $this->parametersParser->parse($parametersString);

        $expectedParameters = array(
            'foo' => '123',
            'bar' => 'baz',
        );

        $this->assertSame($expectedParameters, $result);
    }
}
