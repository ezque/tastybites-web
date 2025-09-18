<template>
    <div class="container">
        <img src="/public/images/tastybites_logo.png" class="logo"/>
        <div class="right-side-container">
            <button class="rs-button">
                <img src="/public/images/Button-icon/search.png"/>
            </button>
            <div class="notification-box">
                <button class="rs-button">
                    <img src="/public/images/Button-icon/notifications.png"/>
                </button>
                <div class="notification-container">
                    <div class="notification-label-container">
                        <h2>Notifications</h2>
                    </div>
                    <div class="all-notifications-box">
                        <div class="notifications-card" v-for="i in 5" :key="i">
                            <h2>Notification</h2>
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
                <button v-if="profileIcon" @click="emit('navigate','Profile')">
                    <img :src="profileIcon" alt="Profile Icon" />
                    <h1>Profile</h1>
                </button>
                <span class="white-line" v-if="profileIcon"></span>
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
        user: Object
    })

    const isMenuVisible = ref(false);

    const toggleMenu = () => {
        isMenuVisible.value = !isMenuVisible.value;
    }

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
    const emit = defineEmits(['navigate'])
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
        display: none;
    }
    .notification-container {
        position: absolute;
        border: 1px solid black;
        right: 0;
        top: 40px;
        padding: 10px;
        display: flex;
        flex-direction: column;
        background-color: #435F77;
        border-radius: 20px 0 20px 20px;
        z-index: 199;
    }
    .notification-label-container {
        width: 250px;
        height: 50px;
        display: flex;
        align-items: center;
    }
    .notification-label-container h2 {
        color: white;
    }
    .all-notifications-box {
        max-height: 300px;
        height: 500px;
        width: 100%;
        border: 1px solid black;
        display: flex;
        flex-direction: column;
        overflow: auto;
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
        border: 1px solid black;
        display: flex;
        flex-direction: row;
    }
</style>
