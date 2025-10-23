<template>
    <div class="w-full h-[100px] flex items-center justify-between p-5 m-0 bg-[#E0E7FF]">
        <!-- Logo -->
        <img src="/public/images/tastybites_logo.png" class="w-[150px] h-auto" alt="icon"/>

        <!-- Right side -->
        <div class="flex items-center gap-3 pl-5">
            <!-- Notification box -->
            <div class="relative flex">
                <button
                    ref="notifButtonRef"
                    class="p-1 flex items-center justify-center bg-transparent cursor-pointer"
                    @click="toggleNotification"
                >
                    <img src="/public/images/Button-icon/notifications.png" class="w-[25px] h-auto" alt="icon"/>
                </button>

                <!-- Notification dropdown -->
                <div
                    v-if="isNotificationVisible"
                    ref="notifDropdownRef"
                    class="absolute right-0 top-[75px] w-[340px] p-3 flex flex-col bg-white rounded-[20px_0_20px_20px] shadow-md border-r border-gray-400 z-[199]"
                >
                    <!-- Header -->
                    <div class="flex flex-col w-[250px] h-[50px] mt-2">
                        <h2 class="text-[#435F77] text-[22px] m-0" style="font-family: 'Poppins-Bold'">Notifications</h2>
                        <div class="flex gap-1 mt-2 mb-2">
                            <button
                                class="px-3 py-1 text-sm rounded-md"
                                :class="activeFilter === 'all'
                  ? 'bg-[#B5BFDE] text-[#435F77]'
                  : 'hover:bg-[#E0E7FF] text-[#435F77]'"
                                style="font-family: 'Poppins-Regular'"
                                @click="activeFilter = 'all'"
                            >All</button>
                            <button
                                class="px-3 py-1 text-sm rounded-md"
                                :class="activeFilter === 'unread'
                  ? 'bg-[#B5BFDE] text-[#435F77]'
                  : 'hover:bg-[#E0E7FF] text-[#435F77]'"
                                style="font-family: 'Poppins-Regular'"
                                @click="activeFilter = 'unread'"
                            >Unread</button>
                        </div>
                    </div>

                    <!-- Menu (options) -->
                    <div class="absolute right-2 top-5 cursor-pointer">
                        <img
                            src="/public/images/Button-icon/option.png"
                            alt="options"
                            @click="toggleMenuOpen"
                            class="w-2 h-auto mr-[5px]"
                        />

                        <div
                            v-if="menuOpen"
                            class="absolute right-0 top-[30px] flex flex-col bg-white rounded-[20px_0_20px_20px] shadow-lg px-3 py-2 w-[185px] z-10"
                        >
                            <button
                                @click="markAllRead"
                                class="flex items-center gap-2 text-xs text-[#435F77] hover:bg-gray-100 rounded-md px-2 py-1"
                            >
                                <img src="/public/images/Button-icon/check.png" class="w-[25px] h-[20px]" alt="icon"/>
                                <span style="font-family: 'Poppins-Bold'">Mark All Read</span>
                            </button>
                            <div class="w-full border border-[#435F77] rounded-md my-1"></div>
                            <button
                                @click="menuOpen = false; isNotificationVisible = false; emit('navigate', 'Notification');"
                                class="flex items-center gap-2 text-xs text-[#435F77] hover:bg-gray-100 rounded-md px-2 py-1"
                            >
                                <img src="/public/images/Button-icon/notifications.png" class="w-[20px] h-[20px]" alt="icon"/>
                                <span style="font-family: 'Poppins-Bold'">Open Notifications</span>
                            </button>
                        </div>
                    </div>

