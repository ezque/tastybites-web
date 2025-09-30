<template>
    <div class="main-container-recipe">
        <div class="top-button-container">
            <button 
                :class="{ active: activeFilter === 'all' }" 
                @click="activeFilter = 'all'">
                ALL RECIPE
            </button>

            <button 
                :class="{ active: activeFilter === 'premium' }" 
                @click="activeFilter = 'premium'">
                PREMIUM
            </button>

            <button 
                :class="{ active: activeFilter === 'free' }" 
                @click="activeFilter = 'free'">
                FREE
            </button>
        </div>
        <div class="main-body">
            <button
                class="add-button"
                @click="emit('navigate', 'AddRecipe')"
                v-if="props.user.role === 'chef' && props.user.status === 'active' && activeFilter  === 'all'"
            >
                Add Recipe
                <img src="/public/images/Button-icon/add.png"/>
            </button>
            <RecipeCard
                v-for="(recipeCardDetail, index) in filteredRecipes"
                :key="recipeCardDetail.id"
                :recipeCardDetail="recipeCardDetail"
                :index="index"
                @navigate="(component, data) => emit('navigate', component, data)"
            />


        </div>
        <Footer/>
    </div>
</template>

<script setup>
    import {computed, ref} from "vue";
    import RecipeCard from "@/Component/RecipeCard.vue";
    import Footer from "@/Component/Footer.vue";
    const emit = defineEmits(['navigate'])

    const activeFilter = ref("all")

    const props = defineProps({
        user: Object,
        recipeCardDetails: Array
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
        width: 140px;
        height: 40px;
        border: none;
        border-radius: 20px;
        background: #435F77;
        color: white;
        cursor: pointer;
        font-size: 13px;
        font-family: 'Poppins-Bold';
    }
    .top-button-container button.active {
        background: #E0E7FF;
        color: #435F77;
        box-shadow: 4px 4px 12px #AFADAD;
        border-right: #AFADAD solid 1px;
    }
    .top-button-container button:hover {
        transform: scale(1.1);
    }
    .main-body {
        width: 100%;
        flex-wrap: wrap;
        flex-direction: row;
        display: flex;
        gap: 90px;
        align-items: center;
        justify-content: center;
        padding-top: 10px;
        padding-bottom: 10px;
        max-height: 73%;
        overflow: auto;
        scrollbar-width: thin;
        scrollbar-color: #AFADAD transparent;
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
        font-family: 'Poppins-Bold';
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

</style>

