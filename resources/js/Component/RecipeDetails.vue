<template>
    <div class="recipe-details-main-container">
        <h2>Recipe Details</h2>
        <div class="details">
            <h3>ID: {{ recipe.id }}</h3>
            <h2>{{ recipe.recipeName }}</h2>
            <p>Cuisine: {{ recipe.cuisineType }}</p>
            <p>Chef: @{{ recipe.user.user_info.userName }}</p>
        </div>
        <div class="not-buy-container">

        </div>
        <div class="pending-container">

        </div>
    </div>
</template>

<script setup>
    const props = defineProps({
        recipe: Object
    })
    const emit = defineEmits(['navigate'])
</script>

<style scoped>
    .recipe-details-main-container {
        width: 100%;
    }

</style>
