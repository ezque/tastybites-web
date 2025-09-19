<template>
    <div class="container">
        <img src="/public/images/tastybites_logo.png" class="logo"/>
        <div class="right-side-container">
            <button class="rs-button">
                <img src="/public/images/Button-icon/search.png"/>
            </button>
            <div class="notification-box">
                <button class="rs-button" @click="toggleNotification">
                    <img src="/public/images/Button-icon/notifications.png"/>
                </button>
                <div class="notification-container" v-if="isNotificationVisible">
                    <div class="notification-label-container">
                    <h2>Notifications</h2>
                        <div class="notification-buttons">
                            <button
                                :class="{ active: activeFilter === 'all' }"
                                @click="activeFilter = 'all'"
                            >
                                All
                            </button>
                            <button
                                :class="{ active: activeFilter === 'unread' }"
                                @click="activeFilter = 'unread'"
                            >
                                Unread
                            </button>
                        </div>
                    </div>
                    <div class="notification-actions">
                        <img
                        src="/public/images/Button-icon/option.png"
                        alt="options"
                        @click="toggleMenuOpen"
                        />

                        <div v-if="menuOpen" class="dropdown-menu">
                            <button @click="markAllRead" class="mark-all-read">
                                <img src="/public/images/Button-icon/check.png" alt="mark all read"/>
                                <p>Mark All Read</p>
                            </button>
                            <div class="white-line"></div>
                            <button @click="openNotifications" class="open-notifications">
                                <img src="/public/images/Button-icon/notifications.png" alt="mark all read"/>
                                <p>Open Notifications</p>
                            </button>
                        </div>
                    </div>
                    <div class="all-notifications-box">
                        <div
                            class="notifications-card"
                            v-for="notif in filteredNotifications"
                            :key="notif.id"
                        >
                            <div class="main-notification">
                                <!-- Admin -->
                                <button v-if="notif.type === '1'" class="notification-item-active">
                                    <div class="notif-icon">
                                        <img src="/public/images/premium-icon.png" alt="Premium recipe" />
                                    </div>
                                    <div class="notif-content">
                                        <p><strong>@{{ notif.sender.user_info.userName }} </strong> {{ notif.message }}</p>
                                        <span class="time">{{ timeAgo(notif.created_at) }}</span>
                                    </div>
                                </button>
                                <button v-if="notif.type === '2'" class="notification-item-active">
                                    <div class="notif-icon">
                                        <img src="/public/images/Button-icon/chef.png" alt="Chef" />
                                    </div>
                                    <div class="notif-content">
                                        <p><strong>@{{ notif.sender.user_info.userName }}</strong> has signed up as a new chef. Review their profile.</p>
                                        <span class="time">{{ timeAgo(notif.created_at) }}</span>
                                    </div>
                                </button>

                                <button v-if="notif.type === '3'" class="notification-item-active">
                                    <div class="notif-icon">
                                        <img src="/public/images/Button-icon/new_user2.png" alt="user" />
                                    </div>
                                    <div class="notif-content">
                                        <p>We have a new user! <strong>@{{ notif.sender.user_info.userName }}</strong> has joined the community.</p>
                                        <span class="time">{{ timeAgo(notif.created_at) }}</span>
                                    </div>
                                </button>

<!--                                <button class="notification-item" v-if="notif.type === '4'">-->
<!--                                    <div class="notif-icon">-->
<!--                                        <img src="/public/images/Button-icon/report.png" alt="report" />-->
<!--                                    </div>-->
<!--                                    <div class="notif-content">-->
<!--                                        <p><strong>Adobo <i>(Filipino cuisine)</i></strong> is reported by <strong>@Zellyace</strong>. Review report now!</p>-->
<!--                                        <span class="time">{{ timeAgo(notif.created_at) }}</span>-->
<!--                                    </div>-->
<!--                                </button>-->

                                <!-- chef -->
                                <button class="notification-item-active" v-if="notif.type === '5' || notif.type === '6'">
                                    <div class="notif-icon">
                                        <img src="/public/images/Button-icon/filled_heart.png" alt="love" v-if="notif.type === '5'"/>
                                        <img src="/public/images/Button-icon/filled_dislike.png" alt="dislike" v-if="notif.type === '6'"/>
                                    </div>
                                    <div class="notif-content">
                                        <p v-if="notif.type === '5'"> {{ notif.message }} </p>
                                        <p v-if="notif.type === '6'"> {{ notif.message }} </p>
                                        <span class="time">{{ timeAgo(notif.created_at) }}</span>
                                    </div>
                                </button>

<!--                                <button class="notification-item">-->
<!--                                    <div class="notif-icon">-->
<!--
<!--                                    </div>-->
<!--                                    <div class="notif-content">-->
<!--                                        <p><strong>Adobo <i>(Filipino cuisine)</i></strong> was loved by <strong>@Zellyace</strong>.</p>-->
<!--                                        <span class="time">{{ timeAgo(notif.created_at) }}</span>-->
<!--                                    </div>-->
<!--                                </button>-->

