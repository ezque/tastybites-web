<template>
    <div class="recipe-details-main-container">
        <!-- Free or owned recipe -->
        <div class="details" v-if="canViewDetails">
            <div class="back-button-container">
                <button @click="$emit('back')">
                    <img src="/public/images/Button-icon/back.png" alt="back">
                </button>
            </div>
            <div class="details-main-container">
                <div class="left-side-details-container">
                    <div class="recipe-img-container">
                        <img :src="`/storage/${recipe.image_path}`" alt="recipe image"/>
                    </div>
                    <div class="recipe-name-container">
                        <p class="recipe-name">{{ recipe.recipeName}}</p>
                        <p class="cuisine-type">{{ recipe.cuisineType }}</p>
                        <p class="chef-name">Chef: @{{ recipe.user.user_info.userName }}</p>
                    </div>
                    <div class="recipe-react-container">
                        <button class="like-bttn" @click="react(1)">
                            <span class="material-icons" v-if="userReactedLike">favorite</span>
                            <span class="material-icons" v-else>favorite_border</span>
                            <p>{{ likeCount }}</p>
                        </button>

                        <!-- DISLIKE -->
                        <button class="dislike-bttn" @click="react(2)">
                            <span class="material-icons" v-if="userReactedDislike">thumb_down</span>
                            <span class="material-icons" v-else>thumb_down_off_alt</span>
                            <p>{{ dislikeCount }}</p>
                        </button>

                    </div>
                </div>
                <div class="right-side-details-container">
                    <div class="right-side-details-main">
                        <div class="description-container">
                            <div class="label-container">
                                <img src="/public/images/Button-icon/description.png" alt="description"/>
                                <h2>Description</h2>
                            </div>
                            <p>{{ recipe.description }}</p>
                        </div>
                        <div class="ingredient-container">
                            <div class="label-container">
                                <img src="/public/images/Button-icon/ingredients.png" alt="description"/>
                                <h2>Ingredient</h2>
                            </div>
                            <div class="main-ingredient">
                                <div class="ingredient-list" v-for="(item, index) in recipe.ingredient" :key="index">
                                    <div class="ingredient-quantity">
                                        <p> {{ item.quantity }} </p>
                                    </div>
                                    <div class="ingredient-name">
                                        <p> {{ item.ingredientName }} </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="procedure-container">
                            <div class="label-container">
                                <img src="/public/images/Button-icon/sidebar_recipe.png" alt="description"/>
                                <h2>Procedure</h2>
                            </div>
                            <div class="procedure-main">
                                <div class="procedure-list-container" v-for="(procedure, index) in recipe.procedure" :key="index">
                                    <div class="procedure-number">
                                        <p>{{ procedure.step }}</p>
                                    </div>
                                    <p>{{ procedure.instruction }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="video-container">
                            <div class="label-container">
                                <img src="/public/images/Button-icon/video.png" alt="description"/>
                                <h2>Video</h2>
                            </div>
                            <div class="video-main">
                                <iframe
                                    v-if="embedUrl"
                                    :src="embedUrl"
                                    frameborder="0"
                                    allow="autoplay; encrypted-media"
                                    allowfullscreen
                                    width="560"
                                    height="315"
                                ></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Pending purchase -->
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
            <div class="back-button-container">
                <button @click="$emit('back')">
                    <img src="/public/images/Button-icon/back.png" alt="back">
                </button>
            </div>
            <div class="details-main-container">
                <div class="left-side-details-container">
                    <div class="recipe-img-container">
                        <img :src="`/storage/${recipe.image_path}`" alt="recipe image"/>
                    </div>
                    <div class="recipe-name-container">
                        <p class="recipe-name">{{ recipe.recipeName}}</p>
                        <p class="cuisine-type">{{ recipe.cuisineType }}</p>
                        <p class="chef-name">Chef: @{{ recipe.user.user_info.userName }}</p>
                    </div>
                    <div class="recipe-react-container">
                        <button class="like-bttn" @click="react(1)">
                            <span class="material-icons" v-if="userReactedLike">favorite</span>
                            <span class="material-icons" v-else>favorite_border</span>
                            <p>{{ likeCount }}</p>
                        </button>

                        <!-- DISLIKE -->
                        <button class="dislike-bttn" @click="react(2)">
                            <span class="material-icons" v-if="userReactedDislike">thumb_down</span>
                            <span class="material-icons" v-else>thumb_down_off_alt</span>
                            <p>{{ dislikeCount }}</p>
                        </button>
                    </div>
                    <div  class="recipe-price-container">
                        <p class="recipe-price-label">COST:</p>
                        <p class="recipe-price-value">₱ {{ recipe.price }}.00</p>
                    </div>
                </div>
                <div class="right-side-details-container">
                    <div class="nb-form-container">
                        <div class="nb-form">
                            <div class="nb-form-left">
                                <label>Payment</label>
                                <div class="qr-code-container">
                                    <img alt="qrcode" :src="`/storage/${recipe.gCash_path}`"/>
                                </div>
                                <div class="gcash-number-container">
                                    <p class="gcash-label">G-Cash Number:</p>
                                    <p class="gcash-number">{{ recipe.gcash_number }}</p>
                                </div>
                                <p class="proof-payment-text">Please send your payment to the QR code or the number provided above and upload a screenshot as proof of payment. Purchased recipe will be available after verification.</p>
                                <div class="proof-payment-container">
                                    <button
                                        @click="triggerFileInput"
                                    >
                                        Upload
                                    </button>
                                    <input
                                        type="file"
                                        ref="fileInput"
                                        @change="handleFileUpload"
                                        hidden
                                    />
                                    <p>{{ form.proof ? form.proof.name : "Proof of Payment" }}</p>
                                </div>
                            </div>
                            <div class="nb-form-right">
                                <label>Order Form</label>
                                <p>
                                    In order to access this premium recipe, a one-time recipe fee is required.
                                    Once purchased, the recipe will be permanently accessible in your account.
                                </p>

                                <div class="form-container">
                                    <div class="form-input">
                                        <div class="form-label-container">
                                            <label>Email:</label>
                                        </div>
                                        <div class="form-field-container">
                                            <p>{{ user.email }}</p>
                                        </div>
                                    </div>

                                    <div class="form-input">
                                        <div class="form-label-container">
                                            <label>Full Name:</label>
                                        </div>
                                        <div class="form-field-container">
                                            <p class="fullName">{{ user.user_info.fullName }}</p>
                                        </div>
                                    </div>

                                    <div class="form-input">
                                        <div class="form-label-container">
                                            <label>Phone Number:</label>
                                        </div>
                                        <div class="form-field-container">
                                            <input
                                            placeholder="Phone Number"
                                            v-model="form.phone_number"
                                            required
                                            />
                                        </div>
                                    </div>

                                    <div class="form-input">
                                        <div class="form-label-container">
                                            <label>Amount:</label>
                                        </div>
                                        <div class="form-field-container">
                                            <input placeholder="Amount" v-model="form.amount" required />
                                        </div>
                                    </div>

                                    <div class="form-input">
                                        <div class="form-label-container">
                                            <label>Reference #:</label>
                                        </div>
                                        <div class="form-field-container">
                                            <input placeholder="Reference" v-model="form.reference" required />
                                        </div>
                                    </div>
                                </div>

                                <div class="nb-submit-container">
                                    <button
                                        @click.prevent="buyRecipe"
                                        :disabled="!isFormComplete"
                                        :class="{ disabled: !isFormComplete }"
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

    // console.log(props.recipe.is_free)

    const recipeIsFree = computed(() => props.recipe?.is_free === "free");

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
        proof: null, // this will hold the file object
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
    onMounted(() => {
        fetchCounts()
    })

    const userReactedLike = computed(() => Number(props.recipe?.reaction_type) === 1)
    const userReactedDislike = computed(() => Number(props.recipe?.reaction_type) === 2)

    const isFormComplete = computed(() => {
        return (
            form.phone_number.trim() !== "" &&
            form.amount.trim() !== "" &&
            form.reference.trim() !== "" &&
            form.proof !== null
        );
    });
</script>

<style scoped>
    .recipe-details-main-container {
        width: 100%;
        height: 100vh;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }
    .back-button-container {
        width: 100%;
        height: 100px;
        display: flex;
        align-items: center;
    }
    .back-button-container button {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: transparent;
        border: none;
        cursor: pointer;
        margin-left: 30px;
    }
    .back-button-container button img {
        width: 100%;
        height: auto;
    }
    .details-main-container {
        width: 100%;
        height: 100vh;
        display: flex;
        flex-direction: row;
    }
    .left-side-details-container {
        height: 72%;
        width: 40%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .right-side-details-container {
        height: 72%;
        width: 60%;
    }
    .recipe-img-container {
        width: 65%;
        height: 59%;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .recipe-img-container img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
    }
    .recipe-name-container {
        width: 100%;
        height: auto;
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-top: 20px;
    }
    .recipe-name {
        margin: 0;
        padding: 0;
        font-family: 'Poppins-Bold';
        font-size: 2.3em;
        color: #31485B;
    }
    .cuisine-type {
        margin: 0;
        padding: 0;
        font-family: 'Poppins-SemiBold';
        font-style: italic;
        font-size: 1.3em;
        color: #768082;
    }
    .chef-name {
        margin-top: 5px;
        padding: 0;
        font-family: 'Poppins-Regular';
        font-size: 1em;
        color: #768082;
    }
    .recipe-react-container {
        width: 100%;
        height: auto;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
        gap: 20px;
    }
    .recipe-react-container button {
        width: 25px;
        height: 25px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        background-color: transparent;
        cursor: pointer;
        margin: 10px;
        gap: 5px;
        font-family: 'Poppins-Regular';
        font-size: 1em;
    }
    .recipe-react-container button span {
        font-size: 2em;
        color: #EC3F57;
    }

    .recipe-price-container {
        width: 216px;
        height: 42px;
        display: flex;
        justify-content: space-between; /* push label left, value right */
        align-items: center; /* vertically center both */
        background-color: #7592AB;
        margin-top: 10px;
        border-radius: 15px;
        padding: 0 15px; /* add inner spacing */
    }

    .recipe-price-label {
        font-family: 'Poppins-Bold';
        font-size: 1em;
        color: #E0E7FF;
        margin: 0;
        padding: 0;
    }

    .recipe-price-value {
        font-family: 'Poppins-Bold';
        font-size: 1.3em;
        color: #FF000D;
        margin: 0;
        padding: 0;
    }

    .right-side-details-main {
        background-color: #B5BFDE;
        width: 85%;
        height: 95%;
        border-radius: 35px;
        margin-left: 30px;
        overflow: auto;
        padding: 20px 40px 20px 40px;
        display: flex;
        flex-direction: column;
    }
    .description-container, .ingredient-container, .procedure-container{
        width: 100%;
        height: auto;
        margin-bottom: 10px;
    }
    .description-container {
        margin-top: 10px;
    }
    .video-container {
        margin-bottom: 20px;
    }
    .description-container p {
        text-align: justify;
        margin-top: 0;
        margin-left: 70px;
        font-family: 'Poppins-Regular';
        font-size: .95em;
    }
    .label-container {
        width: 100%;
        height: 50px;
        display: flex;
        align-items: center;
    }
    .label-container img {
        width: 30px;
        height: 30px;
    }
    .label-container h2 {
        margin: 0;
        margin-left: 13px;
        font-family: 'Poppins-Bold';
        font-size: 1.3em;
    }
    .main-ingredient {
        width: 100%;
        height: auto;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
    }
    .ingredient-list {
        width: 100%;
        height: auto;
        display: flex;
        flex-direction: row;
    }
    .ingredient-quantity {
        width: 50%;
        height: auto;
        display: flex;
        justify-content: end;
    }
    .ingredient-name {
        width: 50%;
        height: auto;
        display: flex;
    }
    .ingredient-quantity p{
        margin: 0;
        padding: 0;
        text-align: justify;
        font-family: 'Poppins-Italic';
        margin-right: 20px;
        font-size: .95em;
    }
    .ingredient-name p{
        margin: 0;
        padding: 0;
        text-align: justify;
        font-family: 'Poppins-Bold';
        margin-left: 20px;
        font-weight: bold;
        text-transform: capitalize;
        font-size: .95em;
    }
    .procedure-main {
        width: 100%;
        height: auto;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 20px;
        padding-top: 10px;
    }
    .procedure-list-container {
        width: 80%;
        height: auto;
        padding-top: 10px;
        padding-right: 20px;
        padding-bottom: 10px;
        padding-left: 40px;
        background-color: #7592AB;
        border-radius: 15px;
        position: relative;
    }
    .procedure-list-container p {
        margin: 0;
        padding: 0;
        text-align: justify;
        font-family: 'Poppins-SemiBold';
        font-size: .95em;
    }
    .procedure-number {
        position: absolute;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #435F77;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        bottom: 45%;
        right: 96%;
        font-family: 'Poppins-Bold';
    }
    .video-main {
        width: 100%;
        height: 500px;

    }
    .video-main iframe {
        width: 100%;
        height: 100%;
        border-radius: 20px;
    }
    .nb-form-container {
        height: 100%;
        width: 100%;
    }
    .nb-form {
        background-color: #F5F5F5;
        border-right: #AFADAD 1px solid;
        box-shadow: #AFADAD 1px 4px 5px;
        width: 85%;
        height: 95%;
        border-radius: 35px;
        margin-left: 30px;
        overflow: auto;
        padding: 20px 40px 20px 40px;
        display: flex;
        flex-direction: row;
    }
    .nb-form-left {
        width: 50%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        border-right: 2px solid white;
        justify-content: center;
        gap: 10px;
        margin-top: -10px;
        padding-right: 40px;
    }
    .nb-form-left label {
        font-family: 'Poppins-Bold';
        font-weight: bold;
        color: #768082;
        font-size: 1.8em;
        align-self: flex-start;
    }
    .qr-code-container {
        width: 100%;
        height: 40%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .qr-code-container img {
        width: auto;
        height: 95%;
    }
    .gcash-number-container {
        width: 100%;
        height: 10%;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }
    .gcash-label {
        color: white;
        padding: 8px 10px 8px 10px;
        background-color: #435F77;
        border-radius: 30px;
        font-size: .7em;
        align-self: center;
        font-family: 'Poppins-Bold';
    }
    .gcash-number {
        padding: 15px 12.5px 15px 12.5px;
        background-color: #B5BFDE;
        border-radius: 10px;
        color: #768082;
        font-size: .8em;
        font-family: 'Poppins-Bold';
    }
    .proof-payment-container {
        width: 100%;
        height: 45px;
        border-radius: 30px;
        background-color: #B5BFDE;
        display: flex;
        flex-direction: row;
        align-items: center;
    }
    .proof-payment-text {
        text-align: justify;
        font-family: 'Poppins-Italic';
        font-size: .75em;
        color: #768082;
    }
    .proof-payment-container button {
        width: 20%;
        height: 65%;
        border-radius: 20px;
        border: none;
        background-color: #435F77;
        color: white;
        margin-left: 20px;
        cursor: pointer;
        font-family: 'Poppins-Bold';
        font-size: .7em;
    }
    .proof-payment-container p {
        color: #768082;
        margin-left: 15px;
        max-width: 220px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        font-family: 'Poppins-Italic';
        font-size: .75em;
    }

    .nb-form-right {
        width: 50%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        border-left: 2px solid white;
        justify-content: center;
        gap: 10px;
        padding-left: 40px;
        margin-top: -10px;
    }

    .nb-form-right > label {
        font-family: 'Poppins-Bold';
        font-weight: bold;
        color: #768082;
        font-size: 1.8em;
        align-self: flex-start;
    }

    .nb-form-right > p {
        text-align: justify;
        font-family: 'Poppins-Italic';
        font-size: .75em;
        color: #768082;
        margin-top: -5px;
    }

    .form-container {
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: 30px;
        margin-top: 20px;
        margin-bottom: 20px;
    }

    .form-input {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-label-container {
        width: 30%;
        display: flex;
        justify-content: flex-end;
        align-items: center;
    }

    .form-field-container {
        width: 60%;
        display: flex;
        flex-direction: column;
    }

    .form-label-container label {
        color: black;
        font-family: 'Poppins-BoldItalic';
        font-size: 0.8em;
        margin-right: 5px;
    }

    .form-field-container p {
        font-family: 'Poppins-Bold';
        font-size: 0.85em;
        color: #31485B;
        margin: 0;
    }

    .form-field-container input {
        width: 100%;
        height: 38px;
        border-radius: 20px;
        padding-left: 20px;
        border: none;
        background-color: #B5BFDE;
        font-family: 'Poppins-Bold';
        font-size: 0.80em;
        color: #31485B;
    }

    .nb-submit-container {
        width: 100%;
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }

    .nb-submit-container button {
        background-color: #435F77;
        width: 80%;
        height: 45px;
        border: none;
        border-radius: 25px;
        color: white;
        font-family: 'Poppins-Bold';
        cursor: pointer;
        align-self: center;
    }
    .nb-submit-container button:hover {
        background-color: #7592AB;
        color: #31485B;
        box-shadow: #AFADAD 1px 4px 5px;
    }

    .nb-submit-container button:disabled,
    .nb-submit-container button.disabled {
        background-color: #ccc;
        color: #666;
        cursor: not-allowed;
        box-shadow: none;
    }

</style>
