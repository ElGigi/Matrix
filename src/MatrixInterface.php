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

use ArrayAccess;
use Countable;
use ElGigi\Matrix\Vector\VectorInterface;
use Generator;
use IteratorAggregate;
use JsonSerializable;

interface MatrixInterface extends Countable, IteratorAggregate, ArrayAccess, JsonSerializable
{
    /**
     * Create vector.
     *
     * @param iterable $values
     *
     * @return static
     */
    public static function create(iterable $values): static;

    /**
     * Get values.
     *
     * @return array
     */
    public function values(): array;

    /**
     * Is square matrix?
     *
     * @return bool
     */
    public function isSquare(): bool;

    /**
     * Count rows.
     *
     * @return int
     */
    public function countRows(): int;

    /**
     * Count columns.
     *
     * @return int
     */
    public function countColumns(): int;

    /**
     * Get rows.
     *
     * @return Generator<VectorInterface>
     */
    public function rows(): Generator;

    /**
     * Get columns.
     *
     * @return Generator<VectorInterface>
     */
    public function columns(): Generator;

    /**
     * Get row.
     *
     * @param int $offset
     *
     * @return VectorInterface
     */
    public function getRow(int $offset): VectorInterface;

    /**
     * Get column.
     *
     * @param int $offset
     *
     * @return VectorInterface
     */
    public function getColumn(int $offset): VectorInterface;

    /**
     * Get value at offset X and Y.
     *
     * @param int $y
     * @param int $x
     *
     * @return mixed
     */
    public function getValue(int $y, int $x): mixed;

    /**
     * Get a new matrix with a callback applied on each element.
     *
     * @param callable $callback
     * @param mixed ...$arg Additional arguments
     *
     * @return static
     */
    public function map(callable $callback, mixed ...$arg): static;

    ///////////////////////////////
    /// MATHEMATICAL OPERATIONS ///
    ///////////////////////////////

    /**
     * Get max value of matrix.
     *
     * @return int|float
     */
    public function max(): int|float;

    /**
     * Get min value of matrix.
     *
     * @return int|float
     */
    public function min(): int|float;

    /**
     * Get sum of matrix values.
     *
     * @return int|float
     */
    public function sum(): int|float;

    /**
     * Get average of matrix values.
     *
     * @return int|float
     */
    public function avg(): int|float;

    /**
     * Get median of matrix values.
     *
     * @return int|float
     */
    public function median(): int|float;

    /**
     * Get variance of matrix values.
     *
     * @return int|float
     */
    public function variance(): int|float;

    /**
     * Get deviation of matrix values.
     *
     * @return int|float
     */
    public function deviation(): int|float;
}