<!--                                <button class="notification-item">-->
<!--                                    <div class="notif-icon">-->
<!--                                        <img src="/public/images/Button-icon/payment.png" alt="payment" />-->
<!--                                    </div>-->
<!--                                    <div class="notif-content">-->
<!--                                        <p><strong>@appletamesis</strong> wants to purchase your <strong>Okonomiyaki <i>(Japanese cuisine)</i></strong>. Review and verify now.</p>-->
<!--                                        <span class="time">{{ timeAgo(notif.created_at) }}</span>-->
<!--                                    </div>-->
<!--                                </button>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button class="menu-button" @click="toggleMenu">
                <div class="profile-container">
                    <img
                        v-if="user.user_info.profilePath"
                        :src="`/storage/${user.user_info.profilePath}`"
                        alt="Profile"
                    />
                    <img
                        v-else-if="user.user_info.gender === 'male'"
                        src="/public/images/male.png"
                        alt="Male Default"
                    />
                    <img
                        v-else
                        src="/public/images/female.png"
                        alt="Female Default"
                    />
                </div>
                <h1>{{ capitalizedfullName }}</h1>
            </button>
        </div>
        <div class="menu-container" v-if="isMenuVisible">
            <div class="menu-profile-container">
                <img
                    v-if="user.user_info.profilePath"
                    :src="`/storage/${user.user_info.profilePath}`"
                    alt="Profile"
                />
                <img
                    v-else-if="user.user_info.gender === 'male'"
                    src="/public/images/male.png"
                    alt="Male Default"
                />
                <img
                    v-else
                    src="/public/images/female.png"
                    alt="Female Default"
                />
            </div>
            <div class="menu-user-info">
                <h1>{{ capitalizedfullName }}</h1>
                <h6>{{ user?.role }}</h6>
            </div>
            <div class="menu-buttons-container">
                <button @click="emit('navigate', 'Settings')">
                    <img src="/public/images/Button-icon/settings.png" alt="img" />
                    <h1>Settings</h1>
                </button>
                <span class="white-line"></span>
                <button @click="handleLogout">
                    <img src="/public/images/Button-icon/logout(3).png" alt="img" />
                    <h1>Logout</h1>
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { computed, ref } from 'vue';
    import {Inertia} from "@inertiajs/inertia";

    const props = defineProps({
        user: Object,
        getNotification: Array,
    })

    console.log(props.getNotification?.[0]);

    const isMenuVisible = ref(false);
    const isNotificationVisible = ref(false);
    const activeFilter = ref("all");
    const menuOpen = ref(false);

    const filteredNotifications = computed(() => {
        if (activeFilter.value === "unread") {
            return props.getNotification.filter(n => n.status === "unread");
        }
        return props.getNotification;
    });
    const toggleNotification = () => {
        isNotificationVisible.value = !isNotificationVisible.value;
    };

    const toggleMenu = () => {
        isMenuVisible.value = !isMenuVisible.value;
    }



    const toggleMenuOpen = () => {
    menuOpen.value = !menuOpen.value;
    };

    const capitalizedfullName = computed(() => {
        const name = props.user?.user_info?.fullName ?? 'User'
        return name
            .toLowerCase()
            .split(' ')
            .map(word => word.charAt(0).toUpperCase() + word.slice(1))
            .join(' ')
    })
    const profileIcon = computed(() => {
        if (props.user?.role === 'chef') {
            return '/images/Button-icon/chef_white.png';
        } else if (props.user?.role === 'user') {
            return '/images/Button-icon/user_white.png';
        } else {
            return null;
        }
    });

    const profile = computed(() =>{
        if (props.user?.user_info?.gender === 'male') {
            return '/images/male.png';
        } else if (props.user?.user_info?.gender === 'female') {
            return '/images/female.png';
        } else {
            return '/images/male.png';
        }

    });
    const handleLogout = () => {
        if (confirm('Are you sure you want to logout?')) {
            Inertia.post('/logout', {}, {
                onFinish: () => {
                    window.location.reload();
                }
            });
        }
    };
    const emit = defineEmits(['navigate']);

    function formatMessage(message) {
        return message.replace(/(@\w+)/, "<strong>$1</strong>");
    }

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

        // Show formatted date after 7 days
        return date.toLocaleDateString("en-US", {
            month: "short", // e.g. "Sep"
            day: "numeric", // e.g. "10"
            year: "numeric" // e.g. "2025"
        });
    }
</script>

