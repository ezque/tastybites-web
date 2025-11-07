<template>
    <div class="flex flex-col w-full h-full overflow-hidden">
        <!-- Header -->
        <div class="flex items-center">
            <h2 class="mt-5 ml-5 text-[35px] font-['Poppins-Bold']">Chefs</h2>
        </div>

        <!-- Chefs Grid -->
        <div
            class="relative flex flex-wrap justify-center w-full h-[70%] gap-[60px] mt-8 overflow-y-auto"
        >
            <div
                v-for="(chef, index) in activeChefs"
                :key="chef.id"
                class="relative flex flex-col items-center justify-center w-[220px] h-[250px] rounded-2xl bg-indigo-100 shadow-md"
            >
                <!-- Card Button -->
                <button
                    class="flex flex-col items-center justify-center w-full h-full cursor-pointer"
                    @click="viewChefInfo(chef)"
                >
                    <span class="w-[73%] h-[60%] rounded-full mb-3">
                        <img
                            :src="getProfilePic(chef)"
                            alt="Chef Image"
                            class="w-full h-full  rounded-full"
                        />
                    </span>

                    <h2
                        class="max-w-[90%] text-[20px] font-['Poppins-Bold'] text-center truncate"
                    >
                        {{ capitalizeFullName(chef.user_info?.fullName) }}
                    </h2>
                    <p class="m-0 text-sm  font-['Poppins-Italic']">
                        Since {{ new Date(chef.created_at).getFullYear() }}
                    </p>
                </button>

                <!-- Dot Menu -->
                <button
                    class="absolute top-1 right-1 mt-2 p-0 bg-transparent border-none cursor-pointer flex items-center justify-center w-5"
                    @click.stop="toggleMenu(index)"
                >
                    <img
                        src="/public/images/Button-icon/option.png"
                        alt="Options"
                        class="w-2 h-6"
                    />
                </button>

                <!-- Dropdown Menu -->
                <div
                    v-if="openMenuIndex === index"
                    class="absolute z-10 flex flex-col p-2 ml-2 bg-slate-700 rounded-br-xl rounded-tl-none rounded-tr-xl rounded-bl-xl left-[88%] top-11"
                >
                    <button
                        @click="reportChef(chef)"
                        class="flex items-center justify-center w-[90px] h-[25px] gap-2 font-bold text-white transition-colors rounded hover:bg-indigo-100 hover:text-slate-700 hover:border-r hover:border-slate-400"
                    >
                        <img src="/public/images/Button-icon/report.png" alt="Report" class="w-[30%] h-[90%]" />
                        <span class="w-[70%] text-left font-['Poppins-Bold']">Report</span>
                    </button>
                </div>
            </div>
        </div>

        <Footer />
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

                <!-- Custom Reason (shown if "Other" is selected) -->
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
    import axios from "axios";

    const props = defineProps({
        chefs: Array,
        user: Object,
    });

    const emit = defineEmits(["navigate"]);
    const showReportDialog = ref(false);
    const selectedChef = ref(null);
    const selectedReason = ref("");
    const customReason = ref("");

    const reasons = [
        "Inappropriate behavior",
        "Harassment or offensive content",
        "Fake profile or impersonation",
        "Scam or misleading information",
        "Other"
    ];

    const activeChefs = computed(() => {
        return props.chefs.filter(
            (chef) => chef.status === "active" && chef.id !== props.user.id
        );
    });

    function viewChefInfo(chef) {
        emit("navigate", "ChefDetails", chef);
    }

    function getProfilePic(chef) {
        const path = chef.user_info?.profilePath;
        if (path && path.trim() !== "") return `/storage/${path}`;
        const gender = chef.user_info?.gender;
        return gender === "female" ? "/images/female.png" : "/images/male.png";
    }

    function capitalizeFullName(name) {
        if (!name) return "No Name Provided";
        return name
            .toLowerCase()
            .split(" ")
            .map((w) => w.charAt(0).toUpperCase() + w.slice(1))
            .join(" ");
    }

    // --- Menu logic ---
    const openMenuIndex = ref(null);

    const toggleMenu = (index) => {
        openMenuIndex.value = openMenuIndex.value === index ? null : index;
    };

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
            const response = await axios.post(`/report/chef/${selectedChef.value.id}`, {
                type: "user",
                reason
            });

            showReportDialog.value = false;
            selectedChef.value = null;
            selectedReason.value = "";
            customReason.value = "";

            // Optional toast
            console.log(response.data.message);
        } catch (error) {
            console.error("Reporting error:", error);
            const errorMessage =
                error.response?.data?.message ||
                "Failed to submit report. Please try again.";
            console.error(errorMessage);
        }
    }
</script>
