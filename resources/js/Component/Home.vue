<template>
    <div class="home-main-container">
            <div class="container-head">
                <div class="text-head-container">
                    <p>Flavor Made Easy</p>
                    <img src="/public/images/tastybites_plate.png" alt="img" />
                </div>

            </div>
            <div class="home-body">
                <RecipeCard
                    v-for="(recipeCardDetail, index) in recipeCardDetails"
                    :key="recipeCardDetail.id"
                    :recipeCardDetail="recipeCardDetail"
                    :index="index"
                    @navigate="(component, data) => emit('navigate', component, data)"
                />
            </div>
        <div class="footer">
            <p>© 2025 Tastybites. All rights reserved.</p>
        </div>
    </div>
</template>

<script setup>
    import {computed} from "vue";
    import RecipeCard from "@/Component/RecipeCard.vue";

    const emit = defineEmits(['navigate'])

    const props = defineProps({
        recipeCardDetails: Array
    })

</script>

<style scoped>
    .home-main-container {
        width: 100%;
        height: 85vh;
        display: flex;
        flex-direction: column;
        overflow-y: auto;
        overflow-x: hidden;
        gap: 10px;
    }
    .container-head {
        width: 100%;
        min-height: 400px;
        display: flex;
        align-items: end;
        justify-content: center;
    }
    .text-head-container {
        width: 75%;
        height: 60%;
        background-color: #E0E7FF;
        display: flex;
        position: relative;
        align-items: center;
        border-radius: 20px;
    }
    .text-head-container p{
        font-family: 'Rouge Script', cursive;
        font-size: 6.5em;
        margin-left: 40px;
    }
    .text-head-container img{
        width: 30%;
        height: auto;
        position: absolute;
        right: 100px;
        margin-bottom: 135px;
    }
    .home-body {
        flex-wrap: wrap;
        flex-direction: row;
        display: flex;
        padding-left: 3%;
        padding-right: 5%;
        gap: 70px;
        align-items: flex-start;
        padding-top: 10px;
        padding-bottom: 10px;
        width: 100%;
    }
    .footer {
        width: 100%;
        height: 7%;
        display: flex;
        align-items: start;
        justify-content: center;
        position: fixed;
        bottom: -20px;
    }
    .footer p {
        margin-right: 10%;
    }
</style>
<script setup lang="ts">
</script>
