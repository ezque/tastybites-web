<template>
    <div class="main-body">
        <Header :user="user"/>
        <div class="main-container">
            <Sidebar :user="user" @navigate="setActiveComponent" :active="activeComponent"/>
            <Home v-if="activeComponent === 'ChefHome'" />
            <Income v-if="activeComponent === 'ChefIncome'"/>
            <Recipes v-if="activeComponent === 'Recipes'" />
        </div>
    </div>
</template>

<script setup>
    import  { Inertia} from "@inertiajs/inertia";
    import Header from '../../Component/Header.vue';
    import Sidebar from "@/Component/Sidebar.vue";

    import Home from "@/Component/Chef/Home.vue";
    import Income from "@/Component/Chef/Income.vue";
    import Recipes from "@/Component/Recipes.vue";

    import {computed, ref} from "vue";

    const props = defineProps({
        user: Object
    })

    const isChef = computed(() => props.user.role === 'chef');

    const activeComponent = ref(isChef.value ? 'ChefHome' : 'ChefIncome');


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
