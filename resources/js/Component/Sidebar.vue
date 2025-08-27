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
                :class="{ active: active === incomeKey }"
                @click="goIncome"
                v-if="!isUser"
            >
                <img src="/public/images/Button-icon/income.png"/>
                <h1>Income</h1>
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
                :class="{ active: active === 'Reports' }"
                @click="emit('navigate','Reports')"
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
    const incomeKey = computed(() => isAdmin.value ? 'AdminIncome' : isChef.value ? 'ChefIncome' : null)

    function goHome() {
        emit('navigate', homeKey.value)
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
        width: 7%;
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
        border-radius: 30px 0 0 30px;
        border: none;
        background-color: transparent;
        cursor: pointer;
        gap: 5px;
    }
    .button-container button.active {
        background-color: #E0E7FF;
    }
    .button-container button img {
        width: 40px;
        height: auto;
    }
    .button-container button h1 {
        font-size: 1em;
        margin: 0;
    }
    .logout-container {
        width: 100px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 30px;
        position: fixed;
        bottom: -30px;
        left: 10px;
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
