<template>
    <div class="container">
        <img src="/public/images/register_back - rotated.png" class="background-image" />
        <div class="leftside-container">
            <img src="/public/images/tastybites_plate.png" class="logo-plate" />
        </div>

        <div class="rightside-container">
            <h1 class="page-label">Join Us Now!</h1>
            <div class="inputs">
                <div class="user-information-group">
                    <div class="user-text-input-group">
                        <span class="material-icons-outlined">person</span>
                        <input placeholder="Fullname" v-model="fullName" />
                    </div>
                    <div class="user-text-input-group">
                        <span class="material-icons-outlined">person</span>
                        <input placeholder="Username" v-model="userName" />
                    </div>
                </div>

                <div class="text-input-group">
                    <span class="material-icons-outlined">email</span>
                    <input placeholder="Email" v-model="email" />
                </div>
                <div class="text-input-group">
                    <span class="material-icons-outlined">person</span>
                    <select v-model="gender">
                        <option disabled value="">Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>

                <div class="text-input-group">
                    <span class="material-icons-outlined">lock</span>
                    <input
                        :type="showPassword ? 'text' : 'password'"
                        placeholder="Password"
                        v-model="password"
                    />
                    <span class="material-icons-outlined visibility-icon" @click="togglePassword">
                        {{ showPassword ? 'visibility_off' : 'visibility' }}
                    </span>
                </div>

                <div class="text-input-group">
                    <span class="material-icons-outlined">lock</span>
                    <input
                        :type="showConfirmPassword ? 'text' : 'password'"
                        placeholder="Confirm Password"
                        v-model="confirmPassword"
                    />
                    <span class="material-icons-outlined visibility-icon" @click="toggleConfirmPassword">
                        {{ showConfirmPassword ? 'visibility_off' : 'visibility' }}
                    </span>
                </div>

                <!-- 👇 Role Buttons -->
                <div class="button-container">
                    <button :class="{ active: role === 'user' }" @click="selectRole('user')">User</button>
                    <button :class="{ active: role === 'chef' }" @click="selectRole('chef')">Chef</button>
                </div>


                <div v-if="role === 'chef'" class="credExp-input-group">
                    <input
                        class="chef-input"
                        type="number"
                        v-model="experience"
                        placeholder="Experience"
                    />

                    <div class="input-with-button">
                        <input
                            class="chef-input"
                            type="text"
                            placeholder="Credentials"
                            v-model="credentialsName"
                            readonly
                        />

                        <button type="button" class="chef-inside-btn" @click="triggerFileInput">
                            Attach
                        </button>

                        <input
                            type="file"
                            ref="fileInput"
                            class="hidden-file-input"
                            @change="handleFileChange"
                            style="display: none"
                        />
                    </div>
                </div>

            </div>

            <button class="submit-button" @click="handleLogin" :disabled="loading">
                <span v-if="loading" class="spinner"></span>
                <span v-else>REGISTER</span>
            </button>

            <p class="below-button-text">Already have an account? <a href="/">Login</a></p>
            <img src="/public/images/tastybites_logo.png" class="form-footer-logo" />
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'

const loading = ref(false)
const password = ref('')
const confirmPassword = ref('')
const fullName = ref('')
const userName = ref('')
const gender = ref('')
const email = ref('')
const experience = ref('')
const credentials = ref('')
const credentialsName = ref('') // this is the filename to show
const role = ref('user')

const showPassword = ref(false)
const showConfirmPassword = ref(false)
const errorMessage = ref('')
const successMessage = ref('')

const fileInput = ref(null) // ref for file input

function togglePassword() {
    showPassword.value = !showPassword.value
}

function toggleConfirmPassword() {
    showConfirmPassword.value = !showConfirmPassword.value
}

function selectRole(selected) {
    role.value = selected
}

function triggerFileInput() {
    fileInput.value.click()
}

function handleFileChange(event) {
    const file = event.target.files[0]
    if (file) {
        credentialsName.value = file.name
        credentials.value = file // store actual file if needed for upload
    }
}

async function handleLogin() {
    errorMessage.value = ''
    successMessage.value = ''
    loading.value = true

    if (password.value !== confirmPassword.value) {
        errorMessage.value = "Passwords do not match."
        loading.value = false
        return
    }

    const formData = new FormData()
    formData.append('fullName', fullName.value)
    formData.append('userName', userName.value)
    formData.append('gender', gender.value)
    formData.append('email', email.value)
    formData.append('password', password.value)
    formData.append('password_confirmation', confirmPassword.value)
    formData.append('role', role.value)
    if (role.value === 'chef') {
        formData.append('experience', experience.value)
        if (credentials.value) {
            formData.append('credentials', credentials.value)
        }
    }

    try {
        const response = await axios.post('/register-post', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })

        successMessage.value = response.data.message || 'Registration successful!'
        window.location.href = '/'
    } catch (error) {
        if (error.response?.data?.errors) {
            const errors = error.response.data.errors
            const firstKey = Object.keys(errors)[0]
            errorMessage.value = errors[firstKey][0]
        } else {
            errorMessage.value = 'Something went wrong. Please try again.'
        }
    } finally {
        loading.value = false
    }
}
</script>



