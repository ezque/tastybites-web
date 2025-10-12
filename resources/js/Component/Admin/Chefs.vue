<template>
    <div class="w-full h-screen overflow-hidden flex flex-col">
        <!-- Top Section -->
        <div class="flex flex-col gap-4">
            <!-- Label Row -->
            <div class="flex justify-between items-center">
                <div class="mt-5 ml-5">
                    <h2 v-if="activeTab === 'register'" class="text-[35px] font-[Poppins-Bold]">Registered Chefs</h2>
                    <h2 v-if="activeTab === 'request'" class="text-[35px] font-[Poppins-Bold]">Chefs Request</h2>
                </div>

                <!-- Search -->
                <div class="flex items-center bg-[#E0E7FF] px-4 py-2 mr-5 rounded-full border-r border-[#B5BFDE] border-b-[3px] border-b-[#B5BFDE]">
                    <input
                        placeholder="Search Chefs"
                        class="border-none outline-none w-[200px] px-5 py-2 bg-[#435F77] rounded-full font-[Poppins-Italic] text-white"
                    />
                    <img src="/public/images/Button-icon/search.png" alt="icon" class="w-[25px] h-[25px] ml-2 cursor-pointer" />
                </div>
            </div>

            <!-- Nav Buttons -->
            <div class="flex justify-center gap-[30px] -mt-5">
                <button
                    :class="[
            'px-10 py-2 rounded-[20px] bg-[#435F77] text-white cursor-pointer text-[13px] font-[Poppins-Bold] transition-transform duration-300',
            activeTab === 'register'
              ? 'bg-[#E0E7FF] text-[#435F77] shadow-[4px_4px_12px_#AFADAD] border-r border-[#AFADAD]'
              : 'hover:scale-110'
          ]"
                    @click="activeTab = 'register'"
                >
                    REGISTERED CHEFS
                </button>

                <button
                    :class="[
            'px-10 py-2 rounded-[20px] bg-[#435F77] text-white cursor-pointer text-[13px] font-[Poppins-Bold] transition-transform duration-300',
            activeTab === 'request'
              ? 'bg-[#E0E7FF] text-[#435F77] shadow-[4px_4px_12px_#AFADAD] border-r border-[#AFADAD]'
              : 'hover:scale-110'
          ]"
                    @click="activeTab = 'request'"
                >
                    PENDING REQUESTS
                </button>
            </div>
        </div>

        <!-- Registered Chefs -->
        <div v-show="activeTab === 'register'" class="w-full h-[70%] mt-8 flex flex-wrap justify-center gap-[30px] overflow-auto">
            <button
                v-for="chef in activeChefs"
                :key="chef.id"
                @click="viewChefInfo(chef)"
                class="w-[220px] h-[250px] flex flex-col items-center justify-center rounded-[20px] bg-[#E0E7FF] shadow-[5px_4px_2px_#AFADAD] cursor-pointer"
            >
                <img :src="getProfilePic(chef)" alt="img" class="w-[73%] rounded-full mb-3" />
                <h2 class="text-[20px] font-[Poppins-Bold] truncate max-w-[90%] text-center">
                    {{ capitalizeFullName(chef.user_info?.fullName) }}
                </h2>
                <p class="font-[Poppins-Italic] text-[0.9em]">Since {{ new Date(chef.created_at).getFullYear() }}</p>
            </button>
        </div>

        <!-- Pending Requests -->
        <div v-show="activeTab === 'request'" class="w-full h-[75%] flex flex-col items-center bg-slate-50 py-5 mt-[7px]">
            <div class="w-[90%] bg-white rounded-md shadow-md overflow-hidden">
                <table class="w-full border-collapse">
                    <!-- Header -->
                    <thead>
                    <tr class="bg-[#7592AB] text-white">
                        <th class="w-[5%] text-xs font-[Poppins-Bold] h-[40px]">ID</th>
                        <th class="w-[20%] text-xs font-[Poppins-Bold]">Full Name</th>
                        <th class="w-[25%] text-xs font-[Poppins-Bold]">Email</th>
                        <th class="w-[5%] text-[10px] font-[Poppins-Bold]">Years</th>
                        <th class="w-[22%] text-xs font-[Poppins-Bold]">Certificate</th>
                        <th class="w-[15%] text-xs font-[Poppins-Bold]">Action</th>
                    </tr>
                    </thead>

                    <tbody>
                        <tr
                            v-for="chef in pendingChefs"
                            :key="chef.id"
                            class="even:bg-gray-50 hover:bg-slate-100 h-[50px] text-center border-b border-gray-200"
                        >
                            <td class="w-[5%] font-[Poppins-Bold] border-r border-[#AFADAD]">{{ chef.id }}</td>
                            <td class="w-[20%] font-[Poppins-Bold] border-r border-[#AFADAD]">{{ chef.user_info?.fullName }}</td>
                            <td class="w-[25%] italic underline border-r border-[#AFADAD]">{{ chef.email }}</td>
                            <td class="w-[5%] font-[Poppins-Bold] border-r border-[#AFADAD]">{{ chef.user_info?.experience }}</td>
                            <td class="w-[22%] truncate border-r border-[#AFADAD]">
                                <a
                                    :href="chef.user_info?.credentials"
                                    :download="getFileName(chef.user_info?.credentials)"
                                    class="text-blue-500 underline"
                                >
                                    {{ chef.user_info?.credentials }}
                                </a>
                            </td>
                            <td class="w-[100%] h-[50px] flex justify-center items-center gap-2">
                                <button
                                    class="px-5 h-[50%] rounded-full bg-green-600 border border-green-900 text-white text-xs font-[Poppins-Bold] hover:bg-green-400 hover:scale-105 hover:text-black"
                                    @click="acceptChef(chef.id)"
                                >
                                    Accept
                                </button>
                                <button
                                    class="px-5 h-[50%] rounded-full bg-red-600 border border-red-900 text-white text-xs font-[Poppins-Bold] hover:bg-red-500 hover:scale-105 hover:text-black"
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


        <Footer />
    </div>
</template>


<script setup>
    import { ref, computed } from "vue";
    import axios from "axios";
    import Footer from "@/Component/Footer.vue";

    const props = defineProps({
      chefs: Array
    })

    const activeChefs = computed(() => {
      return props.chefs.filter(chef => chef.status === 'active');
    });

    const pendingChefs = computed(() => {
      return props.chefs.filter(chef => chef.status === 'pending');
    });

    function getProfilePic(chef) {
      const gender = chef.user_info?.gender;
      if (gender === 'male') {
        return '/images/male.png';
      } else if (gender === 'female') {
        return '/images/female.png';
      } else {
        return '/images/male.png';
      }
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


