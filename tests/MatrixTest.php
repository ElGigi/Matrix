<?php
/*
 * @license   https://opensource.org/licenses/MIT MIT License
 * @copyright 2023 Ronan GIRON
 * @author    Ronan GIRON <https://github.com/ElGigi>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code, to the root.
 */

namespace ElGigi\Matrix\Tests;

use ElGigi\Matrix\Matrix;
use ElGigi\Matrix\Vector\VectorInterface;
use Generator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class MatrixTest extends TestCase
{
    public function test__construct_withoutSameNumberOfElements()
    {
        $this->expectException(InvalidArgumentException::class);

        new Matrix([
            [1, 2, 3],
            [1, 3],
        ]);
    }

    public function testJsonSerialize()
    {
        $matrix = new Matrix(
            $expected = [
                [1, 2, 3, 3, 3, 4, 5],
                [2, 2, 2, 3, 4, 4, 5]
            ]
        );

        $this->assertSame(json_encode($expected), json_encode($matrix));
    }

    public function testRows()
    {
        $matrix = new Matrix(
            [
                $row1 = [1, 2, 3, 3, 3, 4, 5],
                $row2 = [2, 2, 2, 3, 4, 4, 5],
            ]
        );

        $this->assertInstanceOf(Generator::class, $matrix->rows());
        $this->assertContainsOnlyInstancesOf(VectorInterface::class, $matrix->rows());
        $this->assertEquals(
            [
                $row1,
                $row2,
            ],
            array_map(fn(VectorInterface $vector) => $vector->values(), iterator_to_array($matrix->rows())),
        );
    }

    public function testColumns()
    {
        $matrix = new Matrix(
            [
                [1, 2, 3, 3, 3, 4, 5],
                [2, 2, 2, 3, 4, 4, 5],
            ]
        );

        $this->assertInstanceOf(Generator::class, $matrix->columns());
        $this->assertContainsOnlyInstancesOf(VectorInterface::class, $matrix->columns());
        $this->assertEquals(
            [
                [1, 2],
                [2, 2],
                [3, 2],
                [3, 3],
                [3, 4],
                [4, 4],
                [5, 5],
            ],
            array_map(fn(VectorInterface $vector) => $vector->values(), iterator_to_array($matrix->columns())),
        );
    }

    public function testMap()
    {
        $matrix = new Matrix(
            $original = [
                [1, 2, 3],
                [3, 1, 2],
                [2, 3, 1],
            ]
        );

        $this->assertEquals(
            [
                [2, 3, 4],
                [4, 2, 3],
                [3, 4, 2],
            ],
            $matrix->map(fn($value) => $value + 1)->values(),
        );
        $this->assertEquals($original, $matrix->values());
    }

    public function testMapRow()
    {
        $matrix = new Matrix(
            $original = [
                [1, 2, 3],
                [3, 1, 2],
                [2, 3, 1],
            ]
        );

        $this->assertEquals(
            [
                [1, 2, 3],
                [4, 2, 3],
                [2, 3, 1],
            ],
            $matrix->mapRow(1, fn($value) => $value + 1)->values(),
        );
        $this->assertEquals($original, $matrix->values());
    }

    public function testMapColumn()
    {
        $matrix = new Matrix(
            $original = [
                [1, 2, 3],
                [3, 1, 2],
                [2, 3, 1],
            ]
        );

        $this->assertEquals(
            [
                [1, 3, 3],
                [3, 2, 2],
                [2, 4, 1],
            ],
            $matrix->mapColumn(1, fn($value) => $value + 1)->values(),
        );
        $this->assertEquals($original, $matrix->values());
    }

    public function testMin()
    {
        $matrix = new Matrix(
            [
                [1, 2, 3, 3, 3, 4, 5],
                [2, 2, 2, 3, 4, 4, 5],
                [2, 0, 8, 3, 2, 4, 1],
            ]
        );

        $this->assertSame(0, $matrix->min());
    }

    public function testMax()
    {
        $matrix = new Matrix(
            [
                [1, 2, 3, 3, 3, 4, 5],
                [2, 2, 2, 3, 4, 4, 5],
                [2, 0, 8, 3, 2, 4, 1],
            ]
        );

        $this->assertSame(8, $matrix->max());
    }

    public function testAvg()
    {
        $matrix = new Matrix(
            [
                [1, 2, 3, 3, 3, 4, 5],
                [2, 2, 2, 3, 4, 4, 5],
                [2, 0, 8, 3, 2, 4, 1],
            ]
        );

        $this->assertSame(3, $matrix->avg());
    }

    public function testMedian()
    {
        $matrix = new Matrix(
            [
                [1, 2, 3, 3, 3, 4, 5],
                [2, 2, 2, 3, 4, 4, 5],
                [2, 0, 8, 3, 2, 4, 1],
            ]
        );

        $this->assertSame(3, $matrix->median());
    }

    public function testVariance()
    {
        $matrix = new Matrix(
            [
                [1, 2, 3, 3, 3, 4, 5],
                [2, 2, 2, 3, 4, 4, 5],
                [2, 0, 8, 3, 2, 4, 1],
            ]
        );

        $this->assertSame(2.86, round($matrix->variance(), 2));
    }

    public function testDeviation()
    {
        $matrix = new Matrix(
            [
                [1, 2, 3, 3, 3, 4, 5],
                [2, 2, 2, 3, 4, 4, 5],
                [2, 0, 8, 3, 2, 4, 1],
            ]
        );

        $this->assertSame(1.69, round($matrix->deviation(), 2));
    }
}
