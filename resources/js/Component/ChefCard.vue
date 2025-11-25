<template>
    <div class="relative w-[220px] h-[280px] rounded-2xl bg-gradient-to-br from-indigo-100 to-indigo-200 shadow-md flex flex-col items-center justify-center p-3 hover:shadow-xl transition-shadow duration-300">

        <!-- Card Body -->
        <div class="flex flex-col items-center w-full h-full bg-white/60 backdrop-blur-md rounded-2xl shadow-inner p-4 transition-transform duration-300 hover:scale-[1.02]">

            <!-- Profile Picture -->
            <span class="w-[95px] h-[95px] mb-2 relative">
                <img
                    :src="getProfilePic(chef)"
                    alt="Chef Image"
                    class="w-full h-full rounded-full object-cover border-4 border-indigo-300 shadow-sm"
                />
            </span>

            <!-- Chef Name -->
            <h2
                class="text-[17px] font-['Poppins-Bold'] text-center text-gray-800 truncate w-[160px]"
                :title="capitalizeFullName(chef.user_info?.fullName)"
            >
                {{ capitalizeFullName(chef.user_info?.fullName) }}
            </h2>

            <!-- Since Joined -->
            <p class="text-xs font-['Poppins-Italic'] text-gray-500 mt-1">
                Since {{ new Date(chef.created_at).getFullYear() }}
            </p>

            <!-- Followers -->
            <p class="text-xs font-['Poppins-SemiBold'] text-gray-700 mt-2">
                👥 {{ chef.followers_count }} Followers
            </p>

            <!-- Details Button -->
            <button
                class="mt-3 bg-indigo-500 text-white w-[85%] py-2 rounded-full text-xs font-['Poppins-SemiBold'] shadow-md
                hover:bg-indigo-600 hover:shadow-lg transition-all duration-300"
                @click="openDetails"
            >
                View Details
            </button>
        </div>

        <!-- Dots Menu -->
        <button
            class="absolute top-2 right-2 bg-white/80 rounded-full shadow-sm p-1 cursor-pointer hover:bg-white transition"
            @click.stop="toggleMenu"
        >
            <img src="/public/images/Button-icon/option.png" alt="Options" class="w-1 h-4 opacity-75 hover:opacity-100">
        </button>

        <!-- Dropdown Menu -->
        <div
            v-if="isMenuOpen"
            class="absolute z-20 flex flex-col p-2 bg-slate-800/90 backdrop-blur-md text-white rounded-xl shadow-lg top-12 right-1 w-[110px]"
        >
            <button
                @click="emit('follow', chef)"
                class="flex items-center gap-2 w-full py-1 text-sm font-['Poppins-SemiBold'] hover:bg-indigo-100 hover:text-slate-700 rounded-md px-2 transition"
            >
                <span class="material-icons text-base">
                    {{ chef.follow_status === 'following' ? 'person_remove' : 'person_add' }}
                </span>
                <span>
                    {{ chef.follow_status === 'following' ? 'Unfollow' : 'Follow' }}
                </span>
            </button>

            <button
                @click="emit('report', chef)"
                class="flex items-center gap-2 w-full py-1 text-sm font-['Poppins-SemiBold'] hover:bg-red-100 hover:text-slate-700 rounded-md px-2 transition ml-1"
            >
                <img src="/public/images/Button-icon/report.png" class="w-5 h-5" />
                <span>Report</span>
            </button>
        </div>
    </div>
</template>

<script setup>
    import { computed } from "vue";

    const props = defineProps({
        chef: Object,
        index: Number,
        openMenuIndex: Number,
    });

    const emit = defineEmits(["navigate", "follow", "report", "update:openMenuIndex"]);

    const isMenuOpen = computed(() => props.openMenuIndex === props.index);

    const toggleMenu = () => {
        emit("update:openMenuIndex", isMenuOpen.value ? null : props.index);
    };

    const openDetails = () => {
        emit("navigate", "ChefDetails", props.chef);
    };

    function getProfilePic(chef) {
        const path = chef.user_info?.profilePath;
        if (path) return `/storage/${path}`;
        return chef.user_info?.gender === "female"
            ? "/images/female.png"
            : "/images/male.png";
    }

    function capitalizeFullName(name) {
        if (!name) return "";
        return name
            .toLowerCase()
            .split(" ")
            .map((w) => w.charAt(0).toUpperCase() + w.slice(1))
            .join(" ");
    }
</script>

