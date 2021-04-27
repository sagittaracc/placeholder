<?php declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use sagittaracc\PlaceholderHelper;

final class PlaceholderTest extends TestCase
{
  public function testStringPlaceholder(): void
  {
    $this->assertEquals((new PlaceholderHelper("String: ?"))->bind('Yuriy'), "String: 'Yuriy'");
    $this->assertEquals((new PlaceholderHelper("Boolean: ?"))->bind(false), 'Boolean: false');
    $this->assertEquals((new PlaceholderHelper("Integer: ?"))->bind(1), 'Integer: 1');
    $this->assertEquals((new PlaceholderHelper("Array: ?"))->bind([1,2,3]), 'Array: [1,2,3]');
    $this->assertEquals((new PlaceholderHelper("Null: ?"))->bind(null), 'Null: NULL');
  }
}
