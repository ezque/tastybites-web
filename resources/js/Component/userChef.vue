<template>
    <div class="flex flex-col w-full h-full overflow-hidden">
        <!-- Header -->
        <div
            class="relative flex flex-col w-full h-[80%] gap-[50px] mt-6 overflow-y-auto"
        >
            <div class="absolute inset-0 flex items-center justify-center" v-if="loading">
                <div class="w-10 h-10 border-4 border-[#435F77] border-t-transparent rounded-full animate-spin"></div>
            </div>
            <h2 class="mt-5 ml-5 text-[35px] font-['Poppins-Bold']">Top Chefs</h2>
            <div class="relative flex flex-wrap justify-center w-full h-[75%] gap-[50px] mt-6">
                <div
                    v-for="(chef, index) in topChefs"
                    :key="chef.id"
                    class="flex-shrink-0 w-[220px]"
                >
                    <ChefCard
                        :chef="chef"
                        :index="index"
                        :open-menu-index="openMenuIndex"
                        @update:openMenuIndex="openMenuIndex = $event"
                        @navigate="handleNavigation"
                        @follow="follow"
                        @report="reportChef"
                    />
                </div>
            </div>
            <h2 class="mt-5 ml-5 text-[35px] font-['Poppins-Bold']">All Chefs</h2>
            <div class="relative flex flex-wrap justify-center w-full h-[75%] gap-[50px] mt-6">
                <ChefCard
                    v-for="(chef, index) in activeChefs"
                    :key="chef.id"
                    :chef="chef"
                    :index="index"
                    :open-menu-index="openMenuIndex"
                    @update:openMenuIndex="openMenuIndex = $event"
                    @navigate="handleNavigation"
                    @follow="follow"
                    @report="reportChef"
                />
            </div>
        </div>

        <Footer />

        <!-- Report Dialog -->
        <div
            v-if="showReportDialog"
            class="fixed inset-0 flex items-center justify-center bg-black/50 z-50"
        >
            <div class="bg-white rounded-2xl shadow-lg w-[400px] p-6 flex flex-col gap-4">
                <h3 class="text-xl font-['Poppins-Bold'] text-center text-[#435F77]">
                    Report {{ selectedChef?.user_info?.fullName || 'Chef' }}
                </h3>
                <p class="text-sm text-gray-700 font-['Poppins-Regular'] text-center">
                    Please select a reason for reporting:
                </p>

                <!-- Report Reason Choices -->
                <div class="flex flex-col gap-2">
                    <label
                        v-for="(reason, index) in reasons"
                        :key="index"
                        class="flex items-center gap-2 cursor-pointer font-['Poppins-Regular']"
                    >
                        <input
                            type="radio"
                            :value="reason"
                            v-model="selectedReason"
                            class="accent-[#435F77]"
                        />
                        {{ reason }}
                    </label>
                </div>

                <!-- Custom Reason -->
                <textarea
                    v-if="selectedReason === 'Other'"
                    v-model="customReason"
                    maxlength="1000"
                    placeholder="Please specify your reason..."
                    class="w-full h-[80px] mt-2 p-2 border rounded-lg border-[#B5BFDE] outline-none text-sm font-['Poppins-Regular']"
                ></textarea>

                <!-- Buttons -->
                <div class="flex justify-end gap-3 mt-3">
                    <button
                        @click="cancelReport"
                        class="px-4 py-2 rounded-full bg-gray-300 text-gray-700 font-['Poppins-Bold'] hover:bg-gray-400 transition"
                    >
                        Cancel
                    </button>
                    <button
                        @click="submitReport"
                        class="px-4 py-2 rounded-full bg-[#435F77] text-white font-['Poppins-Bold'] hover:bg-[#32495c] transition"
                    >
                        Submit
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref, computed, onMounted, onBeforeUnmount } from "vue";
    import Footer from "@/Component/Footer.vue";
    import ChefCard from "@/Component/ChefCard.vue";
    import axios from "axios";

    const props = defineProps({
        user: Object,
        searchQuery: String,
    });

    const emit = defineEmits(["navigate"]);

    const showReportDialog = ref(false);
    const selectedChef = ref(null);
    const selectedReason = ref("");
    const customReason = ref("");
    const allChefs = ref([]);
    const topChefs = ref([]);
    const loading = ref(true);
    const openMenuIndex = ref(null);

    const reasons = [
        "Inappropriate behavior",
        "Harassment or offensive content",
        "Fake profile or impersonation",
        "Scam or misleading information",
        "Other"
    ];

    const handleNavigation = (componentName, chefData) => {
        emit("navigate", componentName, chefData);
    };


    const activeChefs = computed(() => {
        return allChefs.value
            .filter(chef => chef.status === "active" && chef.id !== props.user.id)
            .filter(chef => {
                if (!props.searchQuery) return true;
                const fullName = chef.user_info?.fullName || "";
                return fullName.toLowerCase().includes(props.searchQuery.toLowerCase());
            });
    });

    // Close menu when clicking outside
    const handleClickOutside = (e) => {
        const clickedInside = e.target.closest(".chef-card");
        if (!clickedInside) openMenuIndex.value = null;
    };

    onMounted(() => document.addEventListener("click", handleClickOutside));
    onBeforeUnmount(() => document.removeEventListener("click", handleClickOutside));

    function reportChef(chef) {
        selectedChef.value = chef;
        selectedReason.value = "";
        customReason.value = "";
        showReportDialog.value = true;
        openMenuIndex.value = null;
    }

    // Cancel the dialog
    function cancelReport() {
        showReportDialog.value = false;
        selectedChef.value = null;
        selectedReason.value = "";
        customReason.value = "";
    }

    async function submitReport() {
        if (!selectedReason.value) {
            alert("Please select a reason before submitting.");
            return;
        }

        const reason =
            selectedReason.value === "Other"
                ? customReason.value.trim()
                : selectedReason.value;

        if (reason === "") {
            alert("Please provide a reason for reporting.");
            return;
        }

        try {
            await axios.post(`/report/chef/${selectedChef.value.id}`, {
                type: "user",
                reason
            });

            showReportDialog.value = false;
            selectedChef.value = null;
            selectedReason.value = "";
            customReason.value = "";

        } catch (error) {
            console.error("Reporting error:", error);
        }
    }

    const follow = async (chef) => {
        try {
            const response = await axios.post(`/follow/${chef.id}`);
            const newStatus = response.data.status;

            chef.follow_status = newStatus;

            if (newStatus === "true") {
                chef.followers_count += 1;
            } else {
                chef.followers_count -= 1;
            }

        } catch (error) {
            console.error(error.response?.data || error);
        }
    };

    // Fetch all chefs
    const fetchChef = async () => {
        try {
            const { data } = await axios.get('/chef-info');
            allChefs.value = data;
        } catch (error) {
            console.error("Failed to load chef info:", error);
        }
    };

    // Fetch top 5 chefs (sorted by total purchases)
    const fetchTopChef = async () => {
        try {
            const { data } = await axios.get('/top-chef'); // Backend returns top 5 based on purchases
            topChefs.value = data;
        } catch (error) {
            console.error("Failed to load top chefs:", error);
        } finally {
            loading.value = false;
        }
    };

    onMounted(() => {
        fetchChef();
        fetchTopChef();
    });
</script>
