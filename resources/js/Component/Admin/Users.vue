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
                        <h3>Email</h3>
                    </div>
                    <div class="rowThreeH">
                        <h3>Full Name</h3>
                    </div>
                    <div class="rowFourH">
                        <h3>Full Name</h3>
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
                            <p>{{ user.email }}</p>
                        </div>
                        <div class="rowThreeB">
                            <p>{{ user.user_info?.fullName }}</p>
                        </div>
                        <div class="rowFourB">
                            <p>{{ user.user_info?.userName }}</p>
                        </div>

                        <div class="rowFiveB">
                            <p>{{user.status}}</p>
                        </div>
                        <div class="rowSixB">
                            <button
                                class="action-btn"
                                @click="blockUser(user.id)">
                                {{ user.status.toLowerCase() === 'blocked' ? 'Unblock' : 'Block' }}
                            </button>
                            <button class="action-btn">View</button>
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
                        <h3>Email</h3>
                    </div>
                    <div class="rowThreeH">
                        <h3>Full Name</h3>
                    </div>
                    <div class="rowFourH">
                        <h3>Full Name</h3>
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
                            <p>{{ user.email }}</p>
                        </div>
                        <div class="rowThreeB">
                            <p>{{ user.user_info?.fullName }}</p>
                        </div>
                        <div class="rowFourB">
                            <p>{{ user.user_info?.userName }}</p>
                        </div>

                        <div class="rowFiveB">
                            <p>{{user.status}}</p>
                        </div>
                        <div class="rowSixB">
                            <button
                                class="action-btn"
                                @click="blockUser(user.id)">
                                {{ user.status.toLowerCase() === 'blocked' ? 'Unblock' : 'Block' }}
                            </button>
                            <button class="action-btn">View</button>
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

</script>

<style scoped>
    .user-main-body {
        width: 100%;
        height: 100vh;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
        width: 160px;
        height: 40px;
        background-color: #435F77;
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: 0.3s ease;
        font-size: 14px;
    }

    .top-bttn-container button.active {
        background-color: #2C3E50;
        font-weight: bold;
        transform: scale(1.05);
    }

    .top-bttn-container button:hover {
        background-color: #34495E;
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

    /* Header */
    .table-head {
        width: 100%;
        height: 50px;
        display: flex;
        background-color: #435F77;
        color: white;
        font-weight: 600;
    }

    .table-head > div {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Row sizing */
    .rowOneH,.rowOneB { width: 10%; }
    .rowTwoH,.rowTwoB { width: 25%; }
    .rowThreeH,.rowThreeB { width: 20%; }
    .rowFourH,.rowFourB { width: 25%; }
    .rowFiveH,.rowFiveB { width: 10%; }
    .rowSixH,.rowSixB { width: 20%; }

    /* Body */
    .table-body {
        display: flex;
        flex-direction: column;
        width: 100%;
        overflow: auto;
    }

    /* Table rows */
    .body-item {
        display: flex;
        height: 50px;
        align-items: center;
        transition: background-color 0.2s ease;
        border-bottom: 1px solid #e0e0e0;
    }

    .body-item:nth-child(even) {
        background-color: #f9f9f9;
    }

    .body-item:hover {
        background-color: #eef3f8;
    }

    /* Cells */
    .rowOneB, .rowTwoB, .rowThreeB, .rowFourB, .rowFiveB, .rowSixB {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 8px;
        font-size: 14px;
        color: #333;
    }

    .rowTwoB, .rowThreeB, .rowFourB {
        justify-content: flex-start;
    }

    /* Buttons */
    .rowSixB {
        gap: 8px;
    }

    .rowSixB button {
        padding: 6px 12px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        font-size: 13px;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .rowSixB button:first-child {
        background-color: #e74c3c;
        color: white;
    }

    .rowSixB button:first-child:hover {
        background-color: #c0392b;
    }

    .rowSixB button:last-child {
        background-color: #3498db;
        color: white;
    }

    .rowSixB button:last-child:hover {
        background-color: #217dbb;
    }

</style>

