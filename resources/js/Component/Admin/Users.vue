<template>
    <div class="user-main-body">
        <div class="top-bttn-container">
            <button
                :class="{ active: activeTab === 'all' }"
                @click="activeTab = 'all'">
                All
            </button>
            <button
                :class="{ active: activeTab === 'blocked' }"
                @click="activeTab = 'blocked'">
                Blocked
            </button>
        </div>

        <!-- All Users Table -->
        <div
            class="user-main-container-all"
            v-if="activeTab === 'all'"
        >
            <div class="table">
                <div class="table-head">
                    <div class="rowOneH">
                        <h3>ID</h3>
                    </div>
                    <div class="rowTwoH">
                        <h3>Full Name</h3>
                    </div>
                    <div class="rowThreeH">
                        <h3>User Name</h3>
                    </div>
                    <div class="rowFourH">
                        <h3>Email</h3>
                    </div>
                    <div class="rowSevenH">
                        <h3>Gender</h3>
                    </div>
                    <div class="rowFiveH">
                        <h3>Status</h3>
                    </div>
                    <div class="rowSixH">
                        <h3>Action</h3>
                    </div>
                </div>
                <div class="table-body">
                    <div class="body-item"
                         v-for="user in users" :key="user.id"
                    >
                        <div class="rowOneB">
                            <p>{{ user.id }}</p>
                        </div>
                        <div class="rowTwoB">
                            <p>{{ user.user_info?.fullName  }}</p>
                        </div>
                        <div class="rowThreeB">
                            <p>@{{ user.user_info?.userName }}</p>
                        </div>
                        <div class="rowFourB">
                            <p>{{ user.email }}</p>
                        </div>
                        <div class="rowSevenB">
                            <p
                                :style="{
                                color: user.user_info?.gender === 'male' ? '#435F77' : '#ec3f57'
                                }"
                            >
                                {{ genderLabel[user.user_info?.gender] }}
                            </p>
                        </div>
                        <div class="rowFiveB">
                            <p
                                :style="{
                                color: user.status === 'active' ? 'green' : 'red'
                                }"
                            >
                                {{ statusLabel[user.status] }}
                            </p>
                        </div>
                        <div class="rowSixB">
                            <button
                                class="action-btn"
                                @click="blockUser(user.id)"
                                :class="user.status.toLowerCase() === 'blocked' ? 'unblock' : 'block'"
                            >
                                {{ user.status.toLowerCase() === 'blocked' ? 'Unblock' : 'Block' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Blocked Users Table -->
        <div
            class="user-main-container-blocked"
            v-if="activeTab === 'blocked'"
        >
            <div class="table">
                <div class="table-head">
                    <div class="rowOneH">
                        <h3>ID</h3>
                    </div>
                    <div class="rowTwoH">
                        <h3>Full Name</h3>
                    </div>
                    <div class="rowThreeH">
                        <h3>User Name</h3>
                    </div>
                    <div class="rowFourH">
                        <h3>Email</h3>
                    </div>
                    <div class="rowSevenH">
                        <h3>Gender</h3>
                    </div>
                    <div class="rowFiveH">
                        <h3>Status</h3>
                    </div>
                    <div class="rowSixH">
                        <h3>Action</h3>
                    </div>
                </div>
                <div class="table-body">
                    <div class="body-item"
                         v-for="(user, index) in blockedUsers" :key="user.id"
                    >
                        <div class="rowOneB">
                            <p>{{ user.id }}</p>
                        </div>
                        <div class="rowTwoB">
                            <p>{{ user.user_info?.fullName  }}</p>
                        </div>
                        <div class="rowThreeB">
                            <p>@{{ user.user_info?.userName }}</p>
                        </div>
                        <div class="rowFourB">
                            <p>{{ user.email }}</p>
                        </div>
                        <div class="rowSevenB">
                            <p
                                :style="{
                                color: user.user_info?.gender === 'male' ? '#435F77' : '#ec3f57'
                                }"
                            >
                                {{ genderLabel[user.user_info?.gender] }}
                            </p>
                        </div>
                        <div class="rowFiveB">
                            <p
                                :style="{
                                color: user.status === 'active' ? '#00ff00' : '#ff0000'
                                }"
                            >
                                {{ statusLabel[user.status] }}
                            </p>
                        </div>
                        <div class="rowSixB">
                            <button
                                class="action-btn"
                                @click="blockUser(user.id)"
                                :class="user.status.toLowerCase() === 'blocked' ? 'unblock' : 'block'"
                            >
                                {{ user.status.toLowerCase() === 'blocked' ? 'Unblock' : 'Block' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref, computed } from 'vue'
    import axios from "axios";

    const props = defineProps({
        usersInfo: {
            type: Array,
            default: () => []
        }
    })

    const activeTab = ref('all')

    const blockedUsers = computed(() => props.usersInfo.filter(user => user.status === 'blocked'))

    const users = ref([...props.usersInfo])

    async function blockUser(userId) {
        try {
            const response = await axios.post('/block-user', {
                user_id: userId
            })
            console.log(response.data.message)

            const index = users.value.findIndex(user => user.id === userId)
            if (index !== -1) {
                // Toggle status to match backend
                users.value[index].status =
                    users.value[index].status.toLowerCase() === 'blocked'
                        ? 'Active'
                        : 'blocked'
            }
        } catch (error) {
            console.error(error.response?.data || error.message)
        }

    }

    const genderLabel = {
        male: 'Male',
        female: 'Female'
    }

    const statusLabel = {
        active: 'Active',
        blocked: 'Blocked'
    }

</script>

<style scoped>
    .user-main-body {
        width: 100%;
        height: 100vh;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        background-color: #f6f8fa;
    }

    /* Top buttons */
    .top-bttn-container {
        width: 100%;
        height: 10%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 20px;
    }

    .top-bttn-container button {
        width: 140px;
        height: 40px;
        border: none;
        border-radius: 20px;
        background: #435F77;
        color: white;
        cursor: pointer;
        font-size: 13px;
        font-family: 'Poppins-Bold';
    }

    .top-bttn-container button.active {
        background: #E0E7FF;
        color: #435F77;
        box-shadow: 4px 4px 12px #AFADAD;
        border-right: #AFADAD solid 1px;
    }

    .top-bttn-container button:hover {
        transform: scale(1.1);
    }

    /* Table containers */
    .user-main-container-all,
    .user-main-container-blocked {
        padding: 20px;
        overflow: auto;
        display: flex;
        justify-content: center;
        max-height: 75%;
    }

    /* Table */
    .table {
        width: 95%;
        display: flex;
        flex-direction: column;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    /* Shared column sizing */
    .rowOne, .rowOneH, .rowOneB { width: 5%; }
    .rowTwo, .rowTwoH, .rowTwoB { width: 20%; }
    .rowThree, .rowThreeH, .rowThreeB { width: 15%; }
    .rowFour, .rowFourH, .rowFourB { width: 20%; }
    .rowFive, .rowFiveH, .rowFiveB { width: 15%; }
    .rowSix, .rowSixH, .rowSixB { width: 10%; }
    .rowSeven, .rowSevenH, .rowSevenB { width: 15%; }

    /* Force borders + padding into width */
    .table-head > div,
    .rowOneB, .rowTwoB, .rowThreeB, .rowFourB, .rowFiveB, .rowSixB, .rowSevenB {
        box-sizing: border-box;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Poppins-Bold';
    }

    .rowFourB {
        color: #435F77;
        text-decoration: underline;
        padding: 0 8px;
        font-size: 14px;
    }

    /* Head row */
    .table-head {
        display: flex;
        font-size: 12px;
        color: black;
        font-family: 'Poppins-Bold';
        text-align: center;
        background-color: #7592AB;
    }

    /* Body container */
    .table-body {
        display: flex;
        flex-direction: column;
        width: 100%;
        overflow: auto;
        -ms-overflow-style: none; 
        scrollbar-width: none; 
    }
    .table-body::-webkit-scrollbar { display: none; }

    /* Body rows */
    .body-item {
        display: flex;
        height: 50px;
        align-items: center;
        transition: background-color 0.2s ease;
        border-bottom: 1px solid #e5e7eb;
    }
    .body-item:nth-child(even) { background-color: #f9f9f9; }
    .body-item:hover { background-color: #eef3f8; }

    /* Body cells */
    .rowOneB, .rowFiveB, .rowSixB, .rowSevenB {
        padding: 0 8px;
        font-size: 14px;
        color: #333;
    }

    .rowTwoB{ 
        justify-content: flex-start; 
        padding: 0 15px;
        font-size: 14px;
        color: #333;
    }
    
    .rowThreeB{  
        padding: 0 15px;
        font-size: 15px;
        color: #333;
        font-family: 'Poppins-BoldItalic';
    }
    /* Buttons */
    .rowSixB {
        gap: 8px;
    }
    .rowOneB, .rowTwoB, .rowThreeB, .rowFourB, .rowFiveB, .rowSevenB {
        border-right: 1px solid #e5e7eb;
    }
    .rowSixB .action-btn {
        width: 120px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 20px;
        border-right: 2px solid #31485B;
        border-bottom: 2px solid #31485B;
        border-top: none;
        border-left: none;
        font-family: 'Poppins-Bold';
        font-size: 12px;
        cursor: pointer;
        transition: transform 0.2s ease, background-color 0.2s ease;
    }

    .rowSixB .block {
        background-color: #ff0000;
        color: white;
    }

    .rowSixB .unblock {
        background-color: #00ff00;
        color: white;
    }

    .rowSixB .action-btn:hover {
        transform: scale(1.1);
    }


</style>

