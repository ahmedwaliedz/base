<?php

declare(strict_types=1);

namespace Tests\Unit\View\Components;

use App\View\Components\Form\Select;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class SelectTest extends TestCase
{
    public function test_construct_sets_name(): void
    {
        $component = new Select(['name' => 'is_active', 'options' => []]);
        $this->assertSame('is_active', $component->name);
    }

    public function test_boolean_true_normalized_to_1(): void
    {
        $component = new Select([
            'name' => 'is_active',
            'value' => true,
            'options' => [['id' => 1, 'name' => 'Yes'], ['id' => 0, 'name' => 'No']],
        ]);
        $this->assertSame(1, $component->value);
    }

    public function test_boolean_false_normalized_to_0(): void
    {
        $component = new Select([
            'name' => 'is_active',
            'value' => false,
            'options' => [['id' => 1, 'name' => 'Yes'], ['id' => 0, 'name' => 'No']],
        ]);
        $this->assertSame(0, $component->value);
    }

    public function test_integer_0_preserved(): void
    {
        $component = new Select([
            'name' => 'is_active',
            'value' => 0,
            'options' => [['id' => 1, 'name' => 'Yes'], ['id' => 0, 'name' => 'No']],
        ]);
        $this->assertSame(0, $component->value);
    }

    public function test_integer_1_preserved(): void
    {
        $component = new Select([
            'name' => 'is_active',
            'value' => 1,
            'options' => [['id' => 1, 'name' => 'Yes'], ['id' => 0, 'name' => 'No']],
        ]);
        $this->assertSame(1, $component->value);
    }

    public function test_string_value_preserved(): void
    {
        $component = new Select([
            'name' => 'country_id',
            'value' => '42',
            'options' => [],
        ]);
        $this->assertSame('42', $component->value);
    }

    public function test_null_value_preserved(): void
    {
        $component = new Select([
            'name' => 'parent_id',
            'value' => null,
            'options' => [],
        ]);
        $this->assertNull($component->value);
    }

    public function test_enum_value_resolved(): void
    {
        $enum = \App\Enums\AdminType::SUPER_ADMIN;
        $component = new Select([
            'name' => 'type',
            'value' => $enum,
            'options' => [],
        ]);
        $this->assertSame($enum->value, $component->value);
    }
}
