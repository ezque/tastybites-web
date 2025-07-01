<template>
    <button @click="handleLogout">Logout</button>
</template>

<script setup>
import  { Inertia} from "@inertiajs/inertia";
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