<style scoped>
    .container {
        height: 100vh;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .background-image {
        width: 40%;
        height: auto;
        max-height: 100%;
        position: absolute;
        top: 0;
        left: 0;
        z-index: 0;
    }

    .leftside-container {
        padding: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1;
        position: relative;
    }

    .logo-plate {
        width: auto;
        max-height: 600px;
    }

    .rightside-container {
        padding: 30px 30px 10px 30px;
        border: 1px solid #D9D9D9;
        background-color: #CFDAFF;
        border-radius: 25px;
        flex-direction: column;
        align-items: center;
        display: flex;
        width: 500px;
    }

    .page-label {
        font-size: 4.5em;
        font-family: 'Rouge Script', cursive;
        font-weight: 100;
        text-align: center;
        position: relative;
        bottom: 15px;
        margin: 0;
    }

    .inputs {
        display: flex;
        flex-direction: column;
        width: 100%;
        gap: 10px;
    }

    .user-information-group {
        display: flex;
        gap: 10px;
        width: 100%;
    }

    .user-text-input-group {
        background-color: white;
        border-radius: 10px;
        align-items: center;
        display: flex;
        padding: 10px 3px 10px 5px;
        width: 40%;
        flex: 1;
    }

    .user-text-input-group input {
        height: 100%;
        border: none;
        outline: none;
        color: #AFADAD;
        padding: 0 2px 0 2px;
        font-family: 'Poppins', sans-serif;
    }

    .user-text-input-group span {
        color: #AFADAD;
    }

    .text-input-group {
        background-color: white;
        border-radius: 10px;
        align-items: center;
        display: flex;
        padding: 10px;
        width: 100%;
        box-sizing: border-box;
    }

    .text-input-group input {
        flex: 1;
        height: 100%;
        border: none;
        outline: none;
        margin-left: 10px;
        color: #AFADAD;
        font-family: 'Poppins', sans-serif;
    }

    .text-input-group span {
        color: #AFADAD;
    }
    .text-input-group select {
        flex: 1;
        height: 100%;
        border: none;
        outline: none;
        margin-left: 10px;
        color: #AFADAD;
        font-family: 'Poppins', sans-serif;
    }
    .submit-button {
        width: 100%;
        background-color: #435F77;
        border: none;
        border-radius: 20px;
        padding: 10px;
        margin-top: 15px;
        color: white;
        font-family: 'Poppins', sans-serif;
        font-weight: bold;
        font-size: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: background-color 0.3s ease, opacity 0.3s ease;
        cursor: pointer;
    }

    .submit-button:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .spinner {
        width: 20px;
        height: 20px;
        border: 3px solid white;
        border-top: 3px solid transparent;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        100% {
            transform: rotate(360deg);
        }
    }

    .below-button-text {
        margin-top: 10px;
        font-family: 'Poppins', sans-serif;
    }

    .form-footer-logo {
        width: 150px;
        height: auto;
        margin-top: 10px;
    }
    .visibility-icon {
        cursor: pointer;
        user-select: none;
    }
    .button-container {
        width: 100%;
        align-items: center;
        justify-content: center;
        display: flex;
        flex-direction: row;
        gap: 20px;
    }
    .button-container button {
        background-color: #435F77;
        padding: 10px;
        width: 150px;
        border-radius: 20px;
        border: none;
        color: white;
        align-items: center;
        justify-content: center;
        display: flex;
    }
    .credExp-input-group {
        width: 100%;
        display: flex;
        flex-direction: row;
        align-items: center;
        gap: 10px; /* Adds spacing between experience and credentials input */
    }

    .input-with-button {
        position: relative;
        width: 100%;
    }

    .chef-input {
        width: 100%;
        padding: 10px 60px 10px 10px; /* Add space for button on the right */
        border-radius: 10px;
        border: 1px solid #ccc;
        background-color: white;
        box-sizing: border-box;
    }

    .chef-inside-btn {
        position: absolute;
        top: 50%;
        right: 10px;
        transform: translateY(-50%);
        padding: 6px 12px;
        border: none;
        background-color: #3498db;
        color: white;
        border-radius: 5px;
        cursor: pointer;
    }

    .hidden-file-input {
        display: none;
    }

    .chef-input::-webkit-outer-spin-button,
    .chef-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .chef-input[type="number"] {
        -moz-appearance: textfield;
    }
    .button-container button.active {
        background-color: #2C3E50;
        color: #FFD700;
        font-weight: bold;
        border: 2px solid #FFD700;
        transform: scale(1.05);
        transition: all 0.2s ease-in-out;
    }




</style>
