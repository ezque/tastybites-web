<template>
    <div class="container">
        <img src="/public/images/register_back - rotated.png" class="background-image" />
        <div class="leftside-container">
            <img src="/public/images/tastybites_plate.png" class="logo-plate" />
        </div>
        <div class="rightside-container">
            <h1 class="page-label">Welcome Back!</h1>
            <div class="inputs">
                <p v-if="errors.email" class="error-message">{{ errors.email[0] }}</p>
                <div class="text-input-group">
                    <img src="/public/images/Button-icon/email.png" class="email-icon" />
                    <input placeholder="Email" v-model="form.email" id="email" />
                </div>

                <p v-if="errors.password" class="error-message">{{ errors.password[0] }}</p>
                <div class="text-input-group">
                    <img src="/public/images/Button-icon/password.png" class="password-icon" />
                    <input
                        :type="showPassword ? 'text' : 'password'"
                        placeholder="Password"
                        v-model="form.password"
                    />
                    <img 
                        :src="showPassword ? '/images/Button-icon/pass_hide.png' : '/images/Button-icon/pass_show.png'" 
                        class="visibility-icon"
                        @click="togglePassword"
                        alt="Toggle Password"
                    />
                </div>
            </div>

            <div class="remember-me-group">
                <input type="checkbox" v-model="form.remember" id="remember" />
                <label for="remember">Remember me</label>
            </div>

            <button class="submit-button" @click="handleLogin" :disabled="loading">
                <span v-if="loading" class="spinner"></span>
                <span v-else>LOGIN</span>
            </button>

            <p class="below-button-text">Don't have an account? <a href="/register" class="register-link">Register</a></p>
            <img src="/public/images/tastybites_logo.png" class="form-footer-logo" />
        </div>
    </div>
</template>

<script setup>
    import { reactive, ref } from 'vue'
    import axios from 'axios'

    const loading = ref(false)
    const showPassword = ref(false)
    const errors = reactive({})

    function togglePassword() {
        showPassword.value = !showPassword.value
    }

    const form = reactive({
        email: '',
        password: '123456',
        remember: false
    })

    const handleLogin = async () => {
        loading.value = true
        Object.keys(errors).forEach((key) => delete errors[key])

        try {
            const response = await axios.post('/login-post', form, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                }
            })

            if (response.data.redirect) {
                window.location.href = response.data.redirect
            }
        } catch (error) {
            if (error.response?.status === 422) {
                Object.assign(errors, error.response.data.errors)
            } else {
                console.error('Unexpected login error:', error)
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
        font-family: 'Rouge Script';
        font-weight: 100;
        text-align: center;
        position: relative;
        bottom: 15px;
        margin: 0;
    }
    .rightside-container {
        padding: 50px 30px 30px 30px;
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
        color: #768082;
        font-family: 'Poppins', sans-serif;
    }
    .email-icon {
        width: 24px;
        height: 18px;
    }
    .password-icon {
        width: 24px;
        height: 22px;
        margin-left: 2px;
        cursor: pointer;
    }
    .visibility-icon {
        width: 24px;
        height: 16px;
        margin-left: 10px;
    }
    .remember-me-group {
        width: 100%;
        align-items: center;
        margin-left: 50px;
        font-family: 'Poppins-SemiBold';
        font-size: 13px;
        margin-top: 5px;
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
    .visibility-icon {
        cursor: pointer;
        user-select: none;
    }
    .error-message {
        color: #ff4d4f;
        font-size: 0.875rem;
    }
</style>
