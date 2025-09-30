<template>
    <div class="admin-main-body">
        <div class="page-label">
            <h2>Settings</h2>
        </div>

        <div class="admin-body">
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
                            <div class="info-container1">
                                <template v-if="editMode === 'fullName'">
                                    <input v-model="editableFullName" placeholder="Full Name" />
                                    <div class="personal-buttons">
                                        <button @click="cancelEdit">
                                            <img alt="icon" src="/public/images/Button-icon/decline.png">
                                        </button>
                                        <button @click="saveEdit('fullName')">
                                            <img alt="icon" src="/public/images/Button-icon/approved.png">
                                        </button>
                                    </div>
                                </template>
                                <template v-else>
                                    <p>Full Name:</p>
                                    <h2>{{ user.user_info.fullName }}</h2>
                                    <button @click="editMode = 'fullName'">
                                        <img alt="icon" src="/public/images/Button-icon/edit%20icon.png" />
                                    </button>
                                </template>
                            </div>

                            <!-- Username -->
                            <div class="info-container2">
                                <template v-if="editMode === 'userName'">
                                    <input v-model="editableUserName" placeholder="Username" />
                                    <div class="personal-buttons">
                                        <button @click="cancelEdit">
                                            <img alt="icon" src="/public/images/Button-icon/decline.png">
                                        </button>
                                        <button @click="saveEdit('userName')">
                                            <img alt="icon" src="/public/images/Button-icon/approved.png">
                                        </button>
                                    </div>
                                </template>
                                <template v-else>
                                    <p>User Name:</p>
                                    <h2>@{{ user.user_info.userName }}</h2>
                                    <button @click="editMode = 'userName'">
                                        <img alt="icon" src="/public/images/Button-icon/edit%20icon.png" />
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="activeButton === 'password'" class="card-2">
                    <div class="card-label">
                        <h2>Change Password</h2>
                        <p>Your password must be at least 6 characters and should include a combination of numbers, letters and special characters (!@$%).</p>
                    </div>
                    <div class="card-body-security">
                        <div class="info-row1">
                            <span class="label">Current Password:</span>
                            <div class="password-field">
                                <input 
                                :type="showCurrent ? 'text' : 'password'" 
                                v-model="currentPassword" 
                                required 
                                />
                                <button type="button" class="toggle-btn" @click="showCurrent = !showCurrent">
                                    <img 
                                        :src="showCurrent ? '/images/Button-icon/hide.png' : '/images/Button-icon/show.png'" 
                                        alt="toggle password" 
                                    />
                                </button>
                            </div>
                        </div>

                        <div class="info-row2">
                            <span class="label">New Password:</span>
                            <div class="password-field">
                                <input 
                                :type="showNew ? 'text' : 'password'" 
                                v-model="newPassword" 
                                required 
                                />
                                <button type="button" class="toggle-btn" @click="showNew = !showNew">
                                    <img 
                                        :src="showNew ? '/images/Button-icon/hide.png' : '/images/Button-icon/show.png'" 
                                        alt="toggle password" 
                                    />
                                </button>
                            </div>
                        </div>

                        <div class="info-row3">
                            <span class="label">Re-type new password:</span>
                            <div class="password-field">
                                <input 
                                :type="showConfirm ? 'text' : 'password'" 
                                v-model="confirmPassword" 
                                @input="checkMatch"
                                required 
                                />
                                <button type="button" class="toggle-btn" @click="showConfirm = !showConfirm">
                                    <img 
                                        :src="showConfirm ? '/images/Button-icon/hide.png' : '/images/Button-icon/show.png'" 
                                        alt="toggle password" 
                                    />
                                </button>
                            </div>
                        </div>

                        <div class="action-row">
                            <p v-if="confirmPassword" :style="{ color: isMatch ? 'green' : 'red' }">
                            {{ isMatch ? 'Passwords match ✅' : 'Passwords do not match ❌' }}
                        </p>
                        <button @click="changePassword">CHANGE PASSWORD</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref } from "vue";
    import axios from "axios";

    const props = defineProps({
        user: Object,
    });

    const activeButton = ref("personal");
    const editMode = ref("");

    // Editable fields
    const editableFullName = ref(props.user.user_info.fullName);
    const editableUserName = ref(props.user.user_info.userName);

    // Password fields
    const currentPassword = ref("");
    const newPassword = ref("");
    const confirmPassword = ref("");
    const passwordMessage = ref(""); // success/error message

    // Save edits for personal info
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

    // Change Password
// Show/Hide toggles
const showCurrent = ref(false);
const showNew = ref(false);
const showConfirm = ref(false);

// Match check
const isMatch = ref(true);

function checkMatch() {
  isMatch.value = newPassword.value === confirmPassword.value;
}

