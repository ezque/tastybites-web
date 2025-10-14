<template>
    <div class="add-recipe-container">
        <div class="top-container">
            <button type="button" @click="emit('navigate', 'Recipes')" :class="{ active: active === 'Recipes' }">
                <img src="/public/images/Button-icon/back.png" alt="back"/>
            </button>
            <h2>Create Recipe</h2>
        </div>
        <div class="main-body">
            <div class="left-side-container">
                <input
                    type="file"
                    ref="fileInput"
                    accept="image/*"
                    @change="handleImagePick"
                    hidden
                />
                <button type="button" @click="triggerImageInput">
                    <!-- Show preview image if available -->
                    <img
                        v-if="recipeImagePreview"
                        :src="recipeImagePreview"
                        alt="Preview"
                        class="preview-image"
                    />
                    <img
                        v-else
                        src="/public/images/Button-icon/add_recipe_image.png"
                        alt="Default"
                        class="default-image"
                    />
                </button>

                <input
                    class="recipe-name-input"
                    placeholder="Recipe Name"
                    v-model="recipeName"
                />
                <input
                    class="recipe-type-input"
                    placeholder="Type of Cuisine"
                    v-model="typeOfCuisine"
                />
            </div>
            <div class="right-side-container">
                <div class="right-button-container">
                    <button @click="setActiveComponent('descriptionCon')" :class="{ active: activeComponent === 'descriptionCon' }">
                        <img src="/public/images/Button-icon/description.png" alt="img"/>
                    </button>
                    <button @click="setActiveComponent('ingredientsCon')" :class="{ active: activeComponent === 'ingredientsCon' }">
                        <img src="/public/images/Button-icon/ingredients.png" alt="img"/>
                    </button>
                    <button @click="setActiveComponent('procedureCon')" :class="{ active: activeComponent === 'procedureCon' }">
                        <img src="/public/images/Button-icon/sidebar_recipe.png" alt="img"/>
                    </button>
                    <button @click="setActiveComponent('videoCon')" :class="{ active: activeComponent === 'videoCon' }">
                        <img src="/public/images/Button-icon/video.png" alt="img"/>
                    </button>
                    <button @click="setActiveComponent('paymentCon')" :class="{ active: activeComponent === 'paymentCon' }">
                        <img src="/public/images/Button-icon/price.png" alt="img"/>
                    </button>
                </div>
                <div class="right-main-body">
                    <div class="description-container" v-show="activeComponent === 'descriptionCon'">
                        <textarea
                            placeholder="Short description here..."
                            v-model="description"
                        >
                        </textarea>
                    </div>
                    <div class="ingredients-container" v-show="activeComponent === 'ingredientsCon'">
                        <div class="ing-button-con">
                            <button
                                @click="addIngredient"
                            >
                                <img src="/public/images/Button-icon/add.png" alt="icon"/>
                                Add new ingredient
                            </button>
                        </div>
                        <div class="ingredients-body">
                            <div
                                class="ingredients-group-input"
                                v-for="(ingredient, index) in ingredients"
                                :key="ingredient.id"
                            >
                                <h6>Ingredient {{ index + 1 }}</h6>
                                <input
                                    class="quantity-input"
                                    placeholder="Quantity"
                                    v-model="ingredient.quantity"
                                />
                                <input
                                    class="ingredients-input"
                                    placeholder="Ingredient"
                                    v-model="ingredient.name"
                                />
                                <button
                                    class="ingredients-delete"
                                    v-if="index !== 0"
                                    @click="removeIngredient(index)"
                                >
                                    <img src="/public/images/Button-icon/delete.png" />
                                </button>

                            </div>
                        </div>
                    </div>
                    <div class="procedure-container" v-show="activeComponent === 'procedureCon'">
                        <div class="pro-button-con">
                            <button
                                @click="addProcedure"
                            >
                                <img src="/public/images/Button-icon/add.png" alt="icon"/>
                                Add new Procedure
                            </button>
                        </div>
                        <div class="procedure-body">
                            <div
                                class="procedure-group-input"
                                v-for="(procedure, index) in procedures"
                                :key="procedure.id"
                            >
                                <input
                                    class="procedure-input"
                                    :placeholder="`Procedure ${index + 1}`"
                                    v-model="procedure.name"
                                />
                                <button
                                    class="procedure-delete"
                                    v-if="index !== 0"
                                    @click="removeProcedure(index)"
                                >
                                    <img src="/public/images/Button-icon/delete.png"/>
                                </button>

                            </div>
                        </div>

                    </div>
                    <div class="video-container" v-show="activeComponent === 'videoCon'">
                        <div class="video-body">
                            <label>Link</label>
                            <input
                                placeholder="Insert Video Link here"
                                v-model="videoLink"
                            />
                        </div>
                    </div>
                    <div class="payment-container" v-show="activeComponent === 'paymentCon'">
                        <div class="payment-body">
                            <div class="payment-left">
                                <div class="price-container">
                                    <label>Price</label>
                                    <input
                                        placeholder="Price"
                                        v-model="price"
                                    >
                                </div>
                                <div class="chef-qrcode-container">
                                    <input
                                        type="file"
                                        ref="qrFileInput"
                                        accept="image/*"
                                        @change="handleQRCodePick"
                                        hidden
                                    />
                                    <button type="button" @click="triggerQRCodeInput">
                                        Your QR Code
                                    </button>
                                    <p v-if="qrCodeFileName">{{ qrCodeFileName }}</p>
                                    <p v-else>Insert picture here</p>
                                </div>
                                <div class="number-container">
                                    <label>GCash</label>
                                    <input
                                        placeholder="Number"
                                        v-model="gcash_number"
                                    />
                                </div>
                                <div class="total-amount-container">
                                    <label>Recipe Fee</label>
                                    <p v-if="recipeFee !== '0.00'">₱{{ recipeFee }}</p>
                                    <p v-else>5% of the recipe price</p>
                                </div>
                            </div>
                            <div class="payment-right">
                                <label>Recipe Collection Fee</label>
                                <p>To help maintain and improve TastyBites: Gourmet Hunt, a one-time 5% collection fee is required before publishing any paid recipe.</p>
                                <div class="admin-qr-code-container">
