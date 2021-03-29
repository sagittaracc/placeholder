<?php declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use sagittaracc\PlaceholderHelper;

final class PlaceholderTest extends TestCase
{
  public function testStringPlaceholder(): void
  {
    $this->assertEquals((new PlaceholderHelper("My name is ?"))->bind('Yuriy'), "My name is 'Yuriy'");
  }
}
