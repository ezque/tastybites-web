<template>
    <div class="the-notification-container">
        <h2>Notification Details</h2>
        <div v-if="notification">
            <p><strong>Type:</strong> {{ notification.type }}</p>
            <p><strong>Message:</strong> {{ notification.message }}</p>
            <p><strong>From:</strong> {{ notification.sender?.user_info?.userName }}</p>
            <p><strong>Time:</strong> {{ notification.created_at }}</p>
        </div>
        <div v-else>
            <p>No notification selected.</p>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    notification: Object
});
</script>
