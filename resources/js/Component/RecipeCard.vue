<template>
    <div class="card">
        <div class="image-container">
            <img :src="`/storage/${recipeCardDetail.image_path}`" alt="example"/>
        </div>
        <img class="premium-icon" src="/public/images/premium-icon.png" alt="icon" v-if="recipeCardDetail.is_free === 'premium'"/>
        <button class="dot-menu" @click="toggleMenu">
            <img alt="icon" src="/public/images/Button-icon/option.png" />
        </button>
        <div v-if="menuOpen" class="menu-container">
            <button @click="toggleHide">
                <img alt="icon" src="/public/images/Button-icon/hide.png" />
                <span>{{ recipeIsHidden ? 'Unhide' : 'Hide' }}</span>
            </button>
            <button @click="saveRecipe">
                <img alt="icon" src="/public/images/Button-icon/save.png" />
                <span>{{ recipeIsSave ? 'Unsave' : 'Save' }}</span>
            </button>
            <button @click="reportRecipe" v-if="!recipeCardDetail.is_owned">
                <img alt="icon" src="/public/images/Button-icon/report.png" />
                <span>Report</span>
            </button>
        </div>
        <div class="text-container">
            <h2>{{recipeCardDetail.recipeName}}</h2>
            <h3>{{recipeCardDetail.cuisineType}}</h3>
            <p>Chef: @{{ recipeCardDetail.user.user_info.userName }}</p>
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
    import {ref, onMounted, computed, watch} from 'vue'
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
    const recipeIsHidden = computed(() => Number(props.recipeCardDetail?.is_hidden) === 1)

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
        console.log('click');
        try {
            const { data } = await axios.post(`/save-recipe/${props.recipeCardDetail.id}`)
            if (data?.save_status !== undefined) {
                props.recipeCardDetail.is_saved = Number(data.save_status)
            }
        } catch (error) {
            console.error(error)
        }
    }

    const recipeIsSave = computed(() => Number(props.recipeCardDetail?.is_saved) === 1)


    watch(recipeIsSave, (newVal) => {
        console.log("recipeIsSave changed:", newVal)
    })

    const reportRecipe = async () => {
        try {
            const { data } = await axios.post(`/report/${props.recipeCardDetail.id}`, {
                type: 'recipe',
                reason: 'Inappropriate content'
            })
            alert(data.message) // show success message
        } catch (error) {
            console.error(error)
            alert("Failed to submit report.")
        }
    }


</script>

<style scoped>
    .card {
        width: 240px;
        height: 270px;
        border-radius: 20px;
        background-color: #CFDAFF;
        box-shadow: 5px 4px 2px #AFADAD;
        border-bottom: #AFADAD solid 1px;
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
        width: 65%;
        height: 100%;
        border-radius: 50%;
        margin-top: 10px;
    }
    .text-container {
        width: 100%;
        height: 30%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        margin-top: 5px;
    }
    .text-container h2 {
        font-size: 1.2em;
        text-align: center;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 200px;
        font-family: 'Poppins-Bold';
    }
    .text-container h3 {
        font-size: .8em;
        font-family: 'Poppins-BoldItalic';
    }
    .text-container p {
        font-size: .8em;
        font-family: 'Poppins-Regular';
    }
    .text-container h2, .text-container h3,.text-container p{
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
        width: 50%;
        height: 33px;
        border-radius: 20px;
        border: none;
        background-color: #435F77;
        color: white;
        font-family: 'Poppins-Bold';
        font-size: .8em;
    }
    .view-bttn:hover {
        background-color: #E0E7FF;
        box-shadow: 4px 4px 12px #AFADAD;
        border-right: #AFADAD solid 1px;
        color: #435F77;
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
        font-family: 'Poppins-Regular';
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
        font-family: 'Poppins-Regular';
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
        color: #ec3f57;
    }
    .premium-icon {
        position: absolute;
        width: 25px;
        height: auto;
        top: 15px;
        left: 15px;
    }
    .dot-menu {
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        position: absolute;
        top: 5px;
        right: 5px;
        background-color: transparent;
        border: none;
        margin-top: 10px;
        width: 20px;
    }
    .dot-menu img{
        width: 10px;
        height: 24px;
    }
    .menu-container {
        border: none;
        position: absolute;
        left: 88%;
        top: 45px;
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
        width: 90px;
        height: 25px;
        background-color: transparent;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        gap: 15px;
        cursor: pointer;
        font-family: 'Poppins-Bold';
    }
    .menu-container button span {
        width: 70%;
        text-align: start;
    }
    .menu-container button img {
        height: 90%;
        width: 30%;
    }
    .menu-container button:hover {
        transform: translateY(-2px);
        transition: all 0.2s ease;
    }


</style>
