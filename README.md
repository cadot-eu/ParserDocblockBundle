# ParserDocblockBundle

## Introduction

A parser for Doctrine comment Docblock in Symfony

## Installation

` composer require cadot.eu/parser-docblock-bundle `

Without flex add in \Config\Bundles.php

` Cadoteu\ParserDocblockBundle\ParserDocblockBundle::class => ['all' => true] `

## Utilisation

``` php
use Cadoteu\ParserDocblockBundle\ParserDocblock;

 $parse = new ParserDocblock('      * ORDRE =id=>DESC ');
 $cleaned=$parse->getCleaned(); // return ORDRE =id=>DESC
 $type=$parse->getType(); // return ORDRE
 $var=$parse->getVar(); // return id
 $val=$parse->getVal(); // return DESC
```
