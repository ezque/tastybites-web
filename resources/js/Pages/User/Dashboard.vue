<template>
    <div class="main-body">
        <Header :user="user"/>
        <div class="main-container">
            <Sidebar :user="user" @navigate="setActiveComponent" :active="activeComponent"/>
            <Home v-if="activeComponent === 'Home'" @navigate="handleNavigation" :recipeCardDetails="recipeCardDetails" />
            <RecipeDetails
                v-if="activeComponent === 'RecipeDetails'"
                @navigate="setActiveComponent"
                @back="back"
                :user="user"
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
        recipeCardDetails: Array,
        recipeAllDetails: Object
    })

    const isUser = computed(() => props.user.role === 'user');
    const activeComponent = ref(isUser.value ? 'Home' : null);
    const selectedRecipe = ref(null);



    const setActiveComponent = (componentName) => {
        if (activeComponent.value) {
            historyStack.value.push(activeComponent.value);
        }
        activeComponent.value = componentName;
    }
    // this is your navigation stack (history)
    const historyStack = ref([]);

    const back = () => {
        if (historyStack.value.length > 0) {
            activeComponent.value = historyStack.value.pop();
        } else {
            activeComponent.value = isUser.value ? 'Home' : null;
        }
    };

    const handleNavigation = (componentName, recipeData) => {
        if (activeComponent.value) {
            historyStack.value.push(activeComponent.value);
        }
        const fullDetails = props.recipeAllDetails.find(r => r.id === recipeData.id);
        selectedRecipe.value = fullDetails;
        activeComponent.value = componentName;
    };
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
