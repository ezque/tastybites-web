<template>
    <div class="admin-main-body">
        <div class="page-label">
            <h2>Settings</h2>
        </div>

        <div class="admin-body">
            <!--navigation Buttons-->
            <div class="settings-button-container">
                <button
                    :class="{ active: activeButton === 'personal' }"
                    @click="activeButton = 'personal'"
                >
                    <img v-if="activeButton === 'personal'" alt="button" src="/public/images/Button-icon/profile_open.png" />
                    <img v-else  alt="button" src="/public/images/Button-icon/profile_notopen.png" />
                    <span class="button-text">Personal Details</span>
                </button>

                <button
                    :class="{ active: activeButton === 'password' }"
                    @click="activeButton = 'password'"
                >
                    <img v-if="activeButton === 'password'" alt="button" src="/public/images/Button-icon/password_open.png" />
                    <img v-else  alt="button" src="/public/images/Button-icon/password_notopen.png" />
                    <span class="button-text">Password & Security</span>
                </button>
                <button
                    :class="{ active: activeButton === 'hiddenRecipe' }"
                    @click="activeButton = 'hiddenRecipe'"
                >
                    <img v-if="activeButton === 'password'" alt="button" src="/public/images/Button-icon/password_open.png" />
                    <img v-else  alt="button" src="/public/images/Button-icon/password_notopen.png" />
                    <span class="button-text">Hidden Recipe</span>
                </button>
                <button
                    :class="{ active: activeButton === 'savedRecipe' }"
                    @click="activeButton = 'savedRecipe'"
                >
                    <img v-if="activeButton === 'password'" alt="button" src="/public/images/Button-icon/password_open.png" />
                    <img v-else  alt="button" src="/public/images/Button-icon/password_notopen.png" />
                    <span class="button-text">Saved Recipe</span>
                </button>
            </div>

            <div class="settings-card-container">
                <div v-if="activeButton === 'personal'" class="card-1">
                    <div class="card-label">
                        <h2>Personal Details</h2>
                    </div>
                    <div class="card-body-personal">
                        <div class="profile-picture-container">
                            <input type="file" id="profileInput" @change="uploadProfile" hidden />
                            <button type="button" @click="triggerFileInput">
                                <img
                                    v-if="user.user_info.profilePath"
                                    :src="`/storage/${user.user_info.profilePath}`"
                                    alt="Profile"
                                />
                                <img
                                    v-else-if="user.user_info.gender === 'male'"
                                    src="/public/images/male.png"
                                    alt="Male Default"
                                />
                                <img
                                    v-else
                                    src="/public/images/female.png"
                                    alt="Female Default"
                                />
                            </button>
                        </div>

                        <div class="personal-information-container">
                            <div class="info-container">
                                <template v-if="editMode === 'fullName'">
                                    <input v-model="editableFullName" placeholder="Full Name" />
                                    <div class="personal-buttons">
                                        <button @click="cancelEdit">
                                            <img alt="icon" src="/public/images/Button-icon/x-white-icon.png">
                                        </button>
                                        <button @click="saveEdit('fullName')">
                                            <img alt="icon" src="/public/images/Button-icon/save-white-button.png">
                                        </button>
                                    </div>
                                </template>
                                <template v-else>
                                    <h2>{{ user.user_info.fullName }}</h2>
                                    <button @click="editMode = 'fullName'">
                                        <img alt="icon" src="/public/images/Button-icon/edit%20icon.png" />
                                    </button>
                                </template>
                            </div>

                            <div class="info-container">
                                <template v-if="editMode === 'userName'">
                                    <input v-model="editableUserName" placeholder="Username" />
                                    <div class="personal-buttons">
                                        <button @click="cancelEdit">
                                            <img alt="icon" src="/public/images/Button-icon/x-white-icon.png">
                                        </button>
                                        <button @click="saveEdit('userName')">
                                            <img alt="icon" src="/public/images/Button-icon/save-white-button.png">
                                        </button>
                                    </div>
                                </template>
                                <template v-else>
                                    <h2>{{ user.user_info.userName }}</h2>
                                    <button @click="editMode = 'userName'">
                                        <img alt="icon" src="/public/images/Button-icon/edit%20icon.png" />
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
                    <div class="chef-certificates-container" v-if="isChef">
                        <button class="nav-btn left" @click="prevCertificate" :disabled="currentCertificateIndex === 0">
                            ⬅
                        </button>

                        <div class="certificate-viewer">
                            <!-- Index 0 = Upload Certificate -->
                            <div v-if="currentCertificateIndex === 0" class="upload-certificate">
                                <input type="file" id="certificateInput" @change="uploadCertificate" hidden />
                                <button class="upload-btn" @click="triggerCertificateInput">
                                    ➕ Upload Certificate
                                </button>
                            </div>

                            <!-- Certificates -->
                            <div v-else>
                                <img
                                    :src="`/storage/${chefCertificate[currentCertificateIndex - 1].certificate_path}`"
                                    alt="Certificate"
                                    class="certificate-image"
                                />
                                <span class="certificate-counter">
                                    {{ currentCertificateIndex }} / {{ chefCertificate.length }}
                                </span>
                            </div>
                        </div>

                        <button
                            class="nav-btn right"
                            @click="nextCertificate"
                            :disabled="currentCertificateIndex === chefCertificate.length"
                        >
                            ➡
                        </button>
                    </div>


                </div>

                <div v-if="activeButton === 'password'" class="card-2">
                    <div class="card-label">
                        <h2>Change Password</h2>
                    </div>
                    <div class="card-body-security">
                        <p>Your password must be at least 6 characters and should include a combination of numbers, letters and special characters (!@$%).</p>
                        <input type="password" v-model="currentPassword" placeholder="Current Password:" required />
                        <input type="password" v-model="newPassword" placeholder="New Password:" required />
                        <input type="password" v-model="confirmPassword" placeholder="Re-type new password:" required />
                        <button @click="changePassword">Change password</button>
                        <p>{{ passwordMessage }}</p>
                    </div>
                </div>
                <div class="card-3" v-if="activeButton === 'hiddenRecipe'">
                    <RecipeCard
                        v-for="recipe in recipeCardDetails.filter(r => r.is_hidden === '1')"
                        :key="recipe.id"
                        :recipeCardDetail="recipe"
                    />
                </div>

                <div class="card-4" v-if="activeButton === 'savedRecipe'">
                    <RecipeCard
                        v-for="recipe in recipeCardDetails.filter(r => r.is_saved === '1')"
                        :key="recipe.id"
                        :recipeCardDetail="recipe"
                    />
                </div>

            </div>
        </div>
    </div>
