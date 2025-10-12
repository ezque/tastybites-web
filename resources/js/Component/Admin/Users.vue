<template>
    <div class="w-full h-screen flex flex-col overflow-hidden bg-[#f6f8fa]">
        <!-- Top Buttons -->
        <div class="w-full h-[10%] flex items-center justify-center gap-5">
            <button
                :class="[
          'w-[140px] h-[40px] rounded-[20px] bg-[#435F77] text-white cursor-pointer text-[13px] font-[Poppins-Bold] transition-transform duration-300',
          activeTab === 'all'
            ? 'bg-[#E0E7FF] text-[#435F77] shadow-[4px_4px_12px_#AFADAD] border-r border-[#AFADAD]'
            : 'hover:scale-110'
        ]"
                @click="activeTab = 'all'"
            >
                All
            </button>
            <button
                :class="[
          'w-[140px] h-[40px] rounded-[20px] bg-[#435F77] text-white cursor-pointer text-[13px] font-[Poppins-Bold] transition-transform duration-300',
          activeTab === 'blocked'
            ? 'bg-[#E0E7FF] text-[#435F77] shadow-[4px_4px_12px_#AFADAD] border-r border-[#AFADAD]'
            : 'hover:scale-110'
        ]"
                @click="activeTab = 'blocked'"
            >
                Blocked
            </button>
        </div>

        <!-- All Users Table -->
        <div
            class="p-5 overflow-auto flex justify-center max-h-[75%]"
            v-if="activeTab === 'all'"
        >
            <div class="w-[95%] h-[100%] flex flex-col rounded-lg overflow-hidden shadow-md">
                <!-- Table Head -->
                <div class="h-[10%] min-h-[60px] flex text-[12px] text-black font-[Poppins-Bold] text-center bg-[#7592AB]">
                    <div class="w-[5%] flex justify-center items-center">ID</div>
                    <div class="w-[20%] flex justify-center items-center">Full Name</div>
                    <div class="w-[15%] flex justify-center items-center">User Name</div>
                    <div class="w-[20%] flex justify-center items-center">Email</div>
                    <div class="w-[15%] flex justify-center items-center">Gender</div>
                    <div class="w-[15%] flex justify-center items-center">Status</div>
                    <div class="w-[10%] flex justify-center items-center">Action</div>
                </div>

                <!-- Table Body -->
                <div class="h-[90%] gap-4 mt-5 flex flex-col w-full overflow-auto">
                    <div
                        v-for="user in users"
                        :key="user.id"
                        class="flex h-[50px] items-center justify-center border-b border-gray-200 even:bg-[#f9f9f9] hover:bg-[#eef3f8] transition-colors duration-200"
                    >
                        <div class="w-[5%] flex justify-center items-center text-sm text-gray-800 border-r border-gray-200">{{ user.id }}</div>
                        <div class="w-[20%] flex items-center pl-4 text-sm text-gray-800 border-r border-gray-200">{{ user.user_info?.fullName }}</div>
                        <div class="w-[15%] flex items-center px-4 text-[15px] text-gray-800 font-[Poppins-BoldItalic] border-r border-gray-200">@{{ user.user_info?.userName }}</div>
                        <div class="w-[20%] flex items-center px-2 text-sm text-[#435F77] underline border-r border-gray-200">{{ user.email }}</div>
                        <div
                            class="w-[15%] flex justify-center items-center text-sm font-[Poppins-Bold] border-r border-gray-200"
                            :style="{ color: user.user_info?.gender === 'male' ? '#435F77' : '#ec3f57' }"
                        >
                            {{ genderLabel[user.user_info?.gender] }}
                        </div>
                        <div
                            class="w-[15%] flex justify-center items-center text-sm font-[Poppins-Bold] border-r border-gray-200"
                            :style="{ color: user.status === 'active' ? 'green' : 'red' }"
                        >
                            {{ statusLabel[user.status] }}
                        </div>
                        <div class="w-[10%] flex justify-center items-center gap-2">
                            <button
                                class="w-[120px] h-[30px] flex items-center justify-center rounded-[20px] border-r-2 border-b-2 border-[#31485B] font-[Poppins-Bold] text-[12px] cursor-pointer transition-transform duration-200 hover:scale-110"
                                @click="blockUser(user.id)"
                                :class="user.status.toLowerCase() === 'blocked' ? 'bg-[#435F77] text-white' : 'bg-red-600 text-white'"
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
            class="p-5 overflow-auto flex justify-center max-h-[75%]"
            v-if="activeTab === 'blocked'"
        >
            <div class="w-[95%] h-[100%] flex flex-col rounded-lg overflow-hidden shadow-md">
                <!-- Table Head -->
                <div class="h-[10%] min-h-[60px] flex text-[12px] text-black font-[Poppins-Bold] text-center bg-[#7592AB]">
                    <div class="w-[5%] flex justify-center items-center">ID</div>
                    <div class="w-[20%] flex justify-center items-center">Full Name</div>
                    <div class="w-[15%] flex justify-center items-center">User Name</div>
                    <div class="w-[20%] flex justify-center items-center">Email</div>
                    <div class="w-[15%] flex justify-center items-center">Gender</div>
                    <div class="w-[15%] flex justify-center items-center">Status</div>
                    <div class="w-[10%] flex justify-center items-center">Action</div>
                </div>

                <!-- Table Body -->
                <div class="h-[90%] gap-4 mt-5 flex flex-col w-full overflow-auto">
                    <div
                        v-for="user in blockedUsers"
                        :key="user.id"
                        class="flex h-[50px] items-center justify-center border-b border-gray-200 even:bg-[#f9f9f9] hover:bg-[#eef3f8] transition-colors duration-200"
                    >
                        <div class="w-[5%] flex justify-center items-center text-sm text-gray-800 border-r border-gray-200">{{ user.id }}</div>
                        <div class="w-[20%] flex items-center pl-4 text-sm text-gray-800 border-r border-gray-200">{{ user.user_info?.fullName }}</div>
                        <div class="w-[15%] flex items-center px-4 text-[15px] text-gray-800 font-[Poppins-BoldItalic] border-r border-gray-200">@{{ user.user_info?.userName }}</div>
                        <div class="w-[20%] flex items-center px-2 text-sm text-[#435F77] underline border-r border-gray-200">{{ user.email }}</div>
                        <div
                            class="w-[15%] flex justify-center items-center text-sm font-[Poppins-Bold] border-r border-gray-200"
                            :style="{ color: user.user_info?.gender === 'male' ? '#435F77' : '#ec3f57' }"
                        >
                            {{ genderLabel[user.user_info?.gender] }}
                        </div>
                        <div
                            class="w-[15%] flex justify-center items-center text-sm font-[Poppins-Bold] border-r border-gray-200"
                            :style="{ color: user.status === 'active' ? '#00ff00' : '#ff0000' }"
                        >
                            {{ statusLabel[user.status] }}
                        </div>
                        <div class="w-[10%] flex justify-center items-center gap-2">
                            <button
                                class="w-[120px] h-[30px] flex items-center justify-center rounded-[20px] border-r-2 border-b-2 border-[#31485B] font-[Poppins-Bold] text-[12px] cursor-pointer transition-transform duration-200 hover:scale-110"
                                @click="blockUser(user.id)"
                                :class="user.status.toLowerCase() === 'blocked' ? 'bg-[#435F77] text-white' : 'bg-red-600 text-white'"
                            >
                                {{ user.status.toLowerCase() === 'blocked' ? 'Unblock' : 'Block' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <Footer />
    </div>
</template>


<script setup>
    import { ref, computed } from 'vue'
    import axios from "axios";
    import Footer from "@/Component/Footer.vue";

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
            });
            console.log(response.data.message);

            const index = users.value.findIndex(user => user.id === userId);
            if (index !== -1) {
<<<<<<< HEAD
            // Toggle status correctly (use lowercase to match filters and display)
            users.value[index].status =
                users.value[index].status.toLowerCase() === 'blocked'
                ? 'active'
                : 'blocked';
=======
                // Toggle status to match backend
                users.value[index].status =
                    users.value[index].status.toLowerCase() === 'blocked'
                        ? 'active'
                        : 'blocked'
>>>>>>> e68e7a1024ea383a889e53ee8dedd10dd606619a
            }
        } catch (error) {
            console.error(error.response?.data || error.message);
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

<<<<<<< HEAD
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
        overflow-y: scroll;
        scrollbar-width: none;
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
        font-size: 14px;
        color: black;
        font-family: 'Poppins-Bold';
        text-align: center;
        background-color: #7592AB;
        padding: 12px;
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
        background-color: #435F77;
        color: white;
    }

    .rowSixB .action-btn:hover {
        transform: scale(1.1);
    }


</style>
=======
>>>>>>> e68e7a1024ea383a889e53ee8dedd10dd606619a

