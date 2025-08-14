<template>
    <div class="main-body">
        <Header :user="user"/>
        <div class="main-container">
            <Sidebar :user="user" @navigate="setActiveComponent" :active="activeComponent"/>
            <Home v-if="activeComponent === 'Home'" @navigate="handleNavigation" :recipes="recipes" />
            <RecipeDetails
                v-if="activeComponent === 'RecipeDetails'"
                @navigate="setActiveComponent"
                :recipe="selectedRecipe"
            />

        </div>

    </div>

</template>

<script setup>
    import  { Inertia} from "@inertiajs/inertia";
    import Header from '../../Component/Header.vue';
    import Sidebar from '../../Component/Sidebar.vue';
    import Home from "@/Component/Home.vue";
    import RecipeDetails from "@/Component/RecipeDetails.vue";

    import {computed, ref} from "vue";

    const props = defineProps({
        user: Object,
        recipes: Array
    })

    const isUser = computed(() => props.user.role === 'user');
    const activeComponent = ref(isUser.value ? 'Home' : null);
    const selectedRecipe = ref(null);


    const setActiveComponent = (componentName) => {
        activeComponent.value = componentName;
    }
    const handleNavigation = (componentName, recipeData) => {
        selectedRecipe.value = recipeData;
        activeComponent.value = componentName;
    }

</script>
<style scoped>
    .main-body {
        width: 100%;
        height: 100vh;
        display: flex;
        overflow: hidden;
        flex-direction: column;
    }
    .main-container {
        width: 100%;
        height: 100%;
        display: flex;
        background-color: white;
        flex-direction: row;
    }
</style>
