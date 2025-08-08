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
                            <button>
                                Accept
                            </button>
                            <button>
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
    }
    .table {
        border: 1px solid black;
        width: 90%;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .table-head {
        width: 100%;
        height: 60px;
        display: flex;
        flex-direction: row;
        background-color: #7592AB;
    }
    .rowOneH,.rowTwoH,.rowThreeH,.rowFourH,.rowFiveH {
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .rowOneB,.rowTwoB,.rowThreeB,.rowFourB,.rowFiveB {
        border: 1px solid black;
    }
    .rowOneH,.rowOneB {
        width: 10%;
    }
    .rowTwoH,.rowTwoB {
        width: 25%;
    }
    .rowThreeH,.rowThreeB {
        width: 20%;
    }
    .rowFourH,.rowFourB {
        width: 25%;
    }
    .rowFiveH,.rowFiveB {
        width: 20%;
    }
    .table-body{
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        overflow: auto;
    }
    .body-item {
        width: 100%;
        height: 50px;
        display: flex;
        flex-direction: row;
        flex-shrink: 0;
    }
    .rowOneB {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .rowFourB a {
        display: inline-block;
        max-width: 80%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        vertical-align: middle;
    }



</style>
