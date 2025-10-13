<template>
    <div class="w-full h-full flex flex-col">
        <div class="w-full h-[50px] flex border border-black items-center">
            <h2 class="ml-5">Notification</h2>
        </div>
        <div class="w-full h-full flex items-start justify-center">
            <div
                v-if="notification"
                class="w-[40%] h-[50%] flex flex-col mt-[20px] border border-black"
            >
                <div class="w-full h-[20%] flex items-center justify-center border border-black">
                    <img
                        v-if="notification.type === 'report'"
                        src="/public/images/Button-icon/report.png"
                        class="w-[50px] h-[50px]"
                    />
                </div>
                <div
                    v-if="notification.type === 'report'"
                >
                    <p><strong>Message: @{{ notification.sender.user_info.userName }} </strong> {{ notification.message }}</p>
                    <p><strong>From:</strong> {{ notification.sender?.user_info?.userName }}</p>
                    <p><strong>Time:</strong> {{ notification.created_at }}</p>
                </div>

            </div>
            <div v-else>
                <p>No notification selected.</p>
            </div>
        </div>

    </div>
</template>

<script setup>
    const props = defineProps({
        notification: Object
    });
</script>
