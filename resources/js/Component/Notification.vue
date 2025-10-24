<template>
    <div class="notification-main-body">
        <div class="notification-header">
            <h2>Notification</h2>
        </div>
        <div class="notification-main">
            <div class="card-main">
                <div class="card-main-header">
                    <button @click="activeFilter = 'all'" :class="{ active: activeFilter === 'all' }">All</button>
                    <button @click="activeFilter = 'unread'" :class="{ active: activeFilter === 'unread' }">Unread</button>

                    <!-- Sorting -->
                    <button @click="sortOrder = sortOrder === 'desc' ? 'asc' : 'desc'">
                        Sort: {{ sortOrder === 'asc' ? 'Newest' : 'Oldest' }}
                    </button>
                </div>

                <div class="all-notifications-box">
                    <div
                        class="notifications-card"
                        v-for="notif in filteredNotifications"
                        :key="notif.id"
                    >
                        <div class="main-notification">
                            <!-- Premium Recipe -->
                            <button v-if="notif.type === 'addPremiumRecipe'" class="notification-item-active">
                                <div class="notif-icon">
                                    <img src="/public/images/premium-icon.png" alt="Premium recipe" />
                                </div>
                                <div class="notif-content">
                                    <p><strong>@{{ notif.sender.user_info.userName }} </strong> {{ notif.message }}</p>
                                    <span class="time">{{ timeAgo(notif.created_at) }}</span>
                                </div>
                            </button>
                            <!--New Chef-->
                            <button v-if="notif.type === 'chefApplicant'" class="notification-item-active">
                                <div class="notif-icon">
                                    <img src="/public/images/Button-icon/chef.png" alt="Chef" />
                                </div>
                                <div class="notif-content">
                                    <p><strong>@{{ notif.sender.user_info.userName }}</strong> has signed up as a new chef. Review their profile.</p>
                                    <span class="time">{{ timeAgo(notif.created_at) }}</span>
                                </div>
                            </button>
                            <!--New User-->
                            <button v-if="notif.type === 'userApplicant'" class="notification-item-active">
                                <div class="notif-icon">
                                    <img src="/public/images/Button-icon/new_user3.png" alt="user" />
                                </div>
                                <div class="notif-content">
                                    <p>We have a new user! <strong>@{{ notif.sender.user_info.userName }}</strong> has joined the community.</p>
                                    <span class="time">{{ timeAgo(notif.created_at) }}</span>
                                </div>
                            </button>

                            <!--Report-->
                            <button v-if="notif.type === 'report'" class="notification-item-active">
                                <div class="notif-icon">
                                    <img src="/public/images/Button-icon/report.png" alt="report" />
                                </div>
                                <div class="notif-content">
                                    <p>{{notif.message}}</p>
                                    <span class="time">{{ timeAgo(notif.created_at) }}</span>
                                </div>
                            </button>

                            <!--Reaction-->
                            <button class="notification-item-active" v-if="notif.type === 'liked' || notif.type === 'disliked'">
                                <div class="notif-icon">
                                    <img src="/public/images/Button-icon/filled_heart.png" alt="love" v-if="notif.type === 'liked'"/>
                                    <img src="/public/images/Button-icon/filled_dislike.png" alt="dislike" v-if="notif.type === 'disliked'"/>
                                </div>
                                <div class="notif-content">
                                    <p v-if="notif.type === 'liked'"> {{ notif.message }} </p>
                                    <p v-if="notif.type === 'disliked'"> {{ notif.message }} </p>
                                    <span class="time">{{ timeAgo(notif.created_at) }}</span>
                                </div>
                            </button>

                            <!--Recipe purchase-->
                            <button class="notification-item-active" v-if="notif.type === 'recipePurchased'">
                                <div class="notif-icon">
                                    <img src="/public/images/Button-icon/payment.png" alt="payment" />
                                </div>
                                <div class="notif-content">
                                    <p><strong>@{{ notif.sender.user_info.userName }}</strong> {{ notif.message }}</p>
                                    <span class="time">{{ timeAgo(notif.created_at) }}</span>
                                </div>
                            </button>

                            <!--Followed-->
                            <button class="notification-item" v-if="notif.type === 'followed'">
                                <div class="notif-icon">
                                    <img src="/public/images/Button-icon/follow.png" alt="follow" />
                                </div>
                                <div class="notif-content">
                                    <p><strong>@appletamesis</strong> is now following you!</p>
                                    <span class="time">{{ timeAgo(notif.created_at) }}</span>
                                </div>
                            </button>

                            <!-- recipe Block by admin-->
                            <button class="notification-item" v-if="notif.type === 'recipeBlocked'">
                                <div class="notif-icon">
                                    <img src="/public/images/Button-icon/block.png" alt="follow" />
                                </div>
                                <div class="notif-content">
                                    <p><strong>Adobo <i>(Filipino cuisine)</i></strong> was blocked by <strong>Admin</strong>.</p>
                                    <span class="time">{{ timeAgo(notif.created_at) }}</span>
                                </div>
                            </button>
                            <!-- approved as a chef -->
                            <button class="notification-item" v-if="notif.type === 'chefApproved'">
                                <div class="notif-icon">
                                    <img src="/public/images/Button-icon/chef.png" alt="as_chef" />
                                </div>
                                <div class="notif-content">
                                    <p><strong>Chef status unlocked! ✨</strong> Share your first recipe now!</p>
                                    <span class="time">{{ timeAgo(notif.created_at) }}</span>
                                </div>
                            </button>
                            <!-- premium recipe approved by admin -->
                            <button class="notification-item" v-if="notif.type === 'premiumRecipeApproved'">
                                <div class="notif-icon">
                                    <img src="/public/images/Button-icon/approved.png" alt="approved" />
                                </div>
                                <div class="notif-content">
                                    <p>Your premium recipe, <strong>Adobo <i>(Filipino cuisine)</i></strong> was approved! Recipe is now available for purchase.</p>
                                    <span class="time">{{ timeAgo(notif.created_at) }}</span>
                                </div>
                            </button>
                            <!-- recipe purchase approved -->
                            <button class="notification-item" v-if="notif.type === 'recipePurchaseApproved'">
                                <div class="notif-icon">
                                    <img src="/public/images/Button-icon/payment.png" alt="approved_payment" />
                                </div>
                                <div class="notif-content">
                                    <p>Purchase approved! <strong>Adobo <i>(Filipino cuisine)</i></strong> is now accessible in your profile.</p>
                                    <span class="time">{{ timeAgo(notif.created_at) }}</span>
                                </div>
                            </button>

                            <!-- new recipe added -->
                            <button class="notification-item" v-if="notif.type === 'newRecipeAdded'">
                                <div class="notif-icon">
                                    <img src="/public/images/Button-icon/RecipeFooter.png" alt="approved_payment" />
                                </div>
                                <div class="notif-content">
                                    <p>Chef <strong>@bon</strong> added new recipe, check it now!</p>
                                    <span class="time">{{ timeAgo(notif.created_at) }}</span>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
    import { ref, computed } from "vue";

    const props = defineProps({
        getNotification: Array
    })

    const activeFilter = ref("all"); // all | unread
    const sortOrder = ref("desc");   // desc = newest first, asc = oldest first

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

        // Filter by status
        if (activeFilter.value === "unread") {
            notifications = notifications.filter(n => n.status === "unread");
        }

        // Sort by date
        notifications = [...notifications].sort((a, b) => {
            const dateA = new Date(a.created_at);
            const dateB = new Date(b.created_at);
            return sortOrder.value === "desc"
                ? dateB - dateA
                : dateA - dateB;
        });

        return notifications;
    });
