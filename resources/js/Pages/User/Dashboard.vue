<template>
    <h1>User Dashboard</h1>
    <button @click="handleLogout">Logout</button>
</template>

<script setup>
import  { Inertia} from "@inertiajs/inertia";
import {reactive, ref} from 'vue'

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
