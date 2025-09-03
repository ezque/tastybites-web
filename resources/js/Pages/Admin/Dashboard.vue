<template>
    <div class="main-body">
        <Header :user="user"/>
        <div class="main-container">
            <Sidebar :user="user" @navigate="setActiveComponent" :active="activeComponent"/>
            <Home v-if="activeComponent === 'AdminHome'" />
            <Income v-if="activeComponent === 'AdminIncome'" />
            <Chefs
                v-if="activeComponent === 'AdminChefs'"
                :chefs="chefs"
                @navigate="handleNavigation"
            />
            <ChefInfo
                v-if="activeComponent === 'ChefInfo'"
                :chef="selectedChef"
                :recipeCardDetails="recipeCardDetails"
                @navigate="setActiveComponent"
            />
            <Users v-if="activeComponent === 'Users'" :usersInfo="usersInfo"/>
            <Recipes
                v-if="activeComponent === 'Recipes'"
                @navigate="handleNavigation"
                :recipeCardDetails="recipeCardDetails"
                :user="user"
            />
            <RecipeDetails
                v-if="activeComponent === 'RecipeDetails'"
                @navigate="setActiveComponent"
                :recipe="selectedRecipe"
            />
        </div>
    </div>

</template>

<script setup>
    import {computed, ref} from 'vue';
    import Header from '../../Component/Header.vue';
    import Sidebar from "@/Component/Sidebar.vue";
    import Home from "@/Component/Admin/Home.vue";
    import Income from "@/Component/Admin/Income.vue";
    import Chefs from "@/Component/Admin/Chefs.vue";
    import Users from "@/Component/Admin/Users.vue";
    import Recipes from "@/Component/Recipes.vue";
    import RecipeDetails from "@/Component/RecipeDetails.vue";
    import ChefInfo from "@/Component/Admin/ChefInfo.vue";

    const props = defineProps({
        user: Object,
        chefs: Array,
        recipeCardDetails: Array,
        usersInfo: Array
    })

    const isAdmin = computed(() => props.user.role === 'admin');

    const activeComponent = ref(isAdmin.value ? 'AdminHome' : 'AdminIncome');
    const selectedRecipe = ref(null);
    const selectedChef = ref(null)
    const setActiveComponent = (componentName) => {
        activeComponent.value = componentName;
    }
    const handleNavigation = (componentName, data) => {
        if (activeComponent.value) {
            // optional: implement historyStack only if you declared it
            // historyStack.value.push(activeComponent.value);
        }

        if (componentName === "ChefInfo") {
            selectedChef.value = data;
            console.log(data);
        }

        if (componentName === "RecipeDetails") {
            const fullDetails = props.recipeAllDetails.find(r => r.id === data.id);
            selectedRecipe.value = fullDetails;
        }

        activeComponent.value = componentName;
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

