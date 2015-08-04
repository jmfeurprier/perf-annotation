<?php

namespace perf\Annotation;

/**
 *
 */
class AnnotationTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     */
    public function testWithNonStringKeyWillThrowException()
    {
        $key        = null;
        $parameters = array();
        $suffix     = 'foo';


        $this->setExpectedException("\\InvalidArgumentException", "Provided key is not a string.");

        new Annotation($key, $parameters, $suffix);
    }

    /**
     *
     */
    public function testWithNonStringParameterNameWillThrowException()
    {
        $key        = 'foo\\bar';
        $parameters = array(
            123 => 'baz',
        );
        $suffix = 'qux';

        $this->setExpectedException("\\InvalidArgumentException", "Parameters list contains non-string keys.");

        new Annotation($key, $parameters, $suffix);
    }

    /**
     *
     */
    public function testWithNonStringParameterValueWillThrowException()
    {
        $key        = 'foo\\bar';
        $parameters = array(
            'baz' => 123,
        );
        $suffix = 'qux';

        $this->setExpectedException("\\InvalidArgumentException", "Parameters list contains non-string values.");

        new Annotation($key, $parameters, $suffix);
    }

    /**
     *
     */
    public function testWithNonStringAndNonNullSuffixWillThrowException()
    {
        $key = 'foo\\bar';
        $parameters = array(
            'baz' => 'qux',
        );
        $suffix = 123;

        $this->setExpectedException("\\InvalidArgumentException", "Provided suffix is neither null nor a string.");

        new Annotation($key, $parameters, $suffix);
    }

    /**
     *
     */
    public function testGetKeyWillReturnExpected()
    {
        $key = 'foo\\bar';
        $parameters = array(
            'baz' => 'qux',
        );
        $suffix = 'abc';

        $annotation = new Annotation($key, $parameters, $suffix);

        $result = $annotation->getKey();

        $this->assertSame($key, $result, $suffix);
    }

    /**
     *
     */
    public function testGetParametersWithoutParametersWillReturnEmptyArray()
    {
        $key        = 'foo';
        $parameters = array();
        $suffix     = 'bar';

        $annotation = new Annotation($key, $parameters, $suffix);

        $result = $annotation->getParameters();

        $this->assertInternalType('array', $result);
        $this->assertCount(0, $result);
    }

    /**
     *
     */
    public function testGetParametersWillReturnExpected()
    {
        $key        = 'foo';
        $parameters = array(
            'bar' => 'baz',
        );
        $suffix = 'bar';

        $annotation = new Annotation($key, $parameters, $suffix);

        $result = $annotation->getParameters();

        $this->assertSame($parameters, $result);
    }

    /**
     *
     */
    public function testHasParameterWithNonExistentParameterNameWillReturnFalse()
    {
        $key        = 'foo';
        $parameters = array(
            'bar' => 'baz',
        );
        $suffix        = 'qux';
        $parameterName = 'abc';

        $annotation = new Annotation($key, $parameters, $suffix);

        $result = $annotation->hasParameter($parameterName);

        $this->assertFalse($result);
    }

    /**
     *
     */
    public function testHasParameterWithExistingParameterNameWillReturnTrue()
    {
        $key           = 'foo';
        $parameterName = 'bar';
        $parameters    = array(
            $parameterName => 'baz',
        );
        $suffix = 'qux';

        $annotation = new Annotation($key, $parameters, $suffix);

        $result = $annotation->hasParameter($parameterName);

        $this->assertTrue($result);
    }

    /**
     *
     */
    public function testGetParameterWithNonExistentParameterNameWillThrowException()
    {
        $key        = 'foo';
        $parameters = array(
            'bar' => 'baz',
        );
        $parameterName = 'qux';
        $suffix        = 'abc';

        $annotation = new Annotation($key, $parameters, $suffix);

        $this->setExpectedException('\\DomainException');

        $annotation->getParameter($parameterName);
    }

    /**
     *
     */
    public function testGetParameterWithExistingParameterNameWillReturnParameterValue()
    {
        $key            = 'foo';
        $parameterName  = 'bar';
        $parameterValue = 'baz';
        $parameters     = array(
            $parameterName => $parameterValue,
        );
        $suffix = 'abc';

        $annotation = new Annotation($key, $parameters, $suffix);

        $result = $annotation->getParameter($parameterName);

        $this->assertSame($parameterValue, $result);
    }

    /**
     *
     */
    public function testHasSuffixWithoutSuffixWillReturnFalse()
    {
        $key        = 'foo';
        $parameters = array();
        $suffix     = null;

        $annotation = new Annotation($key, $parameters, $suffix);

        $result = $annotation->hasSuffix();

        $this->assertFalse($result);
    }

    /**
     *
     */
    public function testHasSuffixWithSuffixWillReturnTrue()
    {
        $key        = 'foo';
        $parameters = array();
        $suffix     = 'bar';

        $annotation = new Annotation($key, $parameters, $suffix);

        $result = $annotation->hasSuffix();

        $this->assertTrue($result);
    }

    /**
     *
     */
    public function testGetSuffixWithoutSuffixWillThrowException()
    {
        $key        = 'foo';
        $parameters = array();
        $suffix     = null;

        $annotation = new Annotation($key, $parameters, $suffix);

        $this->setExpectedException('\\RuntimeException');

        $annotation->getSuffix();
    }

    /**
     *
     */
    public function testGetSuffixWithSuffixWillReturnExpected()
    {
        $key        = 'foo';
        $parameters = array();
        $suffix     = 'bar';

        $annotation = new Annotation($key, $parameters, $suffix);

        $result = $annotation->getSuffix();

        $this->assertSame($suffix, $result);
    }
}
