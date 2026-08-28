<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Fruit;
use App\Models\Product;
use App\Models\Series;
use Illuminate\Database\Seeder;

/**
 * The real Vitanea catalog, transcribed from vitanea.com/shop.
 *
 * Two deliberate departures from the source data:
 *
 * 1. The live "тестови продукт" (product_id 96, €10.00, publicly purchasable
 *    on the current site) is not migrated.
 * 2. Syrups that existed as separate products differing only by size — four of
 *    them all named "Арония плодов сироп със захар" — are consolidated into one
 *    product with variants, and dilution ratio becomes a variant attribute
 *    instead of a category.
 */
class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $categories = $this->seedCategories();
        $series = $this->seedSeries();
        $fruits = $this->seedFruits();

        foreach ($this->catalog() as $definition) {
            $product = Product::create([
                'category_id' => $categories[$definition['category']],
                'series_id' => isset($definition['series']) ? $series[$definition['series']] : null,
                'name' => $definition['name'],
                'slug' => $definition['slug'],
                'short_description' => $definition['short'] ?? null,
                'description' => $definition['description'] ?? null,
                'usage' => $definition['usage'] ?? null,
                'storage' => $definition['storage'] ?? null,
                'position' => $definition['position'] ?? 0,
            ]);

            $product->fruits()->sync(array_map(
                fn (string $slug): int => $fruits[$slug],
                $definition['fruits'] ?? [],
            ));

            foreach ($definition['variants'] as $position => $variant) {
                $product->variants()->create([...$variant, 'position' => $position]);
            }

            foreach ($definition['ingredients'] ?? [] as $position => $ingredient) {
                $product->ingredients()->create([...$ingredient, 'position' => $position]);
            }

            if (isset($definition['nutrition'])) {
                $product->nutrition()->create($definition['nutrition']);
            }

            $product->images()->create([
                'path' => '/images/products/'.$definition['image'].'.png',
                'alt' => $definition['name'].' на Vitanea',
                'position' => 0,
            ]);
        }
    }

    /**
     * @return array<string, int>
     */
    private function seedCategories(): array
    {
        $rows = [
            ['name' => 'Сокове', 'slug' => 'sokove', 'description' => 'Студено пресовани сокове от свежи български плодове, без концентрат.'],
            ['name' => 'Нектари', 'slug' => 'nektari', 'description' => 'Плодови нектари със и без добавена захар.'],
            ['name' => 'Сиропи', 'slug' => 'siropi', 'description' => 'Концентрирани плодови сиропи за разреждане.'],
            ['name' => 'Чайове', 'slug' => 'chayove', 'description' => 'Прахообразна арония за запарка, без кофеин.'],
            ['name' => 'Пектини', 'slug' => 'pektini', 'description' => 'Гранулиран ябълков пектин.'],
        ];

        $ids = [];

        foreach ($rows as $position => $row) {
            $ids[$row['slug']] = Category::create([...$row, 'position' => $position])->id;
        }

        return $ids;
    }

    /**
     * @return array<string, int>
     */
    private function seedSeries(): array
    {
        $rows = [
            ['name' => 'Light', 'slug' => 'light', 'tagline' => 'Без добавена захар', 'description' => 'Подсладени само със стевиол-гликозиди от листата на стевия.'],
            ['name' => 'Класик', 'slug' => 'klasik', 'tagline' => 'Със захар', 'description' => 'Класическата рецепта на Vitanea.'],
            ['name' => 'Детска серия', 'slug' => 'detska-seriya', 'tagline' => 'За най-малките', 'description' => 'В стъклена бутилка от 250 мл, подходяща за деца над 6 месеца.'],
        ];

        $ids = [];

        foreach ($rows as $position => $row) {
            $ids[$row['slug']] = Series::create([...$row, 'position' => $position])->id;
        }

        return $ids;
    }

    /**
     * @return array<string, int>
     */
    private function seedFruits(): array
    {
        $rows = [
            ['name' => 'Арония', 'slug' => 'aroniya', 'is_signature' => true, 'description' => 'Плодът, който влагаме в почти всички наши продукти.'],
            ['name' => 'Малина', 'slug' => 'malina'],
            ['name' => 'Ягода', 'slug' => 'yagoda'],
            ['name' => 'Къпина', 'slug' => 'kapina'],
            ['name' => 'Касис', 'slug' => 'kasis'],
            ['name' => 'Вишна', 'slug' => 'vishna'],
            ['name' => 'Лимон', 'slug' => 'limon'],
            ['name' => 'Бъз', 'slug' => 'baz'],
            ['name' => 'Джинджифил', 'slug' => 'dzhindzhifil'],
            ['name' => 'Ябълка', 'slug' => 'yabalka'],
        ];

        $ids = [];

        foreach ($rows as $position => $row) {
            $ids[$row['slug']] = Fruit::create([...$row, 'position' => $position])->id;
        }

        return $ids;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function catalog(): array
    {
        return [...$this->juices(), ...$this->nectars(), ...$this->syrups(), ...$this->teas(), ...$this->pectins()];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function juices(): array
    {
        return [[
            'category' => 'sokove',
            'name' => 'Сок от арония 100%',
            'slug' => 'sok-ot-aroniya-100',
            'image' => 'juce-aronia',
            'fruits' => ['aroniya'],
            'short' => 'Приготвен от 342 зрънца арония. Нищо друго.',
            'description' => '100% студено пресован сок от плод арония. Пастьоризиран. Не е произведен от плодов концентрат, а от 342 плодови зрънца арония. Без синтетични оцветители, аромати и подсладители, без консерванти. От свежи български плодове.',
            'ingredients' => [
                ['name' => 'Сок от арония', 'percentage' => 100, 'is_added' => false],
            ],
            'nutrition' => ['kcal_per_100' => 50, 'kj_per_100' => 215, 'fruit_content_percent' => 100],
            'variants' => [[
                'sku' => 'VIT-SOK-ARO-250',
                'label' => '250 мл',
                'size_value' => 250,
                'size_unit' => 'мл',
                'packaging' => 'Стъклена бутилка',
                'price_minor' => 300,
                'stock' => 0,
            ]],
        ]];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function nectars(): array
    {
        return [
            [
                'category' => 'nektari', 'series' => 'light',
                'name' => 'Плодов нектар от арония без захар',
                'slug' => 'nektar-aroniya-bez-zahar',
                'image' => 'nektar-aronia_1l',
                'fruits' => ['aroniya', 'malina'],
                'short' => 'Без добавена захар. Плодово съдържание минимум 40%.',
                'description' => 'Студено пресован сок от арония и малина с воден екстракт от арония. Без плодов концентрат. От свежи български плодове.',
                'ingredients' => [
                    ['name' => 'Студено пресован сок от арония', 'percentage' => 36, 'is_added' => false],
                    ['name' => 'Студено пресован сок от малина', 'percentage' => 4, 'is_added' => false],
                    ['name' => 'Воден екстракт от арония', 'is_added' => false],
                    ['name' => 'Лимонена киселина', 'is_added' => true],
                    ['name' => 'Витамин C', 'is_added' => true],
                    ['name' => 'Стевиол-гликозиди от стевия', 'is_added' => true],
                ],
                'nutrition' => ['fruit_content_percent' => 40],
                'variants' => [[
                    'sku' => 'VIT-NEK-ARO-1L-L', 'label' => '1 л', 'size_value' => 1, 'size_unit' => 'л',
                    'packaging' => 'Тетрапак', 'price_minor' => 270, 'stock' => 24,
                ]],
            ],
            [
                'category' => 'nektari', 'series' => 'light',
                'name' => 'Плодов нектар от малина и арония без захар',
                'slug' => 'nektar-malina-aroniya-bez-zahar',
                'image' => 'nektar-raspberry-aronia_1l',
                'fruits' => ['malina', 'aroniya'],
                'short' => 'Без добавена захар. Плодово съдържание минимум 40%.',
                'description' => 'Студено пресован сок от малина и арония с воден екстракт от арония. Без плодов концентрат.',
                'ingredients' => [
                    ['name' => 'Студено пресован сок от малина', 'percentage' => 22, 'is_added' => false],
                    ['name' => 'Студено пресован сок от арония', 'percentage' => 18, 'is_added' => false],
                    ['name' => 'Воден екстракт от арония', 'is_added' => false],
                    ['name' => 'Лимонена киселина', 'is_added' => true],
                    ['name' => 'Витамин C', 'is_added' => true],
                    ['name' => 'Стевиол-гликозиди от стевия', 'is_added' => true],
                ],
                'nutrition' => ['fruit_content_percent' => 40],
                'variants' => [[
                    'sku' => 'VIT-NEK-MAL-1L-L', 'label' => '1 л', 'size_value' => 1, 'size_unit' => 'л',
                    'packaging' => 'Тетрапак', 'price_minor' => 270, 'stock' => 18,
                ]],
            ],
            [
                'category' => 'nektari', 'series' => 'klasik',
                'name' => 'Плодов нектар от арония със захар',
                'slug' => 'nektar-aroniya-sas-zahar',
                'image' => 'nektar-aronia_1l_sugar',
                'fruits' => ['aroniya', 'malina'],
                'short' => 'Класическата рецепта. Плодово съдържание минимум 40%.',
                'description' => 'Студено пресован сок от арония и малина с воден екстракт от арония. Без плодов концентрат. От свежи български плодове.',
                'ingredients' => [
                    ['name' => 'Студено пресован сок от арония', 'percentage' => 36, 'is_added' => false],
                    ['name' => 'Студено пресован сок от малина', 'percentage' => 4, 'is_added' => false],
                    ['name' => 'Воден екстракт от арония', 'is_added' => false],
                    ['name' => 'Захар', 'is_added' => true],
                    ['name' => 'Лимонена киселина', 'is_added' => true],
                    ['name' => 'Витамин C', 'is_added' => true],
                ],
                'nutrition' => ['fruit_content_percent' => 40],
                'variants' => [[
                    'sku' => 'VIT-NEK-ARO-1L-K', 'label' => '1 л', 'size_value' => 1, 'size_unit' => 'л',
                    'packaging' => 'Тетрапак', 'price_minor' => 270, 'stock' => 0,
                ]],
            ],
            [
                'category' => 'nektari', 'series' => 'klasik',
                'name' => 'Плодов нектар от ягода и арония със захар',
                'slug' => 'nektar-yagoda-aroniya-sas-zahar',
                'image' => 'nektar-strawberry-aronia_1l_sugar',
                'fruits' => ['yagoda', 'aroniya'],
                'short' => 'Класическата рецепта. Плодово съдържание минимум 40%.',
                'description' => 'Студено пресован сок от ягода и арония. Без плодов концентрат. Аронията стабилизира пигментите на ягодовия сок — изследване на проф. Христо Крачанов.',
                'ingredients' => [
                    ['name' => 'Студено пресован сок от ягода', 'percentage' => 26, 'is_added' => false],
                    ['name' => 'Студено пресован сок от арония', 'percentage' => 14, 'is_added' => false],
                    ['name' => 'Воден екстракт от арония', 'is_added' => false],
                    ['name' => 'Захар', 'is_added' => true],
                    ['name' => 'Лимонена киселина', 'is_added' => true],
                    ['name' => 'Витамин C', 'is_added' => true],
                ],
                'nutrition' => ['fruit_content_percent' => 40],
                'variants' => [[
                    'sku' => 'VIT-NEK-YAG-1L-K', 'label' => '1 л', 'size_value' => 1, 'size_unit' => 'л',
                    'packaging' => 'Тетрапак', 'price_minor' => 270, 'stock' => 0,
                ]],
            ],
            [
                'category' => 'nektari', 'series' => 'detska-seriya',
                'name' => 'Детски нектар от ягода и арония',
                'slug' => 'detski-nektar-yagoda-aroniya',
                'image' => 'KIDS-strawberry-aronia',
                'fruits' => ['yagoda', 'aroniya'],
                'short' => 'От 25 ягоди и 15 зрънца арония. Плодово съдържание минимум 30%.',
                'description' => 'Здравословна напитка с приятен вкус, подходяща за деца над 6 месеца. Произведена от свежи и замразени български плодове, без консерванти, синтетични подсладители и оцветители.',
                'usage' => 'Разклатете преди употреба.',
                'storage' => 'Да се съхранява на сухо място, защитено от пряка слънчева светлина. След отваряне — в хладилник при 1–5 °C, до 3 дни.',
                'ingredients' => [
                    ['name' => 'Студено пресован сок от ягода', 'percentage' => 20, 'is_added' => false],
                    ['name' => 'Студено пресован сок от арония', 'percentage' => 10, 'is_added' => false],
                    ['name' => 'Захар', 'is_added' => true],
                    ['name' => 'Лимонена киселина', 'is_added' => true],
                ],
                'nutrition' => ['kcal_per_100' => 52, 'kj_per_100' => 220, 'fruit_content_percent' => 30],
                'variants' => [[
                    'sku' => 'VIT-NEK-KID-YAG-250', 'label' => '250 мл', 'size_value' => 250, 'size_unit' => 'мл',
                    'packaging' => 'Стъклена бутилка', 'price_minor' => 144, 'stock' => 0,
                ]],
            ],
            [
                'category' => 'nektari', 'series' => 'detska-seriya',
                'name' => 'Детски нектар от малина и арония',
                'slug' => 'detski-nektar-malina-aroniya',
                'image' => 'KIDS-raspberry-and-aronia',
                'fruits' => ['malina', 'aroniya'],
                'short' => 'От 40 малини и 20 зрънца арония. Плодово съдържание минимум 30%.',
                'description' => 'Здравословна напитка с приятен вкус, подходяща за деца над 6 месеца. Произведена от свежи и замразени български плодове.',
                'usage' => 'Разклатете преди употреба.',
                'storage' => 'Да се съхранява на сухо място, защитено от пряка слънчева светлина. След отваряне — в хладилник при 1–5 °C, до 3 дни.',
                'ingredients' => [
                    ['name' => 'Студено пресован сок от малина', 'percentage' => 20, 'is_added' => false],
                    ['name' => 'Студено пресован сок от арония', 'percentage' => 10, 'is_added' => false],
                    ['name' => 'Захар', 'is_added' => true],
                    ['name' => 'Лимонена киселина', 'is_added' => true],
                ],
                'nutrition' => ['kcal_per_100' => 52, 'kj_per_100' => 220, 'fruit_content_percent' => 30],
                'variants' => [[
                    'sku' => 'VIT-NEK-KID-MAL-250', 'label' => '250 мл', 'size_value' => 250, 'size_unit' => 'мл',
                    'packaging' => 'Стъклена бутилка', 'price_minor' => 144, 'stock' => 0,
                ]],
            ],
        ];
    }

    /**
     * The consolidation. On the current site these exist as 16 separate
     * products across six dilution-ratio subcategories, with four of them
     * sharing the name "Арония плодов сироп със захар".
     *
     * @return list<array<string, mixed>>
     */
    private function syrups(): array
    {
        $classic = [
            ['aroniya', 'Арония', 'sirop_aronia_1-13', 'Воден екстракт от плод арония'],
            ['yagoda', 'Ягода', 'sirop_strawberry_1-13', 'Воден екстракт от плодове ягода и арония'],
            ['malina', 'Малина', 'sirop_raspberry_1-13', 'Воден екстракт от плодове малина и арония'],
            ['kapina', 'Къпина', 'sirop_blackberry_1-13', 'Воден екстракт от плодове къпина'],
            ['vishna', 'Вишна', 'sirop_cherry_1-13', 'Воден екстракт от плодове вишна и арония'],
            ['kasis', 'Касис', 'sirop_blackcurrant_1-13', 'Воден екстракт от плодове касис и арония'],
            ['limon', 'Лимон', 'sirop_lemon_1-13', null],
        ];

        $smallSizes = [
            'aroniya' => ['sku' => 'VIT-SIR-ARO-300', 'image' => 'sirop_aronia_1-13_300ml', 'stock' => 20],
            'yagoda' => ['sku' => 'VIT-SIR-YAG-300', 'image' => 'sirop_strawberry_1-13_300ml', 'stock' => 12],
            'malina' => ['sku' => 'VIT-SIR-MAL-300', 'image' => 'sirop_raspberry_1-13_300ml', 'stock' => 0],
            'kapina' => ['sku' => 'VIT-SIR-KAP-300', 'image' => 'sirop_blackberry_1-13_300ml', 'stock' => 0],
        ];

        $products = [];

        foreach ($classic as $index => [$fruitSlug, $fruitName, $image, $extract]) {
            $variants = [[
                'sku' => 'VIT-SIR-'.strtoupper(substr($fruitSlug, 0, 3)).'-1KG',
                'label' => '1 кг · разреждане 1:13',
                'size_value' => 1,
                'size_unit' => 'кг',
                'dilution' => '1:13',
                'packaging' => 'PET бутилка',
                'price_minor' => 530,
                'stock' => 15,
            ]];

            if (isset($smallSizes[$fruitSlug])) {
                $variants[] = [
                    'sku' => $smallSizes[$fruitSlug]['sku'],
                    'label' => '300 г · разреждане 1:13',
                    'size_value' => 300,
                    'size_unit' => 'г',
                    'dilution' => '1:13',
                    'packaging' => 'PET бутилка',
                    'price_minor' => 195,
                    'stock' => $smallSizes[$fruitSlug]['stock'],
                ];
            }

            if ($fruitSlug === 'aroniya') {
                $variants[] = [
                    'sku' => 'VIT-SIR-ARO-1KG-6',
                    'label' => '900 г · разреждане 1:6',
                    'size_value' => 900,
                    'size_unit' => 'г',
                    'dilution' => '1:6',
                    'packaging' => 'Стъклена бутилка',
                    'price_minor' => 460,
                    'stock' => 0,
                ];
            }

            $ingredients = [
                ['name' => 'Студено пресован сок от '.mb_strtolower($fruitName), 'is_added' => false],
            ];

            if ($extract !== null) {
                $ingredients[] = ['name' => $extract, 'is_added' => false];
            }

            $ingredients[] = ['name' => 'Захар', 'is_added' => true];
            $ingredients[] = ['name' => 'Подсладител', 'is_added' => true];
            $ingredients[] = ['name' => 'Лимонена киселина', 'is_added' => true];

            $fruits = $fruitSlug === 'limon' || $fruitSlug === 'kapina'
                ? [$fruitSlug]
                : [$fruitSlug, 'aroniya'];

            $products[] = [
                'category' => 'siropi',
                'series' => 'klasik',
                'name' => $fruitName.' плодов сироп със захар',
                'slug' => 'sirop-'.$fruitSlug,
                'image' => $image,
                'fruits' => array_unique($fruits),
                'short' => 'Плодово съдържание минимум 38%. Без плодов концентрат.',
                'description' => 'Концентриран плодов сироп от студено пресован сок. Без плодов концентрат.',
                'usage' => '100 г от сиропа се разреждат според указаното съотношение.',
                'nutrition' => ['fruit_content_percent' => 38],
                'position' => $index,
                'variants' => $variants,
                'ingredients' => $ingredients,
            ];
        }

        $products[] = [
            'category' => 'siropi', 'series' => 'klasik',
            'name' => 'Сироп от бъз и джинджифил',
            'slug' => 'sirop-baz-dzhindzhifil',
            'image' => 'sirop_elder-and-ginger_1-10',
            'fruits' => ['baz', 'dzhindzhifil'],
            'short' => 'Сухи вещества над 65%. Може да се разрежда и с топла вода.',
            'description' => 'Сок и плодова маса от джинджифил с воден екстракт от свеж цвят от бъз. Без синтетични оцветители, аромати и консерванти.',
            'usage' => '100 г от сиропа се разреждат с 1 л вода. Може да се консумира и като топла напитка.',
            'nutrition' => ['kcal_per_100' => 24, 'kj_per_100' => 100, 'fruit_content_percent' => 38],
            'variants' => [[
                'sku' => 'VIT-SIR-BAZ-1KG', 'label' => '1 кг · разреждане 1:10',
                'size_value' => 1, 'size_unit' => 'кг', 'dilution' => '1:10',
                'packaging' => 'Стъклена бутилка', 'price_minor' => 945, 'stock' => 0,
            ]],
            'ingredients' => [
                ['name' => 'Сок и плодова маса от джинджифил', 'is_added' => false],
                ['name' => 'Воден екстракт от свеж цвят от бъз', 'is_added' => false],
                ['name' => 'Захар', 'is_added' => true],
                ['name' => 'Лимонена киселина', 'is_added' => true],
                ['name' => 'Стевиол-гликозиди', 'is_added' => true],
            ],
        ];

        $products[] = [
            'category' => 'siropi', 'series' => 'klasik',
            'name' => 'Сироп от лимон и джинджифил',
            'slug' => 'sirop-limon-dzhindzhifil',
            'image' => 'sirop_lemon-and-ginger_1-10',
            'fruits' => ['limon', 'dzhindzhifil'],
            'short' => 'Сухи вещества над 65%.',
            'description' => 'Сок и плодова маса от свежи джинджифил и лимони.',
            'usage' => '100 г от сиропа се разреждат с 1 л вода.',
            'variants' => [[
                'sku' => 'VIT-SIR-LIM-DZH-1KG', 'label' => '1 кг · разреждане 1:10',
                'size_value' => 1, 'size_unit' => 'кг', 'dilution' => '1:10',
                'packaging' => 'Стъклена бутилка', 'price_minor' => 945, 'stock' => 0,
            ]],
            'ingredients' => [
                ['name' => 'Сок и плодова маса от джинджифил и лимон', 'is_added' => false],
                ['name' => 'Захар', 'is_added' => true],
                ['name' => 'Лимонена киселина', 'is_added' => true],
                ['name' => 'Стевиол-гликозиди', 'is_added' => true],
            ],
        ];

        $products[] = [
            'category' => 'siropi', 'series' => 'klasik',
            'name' => 'Сироп от цвят бъз',
            'slug' => 'sirop-tsvyat-baz',
            'image' => 'sirop-elderflower-750',
            'fruits' => ['baz'],
            'short' => '34,5% воден екстракт от цвят бъз. Сухи вещества над 65%.',
            'description' => 'Не е произведен от плодов концентрат. Продуктът не е предназначен за директна консумация.',
            'usage' => 'Да се консумира само след разреждане 1:7.',
            'storage' => 'Да се съхранява при стайна температура на сухо място, защитено от пряка слънчева светлина.',
            'nutrition' => ['kcal_per_100' => 34, 'kj_per_100' => 145],
            'variants' => [[
                'sku' => 'VIT-SIR-BAZ-750', 'label' => '1 кг · разреждане 1:7',
                'size_value' => 1, 'size_unit' => 'кг', 'dilution' => '1:7',
                'packaging' => 'Стъклена бутилка', 'price_minor' => 695, 'stock' => 9,
            ]],
            'ingredients' => [
                ['name' => 'Воден екстракт от цвят бъз', 'percentage' => 34.5, 'is_added' => false],
                ['name' => 'Захар', 'is_added' => true],
                ['name' => 'Лимонена и млечна киселина', 'is_added' => true],
                ['name' => 'Естествен ароматизант от цвят бъз', 'is_added' => true],
            ],
        ];

        $products[] = [
            'category' => 'siropi', 'series' => 'light',
            'name' => 'Сироп от лимон и джинджифил със стевия',
            'slug' => 'sirop-limon-dzhindzhifil-steviya',
            'image' => 'sirop-lemon-ginger-stevia-650',
            'fruits' => ['limon', 'dzhindzhifil'],
            'short' => 'Плодово съдържание минимум 32%. Подсладен със стевия.',
            'description' => 'Сок и плодова маса от свежи джинджифил и лимони. Сухи вещества над 65%.',
            'usage' => '1 бутилка от сиропа се разрежда с 10 л вода.',
            'storage' => 'Да се съхранява при стайна температура на сухо място.',
            'nutrition' => ['kcal_per_100' => 24, 'kj_per_100' => 100, 'fruit_content_percent' => 32],
            'variants' => [[
                'sku' => 'VIT-SIR-LIM-STV-650', 'label' => '650 г · разреждане 1:10',
                'size_value' => 650, 'size_unit' => 'г', 'dilution' => '1:10',
                'packaging' => 'PET бутилка', 'price_minor' => 670, 'stock' => 11,
            ]],
            'ingredients' => [
                ['name' => 'Сок и плодова маса от джинджифил и лимон', 'is_added' => false],
                ['name' => 'Захар', 'is_added' => true],
                ['name' => 'Лимонена киселина', 'is_added' => true],
                ['name' => 'Стевиол-гликозиди от стевия', 'is_added' => true],
            ],
        ];

        return $products;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function teas(): array
    {
        $shared = [
            'category' => 'chayove',
            'fruits' => ['aroniya'],
            'short' => '100% студено пресовани зърна от арония. Без кофеин.',
            'description' => 'Прахообразна арония в полиетиленов плик, в кутия от 100% рециклирана хартия. Без консерванти, синтетични оцветители и аромати. Не съдържа кофеин и други алкалоиди.',
            'usage' => '2 ч.л. чай се запарват в 200 мл гореща вода и се оставят 4–5 минути. Прецежда се и се овкусява с мед и лимон по желание.',
            'ingredients' => [
                ['name' => 'Прахообразна арония', 'percentage' => 100, 'is_added' => false],
            ],
        ];

        return [
            [...$shared,
                'name' => 'Чай от арония 150 г',
                'slug' => 'chay-ot-aroniya-150g',
                'image' => 'tea-150gr',
                'variants' => [[
                    'sku' => 'VIT-CHA-ARO-150', 'label' => '150 г', 'size_value' => 150, 'size_unit' => 'г',
                    'packaging' => 'Кутия от рециклирана хартия', 'price_minor' => 240, 'stock' => 0,
                ]],
            ],
            [...$shared,
                'name' => 'Чай от арония 50 г с чаша',
                'slug' => 'chay-ot-aroniya-50g-s-chasha',
                'image' => 'tea-50gr_cup',
                'short' => '100% арония, в комплект със стъклена чаша.',
                'variants' => [[
                    'sku' => 'VIT-CHA-ARO-50C', 'label' => '50 г + чаша', 'size_value' => 50, 'size_unit' => 'г',
                    'packaging' => 'Кутия със стъклена чаша', 'price_minor' => 240, 'stock' => 0,
                ]],
            ],
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function pectins(): array
    {
        $shared = [
            'category' => 'pektini',
            'fruits' => ['yabalka'],
            'short' => 'Гранулиран ябълков пектин, 90 г.',
            'description' => 'Пектиновите вещества са кисели хетерополизахариди, широко разпространени в растенията. Гранулираната форма се разтваря по-лесно.',
            'usage' => 'За 1 доза 2 ч.л. пектин се смесват с 200 мл натурален сок от арония при непрекъснато разбъркване. Консумира се след 30 минути.',
        ];

        return [
            [...$shared,
                'name' => 'Гранулиран ябълков пектин с витамини',
                'slug' => 'pektin-s-vitamini',
                'image' => 'pektin-yabalka-green',
                'ingredients' => [
                    ['name' => 'Ябълков пектин', 'is_added' => false],
                    ['name' => 'Витамини', 'is_added' => true],
                ],
                'variants' => [[
                    'sku' => 'VIT-PEK-VIT-90', 'label' => '90 г', 'size_value' => 90, 'size_unit' => 'г',
                    'packaging' => 'PET бутилка', 'price_minor' => 485, 'stock' => 0,
                ]],
            ],
            [...$shared,
                'name' => 'Гранулиран ябълков пектин с магнезий',
                'slug' => 'pektin-s-magnezii',
                'image' => 'pektin-yabalka-red',
                'ingredients' => [
                    ['name' => 'Ябълков пектин', 'is_added' => false],
                    ['name' => 'Магнезий', 'is_added' => true],
                ],
                'variants' => [[
                    'sku' => 'VIT-PEK-MAG-90', 'label' => '90 г', 'size_value' => 90, 'size_unit' => 'г',
                    'packaging' => 'PET бутилка', 'price_minor' => 485, 'stock' => 0,
                ]],
            ],
        ];
    }
}