<<<<<<< Updated upstream
                    <!-- Notifications list -->
                    <div class="flex flex-col mt-8 max-h-[500px] h-[500px] w-full overflow-auto rounded-b-[20px]">
                        <div v-for="notif in filteredNotifications" :key="notif.id" class="flex flex-col gap-2">
                            <button
                                v-if="notif.type"
                                @click="goToNotification(notif)"
                                :class="[
                  'flex items-center gap-2 rounded-xl px-3 py-2 shadow-sm border-r border-gray-400 mt-2 cursor-pointer',
                  notif.status === 'unread' ? 'bg-[#E0E7FF]' : 'bg-white'
                ]"
                            >
                <span class="w-[10%]">
                   <img v-if="notif.type === 'addPremiumRecipe'" src="/public/images/premium-icon.png" alt="icon"/>
                   <img v-if="notif.type === 'chefApplicant'" src="/public/images/Button-icon/chef.png" alt="icon"/>
                   <img v-if="notif.type === 'userApplicant'" src="/public/images/Button-icon/new_user3.png" alt="icon"/>
                   <img v-if="notif.type === 'report'" src="/public/images/Button-icon/report.png" alt="icon"/>
                   <img v-if="notif.type === 'liked'" src="/public/images/Button-icon/filled_heart.png" alt="icon"/>
                   <img v-if="notif.type === 'disliked'" src="/public/images/Button-icon/filled_dislike.png" alt="icon"/>
                   <img v-if="notif.type === 'recipePurchased'" src="/public/images/Button-icon/payment.png" alt="icon"/>
                   <img v-if="notif.type === 'followed'" src="/public/images/Button-icon/follow.png" alt="icon"/>
                   <img v-if="notif.type === 'recipeBlocked'" src="/public/images/Button-icon/block.png" alt="icon"/>
                   <img v-if="notif.type === 'chefApproved'" src="/public/images/Button-icon/chef.png" alt="icon"/>
                   <img v-if="notif.type === 'premiumRecipeApproved'" src="/public/images/Button-icon/approved.png" alt="icon"/>
                   <img v-if="notif.type === 'premiumRecipeDeclined'" src="/public/images/Button-icon/approved.png" alt="icon"/>
                   <img v-if="notif.type === 'recipePurchaseApproved'" src="/public/images/Button-icon/payment.png" alt="icon"/>
                   <img v-if="notif.type === 'recipePurchaseDenied'" src="/public/images/Button-icon/payment.png" alt="icon"/>
                   <img v-if="notif.type === 'newRecipeAdded'" src="/public/images/Button-icon/RecipeFooter.png" alt="icon"/>
                </span>
                <span class="flex flex-col items-start w-[90%]">
                    <span class="text-xs text-black m-0 text-justify font-[Poppins-Regular] ">
                        <span v-if="notif.type === 'addPremiumRecipe'"><strong>@{{ notif.sender.user_info.userName }}</strong> {{ notif.message }}</span>
                        <span v-else-if="notif.type === 'chefApplicant'"><strong>@{{ notif.sender.user_info.userName }}</strong> has signed up as a new chef. Review their profile.</span>
                        <span v-else-if="notif.type === 'userApplicant'">We have a new user! <strong>@{{ notif.sender.user_info.userName }}</strong> has joined the community.</span>
                        <span v-else>{{ notif.message }}</span>
                    </span>
                    <span class="text-[9px] text-black">{{ timeAgo(notif.created_at) }}</span>
                </span>
                            </button>
