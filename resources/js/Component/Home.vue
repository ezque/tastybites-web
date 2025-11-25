<template>
    <div class="w-full h-[85vh] flex flex-col overflow-y-auto overflow-x-hidden gap-3">
        <!-- HEADER -->
        <div class="w-full min-h-[400px] flex items-end justify-center">
            <div class="w-3/4 h-[60%] bg-indigo-100 rounded-2xl flex items-center relative">
                <p class="font-['Rouge_Script'] text-[6.5em] ml-10">Flavor Made Easy</p>
                <img
                    src="/public/images/tastybites_plate.png"
                    alt="img"
                    class="w-[30%] absolute right-[100px] mb-[135px]"
                />
            </div>
        </div>

        <!-- ====================== FILTER SECTION ====================== -->
        <div class="w-full h-auto flex px-20 items-center justify-start flex-shrink-0 gap-6">
            <!-- Dropdown Filter -->
            <div class="w-[20%] h-full flex items-start px-4">
                <select
                    v-model="selectedFilter"
                    class="w-[80%] h-[45px] bg-white border border-indigo-100 rounded-lg
                 text-sm font-['Poppins-Bold'] text-gray-700 px-0
                 focus:outline-none focus:ring-2 focus:ring-indigo-400
                 hover:border-indigo-400 transition-all duration-200 shadow-md"
                >
                    <option value="">Filter Recipes</option>
                    <option value="free">Free</option>
                    <option value="premium">Premium</option>
                </select>
            </div>

            <!-- Horizontal Scroll Price Slider -->
            <div class="flex-1 flex items-center">
                <input
                    type="range"
                    min="0"
                    :max="maxPrice"
                    step="1"
                    v-model="priceFrom"
                    class="w-[20%] h-2 rounded-lg accent-indigo-500 cursor-pointer ml-auto"
                />
                <div class="w-[60px] text-center font-bold text-gray-700">
                    ₱{{ priceFrom }}
                </div>
            </div>
        </div>

        <!-- ====================== CONTENT ====================== -->
        <div v-if="loading" class="flex justify-center items-center h-[50vh]">
            <div class="w-12 h-12 border-4 border-indigo-400 border-t-transparent rounded-full animate-spin"></div>
        </div>

        <template v-else>
            <template v-if="!props.searchQuery && !selectedFilter && priceFrom <= 0">
                <!-- Popular Recipes -->
                <h2 class="font-[Poppins-Bold] ml-22 text-xl mb-0 p-0 mt-5">Popular Recipes</h2>
                <div
                    class="flex flex-wrap flex-row gap-[70px] items-center justify-center pt-4 pb-3 w-full shadow-[0_10px_10px_-5px_rgba(0,0,0,0.3)] mt-2"
                >
                    <RecipeCard
                        v-for="(recipeCardDetail, index) in topLiked"
                        :key="recipeCardDetail.id"
                        :recipeCardDetail="recipeCardDetail"
                        :index="index"
                        @navigate="(component, data) => emit('navigate', component, data)"
                        :activeMenuId="activeMenuId"
                        @toggle-menu="handleToggleMenu"
                    />
                </div>

                <!-- Most Purchased Recipes -->
                <h2 class="font-[Poppins-Bold] ml-22 text-xl mb-0 p-0 mt-5">Most Purchased Recipes</h2>
                <div
                    class="flex flex-wrap flex-row gap-[70px] items-center justify-center pt-4 pb-3 w-full shadow-[0_10px_10px_-5px_rgba(0,0,0,0.3)]"
                >
                    <RecipeCard
                        v-for="(recipeCardDetail, index) in topPurchased"
                        :key="recipeCardDetail.id"
                        :recipeCardDetail="recipeCardDetail"
                        :index="index"
                        @navigate="(component, data) => emit('navigate', component, data)"
                        :activeMenuId="activeMenuId"
                        @toggle-menu="handleToggleMenu"
                    />
                </div>
            </template>

            <!-- All Recipes -->
            <div class="flex flex-wrap flex-row gap-[70px] items-center justify-center pt-7 pb-3 w-full">
                <RecipeCard
                    v-for="(recipeCardDetail, index) in filteredRecipes"
                    :key="recipeCardDetail.id"
                    :recipeCardDetail="recipeCardDetail"
                    :index="index"
                    @navigate="(component, data) => emit('navigate', component, data)"
                    :activeMenuId="activeMenuId"
                    @toggle-menu="handleToggleMenu"
                />
            </div>
        </template>

        <Footer />
    </div>
