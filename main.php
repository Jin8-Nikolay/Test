<?php
$GLOBALS['currentCountId'] = 0;


$barn = new Farm(['корова' => [10, Cow::class], 'курица' => [20, Chicken::class]]);
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
     * @param array $animals ['type animal' => ['amount animal (int)', 'class'], ...]
     */
    public function __construct(array $animals)
    {
        foreach ($animals as $animal => $characteristics) {
            if (count($characteristics) !== 2) {
                throw new ErrorException('Неправильное переданные значения');
            }
            for ($i = 0; $i < $characteristics[0]; $i++) {
                $this->animals[$animal][] = new $characteristics[1]();
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
    private string $typeProduct = 'молоко';
    private string $productUnit = 'л.';

    public function __construct()
    {
        parent::__construct(8, 12);
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
    private string $typeProduct = 'яйца';
    private string $productUnit = 'шт.';

    public function __construct()
    {
        parent::__construct(0, 1);
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