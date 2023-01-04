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

use LogicException;
use OutOfBoundsException;

abstract class AbstractVector implements VectorInterface
{
    /**
     * Get values.
     *
     * @return array
     */
    public function values(): array
    {
        return iterator_to_array($this, false);
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return $this->values();
    }

    /**
     * Assert offset.
     *
     * @param mixed $offset
     *
     * @return void
     */
    protected function assertIntegerOffset(mixed $offset): void
    {
        if (false === is_int($offset)) {
            throw new LogicException('Offset of vector must be an integer');
        }
    }

    /**
     * Assert out of bounds.
     *
     * @param int $offset
     *
     * @return void
     */
    protected function assertOutOfBounds(int $offset): void
    {
        if ($offset < 0 || $offset >= count($this)) {
            throw new OutOfBoundsException();
        }
    }

    /**
     * @inheritDoc
     */
    public function extend(int $length, mixed $value = null): static
    {
        return static::create(
            array_merge(
                $values = $this->values(),
                array_fill(count($values), $length, $value),
            ),
        );
    }

    /**
     * @inheritDoc
     */
    public function reduce(int $length): static
    {
        $newLength = count($this) - $length;

        return static::create(
            array_filter(
                $this->values(),
                fn($key) => $key < $newLength,
                ARRAY_FILTER_USE_KEY
            )
        );
    }

    /**
     * @inheritDoc
     */
    public function offsetSet(mixed $offset, mixed $value)
    {
        throw new LogicException('Can\'t set element of vector');
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset(mixed $offset)
    {
        throw new LogicException('Can\'t unset element of vector');
    }

    /**
     * @inheritDoc
     */
    public function max(): int|float
    {
        return max($this->values());
    }

    /**
     * @inheritDoc
     */
    public function min(): int|float
    {
        return min($this->values());
    }

    /**
     * @inheritDoc
     */
    public function sum(): int|float
    {
        return array_sum($this->values());
    }

    /**
     * @inheritDoc
     */
    public function avg(): int|float
    {
        $count = count($this);

        return $this->sum() / $count;
    }

    /**
     * @inheritDoc
     */
    public function median(): int|float
    {
        $values = $this->values();
        sort($values);
        $count = count($values);
        $middleIndex = $count / 2;

        if (is_float($middleIndex)) {
            return $values[(int)$middleIndex];
        }

        return ($values[$middleIndex] + $values[$middleIndex - 1]) / 2;
    }

    /**
     * @inheritDoc
     */
    public function variance(): int|float
    {
        $values = $this->values();
        sort($values);
        $count = count($values);
        $avg = array_sum($values) / $count;
        $variance = .0;

        foreach ($values as $value) {
            $variance += pow(($value - $avg), 2);
        }

        return $variance / $count;
    }

    /**
     * @inheritDoc
     */
    public function deviation(): int|float
    {
        $count = count($this);

        if (0 === $count) {
            return .0;
        }

        return pow($this->variance(), .5);
    }
}