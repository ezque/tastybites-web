<template>
    <div class="main-body">
        <Header :user="user"/>
        <div class="main-container">
            <Sidebar :user="user" @navigate="setActiveComponent" :active="activeComponent"/>
            <Home v-if="activeComponent === 'AdminHome'" />
            <Income v-if="activeComponent === 'AdminIncome'" />
            <Chefs v-if="activeComponent === 'AdminChefs'" :chefs="chefs"/>
            <Users v-if="activeComponent === 'AdminUsers'" />
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

    const props = defineProps({
        user: Object,
        chefs: Array
    })

    const isAdmin = computed(() => props.user.role === 'admin');

    const activeComponent = ref(isAdmin.value ? 'AdminHome' : 'AdminIncome');
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

