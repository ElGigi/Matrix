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

class SquareMatrix extends Matrix
{
    public function __construct(array $values)
    {
        parent::__construct($values);

        $this->isSquare() || throw new \InvalidArgumentException('Not a square matrix');
    }
}