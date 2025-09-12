<template>
    <div class="main-body">
        <Header :user="user" @navigate="setActiveComponent"/>
        <div class="main-container">
            <Sidebar :user="user" @navigate="setActiveComponent" :active="activeComponent"/>
            <Home
                v-if="activeComponent === 'Home'"
                :recipeCardDetails="recipeCardDetails"
                @navigate="handleNavigation"
            />
            <Income
                v-if="activeComponent === 'ChefIncome'"
                :purchases="purchases"
            />
            <Recipes
                v-if="activeComponent === 'Recipes'"
                @navigate="handleNavigation"
                :recipeCardDetails="recipeCardDetails"
                :user="user"
            />
            <AddRecipe v-if="activeComponent === 'AddRecipe'" @navigate="setActiveComponent" :active="activeComponent"/>
            <Profile v-if="activeComponent === 'Profile'"/>
            <RecipeDetails
                v-if="activeComponent === 'RecipeDetails'"
                @navigate="setActiveComponent"
                :recipe="selectedRecipe"
                :user="user"
                @back="back"
            />
            <Report
                v-if="activeComponent === 'Report'"
            />
            <UserSettings
                v-if="activeComponent === 'Settings'"
                @navigate="handleNavigation"
                :user="user"
                :recipeCardDetails="recipeCardDetails"
            />
        </div>
    </div>
</template>

<script setup>
    import  { Inertia} from "@inertiajs/inertia";
    import {computed, ref} from "vue";
    import Header from '../../Component/Header.vue';
    import Sidebar from "@/Component/Sidebar.vue";

    import Home from "@/Component/Home.vue";
    import Income from "@/Component/Chef/Income.vue";
    import Recipes from "@/Component/Recipes.vue";
    import AddRecipe from "@/Component/Chef/AddRecipe.vue";
    import RecipeDetails from "@/Component/RecipeDetails.vue";
    import Profile from "@/Component/Profile.vue";
    import Report from "@/Component/Chef/Report.vue";
    import UserSettings from "@/Component/UserSettings.vue";



    const props = defineProps({
        user: Object,
        recipeCardDetails: Array,
        recipeAllDetails: Object,
        purchases: Array,
    })

    const isChef = computed(() => props.user.role === 'chef');

    const activeComponent = ref(isChef.value ? 'Home' : 'ChefIncome');

    const selectedRecipe = ref(null);

    const setActiveComponent = (componentName) => {
        activeComponent.value = componentName;
    }
    const handleNavigation = (componentName, recipeData = null) => {
        if (componentName === 'AddRecipe') {
            selectedRecipe.value = null;
            activeComponent.value = 'AddRecipe';
            return;
        }

        if (componentName === 'RecipeDetails' && recipeData) {
            const fullDetails = props.recipeAllDetails.find(r => r.id === recipeData.id);
            selectedRecipe.value = fullDetails;
        }

        activeComponent.value = componentName;
    };


    // this is your navigation stack (history)
    const historyStack = ref([]);
    const isUser = computed(() => props.user.role === 'chef');

    const back = () => {
        if (historyStack.value.length > 0) {
            activeComponent.value = historyStack.value.pop();
        } else {
            activeComponent.value = isUser.value ? 'Home' : null;
        }
    };

</script>
<style scoped>
    .main-body {
        width: 100%;
        height: 100vh;
        background-color: gray;
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
