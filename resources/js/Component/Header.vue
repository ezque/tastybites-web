<template>
    <div class="w-full h-[100px] flex items-center justify-between p-5 m-0 bg-[#E0E7FF]">
        <!-- Logo -->
        <img src="/public/images/tastybites_logo.png" class="w-[150px] h-auto" />

        <!-- Right side -->
        <div class="flex items-center gap-3 pl-5">
            <!-- Notification box -->
            <div class="relative flex">
                <button class="p-1 flex items-center justify-center bg-transparent cursor-pointer" @click="toggleNotification">
                    <img src="/public/images/Button-icon/notifications.png" class="w-[25px] h-auto" />
                </button>

                <!-- Notification dropdown -->
                <div v-if="isNotificationVisible"
                     class="absolute right-0 top-[75px] w-[320px] p-3 flex flex-col bg-white rounded-[20px_0_20px_20px] shadow-md border-r border-gray-400 z-[199]">

                    <!-- Header -->
                    <div class="flex flex-col w-[250px] h-[50px] mt-2">
                        <h2 class="text-[#435F77] font-bold text-[20px] m-0">Notifications</h2>
                        <div class="flex gap-1 mt-2 mb-2">
                            <button
                                class="px-3 py-1 text-sm rounded-md"
                                :class="activeFilter === 'all'
                  ? 'bg-[#B5BFDE] font-semibold text-[#435F77]'
                  : 'hover:bg-[#E0E7FF] text-[#435F77]'"
                                @click="activeFilter = 'all'">All</button>
                            <button
                                class="px-3 py-1 text-sm rounded-md"
                                :class="activeFilter === 'unread'
                  ? 'bg-[#B5BFDE] font-semibold text-[#435F77]'
                  : 'hover:bg-[#E0E7FF] text-[#435F77]'"
                                @click="activeFilter = 'unread'">Unread</button>
                        </div>
                    </div>

                    <!-- Menu (options) -->
                    <div class="absolute right-2 top-5 cursor-pointer">
                        <img src="/public/images/Button-icon/option.png" alt="options" @click="toggleMenuOpen" />
                        <div v-if="menuOpen"
                             class="absolute right-0 top-[30px] flex flex-col bg-white rounded-[20px_0_20px_20px] shadow-lg px-3 py-2 w-[170px] z-10">
                            <button @click="markAllRead"
                                    class="flex items-center gap-2 text-xs font-bold text-[#435F77] hover:bg-gray-100 rounded-md px-2 py-1">
                                <img src="/public/images/Button-icon/check.png" class="w-[25px] h-[20px]" />
                                <p>Mark All Read</p>
                            </button>
                            <div class="w-full border border-[#435F77] rounded-md my-1"></div>
                            <button
                                @click="menuOpen = false; isNotificationVisible = false; emit('navigate', 'Notification');"
                                class="flex items-center gap-2 text-xs font-bold text-[#435F77] hover:bg-gray-100 rounded-md px-2 py-1">
                                <img src="/public/images/Button-icon/notifications.png" class="w-[20px] h-[20px]" />
                                <p>Open Notifications</p>
                            </button>
                        </div>
                    </div>

                    <!-- Notifications list -->
                    <div class="flex flex-col mt-8 max-h-[500px] h-[500px] w-full overflow-auto rounded-b-[20px]">
                        <div v-for="notif in filteredNotifications" :key="notif.id" class="flex flex-col gap-2">
                            <button v-if="notif.type" @click="goToNotification(notif)"
                                    class="flex items-center gap-2 bg-[#E0E7FF] rounded-xl px-3 py-2 shadow-sm border-r border-gray-400 mt-2 cursor-pointer">
                                <div class="w-[10%]">
                                    <!-- Icons based on type -->
                                    <img v-if="notif.type === 'addPremiumRecipe'" src="/public/images/premium-icon.png" />
                                    <img v-if="notif.type === 'chefApplicant'" src="/public/images/Button-icon/chef.png" />
                                    <img v-if="notif.type === 'userApplicant'" src="/public/images/Button-icon/new_user3.png" />
                                    <img v-if="notif.type === 'report'" src="/public/images/Button-icon/report.png" />
                                    <img v-if="notif.type === 'liked'" src="/public/images/Button-icon/filled_heart.png" />
                                    <img v-if="notif.type === 'disliked'" src="/public/images/Button-icon/filled_dislike.png" />
                                    <img v-if="notif.type === 'recipePurchased'" src="/public/images/Button-icon/payment.png" />
                                    <img v-if="notif.type === 'followed'" src="/public/images/Button-icon/follow.png" />
                                    <img v-if="notif.type === 'recipeBlocked'" src="/public/images/Button-icon/block.png" />
                                    <img v-if="notif.type === 'chefApproved'" src="/public/images/Button-icon/chef.png" />
                                    <img v-if="notif.type === 'premiumRecipeApproved'" src="/public/images/Button-icon/approved.png" />
                                    <img v-if="notif.type === 'recipePurchaseApproved'" src="/public/images/Button-icon/payment.png" />
                                    <img v-if="notif.type === 'newRecipeAdded'" src="/public/images/Button-icon/RecipeFooter.png" />
                                </div>
                                <div class="flex flex-col items-start w-[90%]">
                                    <p class="text-xs text-black m-0">
                                        <!-- Messages -->
                                        <span v-if="notif.type === 'addPremiumRecipe'"><strong>@{{ notif.sender.user_info.userName }}</strong> {{ notif.message }}</span>
                                        <span v-else-if="notif.type === 'chefApplicant'"><strong>@{{ notif.sender.user_info.userName }}</strong> has signed up as a new chef. Review their profile.</span>
                                        <span v-else-if="notif.type === 'userApplicant'">We have a new user! <strong>@{{ notif.sender.user_info.userName }}</strong> has joined the community.</span>
                                        <span v-else>{{ notif.message }}</span>
                                    </p>
                                    <span class="text-[9px] text-black">{{ timeAgo(notif.created_at) }}</span>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Divider -->
            <div class="h-[50px] border border-white rounded-md"></div>

            <!-- Profile + Menu button -->
            <button class="flex items-center gap-2 pr-6 cursor-pointer bg-transparent" @click="toggleMenu">
                <div class="w-[45px] h-[45px] rounded-full bg-[#BB98B8] overflow-hidden">
                    <img v-if="user.user_info.profilePath" :src="`/storage/${user.user_info.profilePath}`" class="w-full h-full object-cover rounded-full"/>
                    <img v-else-if="user.user_info.gender === 'male'" src="/public/images/male.png" class="w-full h-full object-cover rounded-full"/>
                    <img v-else src="/public/images/female.png" class="w-full h-full object-cover rounded-full"/>
                </div>
                <h1 class="text-[1.1em] font-bold">{{ capitalizedfullName }}</h1>
            </button>
        </div>

        <!-- Profile menu dropdown -->
        <div v-if="isMenuVisible"
             class="absolute right-2 top-[10%] flex flex-col items-center bg-[#435F77] rounded-[20px_0_20px_20px] p-5 min-w-[200px] z-[999]">
            <div class="w-[100px] h-[100px] rounded-full bg-[#BB98B8] overflow-hidden">
                <img v-if="user.user_info.profilePath" :src="`/storage/${user.user_info.profilePath}`" class="w-full h-full object-cover rounded-full"/>
                <img v-else-if="user.user_info.gender === 'male'" src="/public/images/male.png" class="w-full h-full object-cover rounded-full"/>
                <img v-else src="/public/images/female.png" class="w-full h-full object-cover rounded-full"/>
            </div>
            <div class="flex flex-col items-center justify-center p-2">
                <h1 class="text-white font-bold text-[1.2em]">{{ capitalizedfullName }}</h1>
                <h6 class="text-white italic">{{ roleLabels[user?.role] }}</h6>
            </div>
            <div class="flex flex-col w-full items-center">
                <button @click="isMenuVisible = false; emit('navigate', 'Settings');"
                        class="flex items-center gap-2 w-[70%] text-white hover:translate-y-[-2px] transition">
                    <img src="/public/images/Button-icon/settings.png" class="w-[25px] ml-1" />
                    <h1 class="text-white font-bold text-[1.2em]">Settings</h1>
                </button>
                <div class="w-[80%] border border-white my-2"></div>
                <button @click="handleLogout"
                        class="flex items-center gap-2 w-[70%] text-white hover:translate-y-[-2px] transition">
                    <img src="/public/images/Button-icon/logout(3).png" class="w-[25px] ml-1" />
                    <h1 class="text-white font-bold text-[1.2em]">Logout</h1>
                </button>
            </div>
        </div>
    </div>
