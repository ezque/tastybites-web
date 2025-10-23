<template>
    <div class="w-full h-[95%] flex flex-col">
        <!-- Filter Buttons -->
        <div class="w-full h-20 flex flex-row items-center justify-center gap-10 font-['Poppins-Bold']">
            <button
                class="w-[170px] h-[40px] rounded-[20px] cursor-pointer text-[13px] font-[Poppins-Bold] transition-transform duration-300"
                :class="activeFilter === 'saved' ? 'bg-[#E0E7FF] text-[#435F77] shadow-[4px_4px_12px_#AFADAD] border-r border-[#AFADAD]' : 'bg-[#435F77] text-white hover:scale-110'"
                @click="activeFilter = 'saved'"
            >
                <span>SAVED</span>
            </button>

            <button
                class="w-[170px] h-[40px] rounded-[20px] cursor-pointer text-[13px] font-[Poppins-Bold] transition-transform duration-300"
                :class="activeFilter === 'purchased' ? 'bg-[#E0E7FF] text-[#435F77] shadow-[4px_4px_12px_#AFADAD] border-r border-[#AFADAD]' : 'bg-[#435F77] text-white hover:scale-110'"
                @click="activeFilter = 'purchased'"
            >
                <span>PURCHASED</span>
            </button>

            <button
                class="w-[170px] h-[40px] rounded-[20px] cursor-pointer text-[13px] font-[Poppins-Bold] transition-transform duration-300"
                :class="activeFilter === 'hidden' ? 'bg-[#E0E7FF] text-[#435F77] shadow-[4px_4px_12px_#AFADAD] border-r border-[#AFADAD]' : 'bg-[#435F77] text-white hover:scale-110'"
                @click="activeFilter = 'hidden'"
            >
                <span>HIDDEN</span>

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
