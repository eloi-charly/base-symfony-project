<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Ingredient;
use App\Entity\Quality;
use App\Entity\Recipe;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use FakerRestaurant\Provider\fr_FR\Restaurant;
use Symfony\Component\String\Slugger\SluggerInterface;

class RecipeFixtures extends Fixture implements DependentFixtureInterface
{

    public function __construct(private readonly SluggerInterface  $slugger) {}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create("fr_FR");
        $faker->addProvider(new Restaurant($faker));

        $ingredients = array_map(fn(string $name) => ((new Ingredient())
            ->setName($name)
            ->setSlug(strtolower($this->slugger->slug($name)))), [
            "farine",
            "beurre",
            "huile d'olive",
            "sel",
            "poivre",
            "ail",
            "oignon",
            "tomates",
            "poulet",
            "boeuf",
            "poisson",
            "crevettes",
            "pâtes",
            "riz",
            "fromage",
            "lait",
            "oeufs",
            "sucre",
            "vinaigre",
            "herbes fraîches",
            "épices",
            "champignons",
            "pommes de terre",
            "carottes",
            "poivrons"
        ]);

        foreach ($ingredients as $value) {
            $manager->persist($value);
        }

        $categories = ["Entrée", "Plat Chaud", "Dessert", "Boisson", "Gouter"];

        foreach ($categories as $value) {
            $category = (new Category())
                ->setName($value)
                ->setSlug($this->slugger->slug($value))
                ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
                ->setUpdatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()));
            $manager->persist($category);
            $this->addReference($value, $category);
        }

        for ($i = 0; $i < 10; $i++) {

            // dd($user);
            $title = $faker->foodName();
            $recipe = (new Recipe())->setTitle($title)
                ->setSlug(strtolower($this->slugger->slug($title)))
                ->setUpdatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
                ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
                ->setUser($this->getReference("USER" . $i, User::class))
                ->setContent($faker->paragraph(10, true))
                ->setDuration($faker->numberBetween(10, 60))
                ->setCategory($this->getReference($faker->randomElement($categories), Category::class));

            foreach ($faker->randomElements($ingredients, $faker->randomElement(1, 5)) as $ingredient) {
                $recipe->addQuality((new Quality))
                    ->setQuantity($faker->randomFloat(2, 1, 10))
                    ->setIngrdient($ingredient);
            }

            $manager->persist($recipe);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
}
