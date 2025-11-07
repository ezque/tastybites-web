<template>
    <div class="w-full h-screen flex flex-col overflow-hidden bg-[#f6f8fa]">
        <div class="flex justify-between items-center">
            <div class="mt-5 ml-5">
                <h2 class="text-[35px] font-[Poppins-Bold]">Users</h2>
            </div>

            <!-- Search -->
            <div class="flex items-center bg-[#F5F5F5] px-1 py-1 mr-5 mt-3 rounded-full border-r border-[#B5BFDE] border-b-[3px] border-b-[#B5BFDE]">
                <input
                    v-model="searchQuery"
                    placeholder="Search Users"
                    class="border-none outline-none w-[200px] px-5 py-2 bg-[#435F77] rounded-full font-[Poppins-Italic] text-white"
                />
                <img src="/public/images/Button-icon/search.png" alt="icon" class="w-[20px] h-[20px] ml-1 cursor-pointer" />
            </div>
        </div>
        <!-- Top Buttons -->
        <div class="w-full h-[3%] flex items-center justify-center gap-5">
            <button
                :class="[
                'w-[140px] h-[40px] rounded-[20px] cursor-pointer text-[13px] font-[Poppins-Bold] transition-transform duration-300',
                activeTab === 'all'
                ? 'bg-[#E0E7FF] text-[#435F77] shadow-[4px_4px_12px_#AFADAD] border-r border-[#AFADAD]'
                : 'bg-[#435F77] text-white hover:scale-110'
                ]"
                @click="activeTab = 'all'"
            >
                ALL
            </button>
            <button
                :class="[
                'w-[140px] h-[40px] rounded-[20px] cursor-pointer text-[13px] font-[Poppins-Bold] transition-transform duration-300',
                activeTab === 'blocked'
                    ? 'bg-[#E0E7FF] text-[#435F77] shadow-[4px_4px_12px_#AFADAD] border-r border-[#AFADAD]'
                    : 'bg-[#435F77] text-white hover:scale-110'
                ]"
                        @click="activeTab = 'blocked'"
                    >
                        BLOCKED
            </button>
        </div>

        <!-- All Users Table -->
        <div
            class="p-5 overflow-auto flex justify-center max-h-[74%]"
            v-if="activeTab === 'all'"
        >
            <div class="w-[95%] h-[100%] flex flex-col rounded-lg overflow-hidden shadow-md">
                <!-- Table Head -->
                <div class="h-[5%] min-h-[50px] flex text-[14px] text-black font-[Poppins-Bold] text-center bg-[#7592AB] uppercase">
                    <div class="w-[5%] flex justify-center items-center">ID</div>
                    <div class="w-[20%] flex justify-center items-center">Full Name</div>
                    <div class="w-[15%] flex justify-center items-center">User Name</div>
                    <div class="w-[20%] flex justify-center items-center">Email</div>
                    <div class="w-[15%] flex justify-center items-center">Gender</div>
                    <div class="w-[15%] flex justify-center items-center">Status</div>
                    <div class="w-[10%] flex justify-center items-center">Action</div>
                </div>

                <!-- Table Body -->
                <div class="max-h-[90%] flex flex-col w-full overflow-y-auto overflow-x-hidden">
                    <div
                        v-for="user in filteredUsers"
                        :key="user.id"
                        class="w-[100%] flex items-center justify-center border-b-2 border-[#B5BFDE] bg-[#E0E7FF] transition-colors duration-200 hover:bg-[#eef3f8]"
                    >
                        <div class="w-[5%] h-[50px] flex justify-center items-center text-sm font-[Poppins-Regular] border-r-2 border-[#B5BFDE]">{{ user.id }}</div>
                        <div class="w-[20%] h-[50px] flex items-center pl-4 text-sm font-[Poppins-Regular] border-r-2 border-[#B5BFDE]">{{ user.user_info?.fullName }}</div>
                        <div class="w-[15%] h-[50px] flex justify-center items-center px-4 text-[15px] font-[Poppins-BoldItalic] border-r-2 border-[#B5BFDE]">@{{ user.user_info?.userName }}</div>
                        <div class="w-[20%] h-[50px] flex justify-center items-center px-2 text-sm text-[#435F77] font-[Poppins-Italic] underline border-r-2 border-[#B5BFDE]">{{ user.email }}</div>
                        <div
                            class="w-[15%] h-[50px] flex justify-center items-center text-sm font-[Poppins-Bold] border-r-2 border-[#B5BFDE]"
                            :style="{ color: user.user_info?.gender === 'male' ? '#435F77' : '#ec3f57' }"
                        >
                            {{ genderLabel[user.user_info?.gender] }}
                        </div>
                        <div
                            class="w-[15%] h-[50px] flex justify-center items-center text-sm font-[Poppins-Bold] border-r-2 border-[#B5BFDE]"
                            :style="{ color: user.status === 'active' ? 'green' : 'red' }"
                        >
                            {{ statusLabel[user.status] }}
                        </div>
                        <div class="w-[10%] h-[50px] flex justify-center items-center gap-2">
                            <button
                                class="w-[120px] h-[30px] flex items-center justify-center rounded-[20px] border-r-2 border-b-2 border-[#31485B] font-[Poppins-Bold] text-[12px] cursor-pointer transition-transform duration-200 hover:scale-110"
                                @click="updateUserStatus(user.id)"
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
                <div class="h-[5%] min-h-[50px] flex text-[14px] text-black font-[Poppins-Bold] text-center bg-[#7592AB]">
                    <div class="w-[5%] flex justify-center items-center">ID</div>
                    <div class="w-[20%] flex justify-center items-center">Full Name</div>
                    <div class="w-[15%] flex justify-center items-center">User Name</div>
                    <div class="w-[20%] flex justify-center items-center">Email</div>
                    <div class="w-[15%] flex justify-center items-center">Gender</div>
                    <div class="w-[15%] flex justify-center items-center">Status</div>
                    <div class="w-[10%] flex justify-center items-center">Action</div>
                </div>

                <!-- Table Body -->
                <div class="max-h-[90%] flex flex-col w-full overflow-y-auto overflow-x-hidden">
                    <div
                        v-for="user in filteredUsers.filter(u => u.status === 'blocked')"
                        :key="user.id"
                        class="w-[100%] flex items-center justify-center border-b-2 border-[#B5BFDE] bg-[#E0E7FF] transition-colors duration-200 hover:bg-[#eef3f8]"
                    >
                        <div class="w-[5%] h-[50px] flex justify-center items-center text-sm font-[Poppins-Regular] border-r-2 border-[#B5BFDE]">{{ user.id }}</div>
                        <div class="w-[20%] h-[50px] flex items-center pl-4 text-sm font-[Poppins-Regular] border-r-2 border-[#B5BFDE]">{{ user.user_info?.fullName }}</div>
                        <div class="w-[15%] h-[50px] flex justify-center items-center px-4 text-[15px] font-[Poppins-BoldItalic] border-r-2 border-[#B5BFDE]">@{{ user.user_info?.userName }}</div>
                        <div class="w-[20%] h-[50px] flex justify-center items-center px-2 text-sm text-[#435F77] underline font-[Poppins-Italic] border-r-2 border-[#B5BFDE]">{{ user.email }}</div>
                        <div
                            class="w-[15%] h-[50px] flex justify-center items-center text-sm font-[Poppins-Bold] border-r-2 border-[#B5BFDE]"
                            :style="{ color: user.user_info?.gender === 'male' ? '#435F77' : '#ec3f57' }"
                        >
                            {{ genderLabel[user.user_info?.gender] }}
                        </div>
                        <div
                            class="w-[15%] h-[50px] flex justify-center items-center text-sm font-[Poppins-Bold] border-r-2 border-[#B5BFDE]"
                            :style="{ color: user.status === 'active' ? '#00ff00' : '#ff0000' }"
                        >
                            {{ statusLabel[user.status] }}
                        </div>
                        <div class="w-[10%] h-[50px] flex justify-center items-center gap-2">
                            <button
                                class="w-[120px] h-[30px] flex items-center justify-center rounded-[20px] border-r-2 border-b-2 border-[#31485B] font-[Poppins-Bold] text-[12px] cursor-pointer transition-transform duration-200 hover:scale-110"
                                @click="updateUserStatus(user.id)"
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



    const users = ref([...props.usersInfo])
    const searchQuery = ref("");
    const activeTab = ref('all')

    const blockedUsers = computed(() =>
        users.value.filter(user => user.status === 'blocked')
    )
    const filteredUsers = computed(() => {
        const query = searchQuery.value.toLowerCase();
        return users.value.filter(user =>
            user.user_info?.fullName?.toLowerCase().includes(query) ||
            user.user_info?.userName?.toLowerCase().includes(query) ||
            user.email?.toLowerCase().includes(query)
        );
    });

    async function updateUserStatus(userId) {
        try {
            const response = await axios.post('/update-user-status', { user_id: userId });
            console.log(response.data.message);

            const newStatus = response.data.new_status; // <- backend gives us this
            const index = users.value.findIndex(user => user.id === userId);

            if (index !== -1) {
                users.value[index].status = newStatus;
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


