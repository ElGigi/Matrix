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
use ElGigi\Matrix\Vector\Vector;
use ElGigi\Matrix\Vector\VectorInterface;
use PHPUnit\Framework\TestCase;

class AbstractVectorTest extends TestCase
{
    public function providesClass(): array
    {
        return [
            [Vector::class],
            [LazyVector::class],
        ];
    }

    /**
     * @var class-string<VectorInterface> $class
     * @dataProvider providesClass
     */
    public function testJsonSerialize(string $class)
    {
        /** @var VectorInterface $vector */
        $vector = $class::create($values = [1, 2, 3, 3, 3, 4, 5, 6, 7]);

        $this->assertSame(
            json_encode($values, JSON_OBJECT_AS_ARRAY),
            json_encode($vector),
        );
    }

    /**
     * @var class-string<VectorInterface> $class
     * @dataProvider providesClass
     */
    public function testReduce(string $class)
    {
        /** @var VectorInterface $vector */
        $vector = $class::create([1, 2, 3, 3, 3, 4, 5, 6, 7]);
        $vector2 = $vector->reduce(2);

        $this->assertSame(
            [1, 2, 3, 3, 3, 4, 5],
            $vector2->values(),
        );
    }

    /**
     * @var class-string<VectorInterface> $class
     * @dataProvider providesClass
     */
    public function testExtend(string $class)
    {
        /** @var VectorInterface $vector */
        $vector = $class::create([1, 2, 3, 3, 3, 4, 5, 6, 7]);
        $vector2 = $vector->extend(2, 8);

        $this->assertSame(
            [1, 2, 3, 3, 3, 4, 5, 6, 7, 8, 8],
            $vector2->values(),
        );
    }

    /**
     * @var class-string<VectorInterface> $class
     * @dataProvider providesClass
     */
    public function testMin(string $class)
    {
        /** @var VectorInterface $vector */
        $vector = $class::create([1, 2, 3, 3, 3, 4, 5, 6, 7]);

        $this->assertEquals(1, $vector->min());
    }

    /**
     * @var class-string<VectorInterface> $class
     * @dataProvider providesClass
     */
    public function testMax(string $class)
    {
        /** @var VectorInterface $vector */
        $vector = $class::create([1, 2, 3, 3, 3, 4, 5, 6, 7]);

        $this->assertEquals(7, $vector->max());
    }

    /**
     * @var class-string<VectorInterface> $class
     * @dataProvider providesClass
     */
    public function testSum(string $class)
    {
        /** @var VectorInterface $vector */
        $vector = $class::create([1, 2, 3, 3, 3, 4, 5, 6, 7]);

        $this->assertEquals(34, $vector->sum());
    }

    /**
     * @var class-string<VectorInterface> $class
     * @dataProvider providesClass
     */
    public function testAvg(string $class)
    {
        /** @var VectorInterface $vector */
        $vector = $class::create([1, 2, 3, 3, 3, 4, 5, 6, 7]);

        $this->assertEquals(3.78, round($vector->avg(), 2));
    }

    /**
     * @var class-string<VectorInterface> $class
     * @dataProvider providesClass
     */
    public function testMedian(string $class)
    {
        /** @var VectorInterface $vector */
        $vector = $class::create([1, 2, 3, 3, 3, 4, 5, 6, 7]);

        $this->assertEquals(3, $vector->median());
    }

    /**
     * @var class-string<VectorInterface> $class
     * @dataProvider providesClass
     */
    public function testVariance(string $class)
    {
        /** @var VectorInterface $vector */
        $vector = $class::create([1, 2, 3, 3, 3, 4, 5, 6, 7]);

        $this->assertEquals(3.28, round($vector->variance(), 2));
    }

    /**
     * @var class-string<VectorInterface> $class
     * @dataProvider providesClass
     */
    public function testDeviation(string $class)
    {
        $vector = $class::create([1, 2, 3, 3, 3, 4, 5, 6, 7]);

        $this->assertEquals(1.81, round($vector->deviation(), 2));
    }
}
