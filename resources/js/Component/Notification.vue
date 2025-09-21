<template>
    <div class="notification-main-body">
        <div class="notification-header">
            <button>
                <img src="/public/images/Button-icon/back.png" alt="back"/>
            </button>
            <h2>Notification</h2>
        </div>
        <div class="notification-main">
            <div class="card-main">
                <div class="card-main-header">
                    <button>
                        All
                    </button>
                    <button>
                        Unread
                    </button>
                </div>
                <div class="notification-container">
                    <button v-for="index in 20" :key="index">
                        <h2>{{index}}</h2>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>

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
        color: #768082;
        font-size: 2em;
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
    }
    .card-main-header button {
        width: 80px;
        height: 20px;
    }
    .notification-container{
        width: 100%;
        height: 100%;
        overflow: auto;
        display: flex;
        flex-direction: column;
        border: 1px solid black;
        gap: 10px;
    }
    .notification-container button {
        width: 100%;
        height: 40px;
    }
</style>
