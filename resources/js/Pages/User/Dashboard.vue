<template>
    <Header :user="user"/>
    <h1>User Dashboard</h1>
</template>

<script setup>
    import  { Inertia} from "@inertiajs/inertia";
    import Header from '../../Component/Header.vue';

    defineProps({
        user: Object
    })

</script>
