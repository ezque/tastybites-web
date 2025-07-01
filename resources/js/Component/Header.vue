<template>
    <div class="container">
        <img src="/public/images/tastybites_logo.png" class="logo"/>
        <div class="right-side-container">
            <button class="rs-button">
                <img src="/public/images/Button-icon/search.png"/>
            </button>
            <button class="rs-button">
                <img src="/public/images/Button-icon/notifications.png"/>
            </button>
            <button class="menu-button">
                <div class="profile-container">

                </div>
                <h1>{{ user?.user_info?.fullName ?? 'User' }}</h1>
            </button>
        </div>
        <div class="menu-container">

        </div>
    </div>
</template>

<script setup>
    defineProps({
        user: Object
    })
</script>

<style scoped>
    .container {
        height: 50px;
        background-color: #E0E7FF;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px;
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
        width: 100px;
        height: 100px;
        border: 1px solid black;
        right: 1%;
        top: 10%;
        display: flex;
        flex-direction: row;

    }
</style>
