<template>
    <div class="w-full h-screen flex flex-col overflow-hidden">
        <!-- Header -->
        <div class="w-full h-[100px] flex flex-row items-center pl-5 gap-3">
            <h2 class="mt-5 text-[35px] font-[Poppins-Bold]">Notification</h2>
        </div>
        <!-- Main Content -->
        <div class="w-full h-[75%] flex items-center justify-center">
            <div
                class="w-1/2 h-full rounded-[40px] border border-[#D9D9D9] shadow-[3px_2px_2px_#D9D9D9] flex flex-col p-5"
            >
            <!-- Header Buttons -->
            <div class="w-full p-2 flex flex-row items-center gap-3 h-[5%]">
                <button
                    @click="activeFilter = 'all'"
                    :class="[
                    'text-[#435F77] text-[14px] font-[Poppins-Regular] cursor-pointer rounded-md px-3 py-1 transition',
                    activeFilter === 'all'
                    ? 'bg-[#B5BFDE] font-[Poppins-SemiBold]'
                    : 'bg-transparent'
                    ]"
                >
                    All
                </button>
                <button
                    @click="activeFilter = 'unread'"
                    :class="[
                    'text-[#435F77] text-[14px] font-[Poppins-Regular] cursor-pointer rounded-md px-3 py-1 transition',
                    activeFilter === 'unread'
                    ? 'bg-[#B5BFDE] font-[Poppins-SemiBold]'
                    : 'bg-transparent'
                    ]"
                >
                    Unread
                </button>
                <button
                    @click="sortOrder = sortOrder === 'desc' ? 'asc' : 'desc'"
                    class="text-[#435F77] text-[14px] font-[Poppins-Regular] cursor-pointer"
                >
                    Sort: {{ sortOrder === 'asc' ? 'Oldest' : 'Newest' }}
                </button>
            </div>

            <!-- Notifications List -->
            <div
                class="w-full h-full flex flex-col overflow-auto mt-[30px] rounded-b-[20px] p-[5px] scrollbar-thin scrollbar-thumb-[#888] scrollbar-track-[#f1f1f1] hover:scrollbar-thumb-[#555]"
            >
                <div
                    v-for="notif in filteredNotifications"
                    :key="notif.id"
                    @click="markAsRead(notif.id)"
                    class="flex flex-col w-full"
                >
                    <div
                        :class="[
                        'flex flex-row justify-between items-start bg-[#E0E7FF] rounded-[15px] border-r border-[#AFADAD] shadow-[0_4px_1px_#AFADAD] p-[15px] gap-[10px] w-full mt-[10px] transition',
                        notif.status === 'unread' ? 'ring-2 ring-[#B5BFDE]' : ''
                        ]"
                    >
                        <!-- Left: Icon -->
                        <div class="w-[10%] flex justify-center">
                        <img
                            v-if="notif.type === 'addPremiumRecipe'"
                            src="/public/images/premium-icon.png"
                            alt="Premium"
                            class="w-1/2"
                        />
                        <img
                            v-else-if="notif.type === 'userApplicant'"
                            src="/public/images/Button-icon/new_user3.png"
                            alt="New User"
                            class="w-1/2"
                        />
                        <img
                            v-else-if="notif.type === 'chefApplicant'"
                            src="/public/images/Button-icon/chef.png"
                            alt="New Chef"
                            class="w-1/2"
                        />
                        <img
                            v-else-if="notif.type === 'report'"
                            src="/public/images/Button-icon/report.png"
                            alt="Report"
                            class="w-1/2"
                        />
                        <img
                            v-else-if="notif.type === 'liked'"
                            src="/public/images/Button-icon/filled_heart.png"
                            alt="Liked"
                            class="w-1/2"
                        />
                        <img
                            v-else-if="notif.type === 'disliked'"
                            src="/public/images/Button-icon/dislike.png"
                            alt="Disliked"
                            class="w-1/2"
                        />
                        <img
                            v-else-if="notif.type === 'recipePurchased'"
                            src="/public/images/Button-icon/payment.png"
                            alt="Payment"
                            class="w-1/2"
                        />
                        <img
                            v-else
                            src="/public/images/Button-icon/notifications.png"
                            alt="Notification"
                            class="w-1/2"
                        />
                    </div>

                    <!-- Right: Content + Action Buttons -->
                    <div class="flex flex-row w-[90%] items-center justify-between">
                        <!-- Message + Time -->
                        <div class="flex flex-col">
                            <p class="m-0 text-[12px] text-black font-[Poppins-Regular] text-left leading-tight">
                                <strong>@{{ notif.sender?.user_info?.userName || 'System' }}</strong>
                                {{ notif.message }}
                            </p>
                            <span class="text-[9px] text-black font-[Poppins-Regular] mt-1">
                                {{ timeAgo(notif.created_at) }}
                            </span>
                        </div>

                        <!-- Action Buttons (inline, right side) -->
                        <div class="flex flex-row gap-2 ml-3 shrink-0">
                            <button
                                v-if="notif.type === 'report' && !notif.blocked"
                                @click.stop="handleReport(notif)"
                                class="w-[30px] h-[30px] rounded-full flex items-center justify-center cursor-pointer hover:translate-y-[-2px] transition"
                            >
                                <img src="/public/images/Button-icon/block.png" alt="Block" class="w-[20px] h-[20px]" />
                            </button>

                            <button
                                @click.stop="deleteNotification(notif.id)"
                                class="w-[30px] h-[30px] rounded-full flex items-center justify-center cursor-pointer hover:translate-y-[-2px] transition"
                            >
                                <img src="/public/images/Button-icon/delete.png" alt="Delete" class="w-[20px] h-[20px]" />
                            </button>

                        </div>
                    </div>

                </div>
            </div>

            <!-- Empty State -->
            <div
                v-if="filteredNotifications.length === 0"
                class="text-center mt-10 text-gray-500"
            >
                No notifications found.
            </div>

        </div>
        </div>
        </div>
    </div>
