<template>
    <div class="chef-main-container">
        <div class="page-label">
            <h2>Registered Chefs</h2>
        </div>
        <div class="body-container">
            <div class="chef-info-card">
                <div class="chef-info-left">
                    <div class="information-container">
                        <div class="chef-image-container">
                            <img :src="getChefProfile()" alt="profile" />
                        </div>
                        <h2>{{ chef?.user_info?.fullName }}</h2>
                        <p>Since {{ new Date(chef.created_at).getFullYear() }}</p>
                    </div>
                        <p class="cert-label">Certifications</p>
                    <div class="certificate-container">

                        <button @click="prevCertificate">&lt;</button>

                        <div class="certificates">
                            <input
                                type="file"
                                ref="fileInput"
                                accept="image/*"
                                style="display: none"
                                @change="uploadCertificate"
                            />

                            <div v-if="currentIndex === 0" class="add-button-Con">
                                <button @click="chooseFile">
                                    <img src="/public/images/Button-icon/add_image.png" alt="add" />
                                </button>
                            </div>
                            <div v-else class="certificate-con">
                                <img
                                    :src="`/storage/${chef.certificates[currentIndex - 1]?.certificate_path}`"
                                    alt="certificate"
                                />
                            </div>
                        </div>
                        <button @click="nextCertificate">&gt;</button>
                    </div>
                </div>

                <div class="chef-info-right">
                    <div class="chef-recipe-container">
                        <RecipeCard
                            v-for="(recipeCardDetail, index) in chefRecipe"
                            :key="recipeCardDetail.id"
                            :recipeCardDetail="recipeCardDetail"
                            :index="index"
                            @navigate="(component, recipeData) => {
                                      if (component === 'RecipeDetails') {
                                          emit('recipeNavigate', recipeData)  // emit a special event
                                      } else {
                                          emit('navigate', component, recipeData)
                                      }
                                  }"
                            />

                    </div>
                </div>
            </div>
        </div>
        <Footer/>
    </div>
</template>

<script setup>
    import { ref, computed } from "vue";
    import axios from "axios";
    import RecipeCard from "@/Component/RecipeCard.vue";
    import Footer from "@/Component/Footer.vue";

    const props = defineProps({
        chef: Object,
        user: Object,
        recipeCardDetails: Array,
    });




    const chefRecipe = computed(() => {
        return props.recipeCardDetails.filter(
            recipe => recipe.userID === props.chef.id
        )
    })
    console.log(chefRecipe.value[0]?.is_free)



    const fileInput = ref(null);

    const currentIndex = ref(0);

    function chooseFile() {
        fileInput.value.click();
    }

    async function uploadCertificate(event) {
        const file = event.target.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append("certificate", file);
        formData.append("userID", props.chef.id);

        try {
            const response = await axios.post("/add-certificate", formData, {
                headers: { "Content-Type": "multipart/form-data" }
            });

            console.log("Upload successful:", response.data);

            props.chef.certificates.push(response.data.certificate);

            currentIndex.value = 1;
        } catch (error) {
            console.error("Upload failed:", error.response?.data || error.message);
        }
    }

    function getChefProfile() {
        return props.chef?.user_info?.gender === "male"
            ? "/images/male.png"
            : "/images/female.png";
    }

    function prevCertificate() {
        const totalItems = props.chef.certificates.length + 1;
        currentIndex.value = (currentIndex.value - 1 + totalItems) % totalItems;
    }

    function nextCertificate() {
        const totalItems = props.chef.certificates.length + 1;
        currentIndex.value = (currentIndex.value + 1) % totalItems;
    }
    const emit = defineEmits(['navigate'])
</script>

<style scoped>
    .chef-main-container {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .page-label {
        width: 100%;
        height: 10%;
        display: flex;
        align-items: center;
    }

    .page-label h2 {
        margin-top: 35px;
        font-size: 35px;
        margin-left: 20px;
        font-family: 'Poppins-Bold';
    }

    .body-container {
        width: 100%;
        height: 88%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        box-sizing: border-box;
    }

    .chef-info-card {
        width: 70%;
        height: 90%;
        background-color: #E0E7FF;
        border-radius: 24px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: row;
        overflow: hidden;
        margin-bottom: 100px;
    }

    .chef-info-left {
        width: 30%;
        height: 100%;
        display: flex;
        flex-direction: column;
        padding: 20px;
        box-sizing: border-box;
    }

    .information-container {
        width: 100%;
        height: 50%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    .chef-image-container {
        width: 180px;
        height: 180px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        overflow: hidden;
        margin-bottom: 10px;
        background-color: #f0f0f0;
    }

    .chef-image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .information-container h2 {
        font-family: 'Poppins-Bold';
        margin: 5px 0;
        font-size: 1.5em;
        color: #222;
    }

    .information-container p {
        font-family: 'Poppins-Italic';
        margin: 0;
        font-size: 0.9em;
        color: #666;
    }

    .cert-label {
        font-family: 'Poppins-BoldItalic';
        margin-top: 20px;
        margin-bottom: 0;
        font-size: .8em;
        color: #222;
    }

    .certificate-container {
        width: 100%;
        height: 50%;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        padding: 10px;
        box-sizing: border-box;
    }

    .certificate-container button {
        width: 40px;
        height: 40px;
        border: none;
        background-color: #435F77;
        color: #fff;
        font-size: 1.2em;
        border-radius: 50%;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .certificate-container button:hover {
        background-color: #B5BFDE;
        color: #222;
        box-shadow: 4px 4px 12px #AFADAD;
        transform: scale(1.1);
    }

    .certificate-container button:disabled {
        background-color: #ccc;
        cursor: not-allowed;
    }

    .certificates {
        width: 70%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .add-button-Con {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .add-button-Con button {
        width: 80px;
        height: 80px;
        border: 2px dashed #435F77;
        background-color: #e8f0fe;
        border-radius: 12px;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .add-button-Con button:hover {
        background-color: #d1e2ff;
        transform: scale(1.05);
    }

    .add-button-Con button img {
        width: 40px;
        height: 40px;
        object-fit: contain;
    }

    .certificate-con {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .certificate-con img {
        max-width: 90%;
        max-height: 90%;
        object-fit: contain;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .certificate-con img:hover {
        transform: scale(1.05);
    }

    .chef-info-right {
        width: 70%;
        height: 100%;
        box-sizing: border-box;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
    }
    .chef-recipe-container {
        width: 100%;
        height: 90%;
        border-left: 4px solid #A2B0BC;
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        align-items: flex-start;
        justify-content: center;
        overflow-y: auto;
        padding: 10px;
        box-sizing: border-box;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .chef-recipe-container::-webkit-scrollbar {
        display: none;
    }

</style>
