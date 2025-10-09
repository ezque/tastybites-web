<template>
    <div class="w-full h-screen bg-gray-500 flex flex-col overflow-hidden">
        <Header
            :user="user"
            @navigate="setActiveComponent"
            :getNotification="getNotification"
        />

        <div class="w-full h-full flex flex-row bg-white">
            <Sidebar :user="user" @navigate="setActiveComponent" :active="activeComponent" />

            <!-- Pages -->
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
            <AddRecipe
                v-if="activeComponent === 'AddRecipe'"
                @navigate="setActiveComponent"
                :active="activeComponent"
            />
            <Profile v-if="activeComponent === 'Profile'" />

            <RecipeDetails
                v-if="activeComponent === 'RecipeDetails'"
                @navigate="setActiveComponent"
                :recipe="selectedRecipe"
                :user="user"
                @back="back"
            />
            <Report
                v-if="activeComponent === 'Report'"
                :chefTotalIncome="chefTotalIncome"
            />
            <UserSettings
                v-if="activeComponent === 'Settings'"
                @navigate="handleNavigation"
                :user="user"
                :recipeCardDetails="recipeCardDetails"
                :chefCertificate="chefCertificate"
            />
            <Notification
                v-if="activeComponent === 'Notification'"
                :getNotification="getNotification"
            />
            <UserChef
                v-if="activeComponent === 'userChef'"
                :chefs="chefs"
                :user="user"
                @navigate="handleNavigation"
            />
            <ChefDetails
                v-if="activeComponent === 'ChefDetails'"
                :chef="selectedChef"
                :recipeCardDetails="recipeCardDetails"
                @navigate="handleNavigation"
                @recipeNavigate="goToRecipeDetails"
                :user="user"
            />
            <TheNotification
                v-if="activeComponent === 'TheNotification'"
                :notification="selectedNotification"
                class="flex-1 overflow-y-auto"
            />
        </div>
    </div>
</template>

<script setup>
    import  { Inertia } from "@inertiajs/inertia";
    import { computed, ref } from "vue";
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
    import Notification from "@/Component/Notification.vue";
    import UserChef from "@/Component/userChef.vue";
    import ChefDetails from "@/Component/chefDetails.vue";
    import TheNotification from "@/Component/TheNotification.vue";

    const props = defineProps({
        user: Object,
        chefs: Array,
        recipeCardDetails: Array,
        recipeAllDetails: Object,
        purchases: Array,
        getNotification: Array,
        chefCertificate: Array,
        chefTotalIncome: Object,
    })

    const isChef = computed(() => props.user.role === 'chef');
    const selectedChef = ref(null)

    const activeComponent = ref(isChef.value ? 'Home' : 'ChefIncome');

    const selectedRecipe = ref(null);
    const selectedNotification = ref(null);

    const setActiveComponent = (componentName, data = null) => {
        if (componentName === "TheNotification" && data) {
            selectedNotification.value = data;
            activeComponent.value = "TheNotification";
        } else if (componentName === "ChefInfo") {
            selectedChef.value = data;
            activeComponent.value = "ChefInfo";
        } else if (componentName === "RecipeDetails" && data) {
            const fullDetails = props.recipeAllDetails.find(r => r.id === data.id);
            selectedRecipe.value = fullDetails || data;
            activeComponent.value = "RecipeDetails";
        } else {
            activeComponent.value = componentName;
        }
    };

    const handleNavigation = (componentName, recipeData = null, data = null) => {
        if (componentName === 'AddRecipe') {
            selectedRecipe.value = null;
            activeComponent.value = 'AddRecipe';
            return;
        }

        if (componentName === "ChefDetails") {
            selectedChef.value = recipeData;
            activeComponent.value = "ChefDetails";
            return;
        }

        if (componentName === 'RecipeDetails' && recipeData) {
            const fullDetails = props.recipeAllDetails.find(r => r.id === recipeData.id);
            selectedRecipe.value = fullDetails;
        }

        activeComponent.value = componentName;
    };

    const historyStack = ref([]);
    const isUser = computed(() => props.user.role === 'chef');

    const back = () => {
        if (historyStack.value.length > 0) {
            activeComponent.value = historyStack.value.pop();
        } else {
            activeComponent.value = isUser.value ? 'Home' : null;
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
</script>
