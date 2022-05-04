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
             * tpl:no_index
             */
            public $prop = '';
        };
        $property = new ReflectionProperty(get_class($a), 'prop');
        $pc = new ParserDocblock($property);
        $this->assertEquals($pc->getAlias($property), 'choice');
    }


    /* -------------------------------- test type 2------------------------------- */
    public function testType2(): void
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
    /* -------------------------------- test types ------------------------------- */
    public function testTypes(): void
    {
        $a = new class()
        {
            #[ORM\Column(type: Types::STRING, length: 255)]
            #[Assert\File(
                maxSize: "100M",
                maxSizeMessage: "la taille autorisée de 100M est dépassée.",
                mimeTypes: ["image/jpeg", "image/png"],
                mimeTypesMessage: "Votre fichier n'est pas une image."
            )]
            private $prop;
        };
        $property = new ReflectionProperty(get_class($a), 'prop');
        $pc = new ParserDocblock($property);
        $this->assertEquals($pc->getType($property), 'file');
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

    /* ------------------- Test Action without value no_index ------------------- */
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

    /* --------------------- Action test with value TWIG:... -------------------- */
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
        $this->assertEquals(json_encode($pc->getOptions()), '{"twig":{"test":"toto"}}');
    }
    /* --------------------- Multiple identical actions test -------------------- */
    public function testActionMultipleWithValue(): void
    {
        $a = new class()
        {
            #[ORM\Column(type: Types::STRING, length: 255)]
            /**
             * TWIG:{"test":"toto"}
             * TWIG:{"tutu":"tata"}
             */
            private $prop;
        };
        $property = new ReflectionProperty(get_class($a), 'prop');
        $pc = new ParserDocblock($property);
        //test for no recognized for alias
        $this->assertEquals($pc->getAlias(), '');
        $this->assertEquals(json_encode($pc->getOptions()), '{"twig":{"test":"toto","tutu":"tata"}}');
    }

    /* --------------------- Multiple identical actions test -------------------- */
    public function testActionWithValueButNotCompleted(): void
    {
        $a = new class()
        {
            #[ORM\Column(type: Types::STRING, length: 255)]
            /**
             * TWIG:encode
             */
            private $prop;
        };
        $property = new ReflectionProperty(get_class($a), 'prop');
        $pc = new ParserDocblock($property);
        //test for no recognized for alias
        $this->assertEquals($pc->getAlias(), '');
        $this->assertEquals(json_encode($pc->getOptions()), '{"twig":{"encode":""}}');
    }
    //Multiple identical actions test
    public function testActionMultipleWithValueButNotCompleted(): void
    {
        $a = new class()
        {
            #[ORM\Column(type: Types::STRING, length: 255)]
            /**
             * TPL:no_index
             * TPL:no_form
             */
            private $prop;
        };
        $property = new ReflectionProperty(get_class($a), 'prop');
        $pc = new ParserDocblock($property);
        //test for no recognized for alias
        $this->assertEquals($pc->getAlias(), '');
        $this->assertEquals(json_encode($pc->getOptions()), '{"tpl":{"no_index":"","no_form":""}}');
    }
    //Multiple identical actions test
    public function testActionMultipleDifferentWithValueButNotCompleted(): void
    {
        $a = new class()
        {
            #[ORM\Column(type: Types::STRING, length: 255)]
            /**
             * TPL:no_index
             * OPT:{"required":"0","label":"url pour SEO"}
             */
            private $prop;
        };
        $property = new ReflectionProperty(get_class($a), 'prop');
        $pc = new ParserDocblock($property);
        //test for no recognized for alias
        $this->assertEquals($pc->getAlias(), '');
        $this->assertEquals(json_encode($pc->getOptions()), '{"tpl":{"no_index":""},"opt":{"required":"0","label":"url pour SEO"}}');
    }
}
