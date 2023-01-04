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

use ElGigi\Matrix\Vector\Vector;
use PHPUnit\Framework\TestCase;

class VectorTest extends TestCase
{
    public function testCreate()
    {
        $vector = new class($values = [1, 2, 3, 3, 3, 4, 5, 6, 7]) extends Vector {
            public function getRealCount(): int
            {
                return count($this->values);
            }

            public function getFallback(): mixed
            {
                return $this->fallback;
            }
        };

        $this->assertEquals(6, $vector->getRealCount());
        $this->assertEquals(3, $vector->getFallback());
        $this->assertInstanceOf(Vector::class, $vector);
        $this->assertSame($values, iterator_to_array($vector->getIterator()));
    }
}
