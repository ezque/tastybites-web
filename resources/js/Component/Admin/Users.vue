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



    const users = ref([...props.usersInfo])

    const activeTab = ref('all')

    const blockedUsers = computed(() =>
        users.value.filter(user => user.status === 'blocked')
    )

    async function blockUser(userId) {
        try {
            const response = await axios.post('/block-user', { user_id: userId });
            console.log(response.data.message);

            const index = users.value.findIndex(user => user.id === userId);
            if (index !== -1) {
                // Toggle status to match backend
                users.value[index].status =
                    users.value[index].status.toLowerCase() === 'blocked'
                        ? 'active'
                        : 'blocked'

                users.value[index].status =
                    users.value[index].status.toLowerCase() === 'blocked'
                        ? 'active'
                        : 'blocked';

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


