<template>
    <div class="w-[8%] h-full bg-[#B5BFDE] flex flex-col justify-between">
        <div class="pt-10 flex flex-col items-end gap-5">
            <button
                class="w-[90%] flex flex-col items-center justify-center py-[10px]
                       rounded-[50px_0_0_50px] bg-transparent cursor-pointer
                       gap-[5px] transition-all duration-500 ease-in-out
                       hover:bg-[#E0E7FF]
                       [&.active]:bg-[#E0E7FF] [&.active]:border-r [&.active]:border-[#AFADAD]
                       [&.active]:shadow-[0px_4px_1px_rgba(77,102,51,0.2)]"
                :class="{ active: active === homeKey }"
                @click="goHome"
            >
                <img src="/public/images/Button-icon/home.png" class="w-[50px] h-auto"/>
                <h1 class="text-[12px] font-[Poppins-Bold] m-0">Home</h1>
            </button>
            <button
                class="w-[90%] flex flex-col items-center justify-center py-[10px]
                       rounded-[50px_0_0_50px] bg-transparent cursor-pointer
                       gap-[5px] transition-all duration-500 ease-in-out
                       hover:bg-[#E0E7FF]
                       [&.active]:bg-[#E0E7FF] [&.active]:border-r [&.active]:border-[#AFADAD]
                       [&.active]:shadow-[0px_4px_1px_rgba(77,102,51,0.2)]"
                :class="{ active: active === userChef }"
                @click="goUserChef"
                v-if="!isAdmin"
            >
                <img src="/public/images/Button-icon/chef.png" class="w-[50px] h-auto"/>
                <h1 class="text-[12px] font-[Poppins-Bold] m-0">Chef</h1>
            </button>
            <button
                class="w-[90%] flex flex-col items-center justify-center py-[10px]
                       rounded-[50px_0_0_50px] bg-transparent cursor-pointer
                       gap-[5px] transition-all duration-500 ease-in-out
                       hover:bg-[#E0E7FF]
                       [&.active]:bg-[#E0E7FF] [&.active]:border-r [&.active]:border-[#AFADAD]
                       [&.active]:shadow-[0px_4px_1px_rgba(77,102,51,0.2)]"
                :class="{ active: active === incomeKey }"
                @click="goIncome"
                v-if="!isUser"
            >
                <img src="/public/images/Button-icon/queue.png" class="w-[50px] h-auto"/>
                <h1 class="text-[12px] font-[Poppins-Bold] m-0">Queue</h1>
            </button>
            <button
                class="w-[90%] flex flex-col items-center justify-center py-[10px]
                       rounded-[50px_0_0_50px] bg-transparent cursor-pointer
                       gap-[5px] transition-all duration-500 ease-in-out
                       hover:bg-[#E0E7FF]
                       [&.active]:bg-[#E0E7FF] [&.active]:border-r [&.active]:border-[#AFADAD]
                       [&.active]:shadow-[0px_4px_1px_rgba(77,102,51,0.2)]"
                v-if="isAdmin"
                :class="{ active: active === 'AdminChefs' }"
                @click="emit('navigate','AdminChefs')"
            >
                <img src="/public/images/Button-icon/chef.png" class="w-[50px] h-auto"/>
                <h1 class="text-[12px] font-[Poppins-Bold] m-0">Chefs</h1>
            </button>
            <button
                class="w-[90%] flex flex-col items-center justify-center py-[10px]
                       rounded-[50px_0_0_50px] bg-transparent cursor-pointer
                       gap-[5px] transition-all duration-500 ease-in-out
                       hover:bg-[#E0E7FF]
                       [&.active]:bg-[#E0E7FF] [&.active]:border-r [&.active]:border-[#AFADAD]
                       [&.active]:shadow-[0px_4px_1px_rgba(77,102,51,0.2)]"
                v-if="isAdmin || isChef"
                :class="{ active: active === 'Recipes' }"
                @click="emit('navigate', 'Recipes')"
            >
                <img src="/public/images/Button-icon/recipes.png" class="w-[50px] h-auto"/>
                <h1 class="text-[12px] font-[Poppins-Bold] m-0">Recipes</h1>
            </button>
            <button
                class="w-[90%] flex flex-col items-center justify-center py-[10px]
                       rounded-[50px_0_0_50px] bg-transparent cursor-pointer
                       gap-[5px] transition-all duration-500 ease-in-out
                       hover:bg-[#E0E7FF]
                       [&.active]:bg-[#E0E7FF] [&.active]:border-r [&.active]:border-[#AFADAD]
                       [&.active]:shadow-[0px_4px_1px_rgba(77,102,51,0.2)]"
                v-if="isUser || isChef"
                :class="{ active: active === 'UserRecipe' }"
                @click="emit('navigate', 'UserRecipe')"
            >
                <img src="/public/images/Button-icon/recipes.png" class="w-[50px] h-auto"/>
                <h1 class="text-[12px] font-[Poppins-Bold] m-0">Save Recipes</h1>
            </button>
            <button
                class="w-[90%] flex flex-col items-center justify-center py-[10px]
                       rounded-[50px_0_0_50px] bg-transparent cursor-pointer
                       gap-[5px] transition-all duration-500 ease-in-out
                       hover:bg-[#E0E7FF]
                       [&.active]:bg-[#E0E7FF] [&.active]:border-r [&.active]:border-[#AFADAD]
                       [&.active]:shadow-[0px_4px_1px_rgba(77,102,51,0.2)]"
                v-if="isAdmin"
                :class="{ active: active === 'Users' }"
                @click="emit('navigate','Users')"
            >
                <img src="/public/images/Button-icon/user_sidebar.png" class="w-[50px] h-auto"/>
                <h1 class="text-[12px] font-[Poppins-Bold] m-0">Users</h1>
            </button>
            <button
                class="w-[90%] flex flex-col items-center justify-center py-[10px]
                       rounded-[50px_0_0_50px] bg-transparent cursor-pointer
                       gap-[5px] transition-all duration-500 ease-in-out
                       hover:bg-[#E0E7FF]
                       [&.active]:bg-[#E0E7FF] [&.active]:border-r [&.active]:border-[#AFADAD]
                       [&.active]:shadow-[0px_4px_1px_rgba(77,102,51,0.2)]"
                v-if="isChef"
                :class="{ active: active === 'Report' }"
                @click="emit('navigate','Report')"
            >
                <img src="/public/images/Button-icon/chef_reports.png" alt="img" class="w-[50px] h-auto"/>
                <h1 class="text-[12px] font-[Poppins-Bold] m-0">Reports</h1>
            </button>
        </div>
        <div class="w-[100px] flex items-center justify-center mb-[30px] fixed bottom-[-30px] left-[23px]">
            <button class="p-[10px] flex items-center bg-transparent border-none cursor-pointer" @click="handleLogout">
                <img src="/public/images/Button-icon/logout.png" class="w-[40px] h-auto"/>
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

