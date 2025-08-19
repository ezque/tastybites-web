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
                        <h1>{{ recipe.recipeName}}</h1>
                        <h2>{{ recipe.cuisineType }}</h2>
                        <h3>Chef: @{{ recipe.user.user_info.userName }}</h3>
                    </div>
                    <div class="recipe-react-container">
                        <button>
                            <img src="/public/images/Button-icon/filled_heart.png" alt="heart" />
                        </button>
                        <p>500</p>
                        <button>
                            <img src="/public/images/Button-icon/dislike.png" alt="heart" />
                        </button>
                        <p>500</p>
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
            class="pending-container"
            v-else-if="pendingRecipe"
        >
            <p>Your purchase is pending confirmation...</p>
        </div>

        <div class="not-buy-container" v-else>
            <h2>Premium Recipe</h2>
            <p>Please purchase to unlock the full details.</p>
        </div>


    </div>
</template>

<script setup>
    import { computed } from "vue";

    const props = defineProps({
        recipe: Object,
        user: Object,
    });

    const recipeIsFree = computed(() => props.recipe.is_free === "free");

    const ownedRecipe = computed(() => {
        return props.recipe.purchase?.status === "confirmed";
    });

    const isAuthor = computed(() => {
        return props.recipe.userID === props.user.id;
    });

    const pendingRecipe = computed(() => {
        return props.recipe.purchase?.status === "pending";
    });


    const canViewDetails = computed(() => {
        return recipeIsFree.value || ownedRecipe.value || isAuthor.value;
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

    const emit = defineEmits(["back"]);

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
        width: 55%;
        height: 54%;
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
    .recipe-name-container h1, .recipe-name-container h2, .recipe-name-container h3 {
        margin: 0;
        font-family: 'Poppins', sans-serif;
    }
    .recipe-name-container h1 {
        font-weight: bold;
        font-size: 2em;
        color: #31485B;
    }
    .recipe-name-container h2, .recipe-name-container h3 {
        font-style: italic;
        font-size: 1.2em;
        color: #768082;
    }
    .recipe-react-container {
        width: 100%;
        height: auto;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
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
    }
    .recipe-react-container button img {
        height: 100%;
        width: auto;
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
    .description-container, .ingredient-container, .procedure-container, .video-container{
        width: 100%;
        height: auto;
    }
    .description-container p {
        text-align: justify;
        margin-top: 0;
        margin-left: 70px;
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
        margin-left: 20px;
    }
    .label-container h2 {
        margin: 0;
        margin-left: 10px;
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
    .ingredient-quantity p, .ingredient-name p{
        margin: 0;
        padding: 0;
        text-align: justify;
        font-family: 'Poppins', sans-serif;
    }
    .ingredient-quantity p{
        margin-right: 20px;
    }
    .ingredient-name p{
        margin-left: 20px;
        font-weight: bold;
        text-transform: capitalize;
    }
    .procedure-main {
        width: 100%;
        height: auto;
        display: flex;
        flex-direction: column;
        align-items: end;
        gap: 25px;
        padding-top: 20px;
    }
    .procedure-list-container {
        width: 80%;
        height: auto;
        padding-top: 10px;
        padding-right: 20px;
        padding-bottom: 10px;
        padding-left: 40px;
        background-color: #7592AB;
        border-radius: 20px;
        position: relative;
    }
    .procedure-list-container p {
        margin: 0;
        padding: 0;
        text-align: justify;
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

</style>