</template>
<script setup>
    import { ref, computed } from "vue";
    import RecipeCard from "@/Component/RecipeCard.vue";
    import axios from "axios";

    const props = defineProps({
        user: Object,
        recipeCardDetails: Array,
        chefCertificate: Array
    });

    const isChef = computed(() => props.user.role === 'chef');

    const activeButton = ref("personal");
    const editMode = ref("");

    const editableFullName = ref(props.user.user_info.fullName);
    const editableUserName = ref(props.user.user_info.userName);

    const currentPassword = ref("");
    const newPassword = ref("");
    const confirmPassword = ref("");
    const passwordMessage = ref("");
    const currentCertificateIndex = ref(0);

    function triggerCertificateInput() {
        document.getElementById("certificateInput").click();
    }

    async function uploadCertificate(event) {
        const file = event.target.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append("userID", props.user.id);
        formData.append("certificate", file);

        try {
            const response = await axios.post("/add-certificate", formData, {
                headers: { "Content-Type": "multipart/form-data" },
            });

            if (response.data.certificate) {
                props.chefCertificate.push(response.data.certificate);
                currentCertificateIndex.value = props.chefCertificate.length;
            }
        } catch (error) {
            console.error("Error uploading certificate:", error.response?.data || error);
        }
    }

    async function saveEdit(field) {
        try {
            const payload = {};
            if (field === "fullName") payload.fullName = editableFullName.value;
            if (field === "userName") payload.userName = editableUserName.value;

            const response = await axios.post("/edit-personal-information", payload);

            if (response.data.success) {
                props.user.user_info.fullName = response.data.user.user_info.fullName;
                props.user.user_info.userName = response.data.user.user_info.userName;
                editMode.value = "";
            }
        } catch (error) {
            console.error("Error updating info:", error.response?.data || error);
        }
    }
    function cancelEdit() {
        editableFullName.value = props.user.user_info.fullName;
        editableUserName.value = props.user.user_info.userName;
        editMode.value = "";
    }

    async function changePassword() {
        if (newPassword.value !== confirmPassword.value) {
            passwordMessage.value = "Passwords do not match!";
            return;
        }
        try {
            const response = await axios.post("/change-password", {
                current_password: currentPassword.value,
                new_password: newPassword.value,
                new_password_confirmation: confirmPassword.value,
            });
            passwordMessage.value = response.data.message;

            currentPassword.value = "";
            newPassword.value = "";
            confirmPassword.value = "";
        } catch (error) {
            passwordMessage.value = error.response?.data?.message || "Error changing password.";
        }
    }
    async function uploadProfile(event) {
        const file = event.target.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append("profile", file);

        try {
            const response = await axios.post("/update-profile-picture", formData, {
                headers: { "Content-Type": "multipart/form-data" },
            });

            if (response.data.success) {
                props.user.user_info.profilePath = response.data.profilePath; // update UI
            }
        } catch (error) {
            console.error("Error uploading profile picture:", error.response?.data || error);
        }
    }

    function triggerFileInput() {
        document.getElementById("profileInput").click();
    }
    function nextCertificate() {
        if (currentCertificateIndex.value < props.chefCertificate.length) {
            currentCertificateIndex.value++;
        }
    }

    function prevCertificate() {
        if (currentCertificateIndex.value > 0) {
            currentCertificateIndex.value--;
        }
    }

