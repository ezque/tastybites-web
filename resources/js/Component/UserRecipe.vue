<template>
    <div class="w-full h-[95%] flex flex-col">
        <!-- Filter Buttons -->
        <div class="w-full h-20 flex flex-row items-center justify-center gap-10 font-['Poppins-Bold']">
            <button
                class="px-6 py-2 cursor-pointer border border-black rounded"
                :class="activeFilter === 'saved' ? 'bg-gray-300' : ''"
                @click="activeFilter = 'saved'"
            >
                <span>Saved Recipe</span>
            </button>

            <button
                class="px-6 py-2 cursor-pointer border border-black rounded"
                :class="activeFilter === 'purchased' ? 'bg-gray-300' : ''"
                @click="activeFilter = 'purchased'"
            >
                <span>Purchased Recipe</span>
            </button>

            <button
                class="px-6 py-2 cursor-pointer border border-black rounded"
                :class="activeFilter === 'hidden' ? 'bg-gray-300' : ''"
                @click="activeFilter = 'hidden'"
            >
                <span>Hidden Recipe</span>

            </button>

        </div>

        <div
            class="w-full min-h-[80%] max-h-[80%] flex flex-wrap justify-center gap-20 p-5 overflow-auto"
        >
            <RecipeCard
                v-for="recipe in filteredRecipes"
                :key="recipe.id"
                :recipeCardDetail="recipe"
                @navigate="(component, data) => emit('navigate', component, data)"
            />
        </div>
    </div>
</template>

<script setup>
    import { ref, computed } from "vue";
    import RecipeCard from "@/Component/RecipeCard.vue";

    const props = defineProps({
        recipeCardDetails: Array,
    });

    const activeFilter = ref("saved");
    const emit = defineEmits(["navigate"]);

    const filteredRecipes = computed(() => {
        if (activeFilter.value === "saved") {
            return props.recipeCardDetails.filter((r) => r.is_saved);
        }  else if (activeFilter.value === "purchased") {
            return props.recipeCardDetails.filter((r) => r.is_purchased);
        } else if (activeFilter.value === "hidden") {
            return props.recipeCardDetails.filter(r => r.is_hidden === '1');
        }
        return props.recipeCardDetails;
    });
</script>
