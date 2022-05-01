<?php

namespace Cadoteu\ParserDocblockBundle\Tests;

use Cadoteu\ParserDocblockBundle\ParserDocblock;
use Doctrine\DBAL\Types\Types;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

/** It's a helper class to parse a comment line of doctrine
 * format dockblock
 */
class ParserDocblockTest extends TestCase
{
    /* ------------------------------- test alias ------------------------------- */
    public function testAlias(): void
    {
        $a = new class()
        {
            /**
             * choice
             */
            public $prop = '';
        };
        $property = new ReflectionProperty(get_class($a), 'prop');
        $pc = new ParserDocblock($property);
        $this->assertEquals($pc->getAlias($property), 'choice');
    }

    /* -------------------------------- test type ------------------------------- */
    public function testType(): void
    {
        $a = new class()
        {
            #[ORM\Column(type: Types::STRING, length: 255)]
            private $prop;
        };
        $property = new ReflectionProperty(get_class($a), 'prop');
        $pc = new ParserDocblock($property);
        $this->assertEquals($pc->getType($property), 'string');
    }

    /* ------------------------------ test de clean ----------------------------- */
    public function testClean(): void
    {
        $a = new class()
        {
            #[ORM\Column(type: Types::STRING, length: 255)]
            private $prop;
        };
        $property = new ReflectionProperty(get_class($a), 'prop');
        $pc = new ParserDocblock($property);
        $this->assertEquals($pc->Clean('   *   * *  *  ***** clean **  \n'), 'clean **  \n');
    }

    //Test Action without value no_index
    public function testAction(): void
    {
        $a = new class()
        {
            #[ORM\Column(type: Types::STRING, length: 255)]
            /**
             * no_index
             */
            private $prop;
        };
        $property = new ReflectionProperty(get_class($a), 'prop');
        $pc = new ParserDocblock($property);
        //test for no recognized for alias
        $this->assertEquals($pc->getAlias(), '');
        $this->assertEquals($pc->getOptions(), 'no_index');
    }

    //Action test with value TWIG:...
    public function testActionWithValue(): void
    {
        $a = new class()
        {
            #[ORM\Column(type: Types::STRING, length: 255)]
            /**
             * TWIG:{"test":"toto"}
             */
            private $prop;
        };
        $property = new ReflectionProperty(get_class($a), 'prop');
        $pc = new ParserDocblock($property);
        //test for no recognized for alias
        $this->assertEquals($pc->getAlias(), '');
        $this->assertEquals(json_encode($pc->getOptions()), '{"TWIG":"{\"test\":\"toto\"}"}');
    }
    //Multiple identical actions test
    public function testActionMultipleWithValue(): void
    {
        $a = new class()
        {
            #[ORM\Column(type: Types::STRING, length: 255)]
            /**
             * TWIG:{"test":"toto"}
             *  TWIG:{"tutu":"tata"}
             */
            private $prop;
        };
        $property = new ReflectionProperty(get_class($a), 'prop');
        $pc = new ParserDocblock($property);
        //test for no recognized for alias
        $this->assertEquals($pc->getAlias(), '');
        $this->assertEquals(json_encode($pc->getOptions()), '{"TWIG":"{\"test\":\"toto\",\"tutu\":\"tata\"}"}');
    }
}
