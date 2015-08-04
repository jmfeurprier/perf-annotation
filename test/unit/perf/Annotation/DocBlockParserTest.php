<?php

namespace perf\Annotation;

/**
 *
 */
class DocBlockParserTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     */
    protected function setUp()
    {
        $this->annotationParser = $this->getMock('\\perf\\Annotation\\AnnotationParser');

        $this->docBlockParser = new DocBlockParser();
        $this->docBlockParser->setAnnotationParser($this->annotationParser);
    }

    /**
     *
     */
    public function testWithNonStringWillThrowException()
    {
        $docBlock = null;

        $this->annotationParser->expects($this->never())->method('parse');

        $this->setExpectedException('\\InvalidArgumentException');

        $this->docBlockParser->parse($docBlock);
    }

    /**
     *
     */
    public function testWithEmptyStringWillReturnNothing()
    {
        $docBlock = '';

        $this->annotationParser->expects($this->never())->method('parse');

        $result = $this->docBlockParser->parse($docBlock);

        $this->assertInternalType('array', $result);
        $this->assertCount(0, $result);
    }

    /**
     *
     */
    public function testWithEmptyDocBlockWillReturnNothing()
    {
        $docBlock = <<<DOC
/**
 *
 */
DOC;

        $this->annotationParser->expects($this->never())->method('parse');

        $result = $this->docBlockParser->parse($docBlock);

        $this->assertInternalType('array', $result);
        $this->assertCount(0, $result);
    }

    /**
     *
     */
    public function testWithDocBlockWithoutAnnotationWillReturnNothing()
    {
        $docBlock = <<<DOC
/**
 * This is a comment.
 */
DOC;

        $this->annotationParser->expects($this->never())->method('parse');

        $result = $this->docBlockParser->parse($docBlock);

        $this->assertInternalType('array', $result);
        $this->assertCount(0, $result);
    }

    /**
     *
     */
    public function testWithDocBlockWithOneAnnotationWillReturnExpected()
    {
        $docBlock = <<<DOC
/**
 * @foo
 */
DOC;

        $annotation = $this->getMockBuilder('\\perf\\Annotation\\Annotation')->disableOriginalConstructor()->getMock();

        $this->annotationParser->expects($this->once())->method('parse')->with('@foo')->will($this->returnValue($annotation));

        $result = $this->docBlockParser->parse($docBlock);

        $this->assertInternalType('array', $result);
        $this->assertCount(1, $result);
        $this->assertContainsOnly('\\perf\\Annotation\\Annotation', $result);
        $this->assertContains($annotation, $result);
    }

    /**
     *
     */
    public function testWithDocBlockWithManyAnnotationsWillReturnExpected()
    {
        $docBlock = <<<DOC
/**
 * @foo
 * @var \$bar Comment.
 */
DOC;

        $annotationPrimary = $this->getMockBuilder('\\perf\\Annotation\\Annotation')->disableOriginalConstructor()->getMock();

        $annotationSecondary = $this->getMockBuilder('\\perf\\Annotation\\Annotation')->disableOriginalConstructor()->getMock();

        $this->annotationParser->expects($this->at(0))->method('parse')->with('@foo')->will($this->returnValue($annotationPrimary));
        $this->annotationParser->expects($this->at(1))->method('parse')->with('@var $bar Comment.')->will($this->returnValue($annotationSecondary));

        $result = $this->docBlockParser->parse($docBlock);

        $this->assertInternalType('array', $result);
        $this->assertCount(2, $result);
        $this->assertContainsOnly('\\perf\\Annotation\\Annotation', $result);
        $this->assertContains($annotationPrimary, $result);
        $this->assertContains($annotationSecondary, $result);
    }
}
