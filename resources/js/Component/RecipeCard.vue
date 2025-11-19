<template>
    <div class="relative w-60 h-[285px] rounded-2xl bg-[#CFDAFF] shadow-[5px_4px_2px_#AFADAD] border-b border-[#AFADAD]">
        <!-- Recipe Image -->
        <div class="flex items-center justify-center w-full h-[45%] p-1">
            <div class="w-[50%] h-full rounded-full">
                <img
                    :src="`/storage/${recipeCardDetail.image_path}`"
                    alt="recipe"
                    class="w-full h-full rounded-full mt-2 object-cover"
                />
            </div>

        </div>

        <!-- Premium Icon -->
        <img
            v-if="recipeCardDetail.is_free === 'premium'"
            src="/public/images/premium-icon.png"
            alt="premium"
            class="w-10 h-auto absolute -top-6 -left-5 -rotate-30"
        />
        <p
            v-if="recipeCardDetail.is_free === 'premium'"
            class="absolute top-1 left-3 font-['Poppins-Bold'] text-[1.1rem] text-red-500"
        >
            ₱{{recipeCardDetail.price}}
        </p>

        <!-- Dot Menu Button -->
        <button
            @click.stop="toggleMenu"
            class="absolute top-1 right-1 mt-2 p-0 bg-transparent border-none cursor-pointer flex items-center justify-center w-5"
        >
            <img src="/public/images/Button-icon/option.png" alt="options" class="w-2 h-6" />
        </button>

        <!-- Dropdown Menu -->
        <div
            v-if="activeMenuId === recipeCardDetail.id"
            class="absolute left-[88%] top-[45px] ml-2 bg-[#435F77] rounded-r-xl rounded-bl-xl rounded-br-xl p-2.5 flex flex-col gap-2.5 z-10"
        >
            <!-- Hide/Unhide -->
            <button
                @click="toggleHide"
                class="w-[90px] h-[25px] bg-transparent border-none flex items-center justify-center text-white gap-4 cursor-pointer font-['Poppins-Bold'] text-sm hover:transform hover:scale-105 transition-all"
            >
                <img src="/public/images/Button-icon/hide.png" alt="hide" class="h-[90%] w-[30%]" />
                <span class="w-[70%] text-left">{{ recipeIsHidden ? 'Unhide' : 'Hide' }}</span>
            </button>

            <!-- Save/Unsave -->
            <button
                @click="saveRecipe"
                class="w-[90px] h-[25px] bg-transparent border-none flex items-center justify-center text-white gap-4 cursor-pointer font-['Poppins-Bold'] text-sm hover:transform hover:scale-105 transition-all"
            >
                <img src="/public/images/Button-icon/save.png" alt="save" class="h-[90%] w-[30%]" />
                <span class="w-[70%] text-left">{{ recipeIsSave ? 'Unsave' : 'Save' }}</span>
            </button>

            <!-- Report -->
            <button
                @click="reportRecipe"
                v-if="!recipeCardDetail.is_owned"
                class="w-[90px] h-[25px] bg-transparent border-none flex items-center justify-center text-white gap-4 cursor-pointer font-['Poppins-Bold'] text-sm hover:transform hover:scale-105 transition-all"
            >
                <img src="/public/images/Button-icon/report.png" alt="report" class="h-[90%] w-[30%]" />
                <span class="w-[70%] text-left">Report</span>
            </button>

            <!-- Edit (Owner only) -->
            <button
                @click="editRecipe"
                v-if="recipeCardDetail.is_owned"
                class="w-[95px] h-[30px] bg-transparent border-none flex items-center justify-center text-white gap-4 cursor-pointer font-['Poppins-Bold'] text-sm hover:transform hover:scale-105 transition-all"
            >
                <img src="/public/images/Button-icon/edit-icon.png" alt="edit" class="h-[90%] w-[30%]" />
                <span class="w-[70%] text-left">Edit</span>
            </button>
            <!-- Delete (Owner only) -->
            <button
                @click="deleteRecipe"
                v-if="recipeCardDetail.is_owned"
                class="w-[85px] h-[23px] bg-transparent border-none flex items-center justify-center text-white gap-4 cursor-pointer font-['Poppins-Bold'] text-sm hover:transform hover:scale-105 transition-all"
            >
                <img src="/public/images/Button-icon/delete_white.png" alt="edit" class="h-[90%] w-[30%]" />
                <span class="w-[70%] text-left">Delete</span>
            </button>
        </div>

        <!-- Text Content -->
        <div class="flex flex-col items-center justify-center w-full h-[30%] mt-1 text-center">
            <h2
                class="text-lg font-['Poppins-Bold'] whitespace-nowrap overflow-hidden text-ellipsis max-w-[200px]"
            >
                {{ recipeCardDetail.recipeName }}
            </h2>
            <h3 class="text-sm font-['Poppins-BoldItalic']">
                {{ recipeCardDetail.cuisineType }}
            </h3>
            <p class="text-sm font-['Poppins-Regular'] lowercase italic">
                Chef: @{{ recipeCardDetail.user.user_info.userName }}
            </p>
            <div class="flex gap-2">
                <span
                    v-for="n in 5"
                    :key="n"
                    class="material-icons text-[32px] "
                    :class="iconClass(n)"
                >
                    {{ getStarIcon(n) }}
                </span>
            </div>

        </div>

        <!-- Action Buttons -->
        <div class="flex justify-center items-center w-full h-[19%]">
            <!-- View Recipe Button -->
            <button
                @click="emit('navigate', 'RecipeDetails', recipeCardDetail)"
                class="w-1/2 h-8 rounded-xl border-none bg-[#435F77] text-white font-['Poppins-Bold'] text-sm cursor-pointer hover:bg-[#E0E7FF] hover:text-[#435F77] hover:shadow-[4px_4px_12px_#AFADAD] hover:border-r hover:border-[#AFADAD] transition-all"
            >
                View Recipe
            </button>

            <!-- Like Button + Count -->
            <div class="flex flex-col items-center justify-center w-[18%] h-full">
                <button
                    @click="react(1)"
                    class="bg-transparent p-px cursor-pointer flex items-center justify-center border-none"
                >
                    <span
                       class="material-icons text-3xl text-[#ec3f57]"
                       v-if="userReactedLike"
                    >
                        favorite
                    </span>
                    <span
                        class="material-icons text-3xl text-[#ec3f57]"
                        v-else
                    >
                        favorite_border
                    </span>
                </button>
                <p class="m-0 text-xs font-poppins-regular">{{ likeCount }}</p>
            </div>

            <!-- Dislike Button + Count -->
            <div class="flex flex-col items-center justify-center w-[18%] h-full">
                <button
                    @click="react(2)"
                    class="bg-transparent p-px cursor-pointer flex items-center justify-center border-none"
                >
                    <span
                       class="material-icons text-3xl text-[#ec3f57]"
                       v-if="userReactedDislike"
                    >
                        thumb_down
                    </span>
                    <span
                        class="material-icons text-3xl text-[#ec3f57]"
                        v-else
                    >
                        thumb_down_off_alt
                    </span>
                </button>
                <p class="m-0 text-xs font-poppins-regular">{{ dislikeCount }}</p>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref, onMounted, computed, watch } from 'vue'
    import axios from 'axios'

    const props = defineProps({
        recipeCardDetail: Object,
        activeMenuId: Number,
    })
    const emit = defineEmits(['navigate', 'toggle-menu'])

    const likeCount = ref(0)
    const dislikeCount = ref(0)
    const recipeIsHidden = computed(() => Number(props.recipeCardDetail?.is_hidden) === 1)
    const userReactedLike = computed(() => Number(props.recipeCardDetail?.reaction_type) === 1)
    const userReactedDislike = computed(() => Number(props.recipeCardDetail?.reaction_type) === 2)
    const recipeIsSave = computed(() => Number(props.recipeCardDetail?.is_saved) === 1)

    const rating = ref(props.recipeCardDetail.average_rating);
    const getStarIcon = (index) => {
        const fullStars = Math.floor(rating.value);
        const decimal = rating.value - fullStars;

        if (index <= fullStars) {
            return "star";
        }
        if (index === fullStars + 1 && decimal >= 0.25) {
            return "star_half";
        }
        return "star_border";
    };


    const iconClass = (index) => {
        if (rating.value >= index) return "text-yellow-400";
        if (rating.value >= index - 0.5) return "text-yellow-300";
        return "text-gray-400";
    };
    // If recipe changes (e.g., reactivity from parent), update rating
    watch(() => props.recipeCardDetail.average_rating, (newVal) => {
        rating.value = newVal;
    });
    const toggleMenu = () => {
        emit('toggle-menu', props.recipeCardDetail.id)
    }

    const fetchCounts = async () => {
        try {
            const { data } = await axios.get(`/recipes/${props.recipeCardDetail.id}/counts`)
            likeCount.value = data.likeCount
            dislikeCount.value = data.dislikeCount
        } catch (error) {
            console.error(error)
        }
    }


    const react = async (type) => {
        let newType = type
        if (props.recipeCardDetail.reaction_type === type) {
            newType = 3
        }

        try {
            const res = await axios.post(`/react-recipe/${props.recipeCardDetail.id}`, {
                reaction_type: newType,
            })
            props.recipeCardDetail.reaction_type = res.data.reaction.reaction_type
            fetchCounts()
        } catch (error) {
            console.error(error)
        }
    }

    onMounted(() => {
        fetchCounts()
    })



    const toggleHide = async () => {
        try {
            const { data } = await axios.post(`/hide-recipe/${props.recipeCardDetail.id}`)
            props.recipeCardDetail.is_hidden = data.hide.is_hidden
        } catch (error) {
            console.error(error)
        }
    }

    const saveRecipe = async () => {
        try {
            const { data } = await axios.post(`/save-recipe/${props.recipeCardDetail.id}`)
            if (data?.save_status !== undefined) {
                props.recipeCardDetail.is_saved = Number(data.save_status)
            }
        } catch (error) {
            console.error(error)
        }
    }



    watch(recipeIsSave, (newVal) => {
        console.log("recipeIsSave changed:", newVal)
    })

    const reportRecipe = async () => {
        try {
            const { data } = await axios.post(`/report/${props.recipeCardDetail.id}`, {
                type: 'recipe',
                reason: 'Inappropriate content'
            })
            alert(data.message)
        } catch (error) {
            console.error(error)
            alert("Failed to submit report.")
        }
    }
    const editRecipe = () => {
        emit('navigate', 'AddRecipe', {
            recipeData: props.recipeCardDetail,
            isEditMode: true
        })
    }
    const deleteRecipe = async () => {
        if (!confirm("Are you sure you want to delete this recipe? This action cannot be undone.")) {
            return;
        }

        try {
            const token = localStorage.getItem("token");

            const { data } = await axios.delete(
                `/delete-recipe/${props.recipeCardDetail.id}`,
                {
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                }
            );

            alert(data.message || "Recipe deleted successfully.");
            window.location.reload();

        } catch (error) {
            console.error(error);
            alert("Failed to delete the recipe.");
        }
    };

</script>
