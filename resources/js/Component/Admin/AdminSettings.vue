<template>
    <div class="admin-main-body">
        <div class="page-label">
            <h2>Settings</h2>
        </div>

        <div class="admin-body">
            <div class="settings-button-container">
                <button
                    :class="{ active: activeButton === 'personal' }"
                    @click="activeButton = 'personal'"
                >
                    <img v-if="activeButton === 'personal'" alt="button" src="/public/images/Button-icon/profile_open.png" />
                    <img v-else  alt="button" src="/public/images/Button-icon/profile_notopen.png" />
                    <span class="button-text">Personal Details</span>
                </button>

                <button
                    :class="{ active: activeButton === 'password' }"
                    @click="activeButton = 'password'"
                >
                    <img v-if="activeButton === 'password'" alt="button" src="/public/images/Button-icon/password_open.png" />
                    <img v-else  alt="button" src="/public/images/Button-icon/password_notopen.png" />
                    <span class="button-text">Password & Security</span>
                </button>
            </div>

            <div class="settings-card-container">
                <div v-if="activeButton === 'personal'" class="card-1">
                    <div class="card-label">
                        <h2>Personal Details</h2>
                    </div>
                    <div class="card-body">

                    </div>
                </div>
                <div v-if="activeButton === 'password'" class="card-2">
                    <div class="card-label">
                        <h2>Change Password</h2>
                    </div>
                    <div class="card-body-security">
                        <p>Your password must be at least 6 characters and should include a combination of numbers, letters and special characters (!@$%).</p>
                        <input placeholder="Current Password:" class="current-password" required/>
                        <input placeholder="New Password:" class="new-password" required/>
                        <input placeholder="Re-type new password:" class="re-type-password" required/>
                        <button>
                            Change password
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref } from "vue";

    const activeButton = ref("personal");
</script>


<style scoped>
    .admin-main-body {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .page-label {
        width: 100%;
        display: flex;
        align-items: center;
    }
    .page-label h2 {
        margin: 10px 0 10px 10px;
        font-weight: 900;
        font-size: 1.9em;
        font-family: 'Poppins', sans-serif;
    }
    .admin-body {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: row;
    }
    .settings-button-container {
        height: 100%;
        width: 15%;
        display: flex;
        align-items: end;
        gap: 10px;
        flex-direction: column;
    }
    .settings-button-container button {
        width: 70%;
        height: 100px;
        background-color: #B5BFDE;
        border: none;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 10px;
        margin-top: 10px;
        padding: 0 15px;
        color: #435F77;
        font-size: 1.1em;
    }

    .settings-button-container button img {
        width: 60px;
        height: auto;
        flex-shrink: 0;
    }

    .settings-button-container .button-text {
        flex: 1;
        white-space: normal;
        text-align: left;
        word-break: break-word;
    }

    .settings-button-container button.active {
        background-color: #435F77;
        color: white;
        font-weight: bold;
        transform: scale(1.05);
    }
    .settings-card-container {
        height: 100%;
        width: 85%;
        display: flex;
        align-items: start;
        justify-content: start;
    }
    .card-1,.card-2 {
        width: 80%;
        height: 90%;
        margin: 10px 0 0 50px;
        background-color: #E0E7FF;
        border-radius: 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .card-label {
        width: 100%;
    }
    .card-label h2 {
        margin-left: 30px;
        font-weight: bold;
        font-size: 1.7em;
        color: #31485B;
        text-transform: capitalize;
        font-family: 'Poppins', sans-serif;
    }
    .card-body {
        width: 100%;
        height: 100%;
        border: 1px solid black;
    }
    .card-body-security {
        width: 70%;
        height: 100%;
        display: flex;
        align-items: center;
        flex-direction: column;
        padding-left: 50px;
        padding-right: 50px;
    }
    .card-body-security p {
        text-align: left;
        align-self: start;
    }
    .card-body-security input {
        width: 95%;
        height: 50px;
        padding-left: 20px;
        padding-right: 20px;
        background-color: #B5BFDE;
        margin-top: 3px;
        border: none;
    }
    .card-body-security input::placeholder {
        color: white;
        margin-left: 10px;
    }
    .current-password {
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }
    .re-type-password {
        border-bottom-left-radius: 10px;
        border-bottom-right-radius: 10px;
    }
    .card-body-security button {
        align-self: end;
        width: 200px;
        height: 40px;
        border-radius: 20px;
        margin-top: 20px;
        background-color: #435F77;
        color: white;
        border: none;
        cursor: pointer;
    }
</style>
