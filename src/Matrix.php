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

namespace ElGigi\Matrix;

use ElGigi\Matrix\Vector\LazyVector;
use ElGigi\Matrix\Vector\Vector;
use ElGigi\Matrix\Vector\VectorInterface;
use Generator;
use InvalidArgumentException;
use LogicException;
use OutOfBoundsException;
use Traversable;

class Matrix implements MatrixInterface
{
    protected array $rows = [];
    protected int $count;

    public function __construct(array $values)
    {
        $this->count = count($values);

        if ($this->count === 0) {
            throw new InvalidArgumentException('Matrix cannot be empty');
        }

        $this->rows = array_map(function ($row) {
            if ($row instanceof VectorInterface) {
                return $row;
            }

            return new Vector($row);
        }, $values);

        if (count(array_unique(array_map(fn(Vector $row) => count($row), $this->rows))) > 1) {
            throw new InvalidArgumentException('All rows must have the same number of elements');
        }
    }

    /**
     * @inheritDoc
     */
    public static function create(iterable $values): static
    {
        if ($values instanceof Traversable) {
            return new Matrix(iterator_to_array($values, false));
        }

        return new Matrix((array)$values);
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return $this->values();
    }

    /**
     * @inheritDoc
     */
    public function isSquare(): bool
    {
        return $this->countRows() === $this->countColumns();
    }

    /**
     * @inheritDoc
     */
    public function values(): array
    {
        return array_map(fn(VectorInterface $vector) => $vector->values(), $this->rows);
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): Traversable
    {
        yield from $this->rows();
    }

    /**
     * @inheritDoc
     */
    public function rows(): Generator
    {
        yield from $this->rows;
    }

    /**
     * @inheritDoc
     */
    public function columns(): Generator
    {
        $countColumns = $this->countColumns();

        for ($col = 0; $col < $countColumns; $col++) {
            yield $this->getColumn($col);
        }
    }

    /**
     * Get row.
     *
     * @param int $offset
     *
     * @return VectorInterface
     */
    public function getRow(int $offset): VectorInterface
    {
        return $this->rows[$offset] ?? throw new OutOfBoundsException();
    }

    /**
     * Get column.
     *
     * @param int $offset
     *
     * @return VectorInterface
     */
    public function getColumn(int $offset): VectorInterface
    {
        return new LazyVector(
            function () use ($offset): Generator {
                foreach ($this->rows as $row) {
                    yield $row[$offset];
                }
            }
        );
    }

    /**
     * Get value.
     *
     * @param int $y
     * @param int $x
     *
     * @return mixed
     */
    public function getValue(int $y, int $x): mixed
    {
        return $this->getRow($x)[$y];
    }

    /**
     * As vector.
     *
     * @return VectorInterface
     */
    public function asVector(): VectorInterface
    {
        return new LazyVector(function () {
            foreach ($this->rows() as $row) {
                yield from $row;
            }
        });
    }

    /**
     * As vector.
     *
     * @return VectorInterface
     */
    public function asColumnVector(): VectorInterface
    {
        return new LazyVector(function () {
            foreach ($this->columns() as $column) {
                yield from $column;
            }
        });
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
     * @inheritDoc
     */
    public function count(): int
    {
        return count($this->rows) * count(reset($this->rows));
    }

    /**
     * @inheritDoc
     */
    public function countRows(): int
    {
        return count($this->rows);
    }

    /**
     * @inheritDoc
     */
    public function countColumns(): int
    {
        return count(reset($this->rows) ?: []);
    }

    /**
     * @inheritDoc
     */
    public function map(callable $callback, mixed ...$arg): static
    {
        return static::create(array_map(fn(VectorInterface $vector) => $vector->map($callback, ...$arg), $this->rows));
    }

    public function mapRow(int $offset, callable $callback, mixed ...$arg): static
    {
        $offset >= $this->countRows() && throw new OutOfBoundsException();
        $rows = $this->rows;
        $rows[$offset] = $rows[$offset]?->map($callback, ...$arg);

        return static::create($rows);
    }

    public function mapColumn(int $offset, callable $callback, mixed ...$arg): static
    {
        $offset >= $this->countColumns() && throw new OutOfBoundsException();
        $rows = [];

        foreach ($this->rows as $row) {
            $rows[] = $row->map(
                function ($value, int $offset, int $desiredOffset, callable $callback, mixed ...$arg) {
                    if ($offset != $desiredOffset) {
                        return $value;
                    }

                    return $callback($value, $offset, ...$arg);
                },
                $offset,
                $callback,
                ...$arg
            );
        }

        return static::create($rows);
    }

    ///////////////////////////////
    /// MATHEMATICAL OPERATIONS ///
    ///////////////////////////////

    /**
     * @inheritDoc
     */
    public function max(): int|float
    {
        return $this->asVector()->max();
    }

    /**
     * @inheritDoc
     */
    public function min(): int|float
    {
        return $this->asVector()->min();
    }

    /**
     * @inheritDoc
     */
    public function sum(): int|float
    {
        return $this->asVector()->sum();
    }

    /**
     * @inheritDoc
     */
    public function avg(): int|float
    {
        return $this->asVector()->avg();
    }

    /**
     * @inheritDoc
     */
    public function median(): int|float
    {
        return $this->asVector()->median();
    }

    /**
     * @inheritDoc
     */
    public function variance(): int|float
    {
        return $this->asVector()->variance();
    }

    /**
     * @inheritDoc
     */
    public function deviation(): int|float
    {
        return $this->asVector()->deviation();
    }

    ////////////////////
    /// ARRAY ACCESS ///
    ////////////////////

    /**
     * @inheritDoc
     */
    public function offsetExists(mixed $offset): bool
    {
        $this->assertIntegerOffset($offset);

        return array_key_exists($offset, $this->rows);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet(mixed $offset)
    {
        $this->assertIntegerOffset($offset);

        return $this->getRow($offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetSet(mixed $offset, mixed $value)
    {
        throw new LogicException('Can\'t set row of matrix');
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset(mixed $offset)
    {
        throw new LogicException('Can\'t unset row of matrix');
    }
}