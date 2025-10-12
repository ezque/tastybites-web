<template>
    <div class="flex flex-col w-full h-full overflow-hidden">
        <!-- Header -->
        <h2 class="mt-5 ml-5 text-[35px] font-[Poppins-Bold]">Dashboard</h2>

        <!-- Cards Section -->
        <div class="flex flex-row flex-wrap items-center justify-center mt-5 pb-2 w-full">
            <!-- Income -->
            <button class="flex items-center justify-between bg-[#D9D9D9] rounded-lg p-5 w-[380px] h-[130px] mx-5 cursor-pointer text-left hover:shadow-md transition">
                <div class="flex flex-col justify-center align-center w-[70%]">
                    <p class="text-[16px] font-[Poppins-Bold] text-[#333] m-0">TOTAL INCOME</p>
                    <p class="text-[36px] font-[Poppins-Bold] text-[#435F77] my-1">₱{{ props.adminTotalIncome.total }}</p>
                </div>
                <div class="flex flex-col justify-center items-center w-[30%] h-full">
                    <img src="/public/images/Button-icon/dashboard-sales.png" alt="money icon" class="w-[90px] h-[90px]" />
                </div>
            </button>

            <!-- Users -->
            <button @click="emit('navigate','Users')" class="flex items-center justify-between bg-[#D9D9D9] rounded-lg p-5 w-[390px] h-[130px] mx-5 cursor-pointer text-left hover:shadow-md transition">
                <div class="flex flex-col justify-center w-[70%]">
                    <p class="text-[16px] font-[Poppins-Bold] text-[#333] m-0">USERS</p>
                    <p class="text-[32px] font-[Poppins-Bold] text-[#435F77] my-1">{{ props.totalCountsUsers.usersCounts }}</p>
                    <div class="flex items-center gap-2">
                        <img src="/public/images/Button-icon/approved.png" alt="approved" class="inline w-3 h-auto ml-1"/>
                        <span class="text-[#6c757d] text-[11px] font-[Poppins-Italic]">For more information</span>
                    </div>
                </div>
                <div class="flex flex-col justify-center items-center w-[30%] h-full">
                    <img src="/public/images/Button-icon/dashboars-users.png" alt="users icon" class="w-[100px] h-[75px]" />
                </div>
            </button>

            <!-- Chefs -->
            <button @click="emit('navigate','AdminChefs')" class="flex items-center justify-between bg-[#D9D9D9] rounded-lg p-5 w-[390px] h-[130px] mx-5 cursor-pointer text-left hover:shadow-md transition">
                <div class="flex flex-col justify-center w-[70%]">
                    <p class="text-[16px] font-[Poppins-Bold] text-[#333] m-0">CHEFS</p>
                    <p class="text-[32px] font-[Poppins-Bold] text-[#435F77] my-1">{{ props.totalCountsUsers.chefsCounts }}</p>
                    <div class="flex items-center gap-2">
                        <img src="/public/images/Button-icon/approved.png" alt="approved" class="inline w-3 h-auto ml-1"/>
                        <span class="text-[#6c757d] text-[11px] font-[Poppins-Italic]">For more information</span>
                    </div>
                </div>
                <div class="flex flex-col justify-center items-center w-[30%] h-full">
                    <img src="/public/images/Button-icon/dashboard-chef.png" alt="chef icon" class="w-[90px] h-[90px]" />
                </div>
            </button>

            <!-- Recipes -->
            <button @click="emit('navigate','Recipes')" class="flex items-center justify-between bg-[#D9D9D9] rounded-lg p-5 w-[380px] h-[130px] mx-5 cursor-pointer text-left hover:shadow-md transition">
                <div class="flex flex-col justify-center w-[70%]">
                    <p class="text-[16px] font-[Poppins-Bold] text-[#333] m-0">RECIPES</p>
                    <p class="text-[32px] font-[Poppins-Bold] text-[#435F77] my-1">{{ props.getTotalRecipeCounts.recipeCounts }}</p>
                    <div class="flex items-center gap-2">
                        <img src="/public/images/Button-icon/approved.png" alt="approved" class="inline w-3 h-auto ml-1"/>
                        <span class="text-[#6c757d] text-[11px] font-[Poppins-Italic]">For more information</span>
                    </div>
                </div>
                <div class="flex flex-col justify-center items-center w-[30%] h-full">
                    <img src="/public/images/Button-icon/dashboard-recipes.png" alt="recipes icon" class="w-[90px] h-[90px]" />
                </div>
            </button>
        </div>

        <!-- Graph Section -->
        <div class="flex flex-col bg-[#D9D9D9] rounded-lg shadow-md p-3 w-[80%] mt-[60px] mx-auto">
            <!-- Graph Header -->
            <div class="flex items-center justify-between px-2">
                <h3 class="text-[18px] font-[Poppins-Bold] m-2">Income Recap Report</h3>
                <div class="relative">
                    <button class="bg-transparent border-none cursor-pointer" @click="toggleDropdown">
                        <img src="/public/images/Button-icon/option.png" alt="dropdown" class="w-2 h-auto"/>
                    </button>
                    <div v-if="dropdownOpen" class="absolute top-[35px] right-[5px] bg-[#435F77] rounded-tr-none rounded-bl-lg rounded-br-lg shadow-md w-[100px] flex flex-col">
                        <a href="#" @click.prevent="setView('month')" class="block text-white text-center font-[Poppins-Bold] py-2 hover:bg-[#31485B]">Month</a>
                        <a href="#" @click.prevent="setView('year')" class="block text-white text-center font-[Poppins-Bold] py-2 hover:bg-[#31485B] border-t-2 border-white">Year</a>
                    </div>
                </div>
            </div>

            <!-- Chart -->
            <div class="w-[95%] h-[300px] bg-white p-2 self-center">
                <LineChart :data="chartData" :options="chartOptions" class="w-full h-full"/>
            </div>
        </div>

        <Footer />
    </div>
