<template>
    <div class="recipe-details-main-container">
        <!-- Free or owned recipe -->
        <div class="details" v-if="recipeIsFree || ownedRecipe">
            <h3>ID: {{ recipe.id }}</h3>
            <h2>{{ recipe.recipeName }}</h2>
            <p>Cuisine: {{ recipe.cuisineType }}</p>
            <p>Chef: @{{ recipe.user.user_info.userName }}</p>

            <!-- Ingredients -->
            <div class="ingredients" v-if="recipe.ingredient && recipe.ingredient.length">
                <h3>Ingredients</h3>
                <ul>
                    <li v-for="(item, index) in recipe.ingredient" :key="index">
                        {{ item.name }} - {{ item.quantity }}
                    </li>
                </ul>
            </div>

            <!-- Procedures -->
            <div class="procedures" v-if="recipe.procedure && recipe.procedure.length">
                <h3>Procedure</h3>
                <ol>
                    <li v-for="(step, index) in recipe.procedure" :key="index">
                        {{ step.stepDescription }}
                    </li>
                </ol>
            </div>
        </div>

        <!-- Premium but not owned -->
        <div class="not-buy-container" v-else>
            <h2>Premium Recipe</h2>
            <p>Please purchase to unlock the full details.</p>
        </div>

        <!-- Pending purchase -->
        <div
            class="pending-container"
            v-if="recipe.purchase && recipe.purchase.status === 'pending'"
        >
            <p>Your purchase is pending confirmation...</p>
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
    recipe: Object,
});

const recipeIsFree = computed(() => props.recipe.is_free === "free");
const ownedRecipe = computed(() => {
    return props.recipe.purchase?.status === "confirmed";
});

const emit = defineEmits(["navigate"]);
</script>

<style scoped>
    .recipe-details-main-container {
        width: 100%;
    }

</style>
