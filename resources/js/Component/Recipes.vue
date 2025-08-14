<template>
    <div class="main-container-recipe">
        <div class="top-button-container">
            <button>
                All Recipe
            </button>
            <button>
                Premium
            </button>
            <button>
                Free
            </button>
            <button>
                Most Liked
            </button>
        </div>
        <div class="main-body">
            <button
                class="add-button"
                @click="emit('navigate', 'AddRecipe')"
                v-if="props.user.role === 'chef' && props.user.status === 'active'"
            >
                Add Recipe
                <img src="/public/images/Button-icon/add.png"/>
            </button>
            <RecipeCard
                v-for="(recipe, index) in recipes"
                :key="recipe.id"
                :recipe="recipe"
                :index="index"
                @navigate="(component, data) => emit('navigate', component, data)"
            />


        </div>
        <div class="footer">
            <p>© 2025 Tastybites. All rights reserved.</p>
        </div>

    </div>
</template>

<script setup>
    import {computed} from "vue";
    import RecipeCard from "@/Component/RecipeCard.vue";
    const emit = defineEmits(['navigate'])

    const props = defineProps({
        user: Object,
        recipes: Array
    })

</script>

<style scoped>
    .main-container-recipe {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .top-button-container {
        width: 100%;
        height: 10%;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
        gap: 20px;
    }
    .top-button-container button {
        width: 120px;
        height: 40px;
        background-color: #435F77;
        border: none;
        border-radius: 10px;
        color: white;
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
        font-style: italic;
    }
    .main-body {
        width: 100%;
        flex-wrap: wrap;
        flex-direction: row;
        display: flex;
        padding-left: 3%;
        padding-right: 5%;
        gap: 70px;
        align-items: flex-start;
        padding-top: 10px;
        padding-bottom: 10px;
        max-height: 73%;
        overflow: auto;
    }
    .add-button {
        width: 220px;
        height: 270px;
        cursor: pointer;
        border-radius: 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 10px;
        font-family: 'Poppins', sans-serif;
        font-style: italic;
        color: #31485B;
        font-weight: bolder;
        font-size: 1em;
        background-color: #E0E7FF;
        border: none;
        box-shadow: 3px 6px 6px #AFADAD;

    }
    .add-button img {
        width: 150px;
        height: auto;
    }
    .footer {
        width: 100%;
        height: 7%;
        display: flex;
        align-items: start;
        justify-content: center;
        position: fixed;
        bottom: -20px;
    }
    .footer p {
        margin-right: 10%;
    }
</style>

