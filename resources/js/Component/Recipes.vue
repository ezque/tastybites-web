<template>
    <div class="w-full h-full flex flex-col">
        <!-- Top Buttons -->
        <div class="w-full h-[10%] flex flex-row items-center justify-center gap-5">
            <button
                :class="[
                    'w-[140px] h-[40px] rounded-[20px] cursor-pointer text-[13px] font-[Poppins-Bold] transition-transform duration-300',
                    activeFilter === 'all'
                    ? 'bg-[#E0E7FF] text-[#435F77] shadow-[4px_4px_12px_#AFADAD] border-r border-[#AFADAD]'
                    : 'bg-[#435F77] text-white hover:scale-110'
                ]"
                @click="activeFilter = 'all'"
                >
                ALL RECIPE
            </button>

            <button
                :class="[
                    'w-[140px] h-[40px] rounded-[20px] cursor-pointer text-[13px] font-[Poppins-Bold] transition-transform duration-300',
                    activeFilter === 'premium'
                    ? 'bg-[#E0E7FF] text-[#435F77] shadow-[4px_4px_12px_#AFADAD] border-r border-[#AFADAD]'
                    : 'bg-[#435F77] text-white hover:scale-110'
                ]"
                @click="activeFilter = 'premium'"
                >
                PREMIUM
            </button>

            <button
                :class="[
                    'w-[140px] h-[40px] rounded-[20px] cursor-pointer text-[13px] font-[Poppins-Bold] transition-transform duration-300',
                    activeFilter === 'free'
                    ? 'bg-[#E0E7FF] text-[#435F77] shadow-[4px_4px_12px_#AFADAD] border-r border-[#AFADAD]'
                    : 'bg-[#435F77] text-white hover:scale-110'
                ]"
                @click="activeFilter = 'free'"
                >
                FREE
            </button>
        </div>

        <!-- Main Body -->
        <div
            class="w-full h-[690px] flex flex-wrap flex-row gap-[90px] items-center justify-center py-2 overflow-auto scrollbar-thin scrollbar-thumb-[#AFADAD] scrollbar-track-transparent "
        >
            <!-- Add Recipe Button -->
            <button
                class="w-[220px] h-[270px] rounded-[20px] flex flex-col items-center justify-center gap-2 font-[Poppins-Bold] italic text-[#31485B] font-extrabold text-base bg-[#E0E7FF] border-none shadow-[3px_6px_6px_#AFADAD] cursor-pointer"
                @click="emit('navigate', 'AddRecipe')"
                v-if="props.user.role === 'chef' && props.user.status === 'active' && activeFilter === 'all'"
            >
                Add Recipe
                <img src="/public/images/Button-icon/add.png" class="w-[150px] h-auto" />
            </button>

            <!-- Recipe Cards -->
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
    import {computed, ref, onMounted, onBeforeUnmount} from "vue";
    import RecipeCard from "@/Component/RecipeCard.vue";
    import Footer from "@/Component/Footer.vue";
    const emit = defineEmits(['navigate'])

    const activeFilter = ref("all")

    const props = defineProps({
        user: Object,
        recipeCardDetails: Array
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
    const userRecipes = computed(() => {
        return props.recipeCardDetails.filter(
            recipe => recipe.userID === props.user.id
        )
    })
    const baseRecipes = computed(() => {
        if (props.user.role === "admin") {
            return props.recipeCardDetails
        }
        return userRecipes.value
    })

    const filteredRecipes = computed(() => {
        return baseRecipes.value.filter((recipe) => {
            // always exclude pending recipes first
            if (recipe.status === "pending") return false;

            // then apply activeFilter logic
            switch (activeFilter.value) {
                case "premium":
                    return recipe.is_free === "premium";
                case "free":
                    return recipe.is_free === "free";
                default:
                    return true;
            }
        });
    });



</script>


