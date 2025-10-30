<template>
    <div class="w-full h-screen flex flex-col overflow-hidden">
        <Header :user="user" @navigate="setActiveComponent" :getNotification="getNotification" />

        <div class="w-full h-full flex flex-row bg-white">
            <Sidebar :user="user" @navigate="setActiveComponent" :active="activeComponent" />

            <Home
                v-if="activeComponent === 'Home'"
                @navigate="handleNavigation"
                :recipeCardDetails="recipeCardDetails"
            />
            <RecipeDetails
                v-if="activeComponent === 'RecipeDetails'"
                @navigate="setActiveComponent"
                @back="back"
                :user="user"
                :recipe="selectedRecipe"
            />
            <UserSettings
                v-if="activeComponent === 'Settings'"
                @navigate="handleNavigation"
                :user="user"
                :recipeCardDetails="recipeCardDetails"
            />
            <Notification
                v-if="activeComponent === 'Notification'"
                :getNotification="getNotification"
                @navigate="setActiveComponent"
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
            <UserRecipe
                v-if="activeComponent === 'UserRecipe'"
                :recipeCardDetails="recipeCardDetails"
                @navigate="handleNavigation"
            />
        </div>
    </div>
</template>

<script setup>
    import { Inertia } from "@inertiajs/inertia";
    import Header from '../../Component/Header.vue';
    import Sidebar from '../../Component/Sidebar.vue';
    import Home from "@/Component/Home.vue";
    import RecipeDetails from "@/Component/RecipeDetails.vue";
    import UserSettings from "@/Component/UserSettings.vue";
    import Notification from "@/Component/Notification.vue";
    import UserChef from "@/Component/userChef.vue";
    import ChefDetails from "@/Component/chefDetails.vue";
    import UserRecipe from "@/Component/UserRecipe.vue";

    import { computed, ref } from "vue";

    const props = defineProps({
        user: Object,
        chefs: Array,
        recipeCardDetails: Array,
        recipeAllDetails: Object,
        getNotification: Array,
    })

    const isUser = computed(() => props.user.role === 'user');
    const activeComponent = ref(isUser.value ? 'Home' : null);
    const selectedRecipe = ref(null);
    const selectedChef = ref(null);
    const selectedNotification = ref(null);
    const setActiveComponent = (componentName, data = null) => {
        console.log("Navigation triggered:", componentName, data);
        if (componentName === "RecipeDetails" && data) {
            const fullDetails = props.recipeAllDetails.find(r => r.id === data.id);
            selectedRecipe.value = fullDetails || data;
            activeComponent.value = "RecipeDetails";
        }
        else if (componentName === "TheNotification" && data) {
            selectedNotification.value = data;
            activeComponent.value = "TheNotification";
        }
        else if (componentName === "ChefInfo") {
            selectedChef.value = data;
            activeComponent.value = "ChefInfo";
        }
        else {
            activeComponent.value = componentName;
        }
    };


    const historyStack = ref([]);

    const back = () => {
        if (historyStack.value.length > 0) {
            activeComponent.value = historyStack.value.pop();
        } else {
            activeComponent.value = isUser.value ? 'Home' : null;
        }
    };

    const handleNavigation = (componentName, recipeData = null, data = null) => {
        if (activeComponent.value) {
            historyStack.value.push(activeComponent.value);
        }

        if (componentName === "ChefDetails") {
            selectedChef.value = recipeData;
            activeComponent.value = "ChefDetails";
            return;
        }

        if (recipeData) {
            const fullDetails = props.recipeAllDetails.find(r => r.id === recipeData.id);
            selectedRecipe.value = fullDetails ?? recipeData;
        }

        activeComponent.value = componentName;
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
