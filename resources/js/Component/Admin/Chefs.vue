<template>
    <div class="chef-main-container">
        <div class="top-container">
        <div class="label-row">
            <div class="label-container">
            <h2 v-if="activeTab === 'register'">Registered Chefs</h2>
            <h2 v-if="activeTab === 'request'">Chefs Request</h2>
            </div>

            <div class="search-container">
            <div class="search-bar">
                <input placeholder="Search Chefs">
                <img src="/public/images/Button-icon/search.png" alt="icon" />
            </div>
            </div>
        </div>

        <div class="navigate-button-container">
            <button
            :class="{ active: activeTab === 'register' }"
            @click="activeTab = 'register'"
            >
            REGISTERED CHEFS
            </button>
            <button
            :class="{ active: activeTab === 'request' }"
            @click="activeTab = 'request'"
            >
            PENDING REQUESTS
            </button>
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
                    <div class="rowSixH">
                        <h3>Years of Experience</h3>
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
                        <div class="rowSixB">
                            <p>{{ chef.user_info?.experience }}</p>
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


<style scoped>
    .chef-main-container {
        width: 100%;
        height: 100vh;
        overflow: hidden;
    }
    .top-container {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    .label-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .label-container h2 {
        margin-top: 20px;
        font-size: 35px;
        margin-left: 20px;
        font-family: 'Poppins-Bold';
    }
    .navigate-button-container {
        display: flex;
        justify-content: center;
        gap: 30px;
        margin-top: -20px;
    }
    .navigate-button-container button {
        padding: 10px 40px 10px 40px;
        border: none;
        border-radius: 20px;
        background: #435F77;
        color: white;
        cursor: pointer;
        font-size: 13px;
        font-family: 'Poppins-Bold';
    }
    .navigate-button-container button.active {
        background: #E0E7FF;
        color: #435F77;
        box-shadow: 4px 4px 12px #AFADAD;
        border-right: #AFADAD solid 1px;
    }
    .navigate-button-container button:hover {
        transform: scale(1.1);
    }
    .search-bar {
        display: flex;
        align-items: center;
        background: #E0E7FF;
        padding: 10px 15px 10px 10px;
        margin-right: 20px;
        border-radius: 30px;
        border-right: #B5BFDE solid 1px;
        border-bottom: #B5BFDE solid 3px;
    }
    .search-bar input {
        border: none;
        outline: none;
        width: 200px;
        padding: 10px 20px;
        background: #435F77;
        border-radius: 30px;
        font-family: 'Poppins-Italic';
        color: white;
    }
    .search-bar img {
        width: 25px;
        height: 25px;
        margin-left: 5px;
        cursor: pointer;
    }
    .registered-chef-main-body {
        width: 100%;
        height: 70%;
        flex-wrap: wrap;
        flex-direction: row;
        overflow: auto;
        gap: 30px;
        display: flex;
        justify-content: center;
        margin-top: 30px;
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
        background-color: #E0E7FF;
    }
    .registered-chef-main-body button img {
        width: 73%;
        height: auto;
        border-radius: 50%;
        margin-bottom: 10px;
    }
    .registered-chef-main-body button h2{
        margin: 0;
        font-size: 20px;
        font-family: 'Poppins-Bold';
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 90%;
        text-align: center;
    }
    .registered-chef-main-body button p{
        margin: 0;
        font-family: 'Poppins-Italic';
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
        box-shadow: 0 2px 8px #AFADAD;
        background-color: white;
        display: flex;
        flex-direction: column;
    }

    /* Table header */
    .table/* Table header */
    .table-head {
        display: flex;
    }

    .table-head > div {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Column widths (locked with flex-basis) */
    .rowOneH, .rowOneB { flex: 0 0 5%; }
    .rowTwoH, .rowTwoB { flex: 0 0 20%; }
    .rowThreeH, .rowThreeB { flex: 0 0 25%; }
    .rowFourH, .rowFourB { flex: 0 0 22.47%; }
    .rowFiveH, .rowFiveB { flex: 0 0 15%; }
    .rowSixH, .rowSixB { flex: 0 0 5%; }

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

    .rowOneB, .rowTwoB, .rowThreeB, .rowFourB, .rowFiveB, .rowSixB {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 10px;
        font-size: 14px;
        color: #333;
        font-family: 'Poppins-Regular';
        overflow: hidden;
        white-space: nowrap;
    }

    .rowOneB, .rowTwoB, .rowThreeB, .rowSixB {
        border-right: #AFADAD solid 1px;
        font-family: 'Poppins-Bold';
    }

    .rowThreeB {
        text-decoration: underline;
        font-style: italic;
    }

    .rowFourB {
        text-overflow: ellipsis;
        border-right: #AFADAD solid 1px;
    }

    .rowOneH, .rowTwoH, .rowThreeH, .rowFiveH, .rowFourH {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 10px;
        font-size: 12px;
        color: black;
        font-family: 'Poppins-Bold';
        text-align: center;
        background-color: #7592AB;
    }

    .rowSixH {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 10px;
        font-size: 10px;
        color: black;
        font-family: 'Poppins-Bold';
        text-align: center;
        background-color: #7592AB;
    }

    .rowFourB a {
        color: #3498db;
        text-decoration: underline;
        max-width: 95%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .rowFourB a:hover {
        text-decoration: underline;
    }

    .rowFiveB {
        display: flex;
        gap: 8px;
        justify-content: center;
    }

    .rowFiveB button {
        padding: 6px 20px;
        border-radius: 30px;
        cursor: pointer;
        font-size: 10px;
        font-family: 'Poppins-Bold';
        color: white;
        transition: background-color 0.2s ease, transform 0.1s ease;
    }

    /* Accept button */
    .accept-bttn {
        background-color: #28a745;
        border: #006400 1px solid;
    }
    .accept-bttn:hover {
        background-color: #00ff00;
        transform: scale(1.05);
        color: black;
    }

    /* Delete button */
    .delete-bttn {
        background-color: #dc3545;
        border: #800000 1px solid;
    }
    .delete-bttn:hover {
        background-color: #ff0000;
        transform: scale(1.05);
        color: black;
    }



</style>
