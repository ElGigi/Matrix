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

use ElGigi\Matrix\SquareMatrix;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class SquareMatrixTest extends TestCase
{
    public function test__construct()
    {
        $this->expectNotToPerformAssertions();

        new SquareMatrix([
            [1, 2, 3],
            [1, 2, 3],
            [1, 2, 3],
        ]);
    }

    public function test__construct_failed()
    {
        $this->expectException(InvalidArgumentException::class);

        new SquareMatrix([
            [1, 2, 3],
            [1, 2, 3],
        ]);
    }
}