</template>


<script setup>
    import { computed, ref } from 'vue';
    import {Inertia} from "@inertiajs/inertia";

    const props = defineProps({
        user: Object,
        getNotification: Array,
    })

    console.log(props.getNotification?.[0]);

    const isMenuVisible = ref(false);
    const isNotificationVisible = ref(false);
    const activeFilter = ref("all");
    const menuOpen = ref(false);

    const emit = defineEmits(['navigate']);

    const filteredNotifications = computed(() => {
        if (activeFilter.value === "unread") {
            return props.getNotification.filter(n => n.status === "unread");
        }
        return props.getNotification;
    });
    const toggleNotification = () => {
        // Close menu if it's open
        if (isMenuVisible.value) {
            isMenuVisible.value = false;
        }

        // Toggle notification dropdown
        isNotificationVisible.value = !isNotificationVisible.value;
    };

    const toggleMenu = () => {
        // Close notification if it's open
        if (isNotificationVisible.value) {
            isNotificationVisible.value = false;
        }

        // Toggle menu dropdown
        isMenuVisible.value = !isMenuVisible.value;
    };

    const toggleMenuOpen = () => {
        menuOpen.value = !menuOpen.value;
    };

    const capitalizedfullName = computed(() => {
        const name = props.user?.user_info?.fullName ?? 'User'
        return name
            .toLowerCase()
            .split(' ')
            .map(word => word.charAt(0).toUpperCase() + word.slice(1))
            .join(' ')
    })
    const profileIcon = computed(() => {
        if (props.user?.role === 'chef') {
            return '/images/Button-icon/chef_white.png';
        } else if (props.user?.role === 'user') {
            return '/images/Button-icon/user_white.png';
        } else {
            return null;
        }
    });

    const profile = computed(() =>{
        if (props.user?.user_info?.gender === 'male') {
            return '/images/male.png';
        } else if (props.user?.user_info?.gender === 'female') {
            return '/images/female.png';
        } else {
            return '/images/male.png';
        }

    });
    const handleLogout = () => {
        if (confirm('Are you sure you want to logout?')) {
            Inertia.post('/logout', {}, {
                onFinish: () => {
                    window.location.reload();
                }
            });
        }
    };


    function formatMessage(message) {
        return message.replace(/(@\w+)/, "<strong>$1</strong>");
    }

    function timeAgo(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffMs = now - date;

        const seconds = Math.floor(diffMs / 1000);
        const minutes = Math.floor(diffMs / (1000 * 60));
        const hours = Math.floor(diffMs / (1000 * 60 * 60));
        const days = Math.floor(diffMs / (1000 * 60 * 60 * 24));

        if (seconds < 60) return `${seconds}s ago`;
        if (minutes < 60) return `${minutes}m ago`;
        if (hours < 24) return `${hours}h ago`;
        if (days < 7) return `${days}d ago`;

        return date.toLocaleDateString("en-US", {
            month: "short",
            day: "numeric",
            year: "numeric"
        });
    }

    const roleLabels = {
        admin: 'Administrator',
        chef: 'Chef',
        user: 'User'
    }


    const goToNotification = (notif) => {
        emit('navigate', 'TheNotification', notif);
    };

</script>