async function changePassword() {
  if (!isMatch.value) {
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

    // Reset inputs
    currentPassword.value = "";
    newPassword.value = "";
    confirmPassword.value = "";
  } catch (error) {
    passwordMessage.value =
      error.response?.data?.message || "Error changing password.";
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

</script>


<style scoped>
    .admin-main-body {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        background-color: #f8f9fc;
    }

    .page-label {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .page-label h2 {
        margin-top: 20px;
        font-size: 35px;
        margin-left: 20px;
        font-family: 'Poppins-Bold';
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
        font-size: 1.08em;
        transition: all 0.25s ease;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        font-family: 'Poppins-Bold';
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
    .card-2 {
        width: 90%;
        min-height: 85%;
        margin: 20px 0 0 20px;
        background-color: #E0E7FF;
        border-radius: 20px;
        padding: 25px;
        display: flex;
        flex-direction: column;
        gap: 20px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .card-label h2 {
        margin-top: 2em;
        margin-left: 20px;
        font-size: 1.8em;
        color: #31485B;
        font-family: 'Poppins-Bold';
    }
    
    .card-label p {
        font-size: 1em;
        color: #435F77;
        font-family: 'Poppins-Italic';
        margin-left: 70px;
        margin-top: -10px;
    }
    .card-body-personal {
        display: flex;
        flex-direction: row;
        align-self: center;
        width: 85%;
    }
    .profile-picture-container {
        display: flex;
        justify-content: center;
        width: 45%;
    }
    .profile-picture-container button {
        width: 457px;
        height: 457px;
        border-radius: 50%;
        background: #E0E7FF;
        cursor: pointer;
        transition: 0.2s;
        border: none;
    }
    .profile-picture-container button img{
        width: 100%;
        height: 100%;
        border-radius: 50%;
        border: none;
    }
    .profile-picture-container button:hover {
        transform: scale(1.01);
    }

    .personal-information-container {
        flex: 1;
        display: flex;
        flex-direction: column;
        width: 55%;
        justify-content: center;
        align-items: center;
    }
    .info-container1, .info-container2 {
        width: 80%;
        height: 85px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background-color: #cdd6f6;
        padding: 0 12px;
        box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);
    }
    .info-container1 {
        border-radius: 15px 15px 0 0;
    }
    .info-container2 {
        border-radius: 0 0 15px 15px;
        border-top: #E0E7FF solid 2px;
    }
    .info-container1 input, .info-container2 input {
        background-color: #cdd6f6;
        border: none;
        outline: none;
        font-size: 1em;
        color: #31485B;
        font-family: 'Poppins-Bold';
    }
    .info-container1 p, .info-container2 p {
        width: 25%;
        font-size: 1em;
        color: #435F77;
        margin: 0;
        font-family: 'Poppins-Regular';
    }
    .info-container1 h2, .info-container2 h2 {
        width: 70%;
        font-size: 1.1em;
        color: black;
        font-family: 'Poppins-Bold';
        margin: 0;
    }
    .info-container1 button, .info-container2 button {
        width: 42px;
        height: 42px;
        background-color: transparent;
        border: none;
        cursor: pointer;
        padding: 6px;
        margin-right: -5px;
        border-radius: 8px;
        transition: background 0.2s;
    }
    .info-container1 img, .info-container2 img {
        width: 100%;
        height: 100%;
    }

    .personal-buttons {
        display: flex;
        gap: 8px;
    }
    .personal-buttons button {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .personal-buttons img {
        width: 95%;
        height: 95%;
    }
    .card-body-security {
        width: 80%;
        display: flex;
        flex-direction: column;
        align-self: center;
    }
    .info-row1, .info-row2, .info-row3 {
        background: #cdd6f6;
        padding: 12px 15px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 80px;
    }
    .info-row1 {
        border-radius: 15px 15px 0 0;
    }
    .info-row2 {
        border-top: #E0E7FF solid 2px;
    }
    .info-row3 {
        border-radius: 0 0 15px 15px;
        border-top: #E0E7FF solid 2px;
    }
    .label {
        font-size: 1em;
        font-family: 'Poppins-Regular';
        color: #31485B;
        width: 20%;
    }
    .password-field {
        display: flex;
        width: 80%;
    }
    .password-field input{
        flex: 1;
        margin-left: 10px;
        border: none;
        font-family: 'Poppins-Bold';
        background: transparent;
        font-size: 1.5em;
        color: black;
        outline: none;
        width: 80%;
    }
    .password-field button {
        width: 50px;
        height: 35px;
        display: flex;
        align-self: center;
        background-color: transparent;
        border: none;
        cursor: pointer;
    }
    .password-field button img {
        width: 100%;
        height: 100%;
    }
    .action-row {
        margin-top: 15px;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
    }
    .action-row p {
        font-size: .95em;
        font-family: 'Poppins-BoldItalic';
        align-self: flex-start;
        margin-left: 10px;
    }
    .action-row button {
        padding: 10px 25px;
        border-radius: 20px;
        background-color: #435F77;
        color: white;
        border: none;
        font-size: 0.75em;
        font-family: 'Poppins-Bold';
        cursor: pointer;
        transition: all 0.25s;
        box-shadow: 4px 4px 10px rgb(0, 0, 0, 0.3);
        border-right: #AFADAD solid 1px;
    }
    .action-row button:hover {
        background-color: #31485B;
    }


</style>
