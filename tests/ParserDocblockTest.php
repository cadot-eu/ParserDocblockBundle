<?php

namespace Cadoteu\ParserDocblockBundle\Tests;

use PHPUnit\Framework\TestCase;
use Cadoteu\ParserDocblockBundle\ParserDocblock;

/** It's a helper class to parse a comment line of doctrine
 * format dockblock 
 */
class ParserDocblockTest extends TestCase
{
    public function testCleaned(): void
    {
        $pc = new ParserDocblock('     * ORDRE=id=>DESC***   **   *** ');
        $this->assertEquals($pc->getCleaned(), 'ORDRE=id=>DESC');
    }
    public function testType(): void
    {
        $pc = new ParserDocblock('     * ORDRE =id=>DESC***   **   *** ');
        $this->assertEquals($pc->getType(), 'ORDRE');
    }

    public function testTypeWithArobase(): void
    {
        $pc = new ParserDocblock('    * @ORM\Column(type="integer")');
        $this->assertEquals($pc->getType(), null);
    }
    public function testVar(): void
    {
        $pc = new ParserDocblock('     * ORDRE= id =>DESC***   **   *** ');
        $this->assertEquals($pc->getVar(), 'id');
    }
    public function testVarShort(): void
    {
        $pc = new ParserDocblock('     * ORDRE= id ');
        $this->assertEquals($pc->getVar(), 'id');
    }
    public function testVarWithArobase(): void
    {
        $pc = new ParserDocblock('    * @ORM\Column(type="string", length=255)');
        $this->assertNull($pc->getVar());
    }
    public function testVal(): void
    {
        $pc = new ParserDocblock('     * ORDRE =id=>DESC***   **   *** ');
        $this->assertEquals($pc->getVal(), 'DESC');
    }
    //ATTR=options=>'{"toolbar":"simple"}'
    // public function testValDecompose(): void
    // {
    //     $pc = new parsercommentHelper("options=>'{"toolbar":"simple"}'");
    //     $this->assertEquals($pc->getVal(), 'DESC');
    // }
}