</script>


<style scoped>
    .admin-main-body {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        font-family: 'Poppins', sans-serif;
        background-color: #FFE797;
    }

    .page-label {
        width: 100%;
        display: flex;
        align-items: center;
        padding: 15px 20px;
    }
    .page-label h2 {
        font-weight: 700;
        font-size: 1.8em;
        color: #31485B;
    }

    .admin-body {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: row;
        gap: 20px;
    }

    .settings-button-container {
        width: 18%;
        display: flex;
        flex-direction: column;
        gap: 15px;
        padding: 20px 10px;
        align-items: flex-end;
    }
    .settings-button-container button {
        width: 90%;
        height: 90px;
        background-color: #E0E7FF;
        border: none;
        border-radius: 18px;
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 0 18px;
        color: #435F77;
        font-size: 1.05em;
        font-weight: 500;
        transition: all 0.25s ease;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .settings-button-container button:hover {
        background-color: #d0d8f7;
        transform: translateY(-2px);
    }
    .settings-button-container button.active {
        background-color: #435F77;
        color: white;
        font-weight: 600;
        transform: scale(1.05);
    }
    .settings-button-container button img {
        width: 55px;
        height: auto;
    }
    .settings-button-container .button-text {
        flex: 1;
        white-space: normal;
        text-align: left;
        word-break: break-word;
    }

    .settings-card-container {
        flex: 1;
        display: flex;
        align-items: start;
        justify-content: flex-start;
    }

    .card-1,
    .card-2,
    .card-3,
    .card-4 {
        width: 85%;
        min-height: 85%;
        margin: 20px 0 0 20px;
        background-color: #E0E7FF;
        border-radius: 20px;
        display: flex;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        padding: 25px;
    }
    .card-1,
    .card-2 {
        flex-direction: column;
        gap: 20px;
    }
    .card-3,
    .card-4{
        flex-wrap: wrap;
        flex-direction: row;
        overflow: auto;
        gap: 20px;
    }

    .card-label h2 {
        font-weight: 600;
        font-size: 1.5em;
        color: #31485B;
    }

    .card-body-personal {
        display: flex;
        flex-direction: row;
        gap: 30px;
        align-items: flex-start;
    }
    .profile-picture-container {
        flex: 0 0 35%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .profile-picture-container button {
        width: 180px;
        height: 180px;
        border-radius: 50%;
        border: 4px solid #435F77;
        background: #E0E7FF;
        cursor: pointer;
        transition: 0.2s;
    }
    .profile-picture-container button img{
        width: 100%;
        height: 100%;
        border-radius: 50%;
    }
    .profile-picture-container button:hover {
        transform: scale(1.05);
    }

    .personal-information-container {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    .info-container {
        width: 95%;
        min-height: 55px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background-color: #B5BFDE;
        padding: 0 12px;
        border-radius: 12px;
        box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);
    }
    .info-container input {
        width: 70%;
        height: 100%;
        background-color: #B5BFDE;
        border: none;
        outline: none;
        font-size: 1em;
        color: #31485B;
    }
    .info-container h2 {
        font-size: 1.1em;
        color: #31485B;
        font-weight: 500;
        margin: 0;
    }
    .info-container button {
        width: 42px;
        height: 42px;
        background-color: transparent;
        border: none;
        cursor: pointer;
        padding: 6px;
        border-radius: 8px;
        transition: background 0.2s;
    }
    .info-container img {
        width: 100%;
        height: 100%;
    }

    .personal-buttons {
        display: flex;
        gap: 8px;
    }
    .personal-buttons button {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .personal-buttons button img {
        width: 70%;
        height: 70%;
    }

    .card-body-security {
        width: 80%;
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    .card-body-security p {
        font-size: 0.95em;
        color: #435F77;
        line-height: 1.5;
    }
    .card-body-security input {
        width: 100%;
        height: 50px;
        padding: 0 15px;
        background-color: #F1F4FF;
        border: 1px solid #d0d8f7;
        border-radius: 12px;
        font-size: 0.95em;
        color: #31485B;
        transition: border 0.2s, box-shadow 0.2s;
    }
    .card-body-security input:focus {
        border-color: #435F77;
        box-shadow: 0 0 0 3px rgba(67,95,119,0.2);
        outline: none;
    }
    .card-body-security button {
        align-self: flex-end;
        padding: 10px 25px;
        border-radius: 20px;
        background-color: #435F77;
        color: white;
        border: none;
        font-size: 0.95em;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.25s;
    }
    .card-body-security button:hover {
        background-color: #31485B;
        transform: translateY(-2px);
    }
    .chef-certificates-container {
        width: 100%;
        height: 350px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        gap: 15px;
    }

    .certificate-viewer {
        flex: 1;
        text-align: center;
        position: relative;
    }

    .certificate-image {
        width: 100%;
        max-height: 200px;
        object-fit: contain;
        border: 2px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }

    .certificate-counter {
        position: absolute;
        bottom: -25px;
        left: 50%;
        transform: translateX(-50%);
        font-size: 0.9em;
        color: #31485B;
        font-weight: 500;
    }

    .nav-btn {
        background-color: #435F77;
        color: white;
        border: none;
        border-radius: 50%;
        width: 45px;
        height: 45px;
        font-size: 1.2em;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 0.2s, transform 0.2s;
    }

    .nav-btn:hover:not(:disabled) {
        background-color: #31485B;
        transform: scale(1.1);
    }

    .nav-btn:disabled {
        background-color: #aaa;
        cursor: not-allowed;
    }

    .nav-btn.left {
        margin-right: 10px;
    }

    .nav-btn.right {
        margin-left: 10px;
    }


</style>