=======
                                <button v-else>
                                    <h2>🔔 {{ notif.message }}</h2>
                                </button>
                                
                                <button class="notification-item">
                                    <div class="notif-icon">
                                        <img src="/public/images/premium-icon.png" alt="premium" />
                                    </div>
                                    <div class="notif-content">
                                        <p v-html="formatMessage(notif.message)"></p>
                                        <span class="time">{{ timeAgo(notif.created_at) }}</span>
                                    </div>
                                </button>

                                <button class="notification-item">
                                    <div class="notif-icon">
                                        <img src="/public/images/Button-icon/chef.png" alt="Chef" />
                                    </div>
                                    <div class="notif-content">
                                        <p><strong>@chefMario</strong> has signed up as a new chef. Review their profile.</p>
                                        <span class="time">{{ timeAgo(notif.created_at) }}</span>
                                    </div>
                                </button>

                                <button class="notification-item">
                                    <div class="notif-icon">
                                        <img src="/public/images/Button-icon/new_user3.png" alt="user" />
                                    </div>
                                    <div class="notif-content">
                                        <p>We have a new user! <strong>@Zellyace</strong> has joined the community.</p>
                                        <span class="time">{{ timeAgo(notif.created_at) }}</span>
                                    </div>
                                </button>

                                <button class="notification-item">
                                    <div class="notif-icon">
                                        <img src="/public/images/Button-icon/report.png" alt="report" />
                                    </div>
                                    <div class="notif-content">
                                        <p><strong>Adobo <i>(Filipino cuisine)</i></strong> is reported by <strong>@Zellyace</strong>. Review report now!</p>
                                        <span class="time">{{ timeAgo(notif.created_at) }}</span>
                                    </div>
                                </button>

                                <!-- chef -->
                                <button class="notification-item">
                                    <div class="notif-icon">
                                        <img src="/public/images/Button-icon/filled_dislike.png" alt="dislike" />
                                    </div>
                                    <div class="notif-content">
                                        <p><strong>Adobo <i>(Filipino cuisine)</i></strong> was disliked by <strong>@Zellyace</strong>.</p>
                                        <span class="time">{{ timeAgo(notif.created_at) }}</span>
                                    </div>
                                </button>

                                <button class="notification-item">
                                    <div class="notif-icon">
                                        <img src="/public/images/Button-icon/filled_heart.png" alt="love" />
                                    </div>
                                    <div class="notif-content">
                                        <p><strong>Adobo <i>(Filipino cuisine)</i></strong> was loved by <strong>@Zellyace</strong>.</p>
                                        <span class="time">{{ timeAgo(notif.created_at) }}</span>
                                    </div>
                                </button>

                                <button class="notification-item">
                                    <div class="notif-icon">
                                        <img src="/public/images/Button-icon/payment.png" alt="payment" />
                                    </div>
                                    <div class="notif-content">
                                        <p><strong>@appletamesis</strong> wants to purchase your <strong>Okonomiyaki <i>(Japanese cuisine)</i></strong>. Review and verify now.</p>
                                        <span class="time">{{ timeAgo(notif.created_at) }}</span>
                                    </div>
                                </button>

                                <button class="notification-item">
                                    <div class="notif-icon">
                                        <img src="/public/images/Button-icon/follow.png" alt="follow" />
                                    </div>
                                    <div class="notif-content">
                                        <p><strong>@appletamesis</strong> is now following you!</p>
                                        <span class="time">{{ timeAgo(notif.created_at) }}</span>
                                    </div>
                                </button>

                                <button class="notification-item">
                                    <div class="notif-icon">
                                        <img src="/public/images/Button-icon/block.png" alt="follow" />
                                    </div>
                                    <div class="notif-content">
                                        <p><strong>Adobo <i>(Filipino cuisine)</i></strong> was blocked by <strong>Admin</strong>.</p>
                                        <span class="time">{{ timeAgo(notif.created_at) }}</span>
                                    </div>
                                </button>
                                
                                <button class="notification-item">
                                    <div class="notif-icon">
                                        <img src="/public/images/Button-icon/chef.png" alt="as_chef" />
                                    </div>
                                    <div class="notif-content">
                                        <p><strong>Chef status unlocked! ✨</strong> Share your first recipe now!</p>
                                        <span class="time">{{ timeAgo(notif.created_at) }}</span>
                                    </div>
                                </button>

                                <button class="notification-item">
                                    <div class="notif-icon">
                                        <img src="/public/images/Button-icon/approved.png" alt="approved" />
                                    </div>
                                    <div class="notif-content">
                                        <p>Your premium recipe, <strong>Adobo <i>(Filipino cuisine)</i></strong> was approved! Recipe is now available for purchase.</p>
                                        <span class="time">{{ timeAgo(notif.created_at) }}</span>
                                    </div>
                                </button>

                                <button class="notification-item">
                                    <div class="notif-icon">
                                        <img src="/public/images/Button-icon/payment.png" alt="approved_payment" />
                                    </div>
                                    <div class="notif-content">
                                        <p>Purchase approved! <strong>Adobo <i>(Filipino cuisine)</i></strong> is now accessible in your profile.</p>
                                        <span class="time">{{ timeAgo(notif.created_at) }}</span>
                                    </div>
                                </button>

                                <!-- user -->
                                <button class="notification-item">
                                    <div class="notif-icon">
                                        <img src="/public/images/Button-icon/RecipeFooter.png" alt="approved_payment" />
                                    </div>
                                    <div class="notif-content">
                                        <p>Chef <strong>@bon</strong> added new recipe, check it now!</p>
                                        <span class="time">{{ timeAgo(notif.created_at) }}</span>
                                    </div>
                                </button>

                                <button class="notification-item">
                                    <div class="notif-icon">
                                        <img src="/public/images/Button-icon/payment.png" alt="approved_payment" />
                                    </div>
                                    <div class="notif-content">
                                        <p>Purchase approved! <strong>Adobo <i>(Filipino cuisine)</i></strong> is now accessible in your profile.</p>
                                        <span class="time">{{ timeAgo(notif.created_at) }}</span>
                                    </div>
                                </button>
                            </div>
