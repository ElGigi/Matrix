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

use ElGigi\Matrix\Vector\Vector;
use ElGigi\Matrix\Vector\VectorInterface;

class MatrixBuilder
{
    private array $vectors = [];

    public function __construct()
    {
        $this->reset();
    }

    /**
     * Reset builder.
     *
     * @return void
     */
    public function reset(): void
    {
        $this->vectors = [];
    }

    /**
     * Add row.
     *
     * @param array|VectorInterface $values
     *
     * @return void
     */
    public function addRow(array|VectorInterface $values): void
    {
        if ($values instanceof VectorInterface) {
            $this->vectors[] = $values;
            return;
        }

        $this->vectors[] = new Vector($values);
    }

    /**
     * Get matrix.
     *
     * @return MatrixInterface
     */
    public function getMatrix(): MatrixInterface
    {
        return new Matrix($this->vectors);
    }
}