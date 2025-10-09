<template>
    <div class="userChef-main-body">
        <div class="userChef-header">
            <h2>Chefs</h2>
        </div>

        <div class="user-chef-container">
            <div
                v-for="(chef, index) in activeChefs"
                :key="chef.id"
                class="chef-card"
            >
                <button class="chef-card-button" @click="viewChefInfo(chef)">
                    <img :src="getProfilePic(chef)" alt="Chef Image" />
                    <h2>{{ capitalizeFullName(chef.user_info?.fullName) }}</h2>
                    <p>Since {{ new Date(chef.created_at).getFullYear() }}</p>
                </button>

                <button class="dot-menu" @click.stop="toggleMenu(index)">
                    <img src="/public/images/Button-icon/option.png" alt="Options" />
                </button>

                <div v-if="openMenuIndex === index" class="menu-container">
                    <button @click="reportChef(chef)">
                        <img src="/public/images/Button-icon/report.png" alt="Report" />
                        <span>Report</span>
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
        return gender === "female"
            ? "/images/female.png"
            : "/images/male.png";
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
    onBeforeUnmount(() =>
        document.removeEventListener("click", handleClickOutside)
    );


    async function reportChef(chef) {
        const reason = prompt(`Why are you reporting ${chef.user_info?.fullName || "this chef"}? (Max 1000 characters)`);

        if (!reason || reason.trim() === "") {
            alert("Report cancelled or no reason provided.");
            return;
        }

        try {

            openMenuIndex.value = null;

            const response = await axios.post(`/report/chef/${chef.id}`, {
                type: 'user',
                reason: reason.trim(),
            });

            alert(response.data.message);
        } catch (error) {
            console.error("Reporting error:", error);
            const errorMessage = error.response?.data?.message || "Failed to submit report. Please try again.";
            alert(errorMessage);
        }
    }
</script>

<style scoped>
    .userChef-main-body {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .userChef-header {
        display: flex;
        align-items: center;
    }

    .userChef-header h2 {
        margin-top: 20px;
        font-size: 35px;
        margin-left: 20px;
        font-family: 'Poppins-Bold';
    }

    .user-chef-container {
        width: 100%;
        height: 70%;
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 60px;
        margin-top: 30px;
        overflow-y: auto;
        position: relative;
    }

    /* Each chef card */
    .chef-card {
        width: 220px;
        height: 250px;
        position: relative;
        border-radius: 20px;
        background-color: #e0e7ff;
        box-shadow: 5px 4px 2px #afadad;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .chef-card-button {
        width: 100%;
        height: 100%;
        background: transparent;
        border: none;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .chef-card-button img {
        width: 73%;
        border-radius: 100%;
        margin-bottom: 10px;
    }

    .chef-card-button h2 {
        margin: 0;
        font-size: 20px;
        font-family: "Poppins-Bold";
        text-align: center;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 90%;
    }

    .chef-card-button p {
        margin: 0;
        font-family: "Poppins-Italic";
        font-size: 0.9em;
    }

    .dot-menu {
        position: absolute;
        top: 5px;
        right: 5px;
        margin-top: 10px;
        background: transparent;
        border: none;
        cursor: pointer;
        width: 20px;
    }

    .dot-menu img {
        width: 8px;
        height: 22px;
    }

    .menu-container {
        border: none;
        position: absolute;
        left: 88%;
        top: 45px;
        margin-left: 10px;
        background-color: #435F77;
        border-radius: 0 10px 10px 10px;
        z-index: 9;
        padding: 10px;
        display: flex;
        flex-direction: column;
    }

    .menu-container button {
        width: 90px;
        height: 25px;
        background-color: transparent;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        gap: 10px;
        cursor: pointer;
        font-family: 'Poppins-Bold';
    }
    .menu-container button span {
        width: 70%;
        text-align: start;
    }
    .menu-container button img {
        height: 90%;
        width: 30%;
    }
    .menu-container button:hover {
        background-color: #CFDAFF;
        border-right: #AFADAD solid 1px;
        color: #435F77;
        border-radius: 5px;
    }
</style>