</template>


<script setup>
    import { ref, watch } from 'vue'
    import {
        Chart as ChartJS,
        Title,
        Tooltip,
        Legend,
        LineElement,
        CategoryScale,
        LinearScale,
        PointElement,
        Filler
    } from 'chart.js'
    import { Line } from 'vue-chartjs'
    import Footer from "@/Component/Footer.vue";

    ChartJS.register(Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement, Filler)

    const props = defineProps({
        adminTotalIncome: Object,
        totalCountsUsers: Object,
        getTotalRecipeCounts: Object
    })
    const emit = defineEmits(['navigate'])
    const LineChart = Line
    const currentView = ref('month')

    // Prepare monthly chart data
    const monthlyData = {
        labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
        datasets: [{
            label: 'Revenue per Month',
            data: Array.from({length: 12}, (_, i) => {
                const record = props.adminTotalIncome?.monthly?.find(r => r.month === i + 1)
                return record ? record.total : 0
            }),
            borderColor: '#435F77',
            font: {
                family: 'Poppins-Bold',
                size: 13,
                color: '#435F77'
            },
            fill: true,
            tension: 0.4
        }]
    }

    const yearlyData = {
        labels: props.adminTotalIncome?.yearly?.map(r => r.year) || [],
        datasets: [{
            label: 'Revenue per Year',
            data: props.adminTotalIncome?.yearly?.map(r => r.total) || [],
            borderColor: '#435F77',
            font: {
                family: 'Poppins-Bold',
                size: 13,
                color: '#435F77'
            },
            fill: true,
            tension: 0.4
        }]
    }

    const dropdownOpen = ref(false)
    const chartData = ref(monthlyData)

    const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { display: false }
    }
    }

    function toggleDropdown() {
    dropdownOpen.value = !dropdownOpen.value
    }

    function setView(view) {
    currentView.value = view
    chartData.value = view === "month" ? monthlyData : yearlyData
    dropdownOpen.value = false
    }

    function updateChart() {
    chartData.value = currentView.value === 'month' ? monthlyData : yearlyData
    }
</script>


