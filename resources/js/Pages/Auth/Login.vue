<template>
    <div class="h-screen w-full flex items-center justify-center relative">
        <!-- Background -->
        <img
            src="/public/images/register_back - rotated.png"
            class="w-2/5 h-auto max-h-[88%] absolute top-0 left-0 z-0 object-contain"
        />


        <!-- Left side -->
        <div class="p-2 items-center justify-center z-10 relative hidden lg:flex">
            <img src="/public/images/tastybites_plate.png" class="h-[700px] w-auto max-w-full" />
        </div>

        <!-- Right side -->
        <div class="p-[50px_30px_30px_30px] border border-[#D9D9D9] bg-[#CFDAFF] rounded-[25px] flex flex-col items-center z-10 relative">
            <h1 class="text-[4.5em] font-[100] font-[Rouge_Script] text-center relative -bottom-[15px] m-0">
                Welcome Back!
            </h1>

            <!-- Inputs -->
            <div class="flex flex-col gap-[10px] relative -bottom-[10px] m-0">
                <p v-if="errors.email" class="text-[#ff4d4f] text-sm">{{ errors.email[0] }}</p>

                <div class="bg-white rounded-[10px] flex items-center p-[10px] w-[400px]">
                    <img src="/public/images/Button-icon/email.png" class="w-6 h-[18px]" />
                    <input
                        placeholder="Email"
                        v-model="form.email"
                        id="email"
                        class="w-full h-full ml-2 border-0 outline-0 text-[#768082] font-[Poppins] text-base"
                    />
                </div>

                <p v-if="errors.password" class="text-[#ff4d4f] text-sm">{{ errors.password[0] }}</p>

                <div class="bg-white rounded-[10px] flex items-center p-[10px] w-[400px]">
                    <img src="/public/images/Button-icon/password.png" class="w-6 h-[22px] ml-[2px] cursor-pointer" />
                    <input
                        :type="showPassword ? 'text' : 'password'"
                        placeholder="Password"
                        v-model="form.password"
                        class="w-full h-full ml-2 border-0 outline-0 text-[#768082] font-[Poppins] text-base"
                    />
                    <img
                        :src="showPassword ? '/images/Button-icon/pass_hide.png' : '/images/Button-icon/pass_show.png'"
                        class="w-6 h-4 ml-[10px] cursor-pointer select-none"
                        @click="togglePassword"
                        alt="Toggle Password"
                    />
                </div>
            </div>

            <!-- Remember me -->
            <div class="w-full flex items-center mt-5 ml-[20px] font-[Poppins-SemiBold] text-[13px]">
                <input
                    type="checkbox"
                    v-model="form.remember"
                    id="remember"
                    class="scale-140 border border-[#AFADAD] mr-2"
                />
                <label for="remember">Remember me</label>
            </div>

            <!-- Submit button -->
            <button
                class="w-full bg-[#435F77] rounded-[20px] py-2 mt-4 text-white font-[Poppins-Bold] font-bold text-[16px] flex items-center justify-center gap-2 transition-all duration-300 ease-in-out hover:scale-105 disabled:opacity-60 disabled:cursor-not-allowed"
                @click="handleLogin"
                :disabled="loading"
            >
                <span v-if="loading" class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                <span v-else>LOGIN</span>
            </button>

            <!-- Below button text -->
            <p class="mt-2 text-[13px] font-[Poppins-Regular]">
                Don't have an account?
                <a href="/register" class="text-[#435F77] font-[Poppins-SemiBold] italic">Register</a>
            </p>

            <!-- Footer Logo -->
            <img src="/public/images/tastybites_logo.png" class="w-[150px] h-auto mt-2" />
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