<style scoped>
    .container {
        height: 50px;
        background-color: #E0E7FF;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px;
        margin: 0;
    }
    .logo {
        width: 150px;
        height: auto;
    }
    .right-side-container {
        padding: 20px 0 20px 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .menu-button {
        padding: 5px;
        display: flex;
        flex-direction: row;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        border: none;
        background-color: transparent;
    }
    .profile-container {
        width: 40px;
        height: 40px;
        border: none;
        border-radius: 50%;
        background-color: #BB98B8;
    }
    .profile-container img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
    }
    .menu-button h1{
        font-size: 1em;
    }

    .rs-button {
        padding: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        background-color: transparent;
        cursor: pointer;
    }
    .rs-button img {
        width: 25px;
        height: auto;
    }
    .menu-container {
        position: absolute;
        padding: 20px 20px 20px 20px;
        right: 1%;
        top: 10%;
        display: flex;
        flex-direction: column;
        align-items: center;
        margin: 0;
        background-color: #435F77;
        border-radius: 20px 0 20px 20px;
        min-width: 200px;
        z-index: 999;
    }
    .menu-profile-container {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background-color: #BB98B8;
        margin: 0;
    }
    .menu-profile-container img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
    }
    .menu-user-info {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        margin: 0;
        padding: 10px;
    }
    .menu-user-info h1 {
        margin: 0;
        font-size: 1.2em;
        color: white;
        font-family: 'Poppins', sans-serif;
    }
    .menu-user-info h6 {
        margin: 0;
        color: white;
        font-family: 'Poppins', sans-serif;
        font-weight: normal;
        font-style: italic;
    }
    .menu-buttons-container {
        display: flex;
        flex-direction: column;
        width: 100%;
        align-items: center;
        gap: 5px;
    }
    .menu-buttons-container button {
        width: 60%;
        background-color: transparent;
        display: flex;
        align-items: center;
        color: white;
        border: none;
        cursor: pointer;
    }
    .menu-buttons-container button img {
        width: 30px;
        height: auto;
    }
    .menu-buttons-container button h1 {
        font-size: 1.2em;
        color: white;
        margin-left: 10px;
        text-align: left;
        font-family: 'Poppins', sans-serif;
    }
    .white-line {
        width: 60%;
        border: 1px solid white;
    }
    .notification-box {
        position: relative;
        display: flex;
    }
    .notification-container {
        position: absolute;
        right: 0;
        width: 320px;
        top: 75px;
        padding: 10px;
        display: flex;
        flex-direction: column;
        background-color: White;
        border-radius: 20px 0 20px 20px;
        z-index: 199;
        box-shadow: 4px 4px 12px rgba(0, 0, 0, 0.4);
        border-right: #AFADAD solid 1px;
    }
    .notification-label-container {
        width: 250px;
        height: 50px;
        flex-direction: column;
        margin-top: 10px;
    }

    .notification-label-container h2 {
        color: #435F77;
        font-family: 'Poppins-Bold';
        font-size: 20px;
        margin: 0;
    }

    .notification-buttons {
        display: flex;
        gap: 5px;
    }

    .notification-buttons button {
        background-color: transparent;
        border: none;
        color: #435F77;
        font-family: 'Poppins-Regular';
        font-size: 14px;
        cursor: pointer;
    }

    .notification-buttons button.active {
        color: #435F77;
        background-color: #B5BFDE;
        border-radius: 6px;
        padding: 5px 10px;
        font-family: 'Poppins-SemiBold';
    }

    .notification-buttons button:hover {
        background-color: #E0E7FF;
        color: #435F77;
        border-radius: 6px;
    }
    .notification-actions {
        display: flex;
        top: 20px;
        width: 10px;
        height: 24px;
        position: absolute;
        right: 10px;
        cursor: pointer;
    }
    .all-notifications-box {
        max-height: 500px;
        height: 500px;
        width: 100%;
        flex-direction: column;
        overflow: auto;
        margin-top: 30px;
        border-bottom-left-radius: 20px;
        border-bottom-right-radius: 20px;
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

    .notifications-card {
        display: flex;
        flex-direction: row;
    }
    .main-notification {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .notification-item {
        display: flex;
        flex-direction: row;
        border: none;
        box-shadow: 0px 4px 1px rgba(0.3,0.4,0.2,0.2);
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
        box-shadow: 0px 4px 1px rgba(0.3,0.4,0.2,0.2);
        margin-top: 10px;
    }
    .notif-icon {
        width: 10%;
    }
    .notif-icon img {
        width: 100%;
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
    .dropdown-menu {
        position: absolute;
        right: 0;
        top: 30px;
        background: #fff;
        border-radius: 20px 0px 20px 20px;
        box-shadow: 4px 6px 12px rgba(0,0,0,0.5);
        padding: 5px 10px;
        display: flex;
        flex-direction: column;
        z-index: 10;
        width: 170px;
    }

    .mark-all-read, .open-notifications {
        background: none;
        border: none;
        text-align: left;
        font-size: 12px;
        cursor: pointer;
        border-radius: 6px;
        font-family: 'Poppins-Bold';
        color: #435F77;
        display: flex;
        gap: 10px;
        align-items: center;
        flex-direction: row;
    }

    .dropdown-menu button:hover {
        background: #f3f4f6;
    }

    .white-line {
        width: 100%;
        border-radius: 10px;
        border: 1px solid #435F77;
    }

    .mark-all-read img{
        width: 25px;
        height: 20px;
    }

    .open-notifications img{
        width: 20px;
        height: 20px;
    }
    .notification-buttons {
        display: flex;
        margin-top: 10px;
        margin-bottom: 10px;
    }
</style>