>>>>>>> Stashed changes
                        </div>
                    </div>
                </div>
            </div>

            <!-- Divider -->
            <div class="h-[50px] border border-white rounded-md"></div>

            <!-- Profile + Menu button -->
            <button
                ref="profileButtonRef"
                class="flex items-center gap-2 pr-6 cursor-pointer bg-transparent"
                @click="toggleMenu"
            >
        <span class="w-[45px] h-[45px] rounded-full bg-[#BB98B8] overflow-hidden">
          <img v-if="user.user_info.profilePath" :src="`/storage/${user.user_info.profilePath}`" class="w-full h-full object-cover rounded-full" alt="icon"/>
          <img v-else-if="user.user_info.gender === 'male'" src="/public/images/male.png" class="w-full h-full object-cover rounded-full" alt="icon"/>
          <img v-else src="/public/images/female.png" class="w-full h-full object-cover rounded-full" alt="icon"/>
        </span>
                <span class="text-[1.1em] font-[Poppins-Bold] ">{{ capitalizedfullName }}</span>
            </button>
        </div>

        <!-- Profile menu dropdown -->
        <div
            v-if="isMenuVisible"
            ref="menuRef"
            class="absolute right-5 top-[10%] flex flex-col items-center bg-[#435F77] rounded-[20px_0_20px_20px] p-5 min-w-[230px] z-[999]"
        >
            <div class="w-[100px] h-[100px] rounded-full bg-[#BB98B8] overflow-hidden">
                <img v-if="user.user_info.profilePath" :src="`/storage/${user.user_info.profilePath}`" class="w-full h-full object-cover rounded-full" alt="icon"/>
                <img v-else-if="user.user_info.gender === 'male'" src="/public/images/male.png" class="w-full h-full object-cover rounded-full" alt="icon"/>
                <img v-else src="/public/images/female.png" class="w-full h-full object-cover rounded-full" alt="icon"/>
            </div>
            <div class="flex flex-col items-center justify-center p-2">
                <h1 class="text-white text-[1.2em] font-[Poppins-Bold] ">{{ capitalizedfullName }}</h1>
                <h6 class="text-white text-[.8em] font-[Poppins-Italic] ">{{ roleLabels[user?.role] }}</h6>
            </div>
            <div class="flex flex-col w-full items-center justify-center text-center">
                <button
                    @click="isMenuVisible = false; emit('navigate', 'Settings');"
                    class="flex items-center justify-center gap-2 w-[70%] text-white hover:translate-y-[-2px] transition cursor-pointer"
                >
                    <img src="/public/images/Button-icon/settings.png" class="w-[25px] ml-1" alt="icon"/>
                    <span class="text-white text-[1em] w-[75px] ml-1 font-[Poppins-Bold] ">Settings</span>
                </button>

                <div class="w-[80%] border border-white my-2"></div>

                <button
                    @click="handleLogout"
                    class="flex items-center justify-center gap-2 w-[70%] text-white hover:translate-y-[-2px] transition cursor-pointer"
                >
                    <img src="/public/images/Button-icon/logout(3).png" class="w-[25px] ml-1" alt="icon"/>
                    <span class="text-white font-bold text-[1em] w-[75px] ml-1 font-[Poppins-Bold] ">Logout</span>
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
    import { Inertia } from '@inertiajs/inertia';
    import axios from 'axios';

    const props = defineProps({
        user: Object,
        getNotification: Array,
    });

    const emit = defineEmits(['navigate']);

    const isMenuVisible = ref(false);
    const isNotificationVisible = ref(false);
    const activeFilter = ref("all");
    const menuOpen = ref(false);

    const profileButtonRef = ref(null);
    const menuRef = ref(null);
    const notifButtonRef = ref(null);
    const notifDropdownRef = ref(null);

    // ✅ Close dropdowns when clicking outside
    const handleClickOutside = (event) => {
        const profileMenu = menuRef.value;
        const profileButton = profileButtonRef.value;
        const notifDropdown = notifDropdownRef.value;
        const notifButton = notifButtonRef.value;

        if (
            isMenuVisible.value &&
            profileMenu &&
            !profileMenu.contains(event.target) &&
            profileButton &&
            !profileButton.contains(event.target)
        ) {
            isMenuVisible.value = false;
        }

        if (
            isNotificationVisible.value &&
            notifDropdown &&
            !notifDropdown.contains(event.target) &&
            notifButton &&
            !notifButton.contains(event.target)
        ) {
            isNotificationVisible.value = false;
        }
    };

    onMounted(() => {
        document.addEventListener('click', handleClickOutside);
    });

    onBeforeUnmount(() => {
        document.removeEventListener('click', handleClickOutside);
    });

    const filteredNotifications = computed(() => {
        if (activeFilter.value === "unread") {
            return props.getNotification.filter(n => n.status === "unread");
        }
        return props.getNotification;
    });

    const toggleNotification = () => {
        if (isMenuVisible.value) isMenuVisible.value = false;
        isNotificationVisible.value = !isNotificationVisible.value;
    };

    const toggleMenu = () => {
        if (isNotificationVisible.value) isNotificationVisible.value = false;
        isMenuVisible.value = !isMenuVisible.value;
    };

    const toggleMenuOpen = () => {
        menuOpen.value = !menuOpen.value;
    };

    const capitalizedfullName = computed(() => {
        const name = props.user?.user_info?.fullName ?? 'User';
        return name
            .toLowerCase()
            .split(' ')
            .map(word => word.charAt(0).toUpperCase() + word.slice(1))
            .join(' ');
    });

    const roleLabels = {
        admin: 'Administrator',
        chef: 'Chef',
        user: 'User',
    };

    const handleLogout = () => {
        if (confirm('Are you sure you want to logout?')) {
            Inertia.post('/logout', {}, {
                onFinish: () => {
                    window.location.reload();
                },
            });
        }
    };

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
            year: "numeric",
        });
    }

    const goToNotification = async (notif) => {
        try {
            await axios.post(`/read-notification/${notif.id}`);
            const target = props.getNotification.find(n => n.id === notif.id);
            if (target) target.status = "read";
            emit('navigate', 'TheNotification', notif);
        } catch (error) {
            console.error("Error marking notification as read:", error);
            alert("Failed to update notification.");
        }
    };

    const markAllRead = async () => {
        try {
            const response = await axios.post("/read-all-notifications");
            alert(response.data.message);
            props.getNotification.forEach(n => (n.status = "read"));
        } catch (error) {
            console.error("Error marking all as read:", error);
            alert("Failed to mark notifications as read.");
        }
    };
    //new changes
</script>
