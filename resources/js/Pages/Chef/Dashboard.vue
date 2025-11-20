<template>
    <div class="w-full h-screen bg-gray-500 flex flex-col overflow-hidden">
        <Header
            :user="user"
            @navigate="setActiveComponent"
            @search="handleSearch"
            :getNotification="getNotification"
        />

        <div class="w-full h-full flex flex-row bg-white">
            <Sidebar
                :user="user"
                @navigate="setActiveComponent"
                :active="activeComponent"
            />

            <!-- Pages -->
            <Home
                v-if="activeComponent === 'Home'"
                :searchQuery="searchQuery"
                @navigate="handleNavigation"
            />
            <Income
                v-if="activeComponent === 'ChefIncome'"
                :purchases="purchases"
            />
            <Recipes
                v-if="activeComponent === 'Recipes'"
                @navigate="handleNavigation"
                :recipeCardDetails="recipeCardDetails.all"
                :user="user"
            />
            <AddRecipe
                v-if="activeComponent === 'AddRecipe'"
                :active="activeComponent"
                :recipeData="selectedRecipe"
                :isEditMode="isEditMode"
                @back="back"
            />

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
                @navigate="setActiveComponent"
            />
            <UserChef
                v-if="activeComponent === 'userChef'"
                :searchQuery="searchQuery"
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
            <UserRecipe
                v-if="activeComponent === 'UserRecipe'"
                :recipeCardDetails="recipeCardDetails"
            />
            <RecipeReportPurchase
                v-if="activeComponent === 'whoPurchase'"
                :recipe="selectedRecipe"
            />
        </div>
    </div>
</template>

<script setup>
    import { computed, ref } from "vue";
    import Header from '../../Component/Header.vue';
    import Sidebar from "@/Component/Sidebar.vue";

    import Home from "@/Component/Home.vue";
    import Income from "@/Component/Chef/Income.vue";
    import Recipes from "@/Component/Recipes.vue";
    import AddRecipe from "@/Component/Chef/AddRecipe.vue";
    import RecipeDetails from "@/Component/RecipeDetails.vue";
    import Report from "@/Component/Chef/Report.vue";
    import UserSettings from "@/Component/UserSettings.vue";
    import Notification from "@/Component/Notification.vue";
    import UserChef from "@/Component/userChef.vue";
    import ChefDetails from "@/Component/chefDetails.vue";
    import UserRecipe from "@/Component/UserRecipe.vue";
    import RecipeReportPurchase from "@/Component/Chef/RecipeReportPurchase.vue";

    const props = defineProps({
        user: Object,
        chefs: Array,
        recipeCardDetails: Object,
        recipeAllDetails: Object,
        purchases: Array,
        getNotification: Array,
        chefCertificate: Array,
        chefTotalIncome: Object,
    })

    const isChef = computed(() => props.user.role === 'chef');
    const selectedChef = ref(null);
    const isEditMode = ref(false)

    const activeComponent = ref(isChef.value ? 'Home' : 'ChefIncome');

    const selectedRecipe = ref(null);
    const selectedNotification = ref(null);

    const searchQuery = ref("");

    function handleSearch(value) {
        searchQuery.value = value;
    }

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

    const handleNavigation = (componentName, recipeData = null, extraData = null) => {
        // Push current page to history (except when going back)
        if (activeComponent.value && activeComponent.value !== componentName) {
            historyStack.value.push(activeComponent.value);
        }

        if (componentName === 'AddRecipe') {
            selectedRecipe.value = recipeData?.recipeData || null;
            isEditMode.value = recipeData?.isEditMode || false;
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

        if (componentName === "whoPurchase") {
            selectedRecipe.value = recipeData;
            activeComponent.value = "whoPurchase";
            return;
        }

        activeComponent.value = componentName;
    };

    const historyStack = ref([]);
    const isUser = computed(() => props.user.role === 'chef');

    const back = () => {
        if (historyStack.value.length > 0) {
            activeComponent.value = historyStack.value.pop();
            // Clear form data when leaving AddRecipe
            if (activeComponent.value !== 'AddRecipe') {
                selectedRecipe.value = null;
                isEditMode.value = false;
            }
        } else {
            activeComponent.value = isChef.value ? 'Home' : 'ChefIncome';
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
