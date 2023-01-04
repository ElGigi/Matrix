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

use ArrayAccess;
use Countable;
use IteratorAggregate;
use JsonSerializable;

interface VectorInterface extends Countable, IteratorAggregate, ArrayAccess, JsonSerializable
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
     * Extend vector.
     *
     * @param int $length
     * @param mixed|null $value
     *
     * @return static
     */
    public function extend(int $length, mixed $value = null): static;

    /**
     * Reduce vector.
     *
     * @param int $length
     *
     * @return static
     */
    public function reduce(int $length): static;

    /**
     * Get a new vector with a callback applied on each element.
     *
     * @param callable $callback
     * @param mixed ...$arg Additional arguments
     *
     * @return static
     */
    public function map(callable $callback, mixed ...$arg): static;

    /**
     * Get max value of vector.
     *
     * @return int|float
     */
    public function max(): int|float;

    /**
     * Get min value of vector.
     *
     * @return int|float
     */
    public function min(): int|float;

    /**
     * Get sum of vector values.
     *
     * @return int|float
     */
    public function sum(): int|float;

    /**
     * Get average of vector values.
     *
     * @return int|float
     */
    public function avg(): int|float;

    /**
     * Get median of vector values.
     *
     * @return int|float
     */
    public function median(): int|float;

    /**
     * Get variance of vector values.
     *
     * @return int|float
     */
    public function variance(): int|float;

    /**
     * Get deviation of vector values.
     *
     * @return int|float
     */
    public function deviation(): int|float;
}