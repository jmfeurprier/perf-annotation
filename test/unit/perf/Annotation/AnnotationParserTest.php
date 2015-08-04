<?php

namespace perf\Annotation;

/**
 *
 */
class AnnotationParserTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     */
    protected function setUp()
    {
        $this->parametersParser = $this->getMock('\\perf\\Annotation\\ParametersParser');

        $this->annotationParser = new AnnotationParser();
        $this->annotationParser->setParametersParser($this->parametersParser);
    }

    /**
     *
     */
    public function testParseWithNonStringWillThrowException()
    {
        $annotationString = null;

        $this->setExpectedException("\\InvalidArgumentException");

        $this->annotationParser->parse($annotationString);
    }

    /**
     *
     */
    public function testParseWithoutAnnotationWillThrowException()
    {
        $annotationString = 'foo';

        $this->setExpectedException("\\InvalidArgumentException");

        $this->annotationParser->parse($annotationString);
    }

    /**
     *
     */
    public function dataProviderValidAnnotations()
    {
        return array(
            array('@foo\\bar',                                 'foo\\bar', null,                   null),
            array('@param \\Foo\\Bar $baz Comment blah blah.', 'param',    null,                   '\\Foo\\Bar $baz Comment blah blah.'),
            array('@foo\\bar(abc="def")',                      'foo\\bar', 'abc="def"',            null),
            array('@foo\\bar(abc="def",ghi="jkl")',            'foo\\bar', 'abc="def",ghi="jkl"',  null),
            array('@foo\\bar(abc="de(f",ghi="jkl")',           'foo\\bar', 'abc="de(f",ghi="jkl"', null),
//            array('@foo(a="b", c="d") bar baz',                'foo',      'a="b", c="d"',         'bar baz'),
        );
    }

    /**
     *
     * @dataProvider dataProviderValidAnnotations
     */
    public function testParseWithKeyOnlyWillReturnExpected($annotationString, $key, $parametersString, $suffix)
    {
        if (null === $parametersString) {
            $this->parametersParser->expects($this->never())->method('parse');
            $parameters = array();
        } else {
            $parameters = array(
                'ghi' => 'jkl',
            );
            $this->parametersParser->expects($this->once())->method('parse')->with($parametersString)->will($this->returnValue($parameters));
        }

        $result = $this->annotationParser->parse($annotationString);

        $this->assertSame($key, $result->getKey());
        $this->assertSame($parameters, $result->getParameters());

        if (null === $suffix) {
            $this->assertFalse($result->hasSuffix());
        } else {
            $this->assertTrue($result->hasSuffix());
            $this->assertSame($suffix, $result->getSuffix());
        }
    }
}
