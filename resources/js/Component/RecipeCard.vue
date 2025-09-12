<template>
    <div class="card">
        <div class="image-container">
            <img :src="`/storage/${recipeCardDetail.image_path}`" alt="example"/>
        </div>
        <img class="premium-icon" src="/public/images/premium-icon.png" alt="icon" v-if="recipeCardDetail.is_free === 'premium'"/>
        <button class="dot-menu" @click="toggleMenu">
            <span class="material-icons">more_vert</span>
        </button>
        <div v-if="menuOpen" class="menu-container">
            <button @click="toggleHide">
                <img alt="icon" src="/public/images/Button-icon/hide.png" />
                Hide
            </button>
            <button @click="saveRecipe">
                <img alt="icon" src="/public/images/Button-icon/save.png" />
                Save
            </button>
        </div>
        <div class="text-container">
            <h2>{{recipeCardDetail.recipeName}}</h2>
            <h3>{{recipeCardDetail.cuisineType}}</h3>
            <h5>chef: @{{ recipeCardDetail.user.user_info.userName }}</h5>
        </div>
        <div class="button-container">
            <button
                class="view-bttn"
                @click="emit('navigate', 'RecipeDetails', recipeCardDetail)"
            >
                View Recipe
            </button>
            <div class="like-container">
                <button class="like-bttn" @click="react(1)">
                    <span class="material-icons" v-if="userReactedLike">favorite</span>
                    <span class="material-icons" v-else>favorite_border</span>
                </button>
                <p>{{ likeCount }}</p>
            </div>
            <div class="dislike-container">
                <button class="dislike-bttn" @click="react(2)">
                    <span class="material-icons" v-if="userReactedDislike">thumb_down</span>
                    <span class="material-icons" v-else>thumb_down_off_alt</span>
                </button>
                <p>{{ dislikeCount }}</p>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref, onMounted,computed } from 'vue'
    import axios from 'axios'

    const props = defineProps({
        recipeCardDetail: Object,
    })
    const emit = defineEmits(['navigate'])

    const likeCount = ref(0)
    const dislikeCount = ref(0)
    const menuOpen = ref(false);

    const fetchCounts = async () => {
        try {
            const { data } = await axios.get(`/recipes/${props.recipeCardDetail.id}/counts`)
            likeCount.value = data.likeCount
            dislikeCount.value = data.dislikeCount
        } catch (error) {
            console.error(error)
        }
    }

    const toggleMenu = () => {
        menuOpen.value = !menuOpen.value;
    };

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

    const userReactedLike = computed(() => Number(props.recipeCardDetail?.reaction_type) === 1)
    const userReactedDislike = computed(() => Number(props.recipeCardDetail?.reaction_type) === 2)

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
        } catch (error) {
            console.error(error)
        }
    }

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
        font-size: 1.3em;
        text-align: center;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 200px;
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
        color: #E693AB;
    }
    .premium-icon {
        position: absolute;
        width: 35px;
        height: auto;
        top: 10px;
        left: 10px;
    }
    .dot-menu {
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        position: absolute;
        top: 0;
        right: 3px;
        background-color: transparent;
        border: none;
        margin-top: 10px;
        width: 20px;
    }
    .dot-menu span{
        font-size: 28px;
        color: #333;
    }
    .menu-container {
        border: none;
        position: absolute;
        left: 88%;
        top: 40px;
        margin-left: 10px;
        background-color: #435F77;
        border-radius: 0 10px 10px 10px;
        z-index: 9;
        padding: 10px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .menu-container button {
        width: 70px;
        height: 25px;
        background-color: transparent;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        gap: 10px;
        cursor: pointer;
    }
    .menu-container button img {
        height: 100%;
        width: 20px;
    }


</style>
