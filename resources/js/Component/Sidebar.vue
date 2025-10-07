<template>
    <div class="sidebar-container">
        <div class="button-container">
            <button
                :class="{ active: active === homeKey }"
                @click="goHome"
            >
                <img src="/public/images/Button-icon/home.png"/>
                <h1>Home</h1>
            </button>
            <button
                :class="{ active: active === userChef }"
                @click="goUserChef"
                v-if="!isAdmin"
            >
                <img src="/public/images/Button-icon/chef.png"/>
                <h1>Chef</h1>
            </button>
            <button
                :class="{ active: active === incomeKey }"
                @click="goIncome"
                v-if="!isUser"
            >
                <img src="/public/images/Button-icon/queue.png"/>
                <h1>Queue</h1>
            </button>
            <button
                v-if="isAdmin"
                :class="{ active: active === 'AdminChefs' }"
                @click="emit('navigate','AdminChefs')"
            >
                <img src="/public/images/Button-icon/chef.png"/>
                <h1>Chefs</h1>
            </button>
            <button
                v-if="isAdmin || isChef"
                :class="{ active: active === 'Recipes' }"
                @click="emit('navigate', 'Recipes')"
            >
                <img src="/public/images/Button-icon/recipes.png"/>
                <h1>Recipes</h1>
            </button>
            <button
                v-if="isAdmin"
                :class="{ active: active === 'Users' }"
                @click="emit('navigate','Users')"
            >
                <img src="/public/images/Button-icon/user_sidebar.png"/>
                <h1>Users</h1>
            </button>
            <button
                v-if="isChef"
                :class="{ active: active === 'Report' }"
                @click="emit('navigate','Report')"
            >
                <img src="/public/images/Button-icon/chef_reports.png" alt="img"/>
                <h1>Reports</h1>
            </button>
        </div>
        <div class="logout-container">
            <button class="logout-bttn" @click="handleLogout">
                <img src="/public/images/Button-icon/logout.png"/>
            </button>
        </div>

    </div>
</template>

<script setup>
    import {Inertia} from "@inertiajs/inertia";
    import {computed} from "vue";
    import Recipes from "@/Component/Recipes.vue";

    const emit = defineEmits(['navigate'])

    const isAdmin = computed(() => props.user.role === 'admin');
    const isChef = computed(() => props.user.role === 'chef');
    const isUser = computed(() => props.user.role === 'user');

    const props = defineProps({
        user: {
            Object,
            required: true
        },
        active: {
            type: String,
            required: true
        }
    })


    const homeKey   = computed(() => isAdmin.value ? 'AdminHome' : isChef.value ? 'Home' : 'Home')
    const userChef = computed(() => isUser.value ? 'userChef' : isUser.value ? 'userChef' : 'userChef')
    const incomeKey = computed(() => isAdmin.value ? 'AdminIncome' : isChef.value ? 'ChefIncome' : null)

    function goHome() {
        emit('navigate', homeKey.value)
    }

    function goUserChef() {
        if (userChef.value) {
            emit('navigate', userChef.value)
        }
    }

    function goIncome() {
        if (incomeKey.value) {
            emit('navigate', incomeKey.value)
        }
    }

    const handleLogout = () => {
        if (confirm('Are you sure you want to logout?')) {
            Inertia.post('/logout', {}, {
                onFinish: () => {
                    window.location.reload();
                }
            });
        }
    };

</script>

<style scoped>
    .sidebar-container {
        width: 8%;
        height: 100%;
        background-color: #B5BFDE;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .button-container {
        padding-top: 40px;
        flex-direction: column;
        display: flex;
        align-items: end;
        gap: 20px;
    }
    .button-container button {
        width: 90%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding-top: 10px;
        padding-bottom: 10px;
        border-radius: 50px 0 0 50px;
        border: none;
        background-color: transparent;
        cursor: pointer;
        gap: 5px;
        transition: all 0.5s ease-in-out;
    }
    .button-container button:hover {
        background-color: #E0E7FF;
    }
    .button-container button.active {
        background-color: #E0E7FF;
        border-right: #AFADAD solid 1px;
        box-shadow: 0px 4px 1px rgba(0.3,0.4,0.2,0.2);
    }
    .button-container button img {
        width: 50px;
        height: auto;
    }
    .button-container button h1 {
        font-size: 12px;
        margin: 0;
        font-family: 'Poppins-Bold';
    }
    .logout-container {
        width: 100px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 30px;
        position: fixed;
        bottom: -30px;
        left: 23px;
    }
    .logout-bttn {
        padding: 10px;
        display: flex;
        align-items: center;
        border: none;
        background-color: transparent;
        cursor: pointer;
    }
    .logout-bttn img {
        width: 40px;
        height: auto;
    }
</style>
