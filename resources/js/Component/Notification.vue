<template>
    <div class="notification-main-body">
        <div class="notification-header">
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
                    <button class="notification-item">
                        <div class="notif-icon">
                            <img src="/public/images/Button-icon/follow.png" alt="follow" />
                        </div>
                        <div class="notif-content">
                            <p><strong>@appletamesis</strong> is now following you!</p>
                            <span class="time">Just now</span>
                        </div>
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
    .notification-container{
                display: flex;
        flex-direction: column;
        gap: 10px;
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
        box-shadow: 0px 4px 1px #AFADAD;
        margin-top: 10px;
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
</style>
