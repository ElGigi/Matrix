# Matrix

Library implements the notions of matrices and vectors in PHP.
The motivation of this library is first to optimize the large big size of the matrices.

## Installation

### Composer

You can install **ElGigi Matrix** with [Composer](https://getcomposer.org/), it's the recommended installation.

```bash
composer require elgigi/matrix
```

### Dependencies

- **PHP** ^8.0

## Usage

Instanciation of `Matrix` object:

```php
use ElGigi\Matrix\Matrix;

$matrix = new Matrix([
    [1, 2, 3],
    [2, 4, 1],
    [3, 2, 4],
]);
```

Uses builder to make `Matrix` object:

```php
use ElGigi\Matrix\MatrixBuilder;

$builder = new MatrixBuilder();
$builder->addRow([1, 2, 3]);
$builder->addRow([2, 4, 1]);
$builder->addRow([3, 2, 4]);

$matrix = $builder->getMatrix();
```

## API

Matrix and vectors implement default interfaces:

- `\Countable`: object can be used with `count()` function
- `\JsonSearialize`: object can be used with `json_encode()` function
- `\IteratorAggregate`: object can be used with `foreach` statement
- `\ArrayAccess`: object can be used like an array to get values

### Matrix

- `Matrix::isSquare(): bool`: returns if matrix is a square
- `Matrix::count(): int`: get number of elements
- `Matrix::countRows(): int`: get number of rows
- `Matrix::countColumns(): int`: get number of columns
- `Matrix::rows(): Generator<VectorInterface>`: get rows as generator
- `Matrix::columns(): Generator<VectorInterface>`: get columns as generator
- `Matrix::getRow(int $offset): VectorInterface`: get specified indexed row as vector
- `Matrix::getColumn(int $offset): VectorInterface`: get specified indexed column as vector
- `Matrix::getValue(int $y, int $x): mixed`: get specified value at coordinates
- `Matrix::map(callable $callback, mixed ...$arg): static`: apply callback on each value and returns a new matrix object
- `Matrix::max(): int|float`: get max value of matrix
- `Matrix::min(): int|float`: get min value of matrix
- `Matrix::sum(): int|float`: get sum of matrix values
- `Matrix::avg(): int|float`: get average of matrix values
- `Matrix::median(): int|float`: get median of matrix values
- `Matrix::variance(): int|float`: get variance of matrix values
- `Matrix::deviation(): int|float`: get deviation of matrix values

### Vector

- `Vector::values(): array`: get vector values as array
- `Vector::extend(int $length, mixed $value = null): static`: extend vector of specified length with given value
- `Vector::reduce(int $length): static`: reduce vector of specified length
- `Vector::map(callable $callback, mixed ...$arg): static`: apply callback on each value and returns a new vector object
- `Vector::max(): int|float`: get max value of vector
- `Vector::min(): int|float`: get min value of vector
- `Vector::sum(): int|float`: get sum of vector values
- `Vector::avg(): int|float`: get average of vector values
- `Vector::median(): int|float`: get median of vector values
- `Vector::variance(): int|float`: get variance of vector values
- `Vector::deviation(): int|float`: get deviation of vector values
