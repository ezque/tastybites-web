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
            v-if="activeTab === 'all'">
            <table class="user-table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Username</th>
                    <th>Full Name</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <tr  v-for="user in users" :key="user.id">
                    <td>{{ user.id }}</td>
                    <td>{{ user.email }}</td>
                    <td>{{ user.user_info?.userName }}</td>
                    <td>{{ user.user_info?.fullName}}</td>
                    <td>{{user.status}}</td>
                    <td>
                        <button
                            class="action-btn"
                            @click="blockUser(user.id)">
                            {{ user.status.toLowerCase() === 'blocked' ? 'Unblock' : 'Block' }}
                        </button>

                        <button class="action-btn">View</button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <!-- Blocked Users Table -->
        <div
            class="user-main-container-blocked"
            v-if="activeTab === 'blocked'">
            <table class="user-table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Username</th>
                    <th>Full Name</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(user, index) in blockedUsers" :key="user.id">
                    <td>{{ user.id }}</td>
                    <td>{{ user.email }}</td>
                    <td>{{ user.user_info?.username }}</td>
                    <td>{{ user.user_info?.fullName}}</td>
                    <td>{{user.status}}</td>
                    <td>
                        <button
                            class="action-btn unblock"
                            @click="blockUser(user.id)"
                        >
                            Unblock
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>
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
    }

    .top-bttn-container {
        width: 100%;
        height: 10%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 20px;
    }

    .top-bttn-container button {
        width: 180px;
        height: 40px;
        background-color: #435F77;
        color: white;
        border: none;
        border-radius: 20px;
        cursor: pointer;
        transition: 0.3s ease;
    }

    .top-bttn-container button.active {
        background-color: #2C3E50;
        font-weight: bold;
        transform: scale(1.05);
    }

    .user-main-container-all,
    .user-main-container-blocked {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
    }

    .user-table {
        width: 100%;
        border-collapse: collapse;
    }

    .user-table th{
        text-align: center;
    }

    .user-table td:nth-child(1) {
        text-align: center;
    }


    .user-table th,
    .user-table td {
        padding: 10px;
        border: 1px solid #ccc;
    }

    .user-table th {
        background-color: #f4f4f4;
    }

    .action-btn {
        padding: 5px 10px;
        border: none;
        border-radius: 5px;
        background-color: crimson;
        color: white;
        cursor: pointer;
    }

    .action-btn.unblock {
        background-color: green;
    }
</style>

