<template>
    <div class="recipe-details-main-container w-full h-screen overflow-hidden flex flex-col">
        <div class="details" v-if="canViewDetails">
            <div class="back-button-container w-full h-[100px] flex items-center">
                <button @click="$emit('back')" class="w-[50px] h-[50px] flex items-center justify-center bg-transparent border-none cursor-pointer ml-[30px]">
                    <img src="/public/images/Button-icon/back.png" alt="back" class="w-full h-auto">
                </button>
            </div>
            <div class="details-main-container w-full h-screen flex flex-row">
                <div class="left-side-details-container h-[72%] w-[40%] flex flex-col items-center justify-center">
                    <div class="recipe-img-container w-[53%] h-[55%] rounded-full flex items-center justify-center">
                        <img :src="`/storage/${recipe.image_path}`" alt="recipe image" class="w-full h-full rounded-full"/>
                    </div>
                    <div class="recipe-name-container w-full h-auto flex flex-col items-center mt-[20px]">
                        <p class="m-0 p-0 font-['Poppins-Bold'] text-3xl text-[#31485B]">{{ recipe.recipeName}}</p>
                        <p class="cuisine-type m-0 p-0 font-['Poppins-SemiBold'] italic text-[1.3em] text-[#768082]">{{ recipe.cuisineType }}</p>
                        <p class="chef-name mt-[5px] p-0 font-['Poppins-Regular'] text-[1em] text-[#768082]">Chef: @{{ recipe.user.user_info.userName }}</p>
                    </div>
                    <div class="recipe-react-container w-full h-auto flex flex-row items-center justify-center gap-[20px]">
                        <button class="like-bttn w-[25px] h-[25px] flex items-center justify-center border-none bg-transparent cursor-pointer m-[10px] gap-[5px] font-['Poppins-Regular'] text-[1em]" @click="react(1)">
                            <span class="material-icons text-[2em] text-[#EC3F57]" v-if="userReactedLike">favorite</span>
                            <span class="material-icons text-[2em] text-[#EC3F57]" v-else>favorite_border</span>
                            <p>{{ likeCount }}</p>
                        </button>

                        <button class="dislike-bttn w-[25px] h-[25px] flex items-center justify-center border-none bg-transparent cursor-pointer m-[10px] gap-[5px] font-['Poppins-Regular'] text-[1em]" @click="react(2)">
                            <span class="material-icons text-[2em] text-[#EC3F57]" v-if="userReactedDislike">thumb_down</span>
                            <span class="material-icons text-[2em] text-[#EC3F57]" v-else>thumb_down_off_alt</span>
                            <p>{{ dislikeCount }}</p>
                        </button>
                    </div>
                    <div class="w-full h-auto flex flex-col items-center justify-center ">
                        <!-- Replace the entire star rating div with this -->
                        <div class="flex gap-2">
                            <span
                                v-for="n in 5"
                                :key="n"
                                class="material-icons cursor-pointer text-[32px] transition-all hover:scale-110"
                                :class="n <= rating ? 'text-yellow-400' : 'text-gray-400'"
                                @click="rating = n; rateRecipe()"
                            >
                                {{ n <= rating ? 'star' : 'star_border' }}
                            </span>
                        </div>
                        <span class="text-gray-500 font-['Poppins-SemiBold'] text-xs p-0 mt-2">
                            {{ recipe.average_rating || '0' }} out of 5
                        </span>
                    </div>
                </div>
                <div class="right-side-details-container h-[72%] w-[60%]">
                    <div class="right-side-details-main bg-[#B5BFDE] w-[85%] h-[95%] rounded-[35px] ml-[30px] overflow-auto p-[20px] sm:p-[40px] flex flex-col">
                        <div class="h-auto w-full flex justify-end ">
                            <button @click="downloadPdf" class="px-4 py-2 bg-blue-500 text-white rounded cursor-pointer">
                                Download PDF
                            </button>
                        </div>

                        <div class="description-container w-full h-auto mb-[10px] mt-[10px]">
                            <div class="label-container w-full h-[50px] flex items-center">
                                <img src="/public/images/Button-icon/description.png" alt="description" class="w-[30px] h-[30px]"/>
                                <h2 class="m-0 ml-[13px] font-['Poppins-Bold'] text-[1.3em]">Description</h2>
                            </div>
                            <p class="text-justify mt-0 ml-[70px] font-['Poppins-Regular'] text-[.95em]">{{ recipe.description }}</p>
                        </div>
                        <div class="ingredient-container w-full h-auto mb-[10px]">
                            <div class="label-container w-full h-[50px] flex items-center">
                                <img src="/public/images/Button-icon/ingredients.png" alt="description" class="w-[30px] h-[30px]"/>
                                <h2 class="m-0 ml-[13px] font-['Poppins-Bold'] text-[1.3em]">Ingredient</h2>
                            </div>
                            <div class="main-ingredient w-full h-auto flex items-center justify-center flex-col">
                                <div class="ingredient-list w-full h-auto flex flex-row" v-for="(item, index) in recipe.ingredient" :key="index">
                                    <div class="ingredient-quantity w-1/2 h-auto flex justify-end">
                                        <p class="m-0 p-0 text-justify font-['Poppins-Italic'] mr-[20px] text-[.95em]"> {{ item.quantity }} </p>
                                    </div>
                                    <div class="ingredient-name w-1/2 h-auto flex">
                                        <p class="m-0 p-0 text-justify font-['Poppins-Bold'] ml-[20px] font-bold capitalize text-[.95em]"> {{ item.ingredientName }} </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="procedure-container w-full h-auto mb-[10px]">
                            <div class="label-container w-full h-[50px] flex items-center">
                                <img src="/public/images/Button-icon/sidebar_recipe.png" alt="description" class="w-[30px] h-[30px]"/>
                                <h2 class="m-0 ml-[13px] font-['Poppins-Bold'] text-[1.3em]">Procedure</h2>
                            </div>
                            <div class="procedure-main w-full h-auto flex flex-col items-center gap-[20px] pt-[10px]">
                                <div class="procedure-list-container w-[80%] h-auto pt-[10px] pr-[20px] pb-[10px] pl-[40px] bg-[#7592AB] rounded-[15px] relative" v-for="(procedure, index) in recipe.procedure" :key="index">
                                    <div class="procedure-number absolute w-[40px] h-[40px] rounded-full bg-[#435F77] flex items-center justify-center text-white bottom-[45%] right-[96%] font-['Poppins-Bold']">
                                        <p>{{ procedure.step }}</p>
                                    </div>
                                    <p class="m-0 p-0 text-justify font-['Poppins-SemiBold'] text-[.95em]">{{ procedure.instruction }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="video-container mb-[20px]">
                            <div class="label-container w-full h-[50px] flex items-center">
                                <img src="/public/images/Button-icon/video.png" alt="description" class="w-[30px] h-[30px]"/>
                                <h2 class="m-0 ml-[13px] font-['Poppins-Bold'] text-[1.3em]">Video</h2>
                            </div>
                            <div class="video-main w-full h-[500px]">
                                <iframe
                                    v-if="embedUrl"
                                    :src="embedUrl"
                                    frameborder="0"
                                    allow="autoplay; encrypted-media"
                                    allowfullscreen
                                    width="100%"
                                    height="100%"
                                    class="rounded-[20px]"
                                ></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div
            class="fixed inset-0 flex items-center justify-center"
            v-else-if="pendingRecipe"
        >
            <div class="w-1/3 h-1/3 bg-[#435F77] flex flex-col items-center justify-between rounded-[30px] ml-35 p-6">
                <div class="flex-1 flex items-center justify-center px-4">
                    <p class="text-[#E0E7FF] text-center text-2xl font-['Poppins-BoldItalic']">
                        Your Payment has been sent.
                        Please wait for confirmation!
                    </p>
                </div>

                <button
                    class="mb-[10px] px-6 py-2 bg-[#E0E7FF] text-[#435F77] rounded-full font-['Poppins-Bold'] hover:bg-[#B5BFDE] hover:text-[#ffff] transition-all duration-300 cursor-pointer"
                    @click="$emit('back')"
                >
                    CLOSE
                </button>
            </div>
        </div>


        <div class="not-buy-container" v-else>
            <div class="back-button-container w-full h-[100px] flex items-center">
                <button @click="$emit('back')" class="w-[50px] h-[50px] flex items-center justify-center bg-transparent border-none cursor-pointer ml-[30px]">
                    <img src="/public/images/Button-icon/back.png" alt="back" class="w-full h-auto">
                </button>
            </div>
            <div class="details-main-container w-full h-screen flex flex-row">
                <div class="left-side-details-container h-[72%] w-[40%] flex flex-col items-center justify-center">
                    <div class="recipe-img-container w-[57%] h-[57%] rounded-full flex items-center justify-center">
                        <img :src="`/storage/${recipe.image_path}`" alt="recipe image" class="w-full h-full rounded-full"/>
                    </div>
                    <div class="recipe-name-container w-full flex flex-col items-center mt-[20px]">
                        <p class="m-0 p-0 font-['Poppins-Bold'] text-3xl text-[#31485B] text-center">{{ recipe.recipeName }}</p>
                        <p class="cuisine-type m-0 p-0 font-['Poppins-SemiBold'] italic text-[1.3em] text-[#768082]">{{ recipe.cuisineType }}</p>
                        <p class="chef-name mt-[5px] p-0 font-['Poppins-Regular'] text-[1em] text-[#768082]">Chef: @{{ recipe.user.user_info.userName }}</p>
                    </div>
                    <div class="recipe-react-container w-full h-auto flex flex-row items-center justify-center gap-[20px]">
                        <button class="like-bttn w-[25px] h-[25px] flex items-center justify-center border-none bg-transparent cursor-pointer m-[10px] gap-[5px] font-['Poppins-Regular'] text-[1em]" @click="react(1)">
                            <span class="material-icons text-[2em] text-[#EC3F57]" v-if="userReactedLike">favorite</span>
                            <span class="material-icons text-[2em] text-[#EC3F57]" v-else>favorite_border</span>
                            <p>{{ likeCount }}</p>
                        </button>

                        <button class="dislike-bttn w-[25px] h-[25px] flex items-center justify-center border-none bg-transparent cursor-pointer m-[10px] gap-[5px] font-['Poppins-Regular'] text-[1em]" @click="react(2)">
                            <span class="material-icons text-[2em] text-[#EC3F57]" v-if="userReactedDislike">thumb_down</span>
                            <span class="material-icons text-[2em] text-[#EC3F57]" v-else>thumb_down_off_alt</span>
                            <p>{{ dislikeCount }}</p>
                        </button>
                    </div>
                    <div  class="recipe-price-container w-[216px] h-[42px] flex justify-between items-center bg-[#7592AB] mt-[10px] rounded-[15px] px-[15px]">
                        <p class="recipe-price-label font-['Poppins-Bold'] text-[1em] text-[#E0E7FF] m-0 p-0">COST:</p>
                        <p class="recipe-price-value font-['Poppins-Bold'] text-[1.3em] text-[#FF000D] m-0 p-0">₱ {{ recipe.price }}.00</p>
                    </div>
                </div>
                <div class="right-side-details-container h-[72%] w-[60%]">
                    <div class="nb-form-container h-full w-full">
                        <div class="nb-form bg-[#F5F5F5] border-r border-[#AFADAD] shadow-[1px_4px_5px_#AFADAD] w-[85%] h-[95%] rounded-[35px] ml-[30px] overflow-auto p-[20px] sm:p-[40px] flex flex-row">
                            <div class="nb-form-left w-1/2 h-full flex flex-col items-center border-r-[2px] border-white justify-center gap-[10px] mt-[-10px] pr-[40px]">
                                <label class="font-['Poppins-Bold'] font-bold text-[#768082] text-[1.8em] self-start">Payment</label>
                                <div class="qr-code-container w-full h-[40%] flex items-center justify-center">
                                    <img alt="qrcode" :src="`/storage/${recipe.gCash_path}`" class="w-auto h-[95%]"/>
                                </div>
                                <div class="gcash-number-container w-full h-[10%] flex flex-row items-center justify-center gap-[10px]">
                                    <p class="gcash-label text-white p-[8px] sm:p-[10px] bg-[#435F77] rounded-[30px] text-[.7em] self-center font-['Poppins-Bold']">G-Cash Number:</p>
                                    <p class="gcash-number p-[15px] sm:p-[12.5px] bg-[#B5BFDE] rounded-[10px] text-[#768082] text-[.8em] font-['Poppins-Bold']">{{ recipe.gcash_number }}</p>
                                </div>
                                <p class="proof-payment-text text-justify font-['Poppins-Italic'] text-[.75em] text-[#768082]">Please send your payment to the QR code or the number provided above and upload a screenshot as proof of payment. Purchased recipe will be available after verification.</p>
                                <div class="proof-payment-container w-full h-[45px] rounded-[30px] bg-[#B5BFDE] flex flex-row items-center">
                                    <button
                                        @click="triggerFileInput"
                                        class="w-[20%] h-[65%] rounded-[20px] border-none bg-[#435F77] text-white ml-[20px] cursor-pointer font-['Poppins-Bold'] text-[.7em]"
                                    >
                                        Upload
                                    </button>
                                    <input
                                        type="file"
                                        ref="fileInput"
                                        @change="handleFileUpload"
                                        hidden
                                    />
                                    <p class="text-[#768082] ml-[15px] max-w-[220px] whitespace-nowrap overflow-hidden text-ellipsis font-['Poppins-Italic'] text-[.75em]">{{ form.proof ? form.proof.name : "Proof of Payment" }}</p>
                                </div>
                            </div>
                            <div class="nb-form-right w-1/2 h-full flex flex-col items-center border-l-[2px] border-white justify-center gap-[10px] pl-[40px] mt-[-10px]">
                                <label class="font-['Poppins-Bold'] font-bold text-[#768082] text-[1.8em] self-start">Order Form</label>
                                <p class="text-justify font-['Poppins-Italic'] text-[.75em] text-[#768082] mt-[-5px]">
                                    In order to access this premium recipe, a one-time recipe fee is required.
                                    Once purchased, the recipe will be permanently accessible in your account.
                                </p>

                                <div class="form-container w-full flex flex-col gap-[30px] mt-[20px] mb-[20px]">
                                    <div class="form-input flex items-center gap-[10px]">
                                        <div class="form-label-container w-[30%] flex justify-end items-center">
                                            <label class="text-black font-['Poppins-BoldItalic'] text-[0.8em] mr-[5px]">Email:</label>
                                        </div>
                                        <div class="form-field-container w-[60%] flex flex-col">
                                            <p class="font-['Poppins-Bold'] text-[0.85em] text-[#31485B] m-0">{{ user.email }}</p>
                                        </div>
                                    </div>

                                    <div class="form-input flex items-center gap-[10px]">
                                        <div class="form-label-container w-[30%] flex justify-end items-center">
                                            <label class="text-black font-['Poppins-BoldItalic'] text-[0.8em] mr-[5px]">Full Name:</label>
                                        </div>
                                        <div class="form-field-container w-[60%] flex flex-col">
                                            <p class="fullName font-['Poppins-Bold'] text-[0.85em] text-[#31485B] m-0">{{ user.user_info.fullName }}</p>
                                        </div>
                                    </div>

                                    <div class="form-input flex items-center gap-[10px]">
                                        <div class="form-label-container w-[30%] flex justify-end items-center">
                                            <label class="text-black font-['Poppins-BoldItalic'] text-[0.8em] mr-[5px]">Phone Number:</label>
                                        </div>
                                        <div class="form-field-container w-[60%] flex flex-col">
                                            <input
                                                placeholder="Phone Number"
                                                v-model="form.phone_number"
                                                required
                                                class="w-full h-[38px] rounded-[20px] pl-[20px] border-none bg-[#B5BFDE] font-['Poppins-Bold'] text-[0.80em] text-[#31485B]"
                                            />
                                        </div>
                                    </div>

                                    <div class="form-input flex items-center gap-[10px]">
                                        <div class="form-label-container w-[30%] flex justify-end items-center">
                                            <label class="text-black font-['Poppins-BoldItalic'] text-[0.8em] mr-[5px]">Amount:</label>
                                        </div>
                                        <div class="form-field-container w-[60%] flex flex-col">
                                            <input placeholder="Amount" v-model="form.amount" required class="w-full h-[38px] rounded-[20px] pl-[20px] border-none bg-[#B5BFDE] font-['Poppins-Bold'] text-[0.80em] text-[#31485B]" />
                                        </div>
                                    </div>

                                    <div class="form-input flex items-center gap-[10px]">
                                        <div class="form-label-container w-[30%] flex justify-end items-center">
                                            <label class="text-black font-['Poppins-BoldItalic'] text-[0.8em] mr-[5px]">Reference #:</label>
                                        </div>
                                        <div class="form-field-container w-[60%] flex flex-col">
                                            <input placeholder="Reference" v-model="form.reference" required class="w-full h-[38px] rounded-[20px] pl-[20px] border-none bg-[#B5BFDE] font-['Poppins-Bold'] text-[0.80em] text-[#31485B]" />
                                        </div>
                                    </div>
                                </div>

                                <div class="nb-submit-container w-full flex justify-center mt-[20px]">
                                    <button
                                        @click.prevent="buyRecipe"
                                        :disabled="!isFormComplete"
                                        :class="{'disabled': !isFormComplete, 'bg-[#435F77] text-white hover:bg-[#7592AB] hover:text-[#31485B] hover:shadow-[1px_4px_5px_#AFADAD] cursor-pointer': isFormComplete, 'bg-[#ccc] text-[#666] cursor-not-allowed shadow-none': !isFormComplete}"
                                        class="w-[80%] h-[45px] border-none rounded-[25px] font-['Poppins-Bold'] self-center transition-all duration-300"
                                    >
                                        Submit
                                    </button>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</template>

<script setup>
    import { computed, reactive, ref, onMounted  } from "vue";
    import axios from "axios";

    const props = defineProps({
        recipe: Object,
        user: Object,
    });

    console.log(props.recipe);

    const recipeIsFree = computed(() => props.recipe?.is_free === "free");
    const userReactedLike = computed(() => Number(props.recipe?.reaction_type) === 1)
    const userReactedDislike = computed(() => Number(props.recipe?.reaction_type) === 2)
    const rating = ref(0);

    const fetchUserRatings = async () => {
        try {
            const response = await axios.get(`/user-ratings/${props.recipe.id}`,{
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            });
            if (response.data.rating !== null) {
                rating.value = response.data.rating
            }
        } catch (error) {
            console.log('No rating yet or not logged in')
            rating.value = 0
        }
    }
    const rateRecipe = async () => {
        try {
            const res = await axios.post(`/rate-recipe/${props.recipe.id}`, {
                rating: rating.value
            })
            console.log(res.data.message)
        } catch (error) {
            if (error.response?.status === 401) {
                alert('Please login to rate')
            } else {
                alert('Failed to save rating')
            }
            // Revert stars if failed
            await fetchUserRatings()
        }
    }

    const ownedRecipe = computed(() => {
        return props.recipe?.purchase?.status === "confirmed";
    });

    const isAuthor = computed(() => {
        return props.recipe?.userID === props.user?.id;
    });
    const isAdmin = computed(() => {
        return props.user?.role === "admin";
    });

    const pendingRecipe = computed(() => {
        return props.recipe?.purchase?.status === "pending";
    });

    const canViewDetails = computed(() => {
        return recipeIsFree.value || ownedRecipe.value || isAuthor.value || isAdmin.value;
    });

    const embedUrl = computed(() => {
        if (!props.recipe.video_path) return null;

        const url = new URL(props.recipe.video_path);

        // case 1: youtube.com/watch?v=VIDEO_ID
        if (url.searchParams.get("v")) {
            const videoId = url.searchParams.get("v");
            return `https://www.youtube.com/embed/${videoId}?autoplay=1&mute=1`;
        }

        // case 2: youtu.be/VIDEO_ID
        if (url.hostname === "youtu.be") {
            const videoId = url.pathname.slice(1);
            return `https://www.youtube.com/embed/${videoId}?autoplay=1&mute=1`;
        }

        return null;
    });

    const form = reactive({
        phone_number: "",
        amount: "",
        reference: "",
        proof: null,
    });
    const fileInput = ref(null);

    const triggerFileInput = () => {
        fileInput.value.click(); // open file dialog
    };

    const handleFileUpload = (event) => {
        const file = event.target.files?.[0];
        if (file) {
            form.proof = file; // store the actual file
        }
    };


    const buyRecipe = async () => {
        try {
            const formData = new FormData();
            formData.append("recipeID", props.recipe.id);
            formData.append("phone_number", form.phone_number);
            formData.append("amount", form.amount);
            formData.append("reference", form.reference);
            if (form.proof) {
                formData.append("proof", form.proof);
            }

            const response = await axios.post("/buy-recipe", formData, {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            });

            if (response.data.status === "success") {
                alert("Recipe purchase submitted. Pending verification.");

                form.phone_number = "";
                form.amount = "";
                form.reference = "";
                form.proof = null;
                if (fileInput.value) fileInput.value.value = "";

                props.recipe.purchase = { status: "pending" };
            }
        } catch (error) {
            console.error(error);
            alert("Failed to purchase recipe.");
        }
    };

    const emit = defineEmits(["back"]);

    const likeCount = ref(0)
    const dislikeCount = ref(0)

    const fetchCounts = async () => {
        try {
            const { data } = await axios.get(`/recipes/${props.recipe.id}/counts`)
            likeCount.value = data.likeCount
            dislikeCount.value = data.dislikeCount
        } catch (error) {
            console.error(error)
        }
    }

    const downloadPdf = async () => {
        try {
            const response = await axios.get(`/recipe-pdf/${props.recipe.id}`, {
                responseType: "blob", // VERY IMPORTANT
            });

            // Create a Blob URL from the PDF data
            const fileURL = URL.createObjectURL(new Blob([response.data], { type: "application/pdf" }));

            // Open the PDF in a new tab
            window.open(fileURL, "_blank");

        } catch (error) {
            console.error("PDF download failed:", error);
        }
    };


    const react = async (type) => {
        let newType = type

        if (props.recipe.reaction_type === type) {
            newType = 3
        }

        try {
            const res = await axios.post(`/react-recipe/${props.recipe.id}`, {
                reaction_type: newType,
            })

            // update reaction_type from API response
            props.recipe.reaction_type = res.data.reaction.reaction_type

            // refresh counters
            fetchCounts()
        } catch (error) {
            console.error(error)
        }
    }




    const isFormComplete = computed(() => {
        return (
            form.phone_number.trim() !== "" &&
            form.amount.trim() !== "" &&
            form.reference.trim() !== "" &&
            form.proof !== null
        );
    });
    onMounted(() => {
        fetchCounts()
    })
    onMounted(() => {
        fetchUserRatings()
    })
</script>