</template>

<script setup>
    import { computed, ref, onMounted, onBeforeUnmount } from "vue";
    import RecipeCard from "@/Component/RecipeCard.vue";
    import Footer from "@/Component/Footer.vue";
    import axios from "axios";

    const emit = defineEmits(['navigate']);
    const props = defineProps({ searchQuery: String });

    const activeMenuId = ref(null);
    const selectedFilter = ref('');
    const recipeCardDetails = ref({ all: [], topLiked: [], topPurchased: [] });
    const loading = ref(true);

    const allRecipes = computed(() => recipeCardDetails.value.all || []);
    const topLiked = computed(() => recipeCardDetails.value.topLiked || []);
    const topPurchased = computed(() => recipeCardDetails.value.topPurchased || []);

    // Compute max price for slider
    const maxPrice = computed(() => {
        if (!allRecipes.value.length) return 400; // default max
        return Math.max(...allRecipes.value.map(r => Number(r.price)));
    });

    const priceFrom = ref(0);

    // Fetch recipes
    const fetchRecipe = async () => {
        loading.value = true;
        try {
            const { data } = await axios.get('/all-recipes', {
                headers: { 'Content-Type': 'multipart/form-data' }
            });
            recipeCardDetails.value = data;
        } catch (error) {
            console.error(error);
        } finally {
            loading.value = false;
        }
    };

    // Toggle menu for RecipeCard
    function handleToggleMenu(id) {
        activeMenuId.value = activeMenuId.value === id ? null : id;
    }

    // Click outside to close dropdowns
    function handleClickOutside(e) {
        const menuButtons = document.querySelectorAll('[data-menu-button]');
        const dropdowns = document.querySelectorAll('[data-menu-dropdown]');
        const clickedInsideButton = Array.from(menuButtons).some(btn => btn.contains(e.target));
        const clickedInsideDropdown = Array.from(dropdowns).some(drop => drop.contains(e.target));
        if (!clickedInsideButton && !clickedInsideDropdown) {
            activeMenuId.value = null;
        }
    }

    // Compute IDs already displayed in topLiked or topPurchased
    const topRecipeIDs = computed(() => {
        const likedIDs = topLiked.value.map(r => r.id);
        const purchasedIDs = topPurchased.value.map(r => r.id);
        return [...new Set([...likedIDs, ...purchasedIDs])];
    });

    // Filter all recipes for "All Recipes" section
    const filteredRecipes = computed(() => {
        let filteredByStatus = allRecipes.value.filter(
            r => r.status !== 'pending' && r.is_hidden !== '1'
        );

        // Search filter
        if (props.searchQuery) {
            filteredByStatus = filteredByStatus.filter(recipe =>
                recipe.recipeName.toLowerCase().includes(props.searchQuery.toLowerCase())
            );
            // When searching, do NOT exclude top recipes
        } else {
            // Exclude recipes already in topLiked or topPurchased only if no search
            filteredByStatus = filteredByStatus.filter(
                r => !topRecipeIDs.value.includes(r.id)
            );
        }

        // Price filter
        if (priceFrom.value != null) {
            filteredByStatus = filteredByStatus.filter(recipe => recipe.price >= priceFrom.value);
        }

        // Status filter (free/premium)
        if (selectedFilter.value) {
            filteredByStatus = filteredByStatus.filter(
                recipe => recipe.is_free === selectedFilter.value
            );
        }

        return filteredByStatus;
    });


    // Lifecycle hooks
    onMounted(() => {
        fetchRecipe();
        document.addEventListener('click', handleClickOutside);
    });

    onBeforeUnmount(() => {
        document.removeEventListener('click', handleClickOutside);
    });
</script>
