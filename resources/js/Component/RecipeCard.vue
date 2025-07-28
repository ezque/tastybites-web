<template>
    <div class="card">
        <div class="image-container">
            <img :src="`/storage/${recipe.image_path}`" alt="example"/>

        </div>
        <img class="premium-icon" src="/public/images/premium-icon.png" alt="icon" v-if="recipe.is_free === 'premium'"/>
        <div class="text-container">
            <h2>{{recipe.recipeName}}</h2>
            <h3>{{recipe.cuisineType}}</h3>
            <h5>chef: @{{ recipe.user.user_info.userName }}</h5>
        </div>
        <div class="button-container">
            <button class="view-bttn">View Recipe</button>
            <div class="like-container">
                <button class="like-bttn">
                    <span class="material-icons">favorite_border</span>
                </button>
                <p>500</p>
            </div>
            <div class="dislike-container">
                <button class="dislike-bttn">
                    <span class="material-icons-outlined">thumb_down</span>
                </button>
                <p>500</p>
            </div>
        </div>
    </div>
</template>
<script setup>
    const props = defineProps({
        recipe: Object,
    })
</script>
<style scoped>
    .card {
        width: 220px;
        height: 270px;
        border-radius: 20px;
        background-color: #E0E7FF;
        box-shadow: 5px 4px 2px #AFADAD;
        position: relative;
    }
    .image-container {
        width: 96%;
        height: 45%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 5px;
    }
    .image-container img {
        width: 60%;
        height: 90%;
        border-radius: 50%;
    }
    .text-container {
        width: 100%;
        height: 30%;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .text-container h2 {
        font-size: 1.6em;
    }
    .text-container h3 {
        font-size: 1em;
    }
    .text-container h5 {
        font-size: 1em;
        font-weight: normal;
    }
    .text-container h2, .text-container h3,.text-container h5{
        margin: 0;
    }
    .button-container {
        width: 100%;
        height: 19%;
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
    }
    .view-bttn {
        cursor: pointer;
        width: 55%;
        height: 30px;
        border-radius: 20px;
        border: none;
        background-color: #435F77;
        color: white;
        font-family: 'Poppins', sans-serif;
    }
    .like-container {
        width: 18%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .like-container p {
        margin: 0;
        font-size: 0.7em;
    }
    .dislike-container {
        width: 18%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .dislike-container p {
        margin: 0;
        font-size: 0.7em;
    }
    .like-bttn, .dislike-bttn {
        background-color: transparent;
        padding: 1px;
        cursor: pointer;
        display: flex;
        align-items: center;
        border: none;
        justify-content: center;
    }
    .like-bttn span, .dislike-bttn span{
        font-size: 1.7em;
    }
    .premium-icon {
        position: absolute;
        width: 35px;
        height: auto;
        top: 10px;
        left: 10px;
    }

</style>
