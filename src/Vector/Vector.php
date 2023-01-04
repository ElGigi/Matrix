<?php
/*
 * @license   https://opensource.org/licenses/MIT MIT License
 * @copyright 2023 Ronan GIRON
 * @author    Ronan GIRON <https://github.com/ElGigi>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code, to the root.
 */

declare(strict_types=1);

namespace ElGigi\Matrix\Vector;

use InvalidArgumentException;
use OutOfBoundsException;
use Traversable;

class Vector extends AbstractVector
{
    protected int $count;
    protected array $values;
    protected mixed $fallback = null;

    public function __construct(array $values)
    {
        $this->count = count($values);

        if ($this->count === 0) {
            throw new InvalidArgumentException('Vector cannot be empty');
        }

        $this->values = $values;
        $this->optimize();
    }

    /**
     * Optimize vector with memory usage.
     *
     * @return void
     */
    protected function optimize(): void
    {
        if ($this->count === 0) {
            throw new InvalidArgumentException('Vector cannot be empty');
        }

        $values = iterator_to_array($this);
        $countValues = array_count_values($values);
        arsort($countValues);

        $this->fallback = key($countValues);
        $this->values = array_filter($values, fn($value) => $value !== $this->fallback);
    }


    /**
     * @inheritDoc
     */
    public static function create(iterable $values): static
    {
        if ($values instanceof Traversable) {
            return new Vector(iterator_to_array($values, false));
        }

        return new Vector((array)$values);
    }

    /**
     * @inheritDoc
     */
    public function count(): int
    {
        return $this->count;
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): Traversable
    {
        for ($offset = 0; $offset < $this->count; $offset++) {
            yield $this[$offset];
        }
    }

    /**
     * @inheritDoc
     */
    public function offsetExists(mixed $offset): bool
    {
        try {
            $this->assertIntegerOffset($offset);
            $this->assertOutOfBounds($offset);
        } catch (OutOfBoundsException) {
            return false;
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function offsetGet(mixed $offset): mixed
    {
        $this->assertIntegerOffset($offset);
        $this->assertOutOfBounds($offset);

        return $this->values[$offset] ?? $this->fallback;
    }

    /**
     * @inheritDoc
     */
    public function map(callable $callback, mixed ...$arg): static
    {
        $values = [];
        foreach ($this as $key => $value) {
            $values[] = $callback($value, $key, ...$arg);
        }

        return static::create($values);
    }
}