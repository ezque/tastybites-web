<template>
    <div class="w-full h-screen overflow-hidden flex flex-col">
        <!-- Top Section -->
        <div class="flex flex-col gap-4">
            <!-- Label Row -->
            <div class="flex justify-between items-center">
                <div class="mt-5 ml-5">
                    <h2 v-if="activeTab === 'register'" class="text-[35px] font-[Poppins-Bold]">Registered Chefs</h2>
                    <h2 v-if="activeTab === 'request'" class="text-[35px] font-[Poppins-Bold]">Chefs Request</h2>
                    <h2 v-if="activeTab === 'block'" class="text-[35px] font-[Poppins-Bold]">Blocked Chefs</h2>
                </div>

                <!-- Search -->
                <div class="flex items-center bg-[#F5F5F5] px-1 py-1 mr-5 mt-3 rounded-full border-r border-[#B5BFDE] border-b-[3px] border-b-[#B5BFDE]">
                    <input
                        placeholder="Search Chefs"
                        class="border-none outline-none w-[200px] px-5 py-2 bg-[#435F77] rounded-full font-[Poppins-Italic] text-white"
                    />
                    <img src="/public/images/Button-icon/search.png" alt="icon" class="w-[20px] h-[20px] ml-1 cursor-pointer" />
                </div>
            </div>

            <!-- Nav Buttons -->
            <div class="flex justify-center gap-[30px] -mt-5">
                <button
                    :class="[
                        'px-10 py-2 rounded-[20px] cursor-pointer text-[13px] font-[Poppins-Bold] transition-transform duration-300',
                        activeTab === 'register'
                        ? 'bg-[#E0E7FF] text-[#435F77] shadow-[4px_4px_12px_#AFADAD] border-r border-[#AFADAD]'
                        : 'bg-[#435F77] text-white hover:scale-110'
                    ]"
                    @click="activeTab = 'register'"
                >
                    REGISTERED
                </button>

                <button
                    :class="[
                        'px-10 py-2 rounded-[20px] cursor-pointer text-[13px] font-[Poppins-Bold] transition-transform duration-300',
                        activeTab === 'request'
                          ? 'bg-[#E0E7FF] text-[#435F77] shadow-[4px_4px_12px_#AFADAD] border-r border-[#AFADAD]'
                          : 'bg-[#435F77] text-white hover:scale-110'
                    ]"
                    @click="activeTab = 'request'"
                >
                    PENDING REQUESTS
                </button>
                <button
                    :class="[
                        'px-10 py-2 rounded-[20px] text-[13px] font-[Poppins-Bold] transition-transform duration-300',
                        activeTab === 'block'
                          ? 'bg-[#E0E7FF] text-[#435F77] shadow-[4px_4px_12px_#AFADAD] border-r border-[#AFADAD]'
                          : 'bg-[#435F77] text-white hover:scale-110'
                    ]"
                    @click="activeTab = 'block'"
                >
                    BLOCKED
                </button>
            </div>
        </div>

        <!-- Registered Chefs -->
        <div v-show="activeTab === 'register'" class="w-full h-[70%] mt-8 flex flex-wrap justify-center gap-[30px] overflow-auto">
            <div
            v-for="chef in activeChefs"
            :key="chef.id"
            class="relative w-[220px] h-[250px] flex flex-col items-center justify-center rounded-[20px] bg-[#E0E7FF] shadow-[5px_4px_2px_#AFADAD] cursor-pointer"
            >
            <span class="w-[73%] h-[60%] rounded-full mb-3">
                <img :src="getProfilePic(chef)" alt="img" class="w-full h-full rounded-full" />
            </span>

            <!-- Three-dot menu button -->
            <button
                class="absolute top-[5px] right-[5px] mt-[10px] w-[20px] flex items-center justify-center bg-transparent border-none cursor-pointer"
                @click.stop="toggleMenu(chef.id)"
            >
                <img alt="icon" src="/public/images/Button-icon/option.png" class="w-[10px] h-[24px]" />
            </button>

            <!-- Menu for this specific chef -->
            <div
                v-if="menuOpen === chef.id"
                class="absolute left-[88%] top-[45px] ml-[10px] bg-[#435F77] rounded-tr-[10px] rounded-bl-[10px] rounded-br-[10px] z-[9] p-[10px] flex flex-col gap-[10px]"
            >
                <button
                class="w-[90px] h-[25px] bg-transparent border-none flex items-center justify-center text-white gap-[15px] cursor-pointer font-[Poppins-Bold] hover:-translate-y-[2px] transition-all duration-200 ease-in-out"
                >
                <img alt="icon" src="/public/images/Button-icon/block.png" class="h-[90%] w-[30%]" />
                <span class="w-[70%] text-left">Block</span>
                </button>
            </div>

            <span class="text-[20px] font-[Poppins-Bold] truncate max-w-[90%] text-center">
                {{ capitalizeFullName(chef.user_info?.fullName) }}
            </span>
            <span class="font-[Poppins-Italic] text-[0.9em]">
                Since {{ new Date(chef.created_at).getFullYear() }}
            </span>
            </div>
        </div>

        <!-- Pending Requests -->
        <div v-show="activeTab === 'request'" class="w-full h-[75%] flex flex-col items-center bg-slate-50 py-5 mt-[7px]">
            <div class="w-[90%] bg-white rounded-md shadow-md overflow-hidden">
                <table class="w-full border-collapse">
                    <!-- Header -->
                    <thead>
                    <tr class="bg-[#7592AB] text-[14px] text-black">
                        <th class="w-[5%]  font-[Poppins-Bold] h-[50px]">ID</th>
                        <th class="w-[20%] font-[Poppins-Bold]">Full Name</th>
                        <th class="w-[20%] font-[Poppins-Bold]">Email</th>
                        <th class="w-[5%] font-[Poppins-Bold]">Years</th>
                        <th class="w-[27%] font-[Poppins-Bold]">Certificate</th>
                        <th class="w-[15%] font-[Poppins-Bold]">Action</th>
                    </tr>
                    </thead>

                    <tbody>
                        <tr
                            v-for="chef in pendingChefs"
                            :key="chef.id"
                            class="hover:bg-slate-100 h-[50px] text-center border-b-2 border-[#B5BFDE] bg-[#E0E7FF]"
                        >
                            <td class="w-[5%] font-[Poppins-Regular] text-[15px] border-r-2 border-[#B5BFDE]">{{ chef.id }}</td>
                            <td class="w-[20%] font-[Poppins-Bold] text-[15px] border-r-2 border-[#B5BFDE]">{{ chef.user_info?.fullName }}</td>
                            <td class="w-[20%] font-[Poppins-Italic] text-[15px] underline border-r-2 border-[#B5BFDE] text-[#435F77]">{{ chef.email }}</td>
                            <td class="w-[5%] font-[Poppins-Bold] text-[15px] border-r-2 border-[#B5BFDE]">{{ chef.user_info?.experience }}</td>
                            <td class="w-[27%] truncate border-r-2 border-[#B5BFDE]">
                                <a
                                    :href="chef.user_info?.credentials"
                                    :download="getFileName(chef.user_info?.credentials)"
                                    class="text-[#435F77] underline font-[Poppins-Italic] text-[12px]" 
                                >
                                    {{ chef.user_info?.credentials }}
                                </a>
                            </td>
                            <td class="w-[100%] h-[50px] flex justify-center items-center gap-2">
                                <button
                                    class="px-5 h-[50%] rounded-full bg-green-600 border border-green-900 text-white text-xs font-[Poppins-BoldItalic] hover:bg-green-400 hover:scale-105 hover:text-black"
                                    @click="acceptChef(chef.id)"
                                >
                                    Accept
                                </button>
                                <button
                                    class="px-5 h-[50%] rounded-full bg-red-600 border border-red-900 text-white text-xs font-[Poppins-BoldItalic] hover:bg-red-500 hover:scale-105 hover:text-black"
                                    @click="rejectChef(chef.id)"
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!--Blocked Chefs-->
        <div class="w-full h-[75%] flex flex-col items-center bg-slate-50 py-5 mt-[7px]" v-show="activeTab === 'block'">
            <div class="w-[90%] bg-white rounded-md shadow-md overflow-hidden">
                <table class="w-full border-collapse">
                    <!-- Header -->
                    <thead>
                    <tr class="bg-[#7592AB] text-black text-[14px]">
                        <th class="w-[5%] font-[Poppins-Bold] h-[50px]">ID</th>
                        <th class="w-[20%] font-[Poppins-Bold]">Full Name</th>
                        <th class="w-[25%] font-[Poppins-Bold]">Email</th>
                        <th class="w-[5%] font-[Poppins-Bold]">Years</th>
                        <th class="w-[22%] font-[Poppins-Bold]">Certificate</th>
                        <th class="w-[15%] font-[Poppins-Bold]">Action</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr
                        v-for="chef in blockedChefs"
                        :key="chef.id"
                        class="hover:bg-slate-100 h-[50px] text-center border-b-2 border-[#B5BFDE] bg-[#E0E7FF]"
                    >
                        <td class="w-[5%] font-[Poppins-Bold] text-[15px] border-r-2 border-[#B5BFDE]">{{ chef.id }}</td>
                        <td class="w-[20%] font-[Poppins-Bold] text-[15px] border-r-2 border-[#B5BFDE]">{{ chef.user_info?.fullName }}</td>
                        <td class="w-[25%] font-[Poppins-Italic] text-[15px] underline text-[15px] border-r-2 border-[#B5BFDE]">{{ chef.email }}</td>
                        <td class="w-[5%] font-[Poppins-Bold] text-[15px] border-r-2 border-[#B5BFDE]">{{ chef.user_info?.experience }}</td>
                        <td class="w-[22%] truncate text-[15px] border-r-2 border-[#B5BFDE]">
                            <a
                                :href="chef.user_info?.credentials"
                                :download="getFileName(chef.user_info?.credentials)"
                                class="text-[#435F77] underline font-[Poppins-Italic] text-[12px]"
                            >
                                {{ chef.user_info?.credentials }}
                            </a>
                        </td>
                        <td class="w-[100%] h-[50px] flex justify-center items-center gap-2">
                            <button
                                class="px-5 h-[50%] rounded-full bg-green-600 border border-green-900 text-white text-xs font-[Poppins-Bold] hover:bg-green-400 hover:scale-105 hover:text-black"
                                @click="updateUserStatus(chef.id)"
                            >
                                Unblock
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>


        <Footer />
    </div>