</script>

<style scoped>
    .notification-main-body {
        width: 100%;
        height: 100vh;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }
    .notification-header {
        width: 100%;
        height: 100px;
        display: flex;
        flex-direction: row;
        align-items: center;
        padding-left: 20px;
        gap: 10px;
    }
    .notification-header button {
        width: 50px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: transparent;
        border: none;
        cursor: pointer;
    }
    .notification-header button img {
        width: 100%;
        height: 100%;
    }
    .notification-header h2 {
        margin-top: 20px;
        font-size: 35px;
        font-family: 'Poppins-Bold';
    }
    .notification-main {
        width: 100%;
        height: 75%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .card-main {
        width: 50%;
        height: 100%;
        border-radius: 40px;
        border: 1px solid #D9D9D9;
        box-shadow: 3px 2px 2px #D9D9D9;
        display: flex;
        flex-direction: column;
        padding: 15px 20px 20px 20px;
    }
    .card-main-header {
        width: 100%;
        padding: 10px;
        display: flex;
        flex-direction: row;
        align-items: center;
        gap: 10px;
        height: 5%;
    }
    .card-main-header button {
        background-color: transparent;
        border: none;
        color: #435F77;
        font-family: 'Poppins-Regular';
        font-size: 14px;
        cursor: pointer;
    }
    .card-main-header button.active {
        color: #435F77;
        background-color: #B5BFDE;
        border-radius: 6px;
        padding: 5px 10px;
        font-family: 'Poppins-SemiBold';
    }
    .notification-item {
        display: flex;
        flex-direction: row;
        border: none;
        box-shadow: 0px 4px 1px #AFADAD;
        border-radius: 15px;
        padding: 10px;
        gap: 10px;
        align-items: center;
        border-right: #AFADAD solid 1px;
    }
    .notification-item-active {
        display: flex;
        flex-direction: row;
        border: none;
        border-radius: 15px;
        padding: 10px;
        gap: 10px;
        align-items: center;
        background-color: #E0E7FF;
        border-right: #AFADAD solid 1px;
        box-shadow: 0 4px 1px #AFADAD;
        margin-top: 5px;
        width: 100%;
    }
    .notif-icon {
        width: 10%;
    }
    .notif-icon img {
        width: 50%;
    }
    .notif-content {
        display: flex;
        align-items: flex-start;
        flex-direction: column;
        width: 90%;
    }
    .notif-content p {
        margin: 0;
        font-size: 12px;
        color: black;
        font-family: 'Poppins-Regular';
        text-align: left;
    }
    .notif-content span {
        font-size: 9px;
        color: black;
        font-family: 'Poppins-Regular';
    }
    .all-notifications-box {
        max-height: 750px;
        height: 100%;
        width: 100%;
        flex-direction: column;
        overflow: auto;
        margin-top: 30px;
        border-bottom-left-radius: 20px;
        border-bottom-right-radius: 20px;
        padding: 5px;
    }
    .all-notifications-box::-webkit-scrollbar {
        width: 2px;
    }

    .all-notifications-box::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .all-notifications-box::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }

    .all-notifications-box::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
    .notification-item-active {
        display: flex;
        flex-direction: row;
        border: none;
        border-radius: 15px;
        padding: 10px;
        gap: 10px;
        align-items: center;
        background-color: #E0E7FF;
        border-right: #AFADAD solid 1px;
        box-shadow: 0 4px 1px #AFADAD;
        margin-top: 10px;
    }
    .main-notification {
        display: flex;
        flex-direction: column;
        gap: 5px;
        width: 100%;
    }
    .notifications-card {
        display: flex;
        flex-direction: row;
    }
</style>
