<template>
    <div class="userChef-main-body">
        <div class="userChef-header">
            <button>
                <img src="/public/images/Button-icon/back.png" alt="back" />
            </button>
            <h2>Chefs</h2>
        </div>
        <div class="user-chef-container">
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
    const emit = defineEmits(["navigate"]);
    function viewChefInfo(chef) {
        emit("navigate", "ChefDetails", chef);
    }
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
</script>
<style scoped>
    .userChef-main-body{
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    .userChef-header {
        width: 100%;
        display: flex;
        flex-direction: row;
        align-items: center;
        padding-top: 20px;
    }
    .userChef-header button {
        width: 50px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: transparent;
        border-radius: 50%;
        border: none;
        margin-left: 30px;
    }
    .userChef-header button img {
        width: 100%;
        height: 100%;
    }
    .userChef-header h2 {
        font-size: 1.5em;
    }
    .user-chef-container {
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
    .user-chef-container button {
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
    .user-chef-container button img {
        width: 73%;
        height: auto;
        border-radius: 50%;
        margin-bottom: 10px;
    }
    .user-chef-container button h2{
        margin: 0;
        font-size: 20px;
        font-family: 'Poppins-Bold';
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 90%;
        text-align: center;
    }
    .user-chef-container button p{
        margin: 0;
        font-family: 'Poppins-Italic';
        font-size: .9em;
    }
</style>
