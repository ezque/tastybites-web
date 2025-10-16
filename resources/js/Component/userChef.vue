<template>
    <div class="flex flex-col w-full h-full overflow-hidden">
        <!-- Header -->
        <div class="flex items-center">
            <h2 class="mt-5 ml-5 text-[35px] font-bold font-poppins">Chefs</h2>
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
                    class="flex flex-col items-center justify-center w-full h-full"
                    @click="viewChefInfo(chef)"
                >
                    <img
                        :src="getProfilePic(chef)"
                        alt="Chef Image"
                        class="w-[73%] mb-2 rounded-full"
                    />
                    <h2
                        class="max-w-[90%] text-[20px] font-bold font-poppins text-center truncate"
                    >
                        {{ capitalizeFullName(chef.user_info?.fullName) }}
                    </h2>
                    <p class="m-0 text-sm italic font-poppins">
                        Since {{ new Date(chef.created_at).getFullYear() }}
                    </p>
                </button>

                <!-- Dot Menu -->
                <button
                    class="absolute w-5 bg-transparent cursor-pointer top-2 right-2"
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
                        <span class="w-[70%] text-left">Report</span>
                    </button>
                </div>
            </div>
        </div>

        <Footer />
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

    const activeChefs = computed(() => {
        // Filter out inactive chefs and the currently logged-in user
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

    async function reportChef(chef) {
        const reason = prompt(
            `Why are you reporting ${chef.user_info?.fullName || "this chef"}? (Max 1000 characters)`
        );

        if (!reason || reason.trim() === "") {
            alert("Report cancelled or no reason provided.");
            return;
        }

        try {
            openMenuIndex.value = null;
            const response = await axios.post(`/report/chef/${chef.id}`, {
                type: "user",
                reason: reason.trim(),
            });
            alert(response.data.message);
        } catch (error) {
            console.error("Reporting error:", error);
            const errorMessage =
                error.response?.data?.message ||
                "Failed to submit report. Please try again.";
            alert(errorMessage);
        }
    }
</script>