<!--                                    <img src="/public/images/example-qr-code.jpg"/>-->
                                </div>
                                <p>Please send your payment to the QR code provided above and upload a screenshot as proof of payment. Your recipe will be published after verification.</p>
                                <div class="upload-button-container">
                                    <input
                                        type="file"
                                        ref="receiptFileInput"
                                        accept="image/*"
                                        @change="handleRecieptPick"
                                        hidden
                                    />
                                    <button type="button" @click="triggerReceiptInput">
                                        Upload
                                    </button>

                                    <p v-if="recieptFileName">{{recieptFileName}}</p>
                                    <p v-else>Proof of payment</p>
                                </div>
                                <button type="button" class="save-button" @click="submitRecipe">
                                    Save
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <Footer />
    </div>
</template>
<script setup>
    import { ref, computed } from "vue";
    import axios from "axios";
    import Footer from "@/Component/Footer.vue";

    const emit = defineEmits(['navigate']);

    const props = defineProps({
        active: {
            type: String,
            required: true
        }
    })

    // Form data
    const recipeName = ref('');
    const typeOfCuisine = ref('');
    const description = ref('');
    const videoLink = ref('');
    const price = ref('');
    const gcash_number = ref('');
    const recipeImage = ref('');
    const recipeImagePreview = ref(null);
    const qrImage = ref('');
    const recieptImage = ref('');

    // File input refs
    const fileInput = ref(null);
    const qrFileInput = ref(null);
    const receiptFileInput = ref(null);

    const qrCodeFileName = ref('');
    const recieptFileName = ref('');

    // Recipe image selection
    const triggerImageInput = () => {
        fileInput.value?.click();
    };
    const handleImagePick = (event) => {
        const file = event.target.files?.[0];
        if (file) {
            recipeImage.value = file;
            recipeImagePreview.value = URL.createObjectURL(file);
        }
    };

    // QR code image selection
    const triggerQRCodeInput = () => {
        qrFileInput.value?.click();
    };
    const handleQRCodePick = (event) => {
        const file = event.target.files?.[0];
        if (file) {
            qrImage.value = file;
            qrCodeFileName.value = file.name;
        }
    };



    // Receipt image selection
    const triggerReceiptInput = () => {
        receiptFileInput.value?.click();
    };
    const handleRecieptPick = (event) => {
        const file = event.target.files?.[0];
        if (file) {
            recieptImage.value = file;
            recieptFileName.value = file.name;
        }
    };

    const activeComponent = ref('descriptionCon');
    const setActiveComponent = (componentName) => {
        activeComponent.value = componentName;
    };

    const ingredients = ref([
        { id: Date.now(), quantity: '', name: '' }
    ]);
    const procedures = ref([
        { id: Date.now(), name: '' }
    ]);

    const addIngredient = () => {
        ingredients.value.push({ id: Date.now(), quantity: '', name: '' });
    };
    const removeIngredient = (index) => {
        ingredients.value.splice(index, 1);
    };

    const addProcedure = () => {
        procedures.value.push({ id: Date.now(), name: '' });
    };
    const removeProcedure = (index) => {
        procedures.value.splice(index, 1);
    };

    const recipeFee = computed(() => {
        const value = parseFloat(price.value);
        if (!isNaN(value)) {
            return (value * 0.05).toFixed(2);
        }
        return '0.00';
    })

    const submitRecipe = async () => {
        try {
            const formData = new FormData();

            formData.append('recipeName', recipeName.value);
            formData.append('cuisineType', typeOfCuisine.value);
            formData.append('description', description.value);
            formData.append('video_path', videoLink.value);
            formData.append('price', price.value);
            formData.append('gcash_number', gcash_number.value);
            formData.append('is_free', price.value === '' || price.value === '0' ? 1 : 0);
            formData.append('status', 1);

            if (recipeImage.value) {
                formData.append('image_path', recipeImage.value);
            }

            if (qrImage.value) {
                formData.append('gCash_path', qrImage.value);
            }

            if (recieptImage.value) {
                formData.append('receipt_path', recieptImage.value);
            }

            ingredients.value.forEach((ingredient, index) => {
                formData.append(`ingredients[${index}][ingredientName]`, ingredient.name);
                formData.append(`ingredients[${index}][quantity]`, ingredient.quantity);
            });

            procedures.value.forEach((procedure, index) => {
                formData.append(`procedures[${index}][instruction]`, procedure.name);
            });

            const response = await axios.post('/add-recipes', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            });



            alert('Recipe added successfully!');
        } catch (error) {
            console.error(error);
            if (error.response && error.response.data && error.response.data.errors) {
                console.error('Validation errors:', error.response.data.errors);
            }
            alert('Failed to submit recipe.');
        }
    };



