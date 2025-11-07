<template>
    <div class="w-full h-screen bg-gray-100 flex flex-col overflow-hidden">
        <Header
            :user="user"
            @navigate="setActiveComponent"
            :getNotification="getNotification"
        />
        <div class="w-full h-full flex flex-row bg-white">
            <Sidebar
                :user="user"
                @navigate="setActiveComponent"
                :active="activeComponent"
            />

            <Home
                v-if="activeComponent === 'AdminHome'"
                :adminTotalIncome="adminTotalIncome"
                :totalCountsUsers="totalCountsUsers"
                :getTotalRecipeCounts="getTotalRecipeCounts"
                @navigate="setActiveComponent"
                class="flex-1 overflow-y-auto"
            />
            <Income
                v-if="activeComponent === 'AdminIncome'"
                :getRecipeDetailsAdmin="getRecipeDetailsAdmin"
                class="flex-1 overflow-y-auto"
            />
            <Chefs
                v-if="activeComponent === 'AdminChefs'"
                :chefs="chefs"
                :user="user"
                @navigate="handleNavigation"
                :initialTab="selectedChef"
                class="flex-1 overflow-y-auto"
            />
            <ChefDetails
                v-if="activeComponent === 'ChefDetails'"
                :chef="selectedChef"
                :recipeCardDetails="recipeCardDetails"
                @navigate="handleNavigation"
                @recipeNavigate="goToRecipeDetails"
                :user="user"
                class="flex-1 overflow-y-auto"
            />

            <Users
                v-if="activeComponent === 'Users'"
                :usersInfo="usersInfo"
                class="flex-1 overflow-y-auto"
            />
            <Recipes
                v-if="activeComponent === 'Recipes'"
                @navigate="handleNavigation"
                :recipeCardDetails="recipeCardDetails"
                :user="user"
                class="flex-1 overflow-y-auto"
            />
            <RecipeDetails
                v-if="activeComponent === 'RecipeDetails' && selectedRecipe"
                :recipe="selectedRecipe"
                :user="user"
                @back="back"
                class="flex-1 overflow-y-auto"
            />
            <AdminSettings
                v-if="activeComponent === 'Settings'"
                :user="user"
                class="flex-1 overflow-y-auto"
            />
            <Notification
                v-if="activeComponent === 'Notification'"
                :getNotification="getNotification"
                @navigate="setActiveComponent"
                class="flex-1 overflow-y-auto"
            />
            <Reported
                v-if="activeComponent === 'Reported'"
                :allReports="allReports"
                @navigate="setActiveComponent"
                class="flex-1 overflow-y-auto"
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
    import ChefDetails from "@/Component/chefDetails.vue";
    import Users from "@/Component/Admin/Users.vue";
    import Recipes from "@/Component/Recipes.vue";
    import RecipeDetails from "@/Component/RecipeDetails.vue";
    import AdminSettings from "@/Component/Admin/AdminSettings.vue";
    import Notification from "@/Component/Notification.vue";
    import Reported from "@/Component/Admin/Reported.vue";

    const props = defineProps({
        user: Object,
        chefs: Array,
        recipeCardDetails: Array,
        recipeAllDetails: Object,
        usersInfo: Array,
        getRecipeDetailsAdmin: Array,
        getNotification: Array,
        adminTotalIncome: Object,
        totalCountsUsers: Object,
        getTotalRecipeCounts: Object,
        allReports: Array,
    })

    const isAdmin = computed(() => props.user.role === 'admin');
    const activeComponent = ref(isAdmin.value ? 'AdminHome' : 'AdminIncome');
    const selectedRecipe = ref(null);
    const selectedChef = ref(null);
    const selectedNotification = ref(null);

    const setActiveComponent = (componentName, data = null) => {
        if (componentName === "TheNotification" && data) {
            selectedNotification.value = data;
            activeComponent.value = "TheNotification";
        } else if (componentName === "ChefDetails") {
            selectedChef.value = data;
            activeComponent.value = "ChefDetails";
        } else if (componentName === "RecipeDetails" && data) {
            const fullDetails = props.recipeAllDetails.find(r => r.id === data.id);
            selectedRecipe.value = fullDetails || data;
            activeComponent.value = "RecipeDetails";
        } else if (componentName === "AdminChefs") {
            activeComponent.value = "AdminChefs";
            selectedChef.value = data?.tab || "register";
        } else {
            activeComponent.value = componentName;
        }
    };
    const handleNavigation = (componentName, data = null) => {
        if (componentName === "ChefDetails") {
            selectedChef.value = data;
            activeComponent.value = "ChefDetails";
        }
        else if (componentName === "RecipeDetails" && data) {
            const fullDetails = props.recipeAllDetails.find(r => r.id === data.id);
            selectedRecipe.value = fullDetails || data;
            activeComponent.value = "RecipeDetails";
        }
        else if (componentName === "AdminChefs") {
            selectedChef.value = null;
            activeComponent.value = "AdminChefs";
        }
        else {
            activeComponent.value = componentName;
            data = null;
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
        activeComponent.value = "Recipes";
    };


</script>
