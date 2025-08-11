<template>
    <div class="main-body">
        <Header :user="user"/>
        <div class="main-container">
            <Sidebar :user="user" @navigate="setActiveComponent" :active="activeComponent"/>
            <Home v-if="activeComponent === 'Home'" :recipes="recipes" />
            <Income v-if="activeComponent === 'ChefIncome'"/>
            <Recipes v-if="activeComponent === 'Recipes'" @navigate="setActiveComponent" :recipes="recipes" :user="user" />
            <AddRecipe v-if="activeComponent === 'AddRecipe'" @navigate="setActiveComponent" :active="activeComponent"/>
        </div>
    </div>
</template>

<script setup>
    import  { Inertia} from "@inertiajs/inertia";
    import Header from '../../Component/Header.vue';
    import Sidebar from "@/Component/Sidebar.vue";

    import Home from "@/Component/Home.vue";
    import Income from "@/Component/Chef/Income.vue";
    import Recipes from "@/Component/Recipes.vue";
    import AddRecipe from "@/Component/Chef/AddRecipe.vue";

    import {computed, ref} from "vue";

    const props = defineProps({
        user: Object,
        recipes: Array
    })

    const isChef = computed(() => props.user.role === 'chef');

    const activeComponent = ref(isChef.value ? 'Home' : 'ChefIncome');


    const setActiveComponent = (componentName) => {
        activeComponent.value = componentName;
    }

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