</template>

<script setup>
    import { ref, computed } from "vue";
    import axios from "axios";

    const props = defineProps({
        getNotification: Array
    });

    const activeFilter = ref("all");
    const sortOrder = ref("desc");
    const blockedReports = ref([]); // Track which reports are blocked

    function timeAgo(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffMs = now - date;

        const seconds = Math.floor(diffMs / 1000);
        const minutes = Math.floor(diffMs / (1000 * 60));
        const hours = Math.floor(diffMs / (1000 * 60 * 60));
        const days = Math.floor(diffMs / (1000 * 60 * 60 * 24));

        if (seconds < 60) return `${seconds}s ago`;
        if (minutes < 60) return `${minutes}m ago`;
        if (hours < 24) return `${hours}h ago`;
        if (days < 7) return `${days}d ago`;

        return date.toLocaleDateString("en-US", {
            month: "short",
            day: "numeric",
            year: "numeric"
        });
    }

    const filteredNotifications = computed(() => {
        let notifications = props.getNotification;

        if (activeFilter.value === "unread") {
            notifications = notifications.filter((n) => n.status === "unread");
        }

        notifications = [...notifications].sort((a, b) => {
            const dateA = new Date(a.created_at);
            const dateB = new Date(b.created_at);
            return sortOrder.value === "desc" ? dateB - dateA : dateA - dateB;
        });

        return notifications;
    });

    async function handleReport(notif) {
        if (!notif.reportID) {
            alert("Report ID not found!");
            return;
        }

        try {
            const { data: report } = await axios.get(`/report/${notif.reportID}`);

            if (report.reportedUserID) {
                // Block the user
                const response = await axios.post('/update-user-status', { user_id: report.reportedUserID });
                alert(response.data.message);
                notif.blocked = true; // hide block button
            } else if (report.reportedRecipeID) {
                // Block the recipe
                const response = await axios.post(`/recipes/${report.reportedRecipeID}/status`, { status: 'declined' });
                alert(response.data.message);
                notif.blocked = true; // hide block button
            }
        } catch (error) {
            alert(error.response?.data?.message || error.message);
            console.error(error.response?.data || error.message);
        }
    }
    async function deleteNotification(id) {
        if (!confirm("Are you sure you want to delete this notification?")) return;

        try {
            const response = await axios.delete(`/notifications/${id}`);
            alert(response.data.message);

            // Remove it locally so the UI updates immediately
            const index = props.getNotification.findIndex(n => n.id === id);
            if (index !== -1) {
                props.getNotification.splice(index, 1);
            }
        } catch (error) {
            alert(error.response?.data?.message || error.message);
            console.error(error.response?.data || error.message);
        }
    }

</script>
