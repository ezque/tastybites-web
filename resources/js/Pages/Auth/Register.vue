<template>
    <div class="h-screen w-full flex items-center justify-center relative">
        <!-- Background image -->
        <img
            src="/public/images/register_back - rotated.png"
            class="w-2/5 h-auto max-h-full absolute top-0 left-0 z-0 object-contain hidden md:block"
            alt="icon"
        />

        <!-- Left side (plate image) -->
        <div class="p-2 flex items-center justify-center z-10 relative hidden lg:flex">
            <img src="/public/images/tastybites_plate.png" class="max-h-[600px] w-auto" alt="icon"/>
        </div>

        <!-- Right side (form) -->
        <div
            class="p-8 border border-[#D9D9D9] bg-[#CFDAFF] rounded-[25px] flex flex-col items-center z-10 relative w-[90%] sm:w-[500px]"
        >
            <!-- Title -->
            <h1 class="text-[3em] sm:text-[4.5em] font-[100] font-[Rouge_Script] text-center mb-4">
                Join Us Now!
            </h1>

            <!-- Inputs -->
            <div class="flex flex-col w-full gap-3">
                <!-- Fullname + Username -->
                <div class="flex gap-2 w-full">
                    <div class="flex items-center bg-white rounded-[10px] p-2 flex-1">
                        <img src="/public/images/Button-icon/user.png" class="w-5 h-[18px]" alt="icon"/>
                        <input
                            placeholder="Fullname"
                            v-model="fullName"
                            class="ml-2 flex-1 border-0 outline-0 text-sm text-black font-[Poppins-Regular]"
                        />
                    </div>
                    <div class="flex items-center bg-white rounded-[10px] p-2 flex-1">
                        <img src="/public/images/Button-icon/user.png" class="w-5 h-[18px]" alt="icon"/>
                        <input
                            placeholder="Username"
                            v-model="userName"
                            class="ml-2 flex-1 border-0 outline-0 text-sm text-black font-[Poppins-Regular]"
                        />
                    </div>
                </div>

                <!-- Email -->
                <div class="flex items-center bg-white rounded-[10px] p-2 w-full">
                    <img src="/public/images/Button-icon/user.png" class="w-6 h-[18px]" alt="icon"/>
                    <input
                        placeholder="Email"
                        v-model="email"
                        class="ml-2 flex-1 border-0 outline-0 text-sm text-black font-[Poppins-Regular]"
                    />
                </div>

                <!-- Gender -->
                <div class="flex items-center bg-white rounded-[10px] p-2 w-full">
                    <img src="/public/images/Button-icon/user.png" class="w-5 h-[20px]" alt="icon"/>
                    <select
                        v-model="gender"
                        class="ml-2 flex-1 border-0 outline-0 text-sm text-black font-[Poppins-Regular] bg-transparent"
                    >
                        <option disabled value="">Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>

                <!-- Password -->
                <div class="flex items-center bg-white rounded-[10px] p-2 w-full">
                    <img src="/public/images/Button-icon/password.png" class="w-5 h-[22px]" alt="icon"/>
                    <input
                        :type="showPassword ? 'text' : 'password'"
                        placeholder="Password"
                        v-model="password"
                        class="ml-2 flex-1 border-0 outline-0 text-sm text-black font-[Poppins-Regular]"
                    />
                    <img
                        :src="showPassword ? '/images/Button-icon/pass_hide.png' : '/images/Button-icon/pass_show.png'"
                        class="w-6 h-5 ml-2 cursor-pointer"
                        @click="togglePassword"
                        alt="icon"
                    />
                </div>

                <!-- Confirm Password -->
                <div class="flex items-center bg-white rounded-[10px] p-2 w-full">
                    <img src="/public/images/Button-icon/password.png" class="w-5 h-[22px]" alt="icon"/>
                    <input
                        :type="showConfirmPassword ? 'text' : 'password'"
                        placeholder="Confirm Password"
                        v-model="confirmPassword"
                        class="ml-2 flex-1 border-0 outline-0 text-sm text-black font-[Poppins-Regular]"
                    />
                    <img
                        :src="showConfirmPassword ? '/images/Button-icon/pass_hide.png' : '/images/Button-icon/pass_show.png'"
                        class="w-6 h-5 ml-2 cursor-pointer"
                        @click="toggleConfirmPassword"
                        alt="icon"
                    />
                </div>

                <!-- Password match text -->
                <p v-if="confirmPassword" :class="passwordMatch ? 'text-green-600 text-xs ml-2' : 'text-red-600 text-xs ml-2'">
                    {{ passwordMatch ? 'Passwords match ✅' : 'Passwords do not match ❌' }}
                </p>

                <!-- Role Buttons -->
                <div class="flex justify-center gap-4 mt-2">
                    <button
                        :class="['flex items-center gap-2 px-4 py-2 rounded-2xl text-white font-bold transition transform shadow-md',
                        role === 'user' ? 'bg-[#31485B] scale-105 shadow-lg' : 'bg-[#435F77] hover:-translate-y-1']"
                        @click="selectRole('user')"
                    >
                        <img src="/public/images/Button-icon/user_white.png" class="w-5 h-5" alt="img"/>
                        <span>USER</span>
                    </button>
                    <button
                        :class="['flex items-center gap-2 px-4 py-2 rounded-2xl text-white font-bold transition transform shadow-md',
                        role === 'chef' ? 'bg-[#31485B] scale-105 shadow-lg' : 'bg-[#435F77] hover:-translate-y-1']"
                        @click="selectRole('chef')"
                    >
                        <img src="/public/images/Button-icon/chef_white.png" class="w-5 h-5" alt="img"/>
                        <span>CHEF</span>
                    </button>
                </div>

                <!-- Extra Inputs for Chef -->
                <div v-if="role === 'chef'" class="flex flex-col gap-3 mt-3">
                    <div class="flex items-center bg-white rounded-[10px] p-2 w-full">
                        <img src="/public/images/Button-icon/experience.png" class="w-5 h-5" />
                        <input
                            type="number"
                            v-model="experience"
                            placeholder="Years of experience"
                            class="ml-2 flex-1 border-0 outline-0 text-sm text-black font-[Poppins-Regular]"
                        />
                    </div>

                    <div class="flex items-center bg-white rounded-[10px] p-2 w-full gap-2">
                        <img src="/public/images/Button-icon/certificates.png" class="w-5 h-5" />
                        <input
                            type="text"
                            placeholder="Credentials"
                            v-model="credentialsName"
                            readonly
                            class="ml-2 flex-1 border-0 outline-0 text-sm text-black font-[Poppins-Regular]"
                        />
                        <button
                            type="button"
                            class="bg-[#435F77] text-white px-3 py-1 rounded-lg text-sm"
                            @click="triggerFileInput"
                        >
                            Attach
                        </button>
                        <input
                            type="file"
                            ref="fileInput"
                            class="hidden"
                            @change="handleFileChange"
                        />
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <button
                class="w-full bg-[#435F77] rounded-[20px] py-2 mt-4 text-white font-bold text-base flex items-center justify-center gap-2 transition-all duration-300 ease-in-out hover:scale-105 disabled:opacity-60 disabled:cursor-not-allowed"
                @click="handleLogin"
                :disabled="loading || !passwordMatch"
            >
        <span
            v-if="loading"
            class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"
        ></span>
                <span v-else>REGISTER</span>
            </button>

            <!-- Footer -->
            <p class="mt-2 text-[13px] font-[Poppins-Regular]">
                Already have an account?
                <a href="/" class="text-[#435F77] font-[Poppins-SemiBold] italic">Login</a>
            </p>
            <img src="/public/images/tastybites_logo.png" class="w-[150px] h-auto mt-2" />
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
    const credentialsName = ref('')
    const role = ref('user')

    const showPassword = ref(false)
    const showConfirmPassword = ref(false)
    const fileInput = ref(null)

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
            credentials.value = file
        }
    }

    async function handleLogin() {
        loading.value = true
        if (password.value !== confirmPassword.value) {
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
                headers: { 'Content-Type': 'multipart/form-data' },
            })
            window.location.href = '/'
        } catch (error) {
            console.error(error)
        } finally {
            loading.value = false
        }
    }

    const passwordMatch = computed(() => {
        if (!confirmPassword.value) return true
        return password.value === confirmPassword.value
    })
</script>