</script>

<style scoped>
    .add-recipe-container {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    .top-container {
        width: 100%;
        height: 10%;
        display: flex;
        flex-direction: row;
        align-items: center;
        gap: 15px;
    }
    .top-container button {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 45px;
        height: 50%;
        background-color: transparent;
        cursor: pointer;
        border: none;
        margin-left: 20px;
    }
    .top-container button img {
        width: 80%;
        height: auto;
    }
    .top-container h2 {
        font-size: 2em;
        color: #768082;
        font-family: 'Poppins-Bold';
        font-style: italic;
    }
    .main-body {
        width: 100%;
        height: 83%;
        display: flex;
        flex-direction: row;
    }
    .left-side-container {
        width: 35%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .left-side-container button{
        width: 60%;
        height: 350px;
        border-radius: 50%;
        margin-top: 15%;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: transparent;
        box-shadow: 0 5px 3px #AFADAD;
    }
    .left-side-container button img {
        height: 100%;
        width: 100%;
        border-radius: 50%;
    }
    .recipe-name-input {
        width: 60%;
        height: 50px;
        margin-top: 20px;
        padding: 35px 20px;
        border: none;
        border-radius: 10px 10px 0 0;
        background-color: #E0E7FF;
        font-family: 'Poppins-Bold';
        font-size: 1.25em;
        outline: none;
    }
    .recipe-name-input::placeholder{
        font-family: 'Poppins-Bold';
        font-size: 1.25em;
    }
    .recipe-type-input {
        width: 60%;
        height: 35px;
        margin-top: 3px;
        padding: 25px 20px;
        border: none;
        border-radius: 0 0 10px 10px;
        background-color: #E0E7FF;
        font-family: 'Poppins-Italic';
        font-size: .9em;
        outline: none;
    }
    .right-side-container {
        width: 70%;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .right-button-container {
        width: 100%;
        height: 100px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: row;
        gap: 30px;
    }
    .right-button-container button{
        width: 80px;
        height: 70px;
        border-radius: 10px;
        cursor: pointer;
        border: none;
        background-color: #E0E7FF;
        align-items: center;
        justify-content: center;
        display: flex;
    }
    .right-button-container button img{
        width: 40%;
        height: auto;
    }
    .right-button-container button.active {
        background-color: #B5BFDE;
        color: white;
        box-shadow: 0 4px 5px #AFADAD;
        border: #435F77 dashed 1px;
    }
    .right-main-body {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: start;
        justify-content: center;
    }
    .description-container, .ingredients-container,  .procedure-container,.video-container, .payment-container{
        width: 80%;
        height: 500px;
        border-radius: 20px;
        background-color: #F5F5F5;
        box-shadow: 3px 5px 3px #AFADAD;
        border: none;
        padding: 20px;
        display: flex;
        margin-top: 15px;
    }
    .description-container {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .description-container textarea {
        width: 100%;
        height: 90%;
        border: none;
        resize: none;
        padding: 20px;
        background-color: #F5F5F5;
        font-size: 1.4em;
        line-height: 1.5;
        font-family: 'Poppins-Bold';
        font-style: italic;
    }
    .description-container textarea:focus {
        outline: none;
        box-shadow: none;
    }
    .description-container textarea::placeholder{
        color: #AFADAD;
        font-size: 1.1em;
        font-family: inherit;
        font-family: 'Poppins-Bold';
        font-style: italic;
        line-height: 1.1;
        vertical-align: top;
    }
    .ingredients-container, .procedure-container {
        display: flex;
        flex-direction: column;
    }
    .ing-button-con,.pro-button-con {
        width: 100%;
        height: 10%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .ing-button-con button,.pro-button-con button {
        width: 40%;
        height: 80%;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
        background-color: #435F77;
        color: white;
        gap: 10px;
        font-family: 'Poppins-Bold';
        font-size: .8em;
        border-radius: 20px;
        border: none;
        cursor: pointer;
    }
    .ing-button-con button img, .pro-button-con button img {
        width: 15px;
        height: auto;
        filter: brightness(0) saturate(0%) invert(1);
    }
    .ingredients-body {
        width: 100%;
        height: 90%;
        display: flex;
        flex-direction: column;
        align-items: center;
        overflow: auto;
        gap: 15px;
    }
    .procedure-body {
        width: 100%;
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        overflow: auto;
        gap: 15px;
    }
    .ingredients-group-input, .procedure-group-input {
        width: 90%;
        height: 40px;
        display: flex;
        flex-direction: row;
        align-items: center;
        padding: 10px;
    }
    .procedure-delete,.ingredients-delete {
        width: 30px;
        height: 25px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
        background-color: transparent;
        margin-left: 5px;
    }
    .procedure-delete img,.ingredients-delete img{
        width: auto;
        height: 90%;
    }
    .ingredients-group-input h6{
        width: 17%;
        font-family: 'Poppins-Italic';
        color: #435F77;
        font-size: 0.8em;
    }
    .quantity-input {
        width: 40%;
        height: 40px;
        box-shadow: 2px 3px 2px #AFADAD;
        border: none;
        border-radius: 10px 0 0 10px;
        padding-left: 20px;
        font-family: 'Poppins-Regular';
        font-size: .8em;
        outline: none;
    }
    .ingredients-input {
        width: 100%;
        height: 40px;
        box-shadow: 2px 3px 2px #AFADAD;
        border: none;
        border-radius: 0 10px 10px 0;
        padding-left: 20px;
        font-family: 'Poppins-Regular';
        font-size: .8em;
        outline: none;
    }
    .procedure-input {
        width: 100%;
        height: 40px;
        box-shadow: 2px 3px 2px #AFADAD;
        border: none;
        border-radius: 10px;
        margin-left: 2px;
        padding-left: 10px;
        outline: none;
    }
    .video-container {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .video-body {
        width: 50%;
        height: 50px;
        background-color: #B5BFDE;
        padding-left: 20px;
        padding-right: 20px;
        padding-top: 10px;
        padding-bottom: 10px;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
        gap: 20px;
        border-radius: 4em;
    }
    .video-body label {
        width: 15%;
        height: 70%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #435F77;
        color: white;
        font-weight: bold;
        font-family: 'Poppins', sans-serif;
        border-radius: 20px;
        padding-left: 20px;
        padding-right: 20px;
    }
    .video-body input {
        width: 100%;
        height: 100%;
        border: none;
        background-color: transparent;
        outline: none;
        box-shadow: none;
    }
    .payment-body {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: row;
        align-items: center;
    }
    .payment-left {
        width: 50%;
        height: 90%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 10px;
        border-right: 4px solid white;
    }
    .price-container {
        width: 90%;
        height: 40px;
        display: flex;
        padding: 10px;
        align-items: center;
        flex-direction: row;
        gap: 10px;
    }
    .price-container label {
        background-color: #435F77;
        width: 20%;
        height: 80%;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 20px;
        color: white;
        font-family: 'Poppins', sans-serif;
        font-size: 1em;
        font-weight: bold;
    }
    .price-container input {
        width: 80%;
        height: 100%;
        border: none;
        background-color: #B5BFDE;
        border-radius: 10px;
        padding-left: 10px;
        color: black;
        outline: none;
    }
    .chef-qrcode-container {
        background-color: #B5BFDE;
        width: 85%;
        height: 30px;
        display: flex;
        align-items: center;
        padding: 10px;
        border-radius: 30px;
        gap: 10px;
    }
    .chef-qrcode-container button {
        background-color: #435F77;
        width: 30%;
        height: 100%;
        border-radius: 20px;
        color: white;
        border: none;
        cursor: pointer;
        font-weight: bold;
        font-family: 'Poppins', sans-serif;
        font-size: .7em;
    }
    .chef-qrcode-container p {
        color: #768082;
    }
    .number-container {
        width: 90%;
        height: 40px;
        padding: 10px;
        display: flex;
        flex-direction: row;
        gap: 10px;
    }
    .number-container label {
        width: 20%;
        height: 100%;
        background-color: #435F77;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-family: 'Poppins', sans-serif;
        font-size: .8em;
        border-radius: 20px;
        font-weight: bold;
    }
    .number-container input {
        width: 80%;
        background-color: #B5BFDE;
        height: 100%;
        border-radius: 10px;
        border: none;
        outline: none;
        padding-left: 10px;
    }
    .total-amount-container {
        width: 85%;
        height: 30px;
        padding: 10px;
        display: flex;
        flex-direction: row;
        align-items: center;
        gap: 10px;
        justify-content: space-between;
        background-color: #B5BFDE;
        border-radius: 20px;
        border: none;
    }
    .total-amount-container label {
        width: 30%;
        height: 100%;
        background-color: #435F77;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 20px;
        color: white;
        font-weight: bold;
        font-family: 'Poppins', sans-serif;
        font-size: .8em;
    }
    .total-amount-container p {
        color: #768082;
    }
    .payment-right {
        width: 50%;
        height: 90%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: start;
        border-left: 4px solid white;
    }
    .payment-right label {
        font-size: 1.5em;
        color: #768082;
        font-family: 'Poppins', sans-serif;
        font-weight: bold;
        margin-left: 10px;
        align-self: start;
    }
    .payment-right p{
        margin-left: 20px;
        color: #768082;
        font-size: .9em;
    }
    .admin-qr-code-container {
        width: 40%;
        height: 40%;
        background-image: url("/images/example-qr-code.jpg");
        background-position: center;
        background-size: cover;
        background-repeat: no-repeat;
    }

    .upload-button-container {
        width: 80%;
        height: 6%;
        display: flex;
        flex-direction: row;
        align-items: center;
        background-color: #B5BFDE;
        padding: 10px;
        border-radius: 20px;
    }
    .upload-button-container button {
        background-color: #435F77;
        width: 20%;
        height: 100%;
        border-radius: 20px;
        border: none;
        color: white;
        cursor: pointer;
    }
    .upload-button-container p{
        color: #768082;
    }
    .save-button {
        width: 85%;
        height: 40px;
        margin-top: 25px;
        border-radius: 20px;
        border: none;
        background-color: #435F77;
        color: white;
        cursor: pointer;
    }

</style>
