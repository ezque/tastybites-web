<template>
    <Header :user="user"/>
    <h1>Admin Dashboard</h1>
</template>

<script setup>
    import Header from '../../Component/Header.vue';

    defineProps({
        user: Object
    })

</script>