</template>


<script setup>
    import { ref, computed } from "vue";
    import axios from "axios";
    import Footer from "@/Component/Footer.vue";

    const props = defineProps({
        chefs: Array,
    })

    const activeChefs = computed(() => {
      return props.chefs.filter(chef => chef.status === 'active');
    });

    const pendingChefs = computed(() => {
      return props.chefs.filter(chef => chef.status === 'pending');
    });

    const blockedChefs = computed(() => {
        return props.chefs.filter(chef => chef.status === 'blocked');
    })

    const menuOpen = ref(null);

    function toggleMenu(chefId) {
        menuOpen.value = menuOpen.value === chefId ? null : chefId;
    }

    function getProfilePic(chef) {
        const path = chef.user_info?.profilePath;
        if (path && path.trim() !== "") return `/storage/${path}`;
        const gender = chef.user_info?.gender;
        return gender === "female"
            ? "/images/female.png"
            : "/images/male.png";
    }

    function capitalizeFullName(name) {
      if (!name) return 'No Name Provided';
      return name
        .toLowerCase()
        .split(' ')
        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
        .join(' ');
    }

    function getFileName(path) {
      if (!path) return '';
      return path.split('/').pop();
    }

    const activeTab = ref("register");

    async function acceptChef(userId) {
      try {
        const response = await axios.post('/accept-chef', {
          user_id: userId
        });
        console.log(response.data.message);

        const index = props.chefs.findIndex(chef => chef.id === userId);
        if (index !== -1) {
          props.chefs[index].status = 'active';
        }
      } catch (error) {
        console.error(error.response?.data || error.message);
      }
    }

    async function rejectChef(userId) {
      try {
        const response = await axios.post('/decline-chef', {
          user_id: userId
        });
        console.log(response.data.message);

        const index = props.chefs.findIndex(chef => chef.id === userId);
        if (index !== -1) {
          props.chefs[index].status = 'inactive';
        }
      } catch (error) {
        console.error(error.response?.data || error.message);
      }
    }

    const emit = defineEmits(["navigate"]);
    function viewChefInfo(chef) {
        emit("navigate", "ChefInfo", chef);
    }
    async function updateUserStatus(userId) {
        try {
            const response = await axios.post('/update-user-status', { user_id: userId });
            console.log(response.data.message);

            const newStatus = response.data.new_status; // backend gives us this
            const index = props.chefs.findIndex(chef => chef.id === userId);

            if (index !== -1) {
                props.chefs[index].status = newStatus;
            }
        } catch (error) {
            console.error(error.response?.data || error.message);
        }
    }


    /* para mu fit ang words into 1 line
    function applyFitty() {
      fitty(".registered-chef-main-body button h2", {
        minSize: 12,
        maxSize: 20
      });
    }

    onMounted(() => {
      applyFitty();
    });

    onUpdated(() => {
      applyFitty();
    });
    */
</script>


