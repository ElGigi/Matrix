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

use Closure;
use Generator;
use InvalidArgumentException;
use LogicException;
use Traversable;

class LazyVector extends AbstractVector
{
    private Generator $values;

    public function __construct(Generator|Closure $values)
    {
        if ($values instanceof Closure) {
            $values = $values();
        }

        if (!$values instanceof Generator) {
            throw new InvalidArgumentException('Values must be a valid Generator');
        }

        $this->values = $values;
    }

    /**
     * @inheritDoc
     */
    public static function create(iterable $values): static
    {
        return new LazyVector(fn() => yield from $values);
    }

    /**
     * @inheritDoc
     */
    public function count(): int
    {
        $arr = $this->values();
        $this->values = (fn() => yield from $arr)();

        return count($arr);
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): Traversable
    {
        yield from $this->values;
    }

    /**
     * @inheritDoc
     */
    public function offsetExists(mixed $offset): bool
    {
        throw new LogicException('Lazy vector can\'t be accessed as array');
    }

    /**
     * @inheritDoc
     */
    public function offsetGet(mixed $offset)
    {
        throw new LogicException('Lazy vector can\'t be accessed as array');
    }

    /**
     * @inheritDoc
     */
    public function map(callable $callback, mixed ...$arg): static
    {
        return new self(function () use ($callback, $arg): Generator {
            foreach ($this as $key => $value) {
                yield $callback($value, $key, ...$arg);
            }
        });
    }
}