<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use sagittaracc\ItemList;
use sagittaracc\Map;
use sagittaracc\PlaceholderHelper;
use sagittaracc\SimpleList;

final class PlaceholderTest extends TestCase
{
    public function testPlaceholder(): void
    {
        $this->assertEquals((new PlaceholderHelper("String: ?"))->bind('Yuriy'), "String: 'Yuriy'");
        $this->assertEquals((new PlaceholderHelper("Boolean: ?"))->bind(false), 'Boolean: false');
        $this->assertEquals((new PlaceholderHelper("Integer: ?"))->bind(1), 'Integer: 1');
        $this->assertEquals((new PlaceholderHelper("Array: ?"))->bind([1, 2, 3]), 'Array: [1,2,3]');
        $this->assertEquals((new PlaceholderHelper("Array: ?, ?"))->setParenthesis('(', ')')->bind([1, 2, 3], [1, 'string']), "Array: (1,2,3), (1,'string')");
        $this->assertEquals((new PlaceholderHelper("Null: ?"))->bind(null), 'Null: NULL');

        $this->assertEquals((new PlaceholderHelper("String: %string%; Boolean: -boolean-; Integer: :integer; Array: <array>"))->bind([
            '%string%' => 'Yuriy',
            '-boolean-' => false,
            ':integer' => 1,
            '<array>' => [1, 2, 3],
        ]), "String: 'Yuriy'; Boolean: false; Integer: 1; Array: [1,2,3]");

        $this->assertEquals((new PlaceholderHelper("{{u}}.id, {{ug}}.id, {{g}}.id"))->bindObject(Map::create([
            'u' => 'users',
            'ug' => 'user_group',
            'g' => 'groups',
        ])), 'users.id, user_group.id, groups.id');
        $this->assertEquals((new PlaceholderHelper("#list({{field-1}} some text {{field-2}})"))->bindObject(ItemList::create([
            'name' => 'list',
            'separator' => ",",
            'list' => [
                ['field-1' => 'foo 0', 'field-2' => 'bar 0'],
                ['field-1' => 'foo 1', 'field-2' => 'bar 1'],
                ['field-1' => 'foo 2', 'field-2' => 'bar 2'],
            ],
        ])), '{{foo 0}} some text {{bar 0}},{{foo 1}} some text {{bar 1}},{{foo 2}} some text {{bar 2}}');
        $this->assertEquals((new PlaceholderHelper("#list{#key some text #value}"))->bindObject(SimpleList::create([
            'name' => 'list',
            'separator' => ",\n",
            'list' => [
                'foo 1' => 'bar 1',
                'foo 2' => 'bar 2',
                'foo 3' => 'bar 3',
            ]
        ])), "foo 1 some text bar 1,\nfoo 2 some text bar 2,\nfoo 3 some text bar 3");
        $this->assertEquals((new PlaceholderHelper)->stringOfChar(3, '?')->bind(1, 'string', 3), "1,'string',3");
    }
}
