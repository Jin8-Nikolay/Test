<?php
$GLOBALS['currentCountId'] = 0;


$barn = new Farm(['корова' => [10, 'молоко', 8, 12, 'л.', Cow::class], 'курица' => [20, 'яйца', 0, 1, 'шт.', Chicken::class]]);
echo 'Всего животных = ' . $barn->getAllAnimals() . ' шт.' . '<br/>';
echo $barn->getListAnimals();
echo 'Вся продукция = ' . $barn->getAllProduction() . '<br/>';
echo $barn->getListProductsAnimal();


class Farm
{
    private string $countAnimals;
    private int $amountAllProducts = 0;
    private int $amountAllAnimals = 0;
    private array $amountAnimalProduction;
    private array $animals;

    /**
     *
     *
     * @param array $animals ['type animal' => ['amount animal (int)' , 'type production (string)', 'min amount production (int)', 'max amount production (int)', 'product unit(string)', 'class'], ...]
     */
    public function __construct(array $animals)
    {
        foreach ($animals as $animal => $characteristics) {
            for ($i = 0; $i < $characteristics[0]; $i++) {
                $this->animals[$animal][] = new $characteristics[5]($characteristics[1], $characteristics[4], $characteristics[2], $characteristics[3]);
            }
        }
        foreach ($this->animals as $key => $animals) {
            foreach ($animals as $animal) {
                $this->productionCounting($animal, $animal->getTypeProduct(), $animal->getProductUnit());
            }
            $this->animalCounter($key, count($animals));
        }
    }

    private function animalCounter($animal, $count)
    {
        $this->amountAllAnimals += $count;
        !empty($this->countAnimals) ? $this->countAnimals .= $animal . ' = ' . $count . ' шт.' . '<br/>' : $this->countAnimals = $animal . ' = ' . $count . ' шт.' . '<br/>';
    }


    private function productionCounting($animal, $typeProduct, $productionUnit)
    {
        $count = 0;
        $count += $animal->getProduction();
        $this->amountAllProducts += $count;
        $this->amountAnimalProduction[$animal->getTypeAnimal()]['typeProduct'] = $typeProduct;
        !empty($this->amountAnimalProduction[$animal->getTypeAnimal()]['amount']) ? $this->amountAnimalProduction[$animal->getTypeAnimal()]['amount'] += $count : $this->amountAnimalProduction[$animal->getTypeAnimal()]['amount'] = $count;
        $this->amountAnimalProduction[$animal->getTypeAnimal()]['productUnit'] = $productionUnit;
    }

    public function getAllAnimals(): int
    {
        return $this->amountAllAnimals;
    }

    public function getListAnimals(): string
    {
        return $this->countAnimals;
    }

    public function getAllProduction(): int
    {
        return $this->amountAllProducts;
    }

    public function getListProductsAnimal(): string
    {
        $str = '';
        foreach ($this->amountAnimalProduction as $value) {
            $str .= 'Продукция ' . $value['typeProduct'] . ' = ' . $value['amount'] . ' ' . $value['productUnit'] . '<br/>';
        }
        return $str;
    }
}


class Animal
{
    private int $minManufacturedProducts;
    private int $maxManufacturedProducts;
    private int $id;

    public function __construct($minManufacturedProducts, $maxManufacturedProducts)
    {
        $this->id = $GLOBALS['currentCountId']++;
        $this->minManufacturedProducts = $minManufacturedProducts;
        $this->maxManufacturedProducts = $maxManufacturedProducts;
    }

    public function getProduction(): int
    {
        try {
            return random_int($this->minManufacturedProducts, $this->maxManufacturedProducts);
        } catch (Exception $e) {
            throw new ErrorException('Неправильное значение min, max');
        }
    }
}

class Cow extends Animal
{
    private string $typeAnimal = 'корова';
    private string $typeProduct;
    private string $productUnit;

    public function __construct($typeProduct, $productUnit, $minManufacturedProducts, $maxManufacturedProducts)
    {
        parent::__construct($minManufacturedProducts, $maxManufacturedProducts);
        $this->typeProduct = $typeProduct;
        $this->productUnit = $productUnit;
    }

    public function getTypeProduct(): string
    {
        return $this->typeProduct;
    }

    public function getProductUnit(): string
    {
        return $this->productUnit;
    }

    public function getTypeAnimal(): string
    {
        return $this->typeAnimal;
    }
}

class Chicken extends Animal
{
    private string $typeAnimal = 'курица';
    private string $typeProduct;
    private string $productUnit;

    public function __construct($typeProduct, $productUnit, $minManufacturedProducts, $maxManufacturedProducts)
    {
        parent::__construct($minManufacturedProducts, $maxManufacturedProducts);
        $this->typeProduct = $typeProduct;
        $this->productUnit = $productUnit;
    }

    public function getTypeProduct(): string
    {
        return $this->typeProduct;
    }

    public function getProductUnit(): string
    {
        return $this->productUnit;
    }

    public function getTypeAnimal(): string
    {
        return $this->typeAnimal;
    }
}