<?php
$GLOBALS['currentCountId'] = 0;


$barn = new Barn(['корова' => [10, 'молоко', 8, 12, 'л.'], 'курица' => [20, 'яйца', 0, 1, 'шт.']]);
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
    private array $typesAnimals;

    private function animalCounter($animal, $count)
    {
        $this->amountAllAnimals += $count;
        !empty($this->countAnimals) ? $this->countAnimals .= $animal . ' = ' . $count . ' шт.' . '<br/>' : $this->countAnimals = $animal . ' = ' . $count . ' шт.' . '<br/>';
    }

    public function registrationAnimals($animals)
    {
        foreach ($animals as $animal => $characteristics) {
            $this->animals['typeProduct'] = $characteristics[1];
            $this->animals['productUnit'] = $characteristics[4];
            for ($i = 0; $i < $characteristics[0]; $i++) {
                $this->animals[$animal][] = new Animal($characteristics[2], $characteristics[3]);
            }
            $this->typesAnimals[] = $animal;
            $this->productionCounting($animal, $characteristics[1], $characteristics[4]);
            $this->animalCounter($animal, $characteristics[0]);
        }

    }

    private function productionCounting($typeAnimal, $typeProduct, $productionUnit)
    {
        $count = 0;
        foreach ($this->animals[$typeAnimal] as $animal) {
            $count += $animal->getProduction();
        }
        $this->amountAllProducts += $count;
        $this->amountAnimalProduction[$typeAnimal]['typeProduct'] = $typeProduct;
        $this->amountAnimalProduction[$typeAnimal]['amount'] = $count;
        $this->amountAnimalProduction[$typeAnimal]['productUnit'] = $productionUnit;
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

class Barn extends Farm
{
    /**
     *
     *
     * @param array $animals ['type animal' => ['amount animal (int)' , 'type production (string)', 'min amount production (int)', 'max amount production (int)', 'product unit(string)'], ...]
     */
    public function __construct(array $animals)
    {
        $this->registrationAnimals($animals);
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
