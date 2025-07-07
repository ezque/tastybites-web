<template>
    <div class="main-body">
        <Header :user="user"/>
        <div class="main-container">
            <Sidebar :user="user" @navigate="setActiveComponent" :active="activeComponent"/>
            <h1>User Dashboard</h1>
        </div>

    </div>

</template>

<script setup>
    import  { Inertia} from "@inertiajs/inertia";
    import Header from '../../Component/Header.vue';
    import Sidebar from '../../Component/Sidebar.vue';
    import {computed, ref} from "vue";

    const props = defineProps({
        user: Object
    })

    const isUser = computed(() => props.user.role === 'user');

    const activeComponent = ref(isUser.value ? 'UserHome' : null);


    const setActiveComponent = (componentName) => {
        activeComponent.value = componentName;
    }

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
