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
                        <img src="/public/images/Button-icon/user.png" class="user-icon" />
                        <input placeholder="Fullname" v-model="fullName" />
                    </div>
                    <div class="user-text-input-group">
                        <img src="/public/images/Button-icon/user.png" class="user-icon" />
                        <input placeholder="Username" v-model="userName" />
                    </div>
                </div>

                <div class="text-input-group">
                    <img src="/public/images/Button-icon/user.png" class="user-icon" />
                    <input placeholder="Email" v-model="email" />
                </div>
                <div class="text-input-group">
                    <img src="/public/images/Button-icon/user.png" class="user-icon" />
                    <select v-model="gender">
                        <option disabled value="">Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>

                <div class="text-input-group">
                    <img src="/public/images/Button-icon/password.png" class="password-icon" />
                    <input
                        :type="showPassword ? 'text' : 'password'"
                        placeholder="Password"
                        v-model="password"
                    />
                    <img 
                        :src="showPassword ? '/images/Button-icon/pass_hide.png' : '/images/Button-icon/pass_show.png'" 
                        class="visibility-icon"
                        @click="togglePassword"
                        alt="Toggle Password"
                    />
                </div>

                <div class="text-input-group">
                    <img src="/public/images/Button-icon/password.png" class="password-icon" />
                    <input
                        :type="showConfirmPassword ? 'text' : 'password'"
                        placeholder="Confirm Password"
                        v-model="confirmPassword"
                    />
                    <img 
                        :src="showConfirmPassword ? '/images/Button-icon/pass_hide.png' : '/images/Button-icon/pass_show.png'" 
                        class="visibility-icon"
                        @click="toggleConfirmPassword"
                        alt="Toggle Password"
                    />
                </div>

                <p v-if="confirmPassword" 
                :class="passwordMatch ? 'match-text' : 'mismatch-text'">
                {{ passwordMatch ? 'Passwords match ✅' : 'Passwords do not match ❌' }}
                </p>

                <!-- Role Buttons -->
                <div class="button-container">
                    <button :class="{ active: role === 'user' }" @click="selectRole('user')">
                        <img src="/public/images/Button-icon/user_white.png" class="role-icon" />
                        <p>USER</p>
                    </button>
                    <button :class="{ active: role === 'chef' }" @click="selectRole('chef')">
                        <img src="/public/images/Button-icon/chef_white.png" class="role-icon" style="margin-right: 8px;" />
                        <p>CHEF</p>
                    </button>
                </div>


                <div v-if="role === 'chef'" class="credExp-input-group">

                    <!-- Experience Input -->
                    <div class="text-input-group">
                        <img src="/public/images/Button-icon/experience.png" class="experience-icon" />
                        <input
                            class="chef-input"
                            type="number"
                            v-model="experience"
                            placeholder="Years of experience"
                        />
                    </div>

                    <!-- Credentials Upload -->
                    <div class="text-input-group input-with-button">
                        <img src="/public/images/Button-icon/certificates.png" class="credential-icon" />
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

            <button 
                class="submit-button" 
                @click="handleLogin" 
                :disabled="loading || !passwordMatch"
            >
                <span v-if="loading" class="spinner"></span>
                <span v-else>REGISTER</span>
            </button>

            <p class="below-button-text">Already have an account? <a href="/" class="register-link">Login</a></p>
            <img src="/public/images/tastybites_logo.png" class="form-footer-logo" />
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
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

const passwordMatch = computed(() => {
  if (!confirmPassword.value) return true // hide until user types
  return password.value === confirmPassword.value
})
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
        padding: 10px;
        box-sizing: border-box;
        width: 40%;
        flex: 1;
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
    .text-input-group input, .user-text-input-group input, .text-input-group select {
        flex: 1;
        height: 100%;
        border: none;
        outline: none;
        margin-left: 10px;
        color: black;
        font-family: 'Poppins-Regular';
        font-size: .80em;
    }   
    .user-icon {
        width: 20px;
        height: 18px;
    }  
    .email-icon {
        width: 24px;
        height: 18px;
    }
    .password-icon {
        width: 20px;
        height: 22px;
        margin-left: 2px;
        cursor: pointer;
    }
    .visibility-icon {
        width: 24px;
        height: 20px;
        flex-shrink: 0; 
        object-fit: contain;
    }
    .submit-button {
        width: 100%;
        background-color: #435F77;
        border: none;
        border-radius: 20px;
        padding: 10px;
        margin-top: 15px;
        color: white;
        font-family: 'Poppins-Bold';
        font-weight: bold;
        font-size: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: background-color 0.3s ease, opacity 0.3s ease;
        cursor: pointer;
    }
    .submit-button:hover {
        transform: scale(1.05);
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
        font-size: 13px;
        font-family: 'Poppins-Regular';
    }
    .register-link {
        color: #435F77;
        font-family: 'Poppins-SemiBold';
        font-style: italic;
        text-decoration: none;
    }
    .form-footer-logo {
        width: 150px;
        height: auto;
        margin-top: 10px;
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
        font-family: 'Poppins-Bold';
        align-self: center;
        gap: 10px;
        cursor: pointer;
        transition: transform 0.2s ease;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .button-container button:hover {
        transform: translateY(-2px);
    }
    .button-container button img {
        width: 25px;
        height: 20px;
    }     
    .button-container button p {
        margin: 0;
        font-size: 1em;
    }

    .credExp-input-group {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .text-input-group img {
        width: 20px;
        height: 20px;
    }

    .chef-input {
        flex: 1;
        border: none;
        outline: none;
        font-size: 14px;
        background: transparent;
    }

    .input-with-button {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .input-with-button .chef-input {
        margin-left: 5px;
    }

    .chef-inside-btn {
        background: #435F77;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 5px 12px;
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
        background-color: #31485B;
        color: white;
        font-weight: 600;
        transform: scale(1.05);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    .experience-icon {
        width: 20px;
        height: 20px;
        margin-left: 2px;
        cursor: pointer;
    }
    .credential-icon {
        width: 20px;
        height: 20px;
        margin-left: 2px;
        cursor: pointer;
    }
    .match-text {
        color: green;
        font-size: .85em;
        font-family: 'Poppins-BoldItalic';
        align-self: flex-start;
        margin-left: 10px;
    }

    .mismatch-text {
        color: red;
        font-size: .85em;
        font-family: 'Poppins-BoldItalic';
        align-self: flex-start;
        margin-left: 10px;
    }
</style>
