<template>
    <div class="chef-main-container">
        <div class="top-container">
            <div class="label-container">
                <h2 v-if="activeTab === 'register'">Registered Chefs</h2>
                <h2 v-if="activeTab === 'request'">Chefs Request</h2>
            </div>

            <div class="navigate-button-container">
                <button
                    :class="{ active: activeTab === 'register' }"
                    @click="activeTab = 'register'"
                >
                    Registered Chef
                </button>
                <button
                    :class="{ active: activeTab === 'request' }"
                    @click="activeTab = 'request'"
                >
                    Pending Request
                </button>
            </div>
            <div class="search-container">
                <div class="search-bar">
                    <input placeholder="Search Chefs">
                    <img src="/public/images/Button-icon/search.png" alt="icon" />
                </div>
            </div>

        </div>

        <div class="registered-chef-main-body" v-show="activeTab === 'register'">
            <button
                v-for="(chef, index) in activeChefs"
                :key="chef.id"
                @click="viewChefInfo(chef)"
            >
                <img :src="getProfilePic(chef)" alt="img"/>
                <h2>{{ capitalizeFullName(chef.user_info?.fullName) }}</h2>
                <p>Since {{ new Date(chef.created_at).getFullYear() }}</p>

            </button>
        </div>

        <div class="chef-request-main-body" v-show="activeTab === 'request'">
            <div class="table">
                <div class="table-head">
                    <div class="rowOneH">
                        <h3>ID</h3>
                    </div>
                    <div class="rowTwoH">
                        <h3>Full Name</h3>
                    </div>
                    <div class="rowThreeH">
                        <h3>Email</h3>
                    </div>
                    <div class="rowFourH">
                        <h3>Certificate</h3>
                    </div>
                    <div class="rowFiveH">
                        <h3>Action</h3>
                    </div>
                </div>
                <div class="table-body">
                    <div class="body-item"
                         v-for="(chef, index) in pendingChefs"
                         :key="chef.id"
                    >
                        <div class="rowOneB">
                            <p>{{ chef.id }}</p>
                        </div>
                        <div class="rowTwoB">
                            <p>{{ chef.user_info?.fullName }}</p>
                        </div>
                        <div class="rowThreeB">
                            <p>{{ chef.email }}</p>
                        </div>
                        <div class="rowFourB">
                            <a
                                :href="chef.user_info?.credentials"
                                :download="getFileName(chef.user_info?.credentials)"
                            >
                                {{ chef.user_info?.credentials }}
                            </a>
                        </div>
                        <div class="rowFiveB">
                            <button
                                class="accept-bttn"
                                @click="acceptChef(chef.id)"
                            >
                                Accept
                            </button>
                            <button
                                class="delete-bttn"
                                @click="rejectChef(chef.id)"
                            >
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script setup>
    import { ref, computed } from "vue";
    import axios from "axios";

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

            // Remove accepted chef from pending list without page reload
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
            const response = await  axios.post('/decline-chef', {
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
    const emit =defineEmits(["navigate"]);
    function viewChefInfo(chef) {
        emit("navigate", "ChefInfo", chef);
    }
</script>

<style scoped>
    .chef-main-container {
        width: 100%;
        height: 100vh;
        overflow: hidden;
    }
    .top-container {
        width: 100%;
        height: 9%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-direction: row;
    }
    .label-container {
        width: 20%;
        height: 100%;
        display: flex;
        align-items: center;
    }
    .label-container h2{
        font-family: 'Poppins', sans-serif;
        font-weight: bold;
        margin-left: 10px;
    }
    .navigate-button-container {
        width: 40%;
        height: 100%;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
    }
    .navigate-button-container button {
        padding: 12px 16px;
        border: none;
        background-color: #435F77;
        cursor: pointer;
        margin-right: 8px;
        width: 200px;
        border-radius: 20px;
        color: white;
        font-family: 'Poppins', sans-serif;
    }

    .navigate-button-container button.active {
        background-color: #E0E7FF;
        color: black;
        font-family: 'Poppins', sans-serif;
    }
    .search-container {
        width: 20%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: end;
    }
    .search-bar {
        width: 70%;
        height: 50%;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        background-color: #E0E7FF;
        border-radius: 20px;
        margin-right: 20px;
    }
    .search-bar input {
        width: 80%;
        height: 95%;
        border: none;
        padding-left: 15px;
        outline: none;
        border-radius: 20px;
        background-color: #E0E7FF;
    }
    .search-bar img {
        width: 25px;
        height: 25px;
        margin-right: 10px;
    }
    .registered-chef-main-body {
        width: 100%;
        height: 75%;
        flex-wrap: wrap;
        flex-direction: row;
        overflow: auto;
        gap: 30px;
        display: flex;
        justify-content: center;

    }
    .registered-chef-main-body button {
        width: 220px;
        height: 250px;
        cursor: pointer;
        border-radius: 20px;
        border: none;
        box-shadow: 5px 4px 2px #AFADAD;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .registered-chef-main-body button img {
        width: 70%;
        height: auto;
        border-radius: 50%;
        margin-bottom: 20px;
    }
    .registered-chef-main-body button h2{
        margin: 0;
        font-size: 1.2em;
        font-family: 'Poppins', sans-serif;
    }
    .registered-chef-main-body button p{
        margin: 0;
        font-family: 'Poppins', sans-serif;
        font-size: .9em;
    }
    .chef-request-main-body {
        width: 100%;
        height: 75%;
        display: flex;
        align-items: center;
        flex-direction: column;
        background-color: #f8fafc;
        padding: 20px 0;
    }

    /* Table wrapper */
    .table {
        width: 90%;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        background-color: white;
        display: flex;
        flex-direction: column;
    }

    /* Table header */
    .table-head {
        display: flex;
        background-color: #435F77;
        color: white;
        font-weight: 600;
        height: 55px;
    }

    .table-head > div {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Column widths */
    .rowOneH, .rowOneB { width: 10%; }
    .rowTwoH, .rowTwoB { width: 25%; }
    .rowThreeH, .rowThreeB { width: 20%; }
    .rowFourH, .rowFourB { width: 25%; }
    .rowFiveH, .rowFiveB { width: 20%; }

    /* Table body */
    .table-body {
        display: flex;
        flex-direction: column;
        width: 100%;
    }

    /* Table rows */
    .body-item {
        display: flex;
        height: 50px;
        align-items: center;
        transition: background-color 0.2s ease;
        border-bottom: 1px solid #e5e7eb;
    }

    .body-item:nth-child(even) {
        background-color: #f9fafb;
    }

    .body-item:hover {
        background-color: #eef3f8;
    }

    /* Row cells */
    .rowOneB, .rowTwoB, .rowThreeB, .rowFourB, .rowFiveB {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 10px;
        font-size: 14px;
        color: #333;
    }

    .rowTwoB, .rowThreeB {
        justify-content: flex-start;
    }

    /* Certificate link */
    .rowFourB a {
        color: #3498db;
        text-decoration: none;
        max-width: 95%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .rowFourB a:hover {
        text-decoration: underline;
    }

    /* Action buttons */
    .rowFiveB {
        display: flex;
        gap: 8px;
        justify-content: center;
    }

    .rowFiveB button {
        padding: 6px 12px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        font-size: 13px;
        font-weight: 500;
        transition: background-color 0.2s ease, transform 0.1s ease;
    }

    /* Accept button */
    .accept-bttn {
        background-color: #28a745;
        color: white;
    }
    .accept-bttn:hover {
        background-color: #218838;
        transform: scale(1.05);
    }

    /* Delete button */
    .delete-bttn {
        background-color: #dc3545;
        color: white;
    }
    .delete-bttn:hover {
        background-color: #b02a37;
        transform: scale(1.05);
    }



</style>
