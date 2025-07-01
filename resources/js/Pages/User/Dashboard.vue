<template>
    <Header :user="user"/>
    <h1>User Dashboard</h1>
    <button @click="handleLogout">Logout</button>
</template>

<script setup>
    import  { Inertia} from "@inertiajs/inertia";
    import Header from '../../Component/Header.vue';

    defineProps({
        user: Object
    })
    const handleLogout = () => {
        if (confirm('Are you sure you want to logout?')) {
            Inertia.post('/logout', {}, {
                onFinish: () => {
                    window.location.reload();
                }
            });
        }
    };

</script>
