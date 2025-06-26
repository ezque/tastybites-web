<template>
    <div class="container">
        <img src="/public/images/register_back - rotated.png" class="background-image"/>
        <div class="leftside-container">
            <img src="/public/images/tastybites_plate.png" class="logo-plate" />
        </div>
        <div class="rightside-container">
            <h1 class="page-label">Welcome Back!</h1>
            <div class="inputs">
                <div class="text-input-group">
                    <span class="material-icons-outlined">email</span>
                    <input
                        placeholder="Email"
                        name="email"
                        v-model="email"
                    />
                </div>
                <div class="text-input-group">
                    <span class="material-icons-outlined">lock</span>
                    <input
                        :type="showPassword ? 'text' : 'password'"
                        placeholder="Password"
                        name="password"
                        v-model="password"
                    />
                    <span class="material-icons-outlined visibility-icon" @click="togglePassword">
                        {{ showPassword ? 'visibility_off' : 'visibility' }}
                    </span>
                </div>
            </div>

            <div class="remember-me-group">
                <input type="checkbox">
                <label>Remember me</label>
            </div>
            <button class="submit-button" @click="handleLogin" :disabled="loading">
                <span v-if="loading" class="spinner"></span>
                <span v-else>LOGIN</span>
            </button>
            <p class="below-button-text">Don't have an account? <a href="/register">Register</a> </p>
            <img src="/public/images/tastybites_logo.png" class="form-footer-logo"/>
        </div>

    </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'

const email = ref('')
const password = ref('')
const showPassword = ref(false)
const loading = ref(false)

function togglePassword() {
    showPassword.value = !showPassword.value
}

async function handleLogin() {
    loading.value = true
    try {
        const response = await axios.post('/login-post', {
            email: email.value,
            password: password.value
        })

        // Redirect to the appropriate dashboard
        const redirectUrl = response.data.redirect_url
        window.location.href = redirectUrl
    } catch (error) {
        alert(error.response?.data?.message || 'Login failed')
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
    }
    .background-image {
        width: 40%;
        height: 88%;
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
    .page-label {
        font-size: 4.5em;
        font-family: 'Rouge Script', cursive;
        font-weight: 100;
        text-align: center;
        position: relative;
        bottom: 15px;
        margin: 0;
    }
    .rightside-container {
        padding: 30px 30px 10px 30px;
        border: 1px solid #D9D9D9;
        background-color: #CFDAFF;
        border-radius: 25px;
        flex-direction: column;
        align-items: center;
        display: flex;
    }
    .inputs {
        display: flex;
        flex-direction: column;
        gap: 10px;
        bottom: 10px;
        position: relative;
        margin: 0;
    }
    .text-input-group {
        background-color: white;
        border-radius: 10px;
        align-items: center;
        display: flex;
        padding: 10px 10px 10px 10px;
        width: 400px;
    }
    .text-input-group input {
        width: 100%;
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
    .remember-me-group {
        width: 100%;
        align-items: center;
    }
    .remember-me-group input {
        transform: scale(1.4);
        background: none;
        border: 1px solid #AFADAD;
    }
    .remember-me-group label {
        margin-left: 5px;
    }
    .logo-plate {
        width: auto;
        height: 700px;
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
</style>
