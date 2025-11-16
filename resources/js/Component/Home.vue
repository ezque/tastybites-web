<template>
    <div class="w-full h-[85vh] flex flex-col overflow-y-auto overflow-x-hidden gap-3">
        <!-- HEADER -->
        <div class="w-full min-h-[400px] flex items-end justify-center">
            <div class="w-3/4 h-[60%] bg-indigo-100 rounded-2xl flex items-center relative">
                <p class="font-['Rouge_Script'] text-[6.5em] ml-10">
                    Flavor Made Easy
                </p>
                <img
                    src="/public/images/tastybites_plate.png"
                    alt="img"
                    class="w-[30%] absolute right-[100px] mb-[135px]"
                />
            </div>
        </div>
        <!-- ====================== FILTER SECTION ====================== -->
        <div class="w-full h-auto flex px-20 items-center justify-start flex-shrink-0">
            <!-- ====================== FILTER Card ====================== -->
            <div class="w-[20%] h-full flex items-start px-4">
                <select
                    class="w-full h-[45px] bg-white border border-indigo-100 rounded-lg
                    text-[14px] font-medium text-gray-700 px-0
                    focus:outline-none focus:ring-2 focus:ring-indigo-400
                    hover:border-indigo-400 transition-all duration-200 shadow-md">
                    <option>Filter Recipes</option>
                    <option>Most Liked</option>
                    <option>Most Purchased</option>
                    <option>Free</option>
                    <option>Premium</option>
                </select>

            </div>

        </div>
        <!-- ====================== RECIPE CARDS ====================== -->
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

        <Footer />
    </div>
</template>


<script setup>
    import { computed, ref, onMounted, onBeforeUnmount  } from "vue";
    import RecipeCard from "@/Component/RecipeCard.vue";
    import Footer from "@/Component/Footer.vue";

    const emit = defineEmits(['navigate'])

    const props = defineProps({
        recipeCardDetails: Array,
        searchQuery: String
    })

    const activeMenuId = ref(null)
    function handleToggleMenu(id) {
        activeMenuId.value = activeMenuId.value === id ? null : id
    }
    function handleClickOutside(e) {
        const menuButtons = document.querySelectorAll('[data-menu-button]')
        const dropdowns = document.querySelectorAll('[data-menu-dropdown]')

        const clickedInsideButton = Array.from(menuButtons).some(btn => btn.contains(e.target))
        const clickedInsideDropdown = Array.from(dropdowns).some(drop => drop.contains(e.target))

        if (!clickedInsideButton && !clickedInsideDropdown) {
            activeMenuId.value = null
        }
    }

    onMounted(() => {
        document.addEventListener('click', handleClickOutside)
    })

    onBeforeUnmount(() => {
        document.removeEventListener('click', handleClickOutside)
    })

    const filteredRecipes = computed(() => {
        const filteredByStatus = props.recipeCardDetails.filter(r => r.status !== 'pending' && r.is_hidden !== '1');

        if (!props.searchQuery) return filteredByStatus;

        return filteredByStatus.filter(recipe =>
            recipe.recipeName.toLowerCase().includes(props.searchQuery.toLowerCase())
        );
    });


</script>
