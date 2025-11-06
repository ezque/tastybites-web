<template>
    <div class="w-full h-full flex flex-col overflow-hidden">
        <div class="w-full h-[10%] flex flex-row items-center gap-[15px]">
            <button type="button" @click="$emit('back')" :class="{ 'bg-[#B5BFDE] text-white shadow-[0_4px_5px_#AFADAD] border border-dashed border-[#435F77]': active === 'Recipes' }" class="flex items-center justify-center w-[45px] h-[50%] bg-transparent cursor-pointer border-none ml-5">
                <img src="/public/images/Button-icon/back.png" alt="back" class="w-[80%] h-auto"/>
            </button>
            <h2 class="text-4xl text-[#768082] font-['Poppins-Bold'] italic">Create Recipe</h2>
        </div>
        <div class="w-full h-[83%] flex flex-row">
            <div class="w-[35%] h-full flex flex-col items-center">
                <input
                    type="file"
                    ref="fileInput"
                    accept="image/*"
                    @change="handleImagePick"
                    hidden
                />
                <button type="button" @click="triggerImageInput" class="w-[60%] h-[350px] rounded-full mt-[15%] border-none cursor-pointer flex items-center justify-center bg-transparent shadow-[0_5px_3px_#AFADAD]">
                    <img
                        v-if="recipeImagePreview"
                        :src="recipeImagePreview"
                        alt="Preview"
                        class="h-full w-full rounded-full preview-image"
                    />
                    <img
                        v-else
                        src="/public/images/Button-icon/add_recipe_image.png"
                        alt="Default"
                        class="h-full w-full rounded-full default-image"
                    />
                </button>

                <input
                    class="w-[60%] h-[50px] mt-5 px-5 pt-[35px] pb-[35px] border-none rounded-t-lg bg-[#E0E7FF] font-['Poppins-Bold'] text-xl outline-none placeholder:font-['Poppins-Bold'] placeholder:text-xl"
                    placeholder="Recipe Name"
                    v-model="recipeName"
                />
                <input
                    class="w-[60%] h-[35px] mt-[3px] px-5 pt-[25px] pb-[25px] border-none rounded-b-lg bg-[#E0E7FF] font-['Poppins-Italic'] text-sm outline-none"
                    placeholder="Type of Cuisine"
                    v-model="typeOfCuisine"
                />
            </div>
            <div class="w-[70%] h-full flex flex-col">
                <div class="w-full h-[100px] flex items-center justify-center flex-row gap-[30px]">
                    <button @click="setActiveComponent('descriptionCon')" :class="{ 'bg-[#B5BFDE] text-white shadow-[0_4px_5px_#AFADAD] border border-dashed border-[#435F77]': activeComponent === 'descriptionCon' }" class="w-20 h-[70px] rounded-lg cursor-pointer border-none bg-[#E0E7FF] flex items-center justify-center">
                        <img src="/public/images/Button-icon/description.png" alt="img" class="w-[40%] h-auto"/>
                    </button>
                    <button @click="setActiveComponent('ingredientsCon')" :class="{ 'bg-[#B5BFDE] text-white shadow-[0_4px_5px_#AFADAD] border border-dashed border-[#435F77]': activeComponent === 'ingredientsCon' }" class="w-20 h-[70px] rounded-lg cursor-pointer border-none bg-[#E0E7FF] flex items-center justify-center">
                        <img src="/public/images/Button-icon/ingredients.png" alt="img" class="w-[40%] h-auto"/>
                    </button>
                    <button @click="setActiveComponent('procedureCon')" :class="{ 'bg-[#B5BFDE] text-white shadow-[0_4px_5px_#AFADAD] border border-dashed border-[#435F77]': activeComponent === 'procedureCon' }" class="w-20 h-[70px] rounded-lg cursor-pointer border-none bg-[#E0E7FF] flex items-center justify-center">
                        <img src="/public/images/Button-icon/sidebar_recipe.png" alt="img" class="w-[40%] h-auto"/>
                    </button>
                    <button @click="setActiveComponent('videoCon')" :class="{ 'bg-[#B5BFDE] text-white shadow-[0_4px_5px_#AFADAD] border border-dashed border-[#435F77]': activeComponent === 'videoCon' }" class="w-20 h-[70px] rounded-lg cursor-pointer border-none bg-[#E0E7FF] flex items-center justify-center">
                        <img src="/public/images/Button-icon/video.png" alt="img" class="w-[40%] h-auto"/>
                    </button>
                    <button @click="setActiveComponent('paymentCon')" :class="{ 'bg-[#B5BFDE] text-white shadow-[0_4px_5px_#AFADAD] border border-dashed border-[#435F77]': activeComponent === 'paymentCon' }" class="w-20 h-[70px] rounded-lg cursor-pointer border-none bg-[#E0E7FF] flex items-center justify-center">
                        <img src="/public/images/Button-icon/price.png" alt="img" class="w-[40%] h-auto"/>
                    </button>
                </div>
                <div class="w-full h-full flex items-start justify-center">
                    <div class="w-[80%] h-[500px] rounded-[20px] bg-[#F5F5F5] shadow-[3px_5px_3px_#AFADAD] border-none p-5 flex mt-[15px]" v-show="activeComponent === 'descriptionCon'">
                        <textarea
                            placeholder="Short description here..."
                            v-model="description"
                            class="w-full h-[90%] border-none resize-none p-5 bg-[#F5F5F5] text-[1.4em] leading-[1.5] font-['Poppins-Bold'] italic focus:outline-none focus:shadow-none placeholder:text-[#AFADAD] placeholder:text-[1.1em] placeholder:font-['Poppins-Bold'] placeholder:italic placeholder:leading-[1.1] placeholder:align-top"
                        ></textarea>
                    </div>
                    <div class="w-[80%] h-[500px] rounded-[20px] bg-[#F5F5F5] shadow-[3px_5px_3px_#AFADAD] border-none p-5 flex flex-col mt-[15px]" v-show="activeComponent === 'ingredientsCon'">
                        <div class="w-full h-[10%] flex items-center justify-center">
                            <button
                                @click="addIngredient"
                                class="w-[40%] h-[80%] flex flex-row items-center justify-center bg-[#435F77] text-white gap-[10px] font-['Poppins-Bold'] text-[0.8em] rounded-[20px] border-none cursor-pointer"
                            >
                                <img src="/public/images/Button-icon/add.png" alt="icon" class="w-[15px] h-auto filter brightness-0 saturate-0 invert"/>
                                Add new ingredient
                            </button>
                        </div>
                        <div class="w-full h-[90%] flex flex-col items-center overflow-auto gap-[15px]">
                            <div
                                class="w-[90%] h-10 flex flex-row items-center p-[10px]"
                                v-for="(ingredient, index) in ingredients"
                                :key="ingredient.id"
                            >
                                <h6 class="w-[17%] font-['Poppins-Italic'] text-[#435F77] text-[0.8em]">Ingredient {{ index + 1 }}</h6>
                                <input
                                    class="w-[40%] h-10 shadow-[2px_3px_2px_#AFADAD] border-none rounded-l-lg pl-5 font-['Poppins-Regular'] text-[0.8em] outline-none quantity-input"
                                    placeholder="Quantity"
                                    v-model="ingredient.quantity"
                                />
                                <input
                                    class="w-full h-10 shadow-[2px_3px_2px_#AFADAD] border-none rounded-r-lg pl-5 font-['Poppins-Regular'] text-[0.8em] outline-none ingredients-input"
                                    placeholder="Ingredient"
                                    v-model="ingredient.name"
                                />
                                <button
                                    class="w-[30px] h-[25px] flex items-center justify-center border-none cursor-pointer bg-transparent ml-[5px] ingredients-delete"
                                    v-if="index !== 0"
                                    @click="removeIngredient(index)"
                                >
                                    <img src="/public/images/Button-icon/delete.png" class="w-auto h-[90%]"/>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="w-[80%] h-[500px] rounded-[20px] bg-[#F5F5F5] shadow-[3px_5px_3px_#AFADAD] border-none p-5 flex flex-col mt-[15px]" v-show="activeComponent === 'procedureCon'">
                        <div class="w-full h-[10%] flex items-center justify-center">
                            <button
                                @click="addProcedure"
                                class="w-[40%] h-[80%] flex flex-row items-center justify-center bg-[#435F77] text-white gap-[10px] font-['Poppins-Bold'] text-[0.8em] rounded-[20px] border-none cursor-pointer"
                            >
                                <img src="/public/images/Button-icon/add.png" alt="icon" class="w-[15px] h-auto filter brightness-0 saturate-0 invert"/>
                                Add new Procedure
                            </button>
                        </div>
                        <div class="w-full flex-1 flex flex-col items-center overflow-auto gap-[15px]">
                            <div
                                class="w-[90%] h-10 flex flex-row items-center p-[10px]"
                                v-for="(procedure, index) in procedures"
                                :key="procedure.id"
                            >
                                <input
                                    class="w-full h-10 shadow-[2px_3px_2px_#AFADAD] border-none rounded-lg ml-[2px] pl-[10px] font-['Poppins-Regular'] text-[0.8em] outline-none procedure-input"
                                    :placeholder="`Procedure ${index + 1}`"
                                    v-model="procedure.name"
                                />
                                <button
                                    class="w-[30px] h-[25px] flex items-center justify-center border-none cursor-pointer bg-transparent ml-[5px] procedure-delete"
                                    v-if="index !== 0"
                                    @click="removeProcedure(index)"
                                >
                                    <img src="/public/images/Button-icon/delete.png" class="w-auto h-[90%]"/>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="w-[80%] h-[500px] rounded-[20px] bg-[#F5F5F5] shadow-[3px_5px_3px_#AFADAD] border-none p-5 flex items-center justify-center font-['Poppins-Italic'] text-[0.8em] mt-[15px]" v-show="activeComponent === 'videoCon'">
                        <div class="w-1/2 h-[50px] bg-[#B5BFDE] p-[5px_10px] flex flex-row items-center justify-center gap-5 rounded-full">
                            <label class="w-[15%] flex items-center justify-center bg-[#435F77] text-white font-bold font-['Poppins-Bold'] rounded-[20px] py-[5px] px-[45px] text-[13px]">Link</label>
                            <input
                                placeholder="Insert Video Link here"
                                v-model="videoLink"
                                class="w-full h-full border-none bg-transparent outline-none shadow-none"
                            />
                        </div>
                    </div>
                    <div class="w-[80%] h-[500px] rounded-[20px] bg-[#F5F5F5] shadow-[3px_5px_3px_#AFADAD] border-none p-5 flex mt-[15px]" v-show="activeComponent === 'paymentCon'">
                        <div class="w-full h-full flex flex-row items-center">
                            <div class="w-1/2 h-[90%] flex flex-col items-center justify-center gap-5 border-r-2 border-white">
                                <div class="w-[90%] h-10 flex p-[10px] items-center flex-row gap-[10px]">
                                    <label class="bg-[#435F77] w-[20%] h-[80%] flex items-center justify-center rounded-[20px] text-white font-['Poppins-Bold'] text-[0.8em] py-0 px-[35px]">Price</label>
                                    <input
                                        placeholder="₱"
                                        v-model="price"
                                        class="w-[80%] h-full border-none bg-[#B5BFDE] rounded-lg text-black font-['Poppins-Regular'] outline-none p-5"
                                    >
                                </div>
                                <div class="bg-[#B5BFDE] w-[85%] h-[45px] flex items-center p-[10px] rounded-[30px] gap-[10px]">
                                    <input
                                        type="file"
                                        ref="qrFileInput"
                                        accept="image/*"
                                        @change="handleQRCodePick"
                                        hidden
                                    />
                                    <button type="button" @click="triggerQRCodeInput" class="bg-[#435F77] w-[30%] h-[35px] rounded-[20px] text-white border-none cursor-pointer font-bold font-['Poppins-Bold'] text-[0.7em]">Your QR Code</button>
                                    <p class="text-[#768082] text-[0.8em] font-['Poppins-Regular']" v-if="qrCodeFileName">{{ qrCodeFileName }}</p>
                                    <p class="text-[#768082] text-[0.8em] font-['Poppins-Regular']" v-else>Insert picture here</p>
                                </div>
                                <div class="w-[90%] h-10 p-[10px] flex flex-row gap-[10px]">
                                    <label class="bg-[#435F77] w-[20%] h-[80%] flex items-center justify-center rounded-[20px] text-white font-['Poppins-Bold'] text-[0.8em] py-0 px-[35px] -mt-[10px]">GCash</label>
                                    <input
                                        placeholder="+63"
                                        v-model="gcash_number"
                                        class="w-[80%] h-full border-none bg-[#B5BFDE] rounded-lg text-black font-['Poppins-Regular'] outline-none p-5 text-[0.8em] -mt-[10px]"
                                    />
                                </div>
                                <div class="bg-[#B5BFDE] w-[85%] h-[45px] flex items-center p-[10px] rounded-[30px] gap-[10px]">
                                    <label class="w-[27%] h-[35px] bg-[#435F77] flex items-center justify-center rounded-[20px] text-white font-['Poppins-Bold'] text-[0.7em]">Recipe Fee</label>
                                    <p class="text-[#768082] text-[0.8em] font-['Poppins-Regular'] text-right w-[70%] pr-[10px]" v-if="recipeFee !== '0.00'">₱{{ recipeFee }}</p>
                                    <p class="text-[#768082] text-[0.8em] font-['Poppins-Regular'] text-right w-[70%] pr-[10px]" v-else>5% of the recipe price</p>
                                </div>
                            </div>
                            <div class="w-1/2 h-[90%] flex flex-col items-center justify-start border-l-4 border-white gap-[10px]">
                                <label class="text-[1.6em] text-[#768082] font-['Poppins-Bold'] ml-[10px] self-start">Recipe Collection Fee</label>
                                <p class="ml-5 text-[#768082] text-[0.8em] font-['Poppins-Italic'] text-justify">To help maintain and improve TastyBites: Gourmet Hunt, a one-time 5% collection fee is required before publishing any paid recipe.</p>
                                <div class="w-1/2 h-1/2 bg-[url('/images/example-qr-code.jpg')] bg-center bg-contain bg-no-repeat"></div>
                                <p class="ml-5 text-[#768082] text-[0.8em] font-['Poppins-Italic'] text-justify">Please send your payment to the QR code provided above and upload a screenshot as proof of payment. Your recipe will be published after verification.</p>
                                <div class="bg-[#B5BFDE] w-[85%] h-[45px] flex items-center p-[10px] rounded-[30px]">
                                    <input
                                        type="file"
                                        ref="receiptFileInput"
                                        accept="image/*"
                                        @change="handleRecieptPick"
                                        hidden
                                    />
                                    <button type="button" @click="triggerReceiptInput" class="w-[27%] h-[35px] bg-[#435F77] flex items-center justify-center rounded-[20px] text-white font-['Poppins-Bold'] text-[0.7em]">Upload</button>
                                    <p class="text-[#768082] text-[0.8em] font-['Poppins-Italic'] w-[75%]" v-if="recieptFileName">{{recieptFileName}}</p>
                                    <p class="text-[#768082] text-[0.8em] font-['Poppins-Italic'] w-[75%]" v-else>Proof of payment</p>
                                </div>
                                <button
                                    type="button"
                                    class="w-[85%] h-[60px] mt-[10px] rounded-[20px] border-none bg-[#435F77] text-white cursor-pointer font-['Poppins-Bold'] text-[0.9em] shadow-[3px_3px_3px_#AFADAD] hover:scale-105 save-button"
                                    @click="handleSave"
                                >
                                    {{ props.isEditMode ? 'Update' : 'Save' }}
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
    import { ref, computed, onMounted, watch } from "vue";
    import axios from "axios";
    import Footer from "@/Component/Footer.vue";


    const props = defineProps({
        active: {
            type: String,
            required: true
        },
        recipeData: {
            type: Object,
            default: null
        },
        isEditMode: {
            type: Boolean,
            default: false
        }
    });
    const emit = defineEmits(['navigate', 'back']);

    /* ---------- Form data ---------- */
    const recipeName        = ref('');
    const typeOfCuisine     = ref('');
    const description       = ref('');
    const videoLink         = ref('');
    const price             = ref('');
    const gcash_number      = ref('');
    const recipeImage       = ref('');
    const recipeImagePreview= ref(null);
    const qrImage           = ref('');
    const recieptImage      = ref('');

    /* ---------- File refs ---------- */
    const fileInput        = ref(null);
    const qrFileInput      = ref(null);
    const receiptFileInput = ref(null);

    const qrCodeFileName   = ref('');
    const recieptFileName  = ref('');

    /* ---------- Image handlers ---------- */
    const triggerImageInput = () => fileInput.value?.click();
    const handleImagePick = (e) => {
        const file = e.target.files?.[0];
        if (file) {
            recipeImage.value = file;
            recipeImagePreview.value = URL.createObjectURL(file);
        }
    };

    const triggerQRCodeInput = () => qrFileInput.value?.click();
    const handleQRCodePick = (e) => {
        const file = e.target.files?.[0];
        if (file) {
            qrImage.value = file;
            qrCodeFileName.value = file.name;
        }
    };

    const triggerReceiptInput = () => receiptFileInput.value?.click();
    const handleRecieptPick = (e) => {
        const file = e.target.files?.[0];
        if (file) {
            recieptImage.value = file;
            recieptFileName.value = file.name;
        }
    };

    /* ---------- Tab handling ---------- */
    const activeComponent = ref('descriptionCon');
    const setActiveComponent = (name) => activeComponent.value = name;

    /* ---------- Ingredients & Procedures ---------- */
    const ingredients = ref([{ id: Date.now(), quantity: '', name: '' }]);
    const procedures  = ref([{ id: Date.now(), name: '' }]);

    const addIngredient = () => ingredients.value.push({ id: Date.now(), quantity: '', name: '' });
    const removeIngredient = (i) => ingredients.value.splice(i, 1);

    const addProcedure = () => procedures.value.push({ id: Date.now(), name: '' });
    const removeProcedure = (i) => procedures.value.splice(i, 1);

    /* ---------- Computed ---------- */
    const recipeFee = computed(() => {
        const v = parseFloat(price.value);
        return (!isNaN(v)) ? (v * 0.05).toFixed(2) : '0.00';
    });


    const validateForm = () => {
        const errors = [];

        // Required always
        if (!recipeName.value.trim())        errors.push('• Recipe Name is required.');
        if (!typeOfCuisine.value.trim())     errors.push('• Type of Cuisine is required.');
        if (!description.value.trim())       errors.push('• Description is required.');
        if (!recipeImagePreview.value)       errors.push('• Recipe Image is required.');

        // At least one ingredient **name** (quantity is optional)
        const hasIngredientName = ingredients.value.some(i => i.name.trim());
        if (!hasIngredientName)              errors.push('• At least one Ingredient name is required.');

        // At least one procedure step
        const hasProcedure = procedures.value.some(p => p.name.trim());
        if (!hasProcedure)                   errors.push('• At least one Procedure step is required.');

        // Conditional – only when price is filled
        const priceVal = parseFloat(price.value);
        if (!isNaN(priceVal) && priceVal > 0) {
            if (!gcash_number.value.trim())  errors.push('• GCash Number is required for paid recipes.');
            if (!qrCodeFileName.value)       errors.push('• Your QR Code is required for paid recipes.');  // ← Check filename (existing or new)
            if (!recieptFileName.value)      errors.push('• Proof of Payment (receipt) is required for paid recipes.');  // ← Check filename
        }

        if (errors.length) {
            alert('Please fix the following errors:\n\n' + errors.join('\n'));
            return false;
        }
        return true;
    };

    /* ---------- Submit ---------- */
    const submitRecipe = async () => {
        if (!validateForm()) return;

        try {
            const formData = new FormData();

            formData.append('recipeName',      recipeName.value);
            formData.append('cuisineType',     typeOfCuisine.value);
            formData.append('description',     description.value);
            formData.append('video_path',      videoLink.value);
            formData.append('price',           price.value);
            formData.append('gcash_number',    gcash_number.value);
            formData.append('is_free',         price.value === '' || price.value === '0' ? 1 : 0);
            formData.append('status',          1);

            if (recipeImage.value)   formData.append('image_path',   recipeImage.value);
            if (qrImage.value)       formData.append('gCash_path',   qrImage.value);
            if (recieptImage.value)  formData.append('receipt_path', recieptImage.value);

            ingredients.value.forEach((ing, i) => {
                formData.append(`ingredients[${i}][ingredientName]`, ing.name);
                formData.append(`ingredients[${i}][quantity]`,       ing.quantity); // quantity may be empty
            });

            procedures.value.forEach((proc, i) => {
                formData.append(`procedures[${i}][instruction]`, proc.name);
            });

            await axios.post('/add-recipes', formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            });

            alert('Recipe added successfully!');
            // optional: reset form / navigate
        } catch (err) {
            console.error(err);
            if (err.response?.data?.errors) {
                const msgs = Object.values(err.response.data.errors).flat();
                alert('Server validation failed:\n' + msgs.join('\n'));
            } else {
                alert('Failed to submit recipe. Please try again.');
            }
        }
    };

    const updateRecipe = async () => {
        if (!props.recipeData) return;

        try {
            const formData = new FormData();
            formData.append('_method', 'PATCH');

            // Only append fields that were edited or exist in the form
            if (recipeName.value !== props.recipeData.recipeName)
                formData.append('recipeName', recipeName.value);

            if (typeOfCuisine.value !== props.recipeData.cuisineType)
                formData.append('cuisineType', typeOfCuisine.value);

            if (description.value !== props.recipeData.description)
                formData.append('description', description.value);

            if (videoLink.value !== props.recipeData.video_path)
                formData.append('video_path', videoLink.value);

            if (price.value !== props.recipeData.price)
                formData.append('price', price.value);

            if (gcash_number.value !== props.recipeData.gcash_number)
                formData.append('gcash_number', gcash_number.value);

            // Optional image uploads — only if user selected new files
            if (recipeImage.value) formData.append('image_path', recipeImage.value);
            if (qrImage.value) formData.append('gCash_path', qrImage.value);
            if (recieptImage.value) formData.append('receipt_path', recieptImage.value);

            // ✅ Ingredients (compare only if changed)
            const newIngredients = ingredients.value.map(i => ({
                ingredientName: i.name,
                quantity: i.quantity
            }));
            formData.append('ingredients', JSON.stringify(newIngredients));

            // ✅ Procedures
            const newProcedures = procedures.value.map(p => ({
                instruction: p.name
            }));
            formData.append('procedures', JSON.stringify(newProcedures));

            await axios.post(`/update-recipe/${props.recipeData.id}`, formData, {
                headers: { 'Content-Type': 'multipart/form-data' },
            });

            alert('✅ Recipe updated successfully!');
        } catch (err) {
            console.error(err);
            alert('❌ Failed to update recipe.');
        }
    };


    const handleSave = () => {
        if (props.isEditMode) updateRecipe();
        else submitRecipe();
    };


    onMounted(() => loadRecipeData());
    watch(
        () => props.recipeData,
        (newVal) => {
            if (newVal && Object.keys(newVal).length > 0) {
                loadRecipeData();
            }
        },
        { deep: true, immediate: true } // 👈 'immediate' ensures it runs once even on mount
    );


    function loadRecipeData() {
        if (!props.recipeData) return;

        recipeName.value   = props.recipeData.recipeName || '';
        typeOfCuisine.value = props.recipeData.cuisineType || '';
        description.value   = props.recipeData.description || '';
        videoLink.value     = props.recipeData.video_path || '';
        price.value         = props.recipeData.price || '';
        gcash_number.value  = props.recipeData.gcash_number || '';

        // ✅ Recipe image
        recipeImagePreview.value = props.recipeData.image_path
            ? `/storage/${props.recipeData.image_path}`
            : null;

        // ✅ QR Code preview
        qrCodeFileName.value = props.recipeData.gCash_path
            ? props.recipeData.gCash_path.split('/').pop()
            : '';

        // ✅ Receipt preview
        recieptFileName.value = props.recipeData.receipt_path
            ? props.recipeData.receipt_path.split('/').pop()
            : '';

        // ✅ Ingredients list
        const ing = props.recipeData.ingredients || props.recipeData.ingredient;
        if (ing && ing.length > 0) {
            ingredients.value = ing.map(i => ({
                id: i.id || Date.now() + Math.random(),
                quantity: i.quantity || '',
                name: i.ingredientName || i.name || ''
            }));
        } else {
            ingredients.value = [{ id: Date.now(), quantity: '', name: '' }];
        }

        // ✅ Procedures list
        const proc = props.recipeData.procedures || props.recipeData.procedure;
        if (proc && proc.length > 0) {
            procedures.value = proc.map(p => ({
                id: p.id || Date.now() + Math.random(),
                name: p.instruction || p.name || ''
            }));
        } else {
            procedures.value = [{ id: Date.now(), name: '' }];
        }
    }

</script>
