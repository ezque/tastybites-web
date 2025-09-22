<template>
    <div class="main-body">
        <Header :user="user" @navigate="setActiveComponent" :getNotification="getNotification"/>
        <div class="main-container">
            <Sidebar :user="user" @navigate="setActiveComponent" :active="activeComponent"/>
            <Home v-if="activeComponent === 'AdminHome'" />
            <Income
                v-if="activeComponent === 'AdminIncome'"
                :getRecipeDetailsAdmin="getRecipeDetailsAdmin"
            />
            <Chefs
                v-if="activeComponent === 'AdminChefs'"
                :chefs="chefs"
                :user="user"
                @navigate="handleNavigation"
            />
            <ChefInfo
                v-if="activeComponent === 'ChefInfo'"
                :chef="selectedChef"
                :recipeCardDetails="recipeCardDetails"
                @navigate="handleNavigation"
                @recipeNavigate="goToRecipeDetails"
                :user="user"
            />

            <Users
                v-if="activeComponent === 'Users'"
                :usersInfo="usersInfo"
            />
            <Recipes
                v-if="activeComponent === 'Recipes'"
                @navigate="handleNavigation"
                :recipeCardDetails="recipeCardDetails"
                :user="user"
            />
            <RecipeDetails
                v-if="activeComponent === 'RecipeDetails'"
                :recipe="selectedRecipe"
                :user="user"
                @back="back"
            />
            <AdminSettings
                v-if="activeComponent === 'Settings'"
                :user="user"
            />
            <Notification
                v-if="activeComponent === 'Notification'"
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
    import AdminSettings from "@/Component/Admin/AdminSettings.vue";
    import Notification from "@/Component/Notification.vue";

    const props = defineProps({
        user: Object,
        chefs: Array,
        recipeCardDetails: Array,
        recipeAllDetails: Object,
        usersInfo: Array,
        getRecipeDetailsAdmin: Array,
        getNotification: Array,
    })

    if (props.getRecipeDetailsAdmin.length > 0) {
        console.log(props.getRecipeDetailsAdmin[0].id)
    } else {
        console.log('No pending premium recipes found')
    }


    const isAdmin = computed(() => props.user.role === 'admin');



    const activeComponent = ref(isAdmin.value ? 'AdminHome' : 'AdminIncome');
    const selectedRecipe = ref(null);
    const selectedChef = ref(null)
    const setActiveComponent = (componentName) => {
        activeComponent.value = componentName;
    }
    const handleNavigation = (componentName, data = null) => {
        if (componentName === "ChefInfo") {
            selectedChef.value = data;
            activeComponent.value = "ChefInfo";
        } else {
            activeComponent.value = componentName;
        }
    };
    const goToRecipeDetails = (recipeData) => {
        if (!recipeData) {
            console.warn("No recipe passed");
            return;
        }

        const fullDetails = props.recipeAllDetails.find(r => r.id === recipeData.id);
        selectedRecipe.value = fullDetails ?? recipeData;

        activeComponent.value = "RecipeDetails";
    };





    // const viewChefOwnedRecipe
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

