<template>
    <div
        class="relative flex flex-col items-center justify-center w-[220px] h-[250px] rounded-2xl bg-indigo-100 shadow-md"
    >
        <!-- CARD BODY -->
        <div
            class="flex flex-col items-center justify-center w-full h-full
            bg-[#CFDAFF] rounded-2xl shadow-md p-4 transition-transform duration-300
            hover:shadow-xl hover:scale-105"
        >
            <span class="w-[100px] h-[100px] mb-3 relative">
                <img
                    :src="getProfilePic(chef)"
                    alt="Chef Image"
                    class="w-full h-full rounded-full object-cover border-2 border-indigo-200"
                />
            </span>

            <h2
                class="text-[18px] font-['Poppins-Bold'] text-center text-gray-800 truncate mb-1"
                :title="capitalizeFullName(chef.user_info?.fullName)"
            >
                {{ capitalizeFullName(chef.user_info?.fullName) }}
            </h2>

            <p class="text-sm font-['Poppins-Italic'] text-gray-500 mb-3">
                Since {{ new Date(chef.created_at).getFullYear() }}
            </p>

            <button
                class="bg-indigo-500 text-white w-[80%] px-4 py-2 rounded-full text-sm font-medium
                hover:bg-indigo-600 transition-colors duration-300 shadow-sm cursor-pointer"
                @click="openDetails"
            >
                Details
            </button>
        </div>

        <!-- DOT MENU -->
        <button
            class="absolute top-1 right-1 mt-2 p-0 bg-transparent border-none cursor-pointer flex items-center justify-center w-5"
            @click.stop="toggleMenu"
        >
            <img
                src="/public/images/Button-icon/option.png"
                alt="Options"
                class="w-2 h-6"
            />
        </button>

        <!-- DROPDOWN MENU -->
        <div
            v-if="menuOpen"
            class="absolute z-10 flex flex-col p-2 ml-2 bg-slate-700 rounded-br-xl rounded-tl-none rounded-tr-xl rounded-bl-xl left-[88%] top-11"
        >
            <button
                @click="emit('follow', chef)"
                class="flex items-center justify-center w-[90px] h-[25px] gap-2 font-bold text-white transition-colors rounded hover:bg-indigo-100 hover:text-slate-700 hover:border-r hover:border-slate-400"
            >
                <img src="/public/images/Button-icon/report.png" class="w-[30%] h-[90%]" />
                <span class="w-[70%] text-left font-['Poppins-Bold']">
                    {{ chef.follow_status === 'following' ? 'Unfollow' : 'Follow' }}
                </span>
            </button>

            <button
                @click="emit('report', chef)"
                class="flex items-center justify-center w-[90px] h-[25px] gap-2 font-bold text-white transition-colors rounded hover:bg-indigo-100 hover:text-slate-700 hover:border-r hover:border-slate-400"
            >
                <img src="/public/images/Button-icon/report.png" class="w-[30%] h-[90%]" />
                <span class="w-[70%] text-left font-['Poppins-Bold']">Report</span>
            </button>
        </div>
    </div>
</template>

<script setup>
    import { ref } from "vue";

    const props = defineProps({
        chef: Object,
        index: Number
    });

    const emit = defineEmits(["navigate", "follow", "report"]);

    const menuOpen = ref(false);
    const openDetails = () => {
        emit("navigate", "ChefDetails", props.chef);
    };

    const toggleMenu = () => {
        menuOpen.value = !menuOpen.value;
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
