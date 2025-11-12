<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Recipe;
use App\Models\Ingredient;
use App\Models\Procedure;
use Illuminate\Support\Facades\DB;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Define the User IDs to seed for (25 users needed for 50 recipes)
        $userIds = [
            38, 39, 40, 41, 42, 43, 44, 107, 108, 109,
            110, 111, 112, 113, 114, 115, 116, 117, 118, 119,
            120, 121, 122, 123, 124, // 25 unique IDs
        ];


        $recipesData = $this->getRecipeData();
        $recipeIndex = 0;

        foreach ($userIds as $userId) {
            // --- 1. FREE RECIPE ---
            if (isset($recipesData[$recipeIndex])) {
                // *** CHANGE: Using the string 'free' ***
                $this->createRecipe($userId, $recipesData[$recipeIndex], 'free', 0.00);
                $recipeIndex++;
            }

            // --- 2. PREMIUM RECIPE ---
            if (isset($recipesData[$recipeIndex])) {
                // Determine a random premium price
                $price = number_format(rand(1500, 5000) / 100, 2); // Price between 15.00 and 50.00
                // *** CHANGE: Using the string 'premium' ***
                $this->createRecipe($userId, $recipesData[$recipeIndex], 'premium', $price);
                $recipeIndex++;
            }
        }
    }

    /**
     * Helper method to create a Recipe and its related Ingredients and Procedures.
     */
    protected function createRecipe(int $userID, array $data, bool $isFree, float $price): void
    {
        // 1. Create Recipe
        $recipe = Recipe::create([
            'userID' => $userID,
            'recipeName' => $data['recipeName'],
            'cuisineType' => $data['cuisineType'],
            'description' => $data['description'],
            'image_path' => 'recipe_images/InQqEPqKNffaizhfPXso1WayCtSAFboloBWehdxb.jpg',
            'video_path' => 'https://www.youtube.com/watch?v=RgkWGnUV62o',
            'gCash_path' => $isFree ? null : 'gcash_qr_codes/EmJvrg5OvRL6xhbr5dNAcS1Tp0z1wZnC71ekerEW.png',
            'receipt_path' =>$isFree ? null : 'payment_receipts/zkq1ETDrRUIA8VVGHGMtHeJfH3ppxB6qupyhTe8b.jpg',
            'gcash_number' => $isFree ? null : '091234567' . $userID,
            'is_free' => $isFree,
            'price' => $price,
            'status' => 'active',
        ]);

        // 2. Create Ingredients
        foreach ($data['ingredients'] as $ingredient) {
            Ingredient::create([
                'recipeID' => $recipe->id,
                'ingredientName' => $ingredient['name'],
                'quantity' => $ingredient['quantity'],
            ]);
        }

        // 3. Create Procedures
        foreach ($data['procedures'] as $index => $instruction) {
            Procedure::create([
                'recipeID' => $recipe->id,
                'step' => $index + 1,
                'instruction' => $instruction,
            ]);
        }
    }

    /**
     * Get 50 distinct dummy recipe data sets.
     */
    protected function getRecipeData(): array
    {
        return [
            // User 1 (ID 38) - FREE
            [
                'recipeName' => 'Classic Chicken Adobo',
                'cuisineType' => 'Filipino',
                'description' => 'The national dish of the Philippines, marinated in soy sauce, vinegar, garlic, and peppercorns.',
                'ingredients' => [
                    ['name' => 'Chicken thighs', 'quantity' => '1 kg'],
                    ['name' => 'Soy Sauce', 'quantity' => '1/2 cup'],
                    ['name' => 'White Vinegar', 'quantity' => '1/4 cup'],
                    ['name' => 'Garlic cloves', 'quantity' => '6'],
                ],
                'procedures' => [
                    'Combine chicken, soy sauce, vinegar, crushed garlic, and peppercorns in a pot.',
                    'Bring to a boil, then lower heat and simmer for 30-40 minutes.',
                    'Remove chicken, fry until browned, then return to the sauce.',
                    'Serve hot over rice.',
                ],
            ],
            // User 1 (ID 38) - PREMIUM
            [
                'recipeName' => 'Beef Wellington',
                'cuisineType' => 'French/British',
                'description' => 'Tenderloin steak coated with pâté and duxelles, wrapped in puff pastry and baked.',
                'ingredients' => [
                    ['name' => 'Beef tenderloin', 'quantity' => '1.5 kg'],
                    ['name' => 'Puff pastry', 'quantity' => '500g'],
                    ['name' => 'Mushroom duxelles', 'quantity' => '200g'],
                    ['name' => 'Prosciutto slices', 'quantity' => '12'],
                ],
                'procedures' => [
                    'Sear the tenderloin and let cool.',
                    'Spread the duxelles onto prosciutto slices, wrap around the beef.',
                    'Wrap the entire thing in puff pastry, brush with egg wash.',
                    'Bake at 200°C for 25-30 minutes for medium-rare.',
                ],
            ],
            // User 2 (ID 39) - FREE
            [
                'recipeName' => 'Spicy Tuna Pasta',
                'cuisineType' => 'Italian/Fusion',
                'description' => 'A simple, quick-to-prepare pasta dish with tuna, chili, and tomato sauce.',
                'ingredients' => [
                    ['name' => 'Spaghetti', 'quantity' => '200g'],
                    ['name' => 'Canned Tuna in oil', 'quantity' => '2 cans'],
                    ['name' => 'Diced Tomatoes', 'quantity' => '400g'],
                    ['name' => 'Chili flakes', 'quantity' => '1 tsp'],
                ],
                'procedures' => [
                    'Cook spaghetti according to package directions.',
                    'In a pan, sauté garlic and chili flakes, then add diced tomatoes.',
                    'Add the tuna (and its oil) to the sauce.',
                    'Toss the cooked spaghetti in the sauce and serve.',
                ],
            ],
            // User 2 (ID 39) - PREMIUM
            [
                'recipeName' => 'Authentic Ramen Broth (Tonkotsu)',
                'cuisineType' => 'Japanese',
                'description' => 'The ultimate guide to making rich, creamy, 18-hour Tonkotsu (Pork Bone) broth.',
                'ingredients' => [
                    ['name' => 'Pork trotters and bones', 'quantity' => '2 kg'],
                    ['name' => 'Water', 'quantity' => '5 liters'],
                    ['name' => 'Ginger', 'quantity' => '1 whole piece'],
                    ['name' => 'Onion', 'quantity' => '1 whole'],
                ],
                'procedures' => [
                    'Blanch the bones to remove impurities.',
                    'Combine bones, water, and aromatics in a large stockpot.',
                    'Bring to a rapid boil, then reduce heat and simmer for 12-18 hours, skimming as needed.',
                    'Strain and emulsify the broth until creamy white.',
                ],
            ],
            // User 3 (ID 40) - FREE
            [
                'recipeName' => 'Pancake Stack',
                'cuisineType' => 'American',
                'description' => 'Fluffy, classic American pancakes perfect for breakfast.',
                'ingredients' => [
                    ['name' => 'All-purpose flour', 'quantity' => '1 1/2 cups'],
                    ['name' => 'Milk', 'quantity' => '1 1/4 cups'],
                    ['name' => 'Egg', 'quantity' => '1 large'],
                    ['name' => 'Baking powder', 'quantity' => '1 tbsp'],
                ],
                'procedures' => [
                    'Whisk dry ingredients (flour, baking powder, sugar, salt) in a bowl.',
                    'In a separate bowl, whisk wet ingredients (milk, egg, melted butter).',
                    'Combine wet and dry ingredients, mixing until just combined (lumps are okay).',
                    'Pour batter onto a hot, greased pan and cook until bubbles form, then flip.',
                ],
            ],
            // User 3 (ID 40) - PREMIUM
            [
                'recipeName' => 'Creme Brûlée',
                'cuisineType' => 'French',
                'description' => 'A rich custard base topped with a layer of hardened caramelized sugar.',
                'ingredients' => [
                    ['name' => 'Heavy cream', 'quantity' => '2 cups'],
                    ['name' => 'Egg yolks', 'quantity' => '5'],
                    ['name' => 'Granulated sugar', 'quantity' => '1/2 cup'],
                    ['name' => 'Vanilla bean', 'quantity' => '1'],
                ],
                'procedures' => [
                    'Steep vanilla bean in heated cream.',
                    'Whisk egg yolks and sugar until pale.',
                    'Temper the cream into the yolk mixture.',
                    'Pour into ramekins, bake in a water bath until set.',
                    'Chill, then sprinkle with sugar and torch until caramelized before serving.',
                ],
            ],
            // User 4 (ID 41) - FREE
            [
                'recipeName' => 'Quick Beef Stir-Fry',
                'cuisineType' => 'Asian',
                'description' => 'A healthy and flavourful stir-fry with thinly sliced beef and mixed vegetables.',
                'ingredients' => [
                    ['name' => 'Thinly sliced beef sirloin', 'quantity' => '500g'],
                    ['name' => 'Broccoli florets', 'quantity' => '1 head'],
                    ['name' => 'Carrots, sliced', 'quantity' => '2'],
                    ['name' => 'Soy Sauce', 'quantity' => '1/4 cup'],
                ],
                'procedures' => [
                    'Marinade beef in soy sauce and ginger.',
                    'Stir-fry beef in a hot wok until browned; remove.',
                    'Add vegetables to the wok and stir-fry until tender-crisp.',
                    'Return beef to the wok, toss with sauce, and serve immediately.',
                ],
            ],
            // User 4 (ID 41) - PREMIUM
            [
                'recipeName' => 'Sourdough Bread Starter & Loaf',
                'cuisineType' => 'European',
                'description' => 'Comprehensive guide to maintaining a starter and baking the perfect naturally leavened loaf.',
                'ingredients' => [
                    ['name' => 'Active Sourdough Starter', 'quantity' => '150g'],
                    ['name' => 'Bread flour', 'quantity' => '500g'],
                    ['name' => 'Water (warm)', 'quantity' => '350g'],
                    ['name' => 'Salt', 'quantity' => '10g'],
                ],
                'procedures' => [
                    'Mix starter, water, and flour, then let rest (autolyse).',
                    'Add salt, then perform several sets of stretch and folds over 3 hours.',
                    'Bulk ferment until doubled, shape the dough, and cold proof overnight.',
                    'Bake in a Dutch oven until deeply caramelized crust forms.',
                ],
            ],
            // User 5 (ID 42) - FREE
            [
                'recipeName' => 'Tuna Omelette',
                'cuisineType' => 'Global',
                'description' => 'A simple, protein-packed omelette with canned tuna and herbs.',
                'ingredients' => [
                    ['name' => 'Eggs', 'quantity' => '3 large'],
                    ['name' => 'Canned Tuna (drained)', 'quantity' => '1/2 can'],
                    ['name' => 'Onion, finely chopped', 'quantity' => '1/4'],
                    ['name' => 'Salt and Pepper', 'quantity' => 'to taste'],
                ],
                'procedures' => [
                    'Whisk eggs with salt and pepper.',
                    'Sauté onion until translucent.',
                    'Add tuna to the pan, then pour the egg mixture over it.',
                    'Cook until set, fold in half, and serve.',
                ],
            ],
            // User 5 (ID 42) - PREMIUM
            [
                'recipeName' => 'Paella Valenciana',
                'cuisineType' => 'Spanish',
                'description' => 'The traditional Spanish rice dish with saffron, chicken, rabbit, and green beans.',
                'ingredients' => [
                    ['name' => 'Bomba Rice', 'quantity' => '300g'],
                    ['name' => 'Chicken pieces', 'quantity' => '300g'],
                    ['name' => 'Rabbit pieces', 'quantity' => '300g'],
                    ['name' => 'Saffron threads', 'quantity' => '1/2 tsp'],
                ],
                'procedures' => [
                    'Sauté meats and vegetables (beans, tomatoes) in a paella pan.',
                    'Add paprika, saffron, and stock to the pan; bring to a simmer.',
                    'Sprinkle the rice evenly over the pan, do not stir.',
                    'Cook until liquid is absorbed and a ' . 'socarrat' . ' (crust) forms on the bottom.',
                ],
            ],
            // User 6 (ID 43) - FREE
            [
                'recipeName' => 'Grilled Cheese Sandwich',
                'cuisineType' => 'American',
                'description' => 'The ultimate comfort food: golden, crispy bread and gooey melted cheese.',
                'ingredients' => [
                    ['name' => 'White bread slices', 'quantity' => '2'],
                    ['name' => 'Cheddar cheese slices', 'quantity' => '2'],
                    ['name' => 'Butter', 'quantity' => '1 tbsp'],
                ],
                'procedures' => [
                    'Spread butter on one side of each bread slice.',
                    'Place one slice, butter-side down, in a pan.',
                    'Layer cheese on top, then top with the second slice (butter-side up).',
                    'Cook until golden brown on both sides and cheese is melted.',
                ],
            ],
            // User 6 (ID 43) - PREMIUM
            [
                'recipeName' => 'Classic French Croissant',
                'cuisineType' => 'French',
                'description' => 'The detailed, multi-day process of making perfectly laminated, buttery croissants.',
                'ingredients' => [
                    ['name' => 'Bread Flour', 'quantity' => '500g'],
                    ['name' => 'Butter (cold, block)', 'quantity' => '250g'],
                    ['name' => 'Milk (cold)', 'quantity' => '170g'],
                    ['name' => 'Yeast', 'quantity' => '10g'],
                ],
                'procedures' => [
                    'Prepare the dough and chill overnight.',
                    'Prepare the butter block and incorporate it into the dough.',
                    'Perform three single folds, chilling between each fold (lamination).',
                    'Shape the croissants and final proof, then bake at high heat.',
                ],
            ],
            // User 7 (ID 44) - FREE
            [
                'recipeName' => 'Banana Bread',
                'cuisineType' => 'American',
                'description' => 'Moist and sweet loaf made with overripe bananas.',
                'ingredients' => [
                    ['name' => 'Overripe bananas', 'quantity' => '3 medium'],
                    ['name' => 'All-purpose flour', 'quantity' => '1 3/4 cups'],
                    ['name' => 'Sugar', 'quantity' => '3/4 cup'],
                    ['name' => 'Egg', 'quantity' => '1 large'],
                ],
                'procedures' => [
                    'Mash bananas in a bowl.',
                    'Mix in wet ingredients (egg, melted butter, sugar).',
                    'Fold in dry ingredients (flour, baking soda, salt).',
                    'Pour into a loaf pan and bake at 175°C for about one hour.',
                ],
            ],
            // User 7 (ID 44) - PREMIUM
            [
                'recipeName' => 'Pork Belly Sisig (Crispy)',
                'cuisineType' => 'Filipino',
                'description' => 'Achieve the perfect crunchy texture for this beloved Filipino appetizer.',
                'ingredients' => [
                    ['name' => 'Pork Belly (Skin-on)', 'quantity' => '1 kg'],
                    ['name' => 'Onion, finely chopped', 'quantity' => '2'],
                    ['name' => 'Chili (Siling Labuyo)', 'quantity' => '5'],
                    ['name' => 'Calamansi juice', 'quantity' => '1/4 cup'],
                ],
                'procedures' => [
                    'Boil the pork belly until tender.',
                    'Deep fry the boiled pork belly until the skin is blistered and extremely crispy.',
                    'Chop the crispy pork into small pieces.',
                    'Mix with chopped onions, chili, and calamansi juice on a sizzling plate.',
                ],
            ],
            // User 8 (ID 107) - FREE
            [
                'recipeName' => 'Baked Salmon with Asparagus',
                'cuisineType' => 'Mediterranean',
                'description' => 'A healthy, one-pan meal with lemon, garlic, and fresh herbs.',
                'ingredients' => [
                    ['name' => 'Salmon fillets', 'quantity' => '2'],
                    ['name' => 'Asparagus spears', 'quantity' => '1 bunch'],
                    ['name' => 'Lemon, sliced', 'quantity' => '1'],
                    ['name' => 'Olive Oil', 'quantity' => '2 tbsp'],
                ],
                'procedures' => [
                    'Preheat oven to 200°C.',
                    'Toss asparagus in olive oil, salt, and pepper.',
                    'Place salmon on a baking sheet, top with lemon slices and herbs.',
                    'Bake for 12-15 minutes until salmon is cooked through.',
                ],
            ],
            // User 8 (ID 107) - PREMIUM
            [
                'recipeName' => 'Coq au Vin (Chicken in Wine)',
                'cuisineType' => 'French',
                'description' => 'A classic French stew of chicken braised with wine, mushrooms, and lardons.',
                'ingredients' => [
                    ['name' => 'Chicken pieces', 'quantity' => '1.5 kg'],
                    ['name' => 'Red wine (Burgundy)', 'quantity' => '750 ml'],
                    ['name' => 'Lardons or bacon', 'quantity' => '150g'],
                    ['name' => 'Button mushrooms', 'quantity' => '200g'],
                ],
                'procedures' => [
                    'Sear the chicken pieces until browned.',
                    'Sauté lardons and mushrooms.',
                    'Return chicken to the pot, cover with red wine and stock.',
                    'Simmer slowly for 1.5 to 2 hours until chicken is very tender.',
                ],
            ],
            // User 9 (ID 108) - FREE
            [
                'recipeName' => 'Tofu Scramble',
                'cuisineType' => 'Vegan',
                'description' => 'A quick and flavourful vegan alternative to scrambled eggs.',
                'ingredients' => [
                    ['name' => 'Firm Tofu (pressed)', 'quantity' => '1 block'],
                    ['name' => 'Turmeric (for color)', 'quantity' => '1/2 tsp'],
                    ['name' => 'Nutritional Yeast', 'quantity' => '1 tbsp'],
                    ['name' => 'Black Salt (Kala Namak)', 'quantity' => '1/4 tsp'],
                ],
                'procedures' => [
                    'Crumble the tofu into a pan.',
                    'Add spices (turmeric, salt, pepper, nutritional yeast) and sauté.',
                    'Cook until heated through and lightly browned, adjusting seasoning.',
                    'Serve with toast or vegetables.',
                ],
            ],
            // User 9 (ID 108) - PREMIUM
            [
                'recipeName' => 'Japanese Wagyu Steak',
                'cuisineType' => 'Japanese',
                'description' => 'Mastering the technique for cooking highly marbled Wagyu for perfect results.',
                'ingredients' => [
                    ['name' => 'Wagyu Beef steak', 'quantity' => '200g'],
                    ['name' => 'Flaky sea salt', 'quantity' => 'to taste'],
                    ['name' => 'Unsalted butter', 'quantity' => '1 tbsp'],
                ],
                'procedures' => [
                    'Bring the steak to room temperature.',
                    'Season heavily with flaky sea salt.',
                    'Sear for 2 minutes per side in a screaming hot cast iron pan.',
                    'Baste with butter and herbs for the final minute; rest for 10 minutes.',
                ],
            ],
            // User 10 (ID 109) - FREE
            [
                'recipeName' => 'Cheesy Garlic Bread',
                'cuisineType' => 'Italian/American',
                'description' => 'Crusty bread loaded with garlic, butter, and melted cheese.',
                'ingredients' => [
                    ['name' => 'French baguette', 'quantity' => '1'],
                    ['name' => 'Butter, softened', 'quantity' => '1/2 cup'],
                    ['name' => 'Garlic, minced', 'quantity' => '3 cloves'],
                    ['name' => 'Mozzarella cheese', 'quantity' => '1 cup'],
                ],
                'procedures' => [
                    'Mix butter and garlic to create garlic butter.',
                    'Slice the baguette lengthwise and spread the butter mixture.',
                    'Top with mozzarella cheese.',
                    'Bake at 180°C until the cheese is melted and bubbly.',
                ],
            ],
            // User 10 (ID 109) - PREMIUM
            [
                'recipeName' => 'Dim Sum Siu Mai (Pork & Shrimp)',
                'cuisineType' => 'Chinese (Cantonese)',
                'description' => 'Detailed technique for making perfectly pleated and steamed Cantonese pork and shrimp dumplings.',
                'ingredients' => [
                    ['name' => 'Ground pork', 'quantity' => '300g'],
                    ['name' => 'Shrimp (chopped)', 'quantity' => '150g'],
                    ['name' => 'Siu Mai wrappers', 'quantity' => '50'],
                    ['name' => 'Shitake mushrooms', 'quantity' => '5 dried'],
                ],
                'procedures' => [
                    'Soak and chop mushrooms; mix with pork, shrimp, and seasoning (soy sauce, sesame oil).',
                    'Fill wrappers with the meat mixture, gathering the edges to form a cup.',
                    'Steam in a bamboo steamer for 10-12 minutes until cooked through.',
                    'Serve hot with chili oil.',
                ],
            ],
            // User 11 (ID 110) - FREE
            [
                'recipeName' => 'Simple Green Smoothie',
                'cuisineType' => 'Health',
                'description' => 'A fast and nutritious way to get your daily greens.',
                'ingredients' => [
                    ['name' => 'Spinach', 'quantity' => '1 cup'],
                    ['name' => 'Banana (frozen)', 'quantity' => '1'],
                    ['name' => 'Almond Milk', 'quantity' => '1 cup'],
                    ['name' => 'Chia Seeds', 'quantity' => '1 tbsp'],
                ],
                'procedures' => [
                    'Combine all ingredients in a high-speed blender.',
                    'Blend until completely smooth.',
                    'Add more almond milk if needed to reach desired consistency.',
                    'Pour and serve immediately.',
                ],
            ],
            // User 11 (ID 110) - PREMIUM
            [
                'recipeName' => 'Classic Tiramisu',
                'cuisineType' => 'Italian',
                'description' => 'Layered dessert with coffee-soaked Ladyfingers, mascarpone cream, and cocoa powder.',
                'ingredients' => [
                    ['name' => 'Ladyfingers (Savoiardi)', 'quantity' => '24'],
                    ['name' => 'Mascarpone cheese', 'quantity' => '500g'],
                    ['name' => 'Egg yolks', 'quantity' => '3'],
                    ['name' => 'Espresso (cooled)', 'quantity' => '1 1/2 cups'],
                ],
                'procedures' => [
                    'Whip egg yolks and sugar until pale; fold in mascarpone.',
                    'Quickly dip ladyfingers into the cooled espresso.',
                    'Layer the coffee-soaked ladyfingers and the mascarpone cream in a dish.',
                    'Chill overnight and dust heavily with cocoa powder before serving.',
                ],
            ],
            // User 12 (ID 111) - FREE
            [
                'recipeName' => 'Microwave Chocolate Mug Cake',
                'cuisineType' => 'Dessert',
                'description' => 'A single-serving chocolate cake ready in under 5 minutes.',
                'ingredients' => [
                    ['name' => 'All-purpose flour', 'quantity' => '4 tbsp'],
                    ['name' => 'Cocoa powder', 'quantity' => '3 tbsp'],
                    ['name' => 'Sugar', 'quantity' => '4 tbsp'],
                    ['name' => 'Milk', 'quantity' => '3 tbsp'],
                ],
                'procedures' => [
                    'Mix all dry ingredients in a microwave-safe mug.',
                    'Stir in the wet ingredients (milk, oil/melted butter, vanilla).',
                    'Mix until no lumps remain.',
                    'Microwave on high for 60-90 seconds until cooked but still soft.',
                ],
            ],
            // User 12 (ID 111) - PREMIUM
            [
                'recipeName' => 'Chicken Tikka Masala (Restaurant Style)',
                'cuisineType' => 'Indian',
                'description' => 'A rich, creamy, and smoky tomato-based curry with marinated chicken.',
                'ingredients' => [
                    ['name' => 'Chicken breast (cubed)', 'quantity' => '750g'],
                    ['name' => 'Plain Yogurt', 'quantity' => '1/2 cup'],
                    ['name' => 'Tomato Puree', 'quantity' => '400g'],
                    ['name' => 'Heavy Cream', 'quantity' => '1/2 cup'],
                ],
                'procedures' => [
                    'Marinate chicken in yogurt and spices for at least 4 hours.',
                    'Grill or bake the chicken (Tikka) until charred.',
                    'Sauté onions, ginger, and garlic, then add tomato puree and spices.',
                    'Stir in the cream and the cooked chicken; simmer until rich.',
                ],
            ],
            // User 13 (ID 112) - FREE
            [
                'recipeName' => 'Easy Garlic Fried Rice',
                'cuisineType' => 'Asian',
                'description' => 'Simple Filipino-style garlic fried rice (Sinangag) perfect for breakfast.',
                'ingredients' => [
                    ['name' => 'Cold cooked rice (leftover)', 'quantity' => '3 cups'],
                    ['name' => 'Garlic, minced', 'quantity' => '6 cloves'],
                    ['name' => 'Oil', 'quantity' => '3 tbsp'],
                    ['name' => 'Salt', 'quantity' => 'to taste'],
                ],
                'procedures' => [
                    'Heat oil in a wok or large pan.',
                    'Fry the minced garlic until golden brown and crispy; remove half for garnish.',
                    'Add the cold rice to the pan and break up clumps.',
                    'Season with salt and stir-fry until heated through.',
                ],
            ],
            // User 13 (ID 112) - PREMIUM
            [
                'recipeName' => 'Handmade Fresh Pasta (Fettuccine)',
                'cuisineType' => 'Italian',
                'description' => 'Detailed technique for kneading, rolling, and cutting perfect fresh egg pasta.',
                'ingredients' => [
                    ['name' => 'Double-Zero (00) Flour', 'quantity' => '300g'],
                    ['name' => 'Large Eggs', 'quantity' => '3'],
                    ['name' => 'Olive Oil', 'quantity' => '1 tsp'],
                ],
                'procedures' => [
                    'Mound the flour on a surface and create a well in the center.',
                    'Whisk eggs and oil in the well, then slowly incorporate the flour.',
                    'Knead the dough for 10-15 minutes until smooth; rest for 30 minutes.',
                    'Roll dough very thinly using a pasta machine; cut into fettuccine strips.',
                ],
            ],
            // User 14 (ID 113) - FREE
            [
                'recipeName' => 'Black Bean Soup',
                'cuisineType' => 'Mexican/Vegan',
                'description' => 'Hearty and flavorful soup, great for a light dinner or appetizer.',
                'ingredients' => [
                    ['name' => 'Canned black beans', 'quantity' => '2 (400g) cans'],
                    ['name' => 'Vegetable broth', 'quantity' => '4 cups'],
                    ['name' => 'Onion and bell pepper (diced)', 'quantity' => '1 cup'],
                    ['name' => 'Cumin', 'quantity' => '1 tsp'],
                ],
                'procedures' => [
                    'Sauté onion and bell pepper until soft.',
                    'Add broth, beans (rinsed), and spices (cumin, chili powder).',
                    'Simmer for 20 minutes.',
                    'Blend half the soup for creaminess, then combine with the chunky half.',
                ],
            ],
            // User 14 (ID 113) - PREMIUM
            [
                'recipeName' => 'Dry-Aged Duck Breast with Cherry Sauce',
                'cuisineType' => 'Fine Dining',
                'description' => 'Crispy-skinned duck breast served with a classic French fruit reduction.',
                'ingredients' => [
                    ['name' => 'Duck Breast (scored skin)', 'quantity' => '2'],
                    ['name' => 'Fresh or frozen cherries', 'quantity' => '1 cup'],
                    ['name' => 'Red wine vinegar', 'quantity' => '1/4 cup'],
                    ['name' => 'Shallot', 'quantity' => '1'],
                ],
                'procedures' => [
                    'Render the fat by searing the duck breast skin-side down in a cold pan.',
                    'Finish cooking in the oven; rest and slice.',
                    'Deglaze the pan with wine and vinegar; add shallots and cherries.',
                    'Simmer the sauce until reduced and glossy; spoon over the sliced duck.',
                ],
            ],
            // User 15 (ID 114) - FREE
            [
                'recipeName' => 'Oatmeal (Classic Porridge)',
                'cuisineType' => 'Breakfast',
                'description' => 'Simple, nourishing rolled oats porridge, customized to your liking.',
                'ingredients' => [
                    ['name' => 'Rolled Oats', 'quantity' => '1/2 cup'],
                    ['name' => 'Water or Milk', 'quantity' => '1 cup'],
                    ['name' => 'Salt', 'quantity' => 'pinch'],
                ],
                'procedures' => [
                    'Combine oats, liquid, and salt in a saucepan.',
                    'Bring to a boil, then reduce heat and simmer for 5 minutes, stirring occasionally.',
                    'Remove from heat, cover, and let stand for 2 minutes.',
                    'Serve with desired toppings (fruit, nuts, honey).',
                ],
            ],
            // User 15 (ID 114) - PREMIUM
            [
                'recipeName' => 'Seafood Bouillabaisse',
                'cuisineType' => 'French (Provencal)',
                'description' => 'A rich and complex Provençal fish stew with various types of fish and shellfish.',
                'ingredients' => [
                    ['name' => 'White fish (cod, snapper)', 'quantity' => '500g'],
                    ['name' => 'Mussels and Clams', 'quantity' => '500g'],
                    ['name' => 'Shrimp (prawns)', 'quantity' => '300g'],
                    ['name' => 'Saffron', 'quantity' => '1 pinch'],
                ],
                'procedures' => [
                    'Sauté vegetables (leeks, fennel, tomato) and aromatics.',
                    'Add fish stock, saffron, and orange peel; simmer for 30 minutes.',
                    'Add the firm fish, then the shellfish and prawns.',
                    'Cook until shellfish opens; serve immediately with rouille and crusty bread.',
                ],
            ],
            // User 16 (ID 115) - FREE
            [
                'recipeName' => 'Hummus (Basic)',
                'cuisineType' => 'Middle Eastern',
                'description' => 'Creamy, homemade chickpea dip perfect for pita or vegetables.',
                'ingredients' => [
                    ['name' => 'Canned chickpeas (drained)', 'quantity' => '1 (400g) can'],
                    ['name' => 'Tahini', 'quantity' => '1/4 cup'],
                    ['name' => 'Lemon juice', 'quantity' => '2 tbsp'],
                    ['name' => 'Garlic clove', 'quantity' => '1'],
                ],
                'procedures' => [
                    'For extra smooth hummus, remove the skins from the chickpeas.',
                    'Blend tahini and lemon juice first until light and creamy.',
                    'Add chickpeas, garlic, and blend, slowly adding cold water until smooth.',
                    'Season with salt and serve drizzled with olive oil.',
                ],
            ],
            // User 16 (ID 115) - PREMIUM
            [
                'recipeName' => 'Peking Duck (Lacquered)',
                'cuisineType' => 'Chinese (Beijing)',
                'description' => 'The traditional method for achieving perfectly crisp, lacquered duck skin.',
                'ingredients' => [
                    ['name' => 'Whole Duck (cleaned)', 'quantity' => '2 kg'],
                    ['name' => 'Maltose (or honey)', 'quantity' => '1/2 cup'],
                    ['name' => 'Rice vinegar', 'quantity' => '1/4 cup'],
                    ['name' => 'Five-spice powder', 'quantity' => '1 tbsp'],
                ],
                'procedures' => [
                    'Air-dry the duck (best overnight) after washing and coating with maltose/vinegar mixture.',
                    'Roast the duck slowly at a low temperature, then blast it with high heat at the end.',
                    'Rest the duck, then carve the crispy skin and meat thinly.',
                    'Serve with thin pancakes, scallions, and hoisin sauce.',
                ],
            ],
            // User 17 (ID 116) - FREE
            [
                'recipeName' => 'Simple Tomato Soup',
                'cuisineType' => 'Global',
                'description' => 'A comforting, creamy tomato soup using canned tomatoes.',
                'ingredients' => [
                    ['name' => 'Canned crushed tomatoes', 'quantity' => '2 (400g) cans'],
                    ['name' => 'Onion, chopped', 'quantity' => '1 medium'],
                    ['name' => 'Vegetable broth', 'quantity' => '3 cups'],
                    ['name' => 'Heavy cream (optional)', 'quantity' => '1/4 cup'],
                ],
                'procedures' => [
                    'Sauté onion until soft.',
                    'Add crushed tomatoes and broth; simmer for 20 minutes.',
                    'Use an immersion blender to blend until smooth.',
                    'Stir in cream (if using) and season with salt and pepper.',
                ],
            ],
            // User 17 (ID 116) - PREMIUM
            [
                'recipeName' => 'Molecular Gastronomy: Spherical Olives',
                'cuisineType' => 'Experimental',
                'description' => 'A detailed lesson in reverse spherification to create edible olive ' . 'caviar' . '.',
                'ingredients' => [
                    ['name' => 'Olive juice', 'quantity' => '200 ml'],
                    ['name' => 'Calcium Chloride', 'quantity' => '1g'],
                    ['name' => 'Sodium Alginate', 'quantity' => '1.5g'],
                    ['name' => 'Distilled Water', 'quantity' => '1 liter'],
                ],
                'procedures' => [
                    'Mix sodium alginate into olive juice and let rest overnight (Base Bath).',
                    'Mix calcium chloride into water (Setting Bath).',
                    'Using a pipette, drop the Base Bath mixture into the Setting Bath.',
                    'Rinse the formed spheres gently and serve immediately.',
                ],
            ],
            // User 18 (ID 117) - FREE
            [
                'recipeName' => 'Tuna Melt Sandwich',
                'cuisineType' => 'American',
                'description' => 'A warm, satisfying sandwich of tuna salad and melted cheese.',
                'ingredients' => [
                    ['name' => 'Canned Tuna (drained)', 'quantity' => '1 can'],
                    ['name' => 'Mayonnaise', 'quantity' => '2 tbsp'],
                    ['name' => 'Celery (diced)', 'quantity' => '1 stalk'],
                    ['name' => 'Cheddar cheese slices', 'quantity' => '2'],
                ],
                'procedures' => [
                    'Mix tuna, mayonnaise, and celery to make tuna salad.',
                    'Spread tuna salad onto one slice of bread.',
                    'Top with cheese and the other slice of bread.',
                    'Grill or toast until the bread is golden and the cheese is melted.',
                ],
            ],
            // User 18 (ID 117) - PREMIUM
            [
                'recipeName' => 'Osso Buco with Gremolata',
                'cuisineType' => 'Italian (Lombardy)',
                'description' => 'Braised veal shanks cooked in white wine and vegetables, topped with a citrus gremolata.',
                'ingredients' => [
                    ['name' => 'Veal shanks (cross-cut)', 'quantity' => '4'],
                    ['name' => 'White wine (dry)', 'quantity' => '1 cup'],
                    ['name' => 'Tomato paste', 'quantity' => '2 tbsp'],
                    ['name' => 'Orange/Lemon Zest', 'quantity' => '1 tbsp'],
                ],
                'procedures' => [
                    'Dredge shanks in flour and sear until browned.',
                    'Sauté soffritto (onion, celery, carrot); add wine and stock.',
                    'Braise in the oven for 2-3 hours until meat is fork-tender.',
                    'Garnish with Gremolata (minced parsley, garlic, and citrus zest) before serving.',
                ],
            ],
            // User 19 (ID 118) - FREE
            [
                'recipeName' => 'Chocolate Chip Cookies (Chewy)',
                'cuisineType' => 'American',
                'description' => 'The perfect recipe for soft, chewy cookies with melted chocolate chips.',
                'ingredients' => [
                    ['name' => 'All-purpose flour', 'quantity' => '2 1/4 cups'],
                    ['name' => 'Brown sugar', 'quantity' => '1 cup'],
                    ['name' => 'Unsalted butter (melted)', 'quantity' => '1 cup'],
                    ['name' => 'Chocolate chips', 'quantity' => '2 cups'],
                ],
                'procedures' => [
                    'Mix melted butter with brown and white sugar.',
                    'Beat in eggs and vanilla.',
                    'Gradually add flour, baking soda, and salt; stir in chocolate chips.',
                    'Chill the dough for 30 minutes, then scoop onto a baking sheet.',
                    'Bake at 175°C for 10-12 minutes.',
                ],
            ],
            // User 19 (ID 118) - PREMIUM
            [
                'recipeName' => 'Thai Green Curry Paste (From Scratch)',
                'cuisineType' => 'Thai',
                'description' => 'The authentic process for grinding fresh ingredients to create a vibrant, aromatic paste.',
                'ingredients' => [
                    ['name' => 'Fresh green chilies', 'quantity' => '10'],
                    ['name' => 'Lemongrass (sliced)', 'quantity' => '2 stalks'],
                    ['name' => 'Galangal (sliced)', 'quantity' => '1 inch piece'],
                    ['name' => 'Kaffir lime zest and leaves', 'quantity' => '2 leaves/zest of 1 lime'],
                ],
                'procedures' => [
                    'Roughly chop all paste ingredients.',
                    'Using a food processor or mortar and pestle, grind the dry ingredients first.',
                    'Add wet ingredients (fish sauce, shrimp paste) and blend until a fine paste is achieved.',
                    'Fry the paste briefly in oil before adding coconut milk for the curry.',
                ],
            ],
            // User 20 (ID 119) - FREE
            [
                'recipeName' => 'Mashed Potatoes (Creamy)',
                'cuisineType' => 'American/European',
                'description' => 'The secret to perfectly smooth, lump-free, creamy mashed potatoes.',
                'ingredients' => [
                    ['name' => 'Russet potatoes', 'quantity' => '1 kg'],
                    ['name' => 'Milk or Cream (heated)', 'quantity' => '1/2 cup'],
                    ['name' => 'Butter', 'quantity' => '1/4 cup'],
                    ['name' => 'Salt', 'quantity' => 'to taste'],
                ],
                'procedures' => [
                    'Peel and cube potatoes; boil in salted water until fork-tender.',
                    'Drain well and return to the pot over low heat to dry them out briefly.',
                    'Mash or ricer the potatoes until smooth.',
                    'Gently fold in the heated butter and milk/cream.',
                ],
            ],
            // User 20 (ID 119) - PREMIUM
            [
                'recipeName' => 'Escargots à la Bourguignonne',
                'cuisineType' => 'French',
                'description' => 'Snails baked in garlic, parsley, and butter—a classic French appetizer.',
                'ingredients' => [
                    ['name' => 'Canned Escargots', 'quantity' => '24'],
                    ['name' => 'Unsalted butter, softened', 'quantity' => '1 cup'],
                    ['name' => 'Fresh parsley, minced', 'quantity' => '1/4 cup'],
                    ['name' => 'Garlic cloves, minced', 'quantity' => '4'],
                ],
                'procedures' => [
                    'Mix the butter, parsley, and garlic until fully combined (Bourguignonne Butter).',
                    'Place one snail in each indentation of an escargot dish.',
                    'Top each snail generously with the garlic butter.',
                    'Bake at 200°C for 8-10 minutes until the butter is sizzling.',
                ],
            ],
            // User 21 (ID 120) - FREE
            [
                'recipeName' => 'Avocado Toast (Basic)',
                'cuisineType' => 'Health/Breakfast',
                'description' => 'Simple, popular, and satisfying toast topped with mashed avocado.',
                'ingredients' => [
                    ['name' => 'Sourdough bread slice', 'quantity' => '1'],
                    ['name' => 'Avocado', 'quantity' => '1/2'],
                    ['name' => 'Flaky sea salt', 'quantity' => 'to taste'],
                    ['name' => 'Red pepper flakes (optional)', 'quantity' => 'pinch'],
                ],
                'procedures' => [
                    'Toast the slice of bread until golden and crisp.',
                    'Mash the avocado in a bowl with a fork (or slice).',
                    'Spread the avocado evenly on the toast.',
                    'Season generously with flaky salt and pepper flakes.',
                ],
            ],
            // User 21 (ID 120) - PREMIUM
            [
                'recipeName' => 'Sous Vide Rack of Lamb',
                'cuisineType' => 'Fine Dining',
                'description' => 'Precision cooking for perfectly cooked lamb, finished with a herb crust.',
                'ingredients' => [
                    ['name' => 'Rack of Lamb (frenched)', 'quantity' => '1'],
                    ['name' => 'Rosemary and Thyme', 'quantity' => '2 sprigs each'],
                    ['name' => 'Panko breadcrumbs', 'quantity' => '1/2 cup'],
                    ['name' => 'Dijon Mustard', 'quantity' => '2 tbsp'],
                ],
                'procedures' => [
                    'Season and vacuum seal the lamb with herbs and butter.',
                    'Cook sous vide at 54°C for 2 hours.',
                    'Sear quickly in a hot pan or torch to develop a crust.',
                    'Coat the seared lamb with mustard, dredge in breadcrumbs, and broil briefly.',
                ],
            ],
            // User 22 (ID 121) - FREE
            [
                'recipeName' => 'Lentil Soup (Vegetarian)',
                'cuisineType' => 'Mediterranean',
                'description' => 'A nourishing and hearty soup with brown lentils and vegetables.',
                'ingredients' => [
                    ['name' => 'Brown lentils', 'quantity' => '1 cup'],
                    ['name' => 'Vegetable broth', 'quantity' => '6 cups'],
                    ['name' => 'Carrots, celery, onion (diced)', 'quantity' => '1 cup each'],
                    ['name' => 'Bay leaf', 'quantity' => '1'],
                ],
                'procedures' => [
                    'Sauté the diced vegetables (mirepoix) until softened.',
                    'Add the lentils, vegetable broth, and bay leaf.',
                    'Bring to a boil, then simmer for 30-40 minutes until lentils are tender.',
                    'Remove bay leaf, season, and serve hot.',
                ],
            ],
            // User 22 (ID 121) - PREMIUM
            [
                'recipeName' => 'Chicken Galantine (De-boned & Stuffed)',
                'cuisineType' => 'Classic French',
                'description' => 'The art of de-boning a whole chicken and stuffing it with forcemeat before poaching.',
                'ingredients' => [
                    ['name' => 'Whole Chicken', 'quantity' => '1 (1.5kg)'],
                    ['name' => 'Ground Veal/Pork (forcemeat)', 'quantity' => '300g'],
                    ['name' => 'Pistachios and Cranberries', 'quantity' => '1/4 cup each'],
                    ['name' => 'Cognac', 'quantity' => '2 tbsp'],
                ],
                'procedures' => [
                    'Carefully de-bone the chicken, keeping the skin and wings intact.',
                    'Mix forcemeat with flavorings and stuff the chicken cavity.',
                    'Roll the chicken tightly into a cylinder using cheesecloth and tie it securely.',
                    'Poach gently in stock, then chill completely before slicing.',
                ],
            ],
            // User 23 (ID 122) - FREE
            [
                'recipeName' => 'Quesadillas (Cheese)',
                'cuisineType' => 'Mexican/Tex-Mex',
                'description' => 'Simple, cheesy, toasted tortillas perfect as a snack or light meal.',
                'ingredients' => [
                    ['name' => 'Flour Tortillas (large)', 'quantity' => '2'],
                    ['name' => 'Monterey Jack/Cheddar cheese (shredded)', 'quantity' => '1 cup'],
                ],
                'procedures' => [
                    'Place a tortilla in a dry skillet over medium heat.',
                    'Sprinkle half the cheese evenly over the tortilla.',
                    'Fold the tortilla in half and cook for 2-3 minutes per side.',
                    'Remove, slice into wedges, and serve with salsa/sour cream.',
                ],
            ],
            // User 23 (ID 122) - PREMIUM
            [
                'recipeName' => 'Butter Chicken with Naan (The Real Way)',
                'cuisineType' => 'Indian',
                'description' => 'A comprehensive lesson on creating the perfect smooth butter chicken sauce and pillowy naan bread.',
                'ingredients' => [
                    ['name' => 'Chicken breast (marinated)', 'quantity' => '500g'],
                    ['name' => 'Tomato Paste', 'quantity' => '1/4 cup'],
                    ['name' => 'Butter', 'quantity' => '1/2 cup'],
                    ['name' => 'Maida (All-purpose flour for Naan)', 'quantity' => '2 cups'],
                ],
                'procedures' => [
                    'Marinate and grill/sear chicken.',
                    'Cook the sauce base (onion, garlic, ginger, tomato) and blend until perfectly smooth.',
                    'Add butter, cream, and chicken; simmer.',
                    'Prepare and roll Naan dough; cook in a very hot skillet/Tandoor until puffed.',
                ],
            ],
            // User 24 (ID 123) - FREE
            [
                'recipeName' => 'Fruit Salad (Simple)',
                'cuisineType' => 'Health/Dessert',
                'description' => 'A light and refreshing mix of seasonal fresh fruits.',
                'ingredients' => [
                    ['name' => 'Strawberries (sliced)', 'quantity' => '1 cup'],
                    ['name' => 'Blueberries', 'quantity' => '1 cup'],
                    ['name' => 'Melon (cubed)', 'quantity' => '1 cup'],
                    ['name' => 'Orange juice', 'quantity' => '1 tbsp'],
                ],
                'procedures' => [
                    'Wash and chop all the fruits.',
                    'Gently toss all the fruits together in a large bowl.',
                    'Drizzle with a little orange juice or honey to prevent browning and add sweetness.',
                    'Chill for 10 minutes before serving.',
                ],
            ],
            // User 24 (ID 123) - PREMIUM
            [
                'recipeName' => 'Confit de Canard (Duck Leg Confit)',
                'cuisineType' => 'French (Gascony)',
                'description' => 'Duck legs slowly cured and cooked in their own fat until fall-off-the-bone tender.',
                'ingredients' => [
                    ['name' => 'Duck legs', 'quantity' => '4'],
                    ['name' => 'Duck fat', 'quantity' => '1 kg'],
                    ['name' => 'Coarse sea salt', 'quantity' => '4 tbsp'],
                    ['name' => 'Thyme sprigs', 'quantity' => '4'],
                ],
                'procedures' => [
                    'Cure the duck legs overnight with salt and seasonings.',
                    'Rinse off the salt, then place legs snugly in a pot.',
                    'Cover completely with rendered duck fat and slow-cook at 90°C for 6-8 hours.',
                    'Remove legs, store in the fat, and finish by crisping the skin in a hot pan before serving.',
                ],
            ],
            // User 25 (ID 124) - FREE
            [
                'recipeName' => 'Boiled Eggs (Perfect Hard-Boiled)',
                'cuisineType' => 'Breakfast',
                'description' => 'The foolproof method for perfectly cooked, easy-to-peel hard-boiled eggs.',
                'ingredients' => [
                    ['name' => 'Large Eggs', 'quantity' => '6'],
                    ['name' => 'Water', 'quantity' => 'to cover'],
                    ['name' => 'Ice', 'quantity' => '1 bowl'],
                ],
                'procedures' => [
                    'Place eggs in a saucepan and cover with cold water.',
                    'Bring water to a rolling boil, then immediately turn off the heat.',
                    'Cover the pan and let stand for exactly 10-12 minutes.',
                    'Transfer eggs immediately to an ice bath to stop cooking and cool rapidly for easy peeling.',
                ],
            ],
            // User 25 (ID 124) - PREMIUM
            [
                'recipeName' => 'Haggis (Traditional)',
                'cuisineType' => 'Scottish',
                'description' => 'The preparation of the classic Scottish dish made from sheep' . "'s offal, spices, and oats.",
                'ingredients' => [
                    ['name' => 'Sheep' . "'s pluck (liver, lungs, heart)", 'quantity' => '1 set'],
                    ['name' => 'Suet (sheep or beef)', 'quantity' => '250g'],
                    ['name' => 'Oatmeal (toasted)', 'quantity' => '200g'],
                    ['name' => 'Dried spices (nutmeg, pepper)', 'quantity' => 'to taste'],
                ],
                'procedures' => [
                    'Boil the pluck until tender; mince the meat.',
                    'Mix minced meat with suet, toasted oatmeal, and spices.',
                    'Stuff the mixture into a natural casing (traditionally a sheep' . "'s stomach).",
                    'Poach gently in water for 3 hours before serving.',
                ],
            ],
        ];
    }
}
