<?php
/*
 * @license   https://opensource.org/licenses/MIT MIT License
 * @copyright 2023 Ronan GIRON
 * @author    Ronan GIRON <https://github.com/ElGigi>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code, to the root.
 */

namespace ElGigi\Matrix\Tests\Vector;

use ElGigi\Matrix\Vector\LazyVector;
use PHPUnit\Framework\TestCase;

class LazyVectorTest extends TestCase
{
    public function testCreate()
    {
        $values = [1, 2, 3, 3, 3, 4, 5, 6, 7];
        $vector = new LazyVector(fn() => yield from $values);

        $this->assertInstanceOf(LazyVector::class, $vector);
        $this->assertSame($values, iterator_to_array($vector->getIterator()));
    }
}
