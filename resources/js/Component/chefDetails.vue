<template>
    <div class="flex flex-col w-full h-full">
        <!-- Page Label -->
        <div class="flex items-center">
            <h2 class="mt-5 ml-5 text-[35px] font-['Poppins-Bold']">Registered Chefs</h2>
        </div>

        <!-- Body -->
        <div class="flex items-center justify-center w-full h-[88%] p-5">
            <div class="flex flex-row w-[70%] h-[90%] mb-24 overflow-hidden bg-[#E0E7FF] rounded-3xl shadow-lg">

                <!-- Left Info -->
                <div class="flex flex-col w-[30%] h-full p-5">
                    <!-- Chef Info -->
                    <div class="flex flex-col items-center justify-center w-full h-1/2 text-center">
                        <div class="flex items-center justify-center w-[180px] h-[180px] mb-2 overflow-hidden bg-gray-200 rounded-full">
                            <img :src="getChefProfile()" alt="profile" class="object-cover w-full h-full" />
                        </div>
                        <h2 class="text-xl text-gray-900 font-['Poppins-Bold']">{{ chef?.user_info?.fullName }}</h2>
                        <p class="text-sm text-gray-600 font-['Poppins-Italic']">Since {{ new Date(chef.created_at).getFullYear() }}</p>
                    </div>

                    <!-- Certifications -->
                    <p class="mt-5 mb-0 text-sm text-gray-900 font-['Poppins-BoldItalic']">Certifications</p>
                    <div class="flex flex-row items-center justify-between w-full h-1/2 p-2">
                        <button
                            @click="prevCertificate"
                            class="flex items-center justify-center w-10 h-10 text-lg text-white bg-slate-700 rounded-full hover:bg-indigo-200 hover:text-gray-900 hover:shadow-lg hover:scale-110 transition font-['Poppins-Bold']"
                        >
                            &lt;
                        </button>

                        <div class="flex items-center justify-center w-[70%] h-full overflow-hidden">
                            <div v-if="chef.certificates.length > 0" class="flex items-center justify-center w-full h-full">
                                <img
                                    :src="`/storage/${chef.certificates[currentIndex]?.certificate_path}`"
                                    alt="certificate"
                                    class="object-contain max-w-[90%] max-h-[90%] rounded-lg shadow-md hover:scale-105 transition"
                                />
                            </div>
                            <div v-else>
                                <p class="text-xs text-gray-600 font-['Poppins-Italic']">No certificates available</p>
                            </div>
                        </div>

                        <button
                            @click="nextCertificate"
                            class="flex items-center justify-center w-10 h-10 text-lg text-white bg-slate-700 rounded-full hover:bg-indigo-200 hover:text-gray-900 hover:shadow-lg hover:scale-110 transition font-['Poppins-Bold']"
                        >
                            &gt;
                        </button>
                    </div>
                </div>

                <!-- Right Info -->
                <div class="flex flex-col items-center justify-center w-[70%] h-[full] border-l-4 border-white">
                    <div
                        class="flex flex-wrap items-start justify-center w-full h-[90%] gap-4 p-2 overflow-y-auto scrollbar-hide"
                    >
                        <RecipeCard
                            v-for="(recipeCardDetail, index) in chefRecipe"
                            :key="recipeCardDetail.id"
                            :recipeCardDetail="recipeCardDetail"
                            :index="index"
                            :activeMenuId="activeMenuId"
                            @toggle-menu="handleToggleMenu"
                            @navigate="(component, recipeData) => {
                                if (component === 'RecipeDetails') {
                                  emit('recipeNavigate', recipeData)
                                } else {
                                  emit('navigate', component, recipeData)
                                }
                            }"
                        />
                    </div>
                </div>
            </div>
        </div>
        <Footer />
    </div>
</template>

<script setup>
    import { ref, computed, onMounted, onBeforeUnmount } from "vue";
    import RecipeCard from "@/Component/RecipeCard.vue";
    import Footer from "@/Component/Footer.vue";
    import axios from "axios";

    const props = defineProps({
        chef: Object,
        user: Object,
    });
    const emit = defineEmits(["navigate", "recipeNavigate"]);
    const loading = ref(true);
    const activeMenuId = ref(null);


    const recipeCardDetails = ref({
        all: [],
        topLiked: [],
        topPurchased: []
    });
    function handleToggleMenu(id) {
        activeMenuId.value = activeMenuId.value === id ? null : id
    }
    function handleClickOutside(e) {
        const menuButtons = document.querySelectorAll('[data-menu-button]')
        const dropdowns = document.querySelectorAll('[data-menu-dropdown]')

        const clickedInsideButton = Array.from(menuButtons).some(btn => btn.contains(e.target))
        const clickedInsideDropdown = Array.from(dropdowns).some(drop => drop.contains(e.target))

        if (!clickedInsideButton && !clickedInsideDropdown) {
            activeMenuId.value = null
        }
    }

    onMounted(() => {
        document.addEventListener('click', handleClickOutside)
    })

    onBeforeUnmount(() => {
        document.removeEventListener('click', handleClickOutside)
    })

    const chefRecipe = computed(() => {
        if (!props.chef) return [];
        return recipeCardDetails.value.all.filter(
            (recipe) => recipe.userID === props.chef.id
        );
    });


    function getChefProfile() {
        const profilePath = props.chef?.user_info?.profilePath;
        if (profilePath && profilePath.trim() !== "") {
            return `/storage/${profilePath}`;
        }
        const gender = props.chef?.user_info?.gender;
        if (gender === "male") {
            return "/images/male.png";
        } else if (gender === "female") {
            return "/images/female.png";
        } else {
            return "/images/male.png"; // default fallback
        }
    }

    const currentIndex = ref(0);

    function prevCertificate() {
        if (!props.chef?.certificates?.length) return;
        const totalItems = props.chef.certificates.length;
        currentIndex.value = (currentIndex.value - 1 + totalItems) % totalItems;
    }

    function nextCertificate() {
        if (!props.chef?.certificates?.length) return;
        const totalItems = props.chef.certificates.length;
        currentIndex.value = (currentIndex.value + 1) % totalItems;
    }

    const fetchRecipe = async () => {
        loading.value = true;
        try {
            const { data } = await axios.get('/all-recipes', {
                headers: { 'Content-Type': 'multipart/form-data' }
            });
            recipeCardDetails.value = data;
        } catch (error) {
            console.error(error);
        } finally {
            loading.value = false;
        }
    };

    onMounted(() => {
        fetchRecipe();
    });

</script>

<style>
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
