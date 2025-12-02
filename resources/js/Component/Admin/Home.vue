<template>
    <!-- Main Container -->
    <div class="flex flex-col w-full h-[97%] overflow-auto">
        <!-- Header -->
        <h2 class="mt-5 ml-5 text-[35px] font-[Poppins-Bold]">Dashboard</h2>

        <!-- Cards Section -->
        <div class="flex flex-row flex-wrap items-center justify-center mt-5 pb-2 w-full">
            <!-- Income -->
            <button class="flex items-center justify-between bg-[#D9D9D9] rounded-lg p-5 w-[380px] h-[130px] mx-5 cursor-pointer text-left hover:shadow-md transition">
                <span class="flex flex-col justify-center align-center w-[70%]">
                    <span class="text-[16px] font-[Poppins-Bold] text-[#333] m-0">TOTAL INCOME</span>
                    <span class="text-[36px] font-[Poppins-Bold] text-[#435F77] my-1">₱{{ props.adminTotalIncome.total }}</span>
                </span>
                <span class="flex flex-col justify-center items-center w-[30%] h-full">
                    <img src="/public/images/Button-icon/dashboard-sales.png" alt="money icon" class="w-[90px] h-[90px]" />
                </span>
            </button>

            <!-- Users -->
            <button @click="emit('navigate','Users')" class="flex items-center justify-between bg-[#D9D9D9] rounded-lg p-5 w-[390px] h-[130px] mx-5 cursor-pointer text-left hover:shadow-md transition">
                <span class="flex flex-col justify-center w-[70%]">
                    <span class="text-[16px] font-[Poppins-Bold] text-[#333] m-0">USERS</span>
                    <span class="text-[32px] font-[Poppins-Bold] text-[#435F77] my-1">{{ props.totalCountsUsers.usersCounts }}</span>
                    <span class="flex items-center gap-2">
                        <img src="/public/images/Button-icon/approved.png" alt="approved" class="inline w-3 h-auto ml-1"/>
                        <span class="text-[#6c757d] text-[11px] font-[Poppins-Italic]">For more information</span>
                    </span>
                </span>
                <span class="flex flex-col justify-center items-center w-[30%] h-full">
                    <img src="/public/images/Button-icon/dashboars-users.png" alt="users icon" class="w-[100px] h-[75px]" />
                </span>
            </button>

            <!-- Chefs -->
            <button @click="emit('navigate','AdminChefs')" class="flex items-center justify-between bg-[#D9D9D9] rounded-lg p-5 w-[390px] h-[130px] mx-5 cursor-pointer text-left hover:shadow-md transition">
                <span class="flex flex-col justify-center w-[70%]">
                    <span class="text-[16px] font-[Poppins-Bold] text-[#333] m-0">CHEFS</span>
                    <span class="text-[32px] font-[Poppins-Bold] text-[#435F77] my-1">{{ props.totalCountsUsers.chefsCounts }}</span>
                    <span class="flex items-center gap-2">
                        <img src="/public/images/Button-icon/approved.png" alt="approved" class="inline w-3 h-auto ml-1"/>
                        <span class="text-[#6c757d] text-[11px] font-[Poppins-Italic]">For more information</span>
                    </span>
                </span>
                <span class="flex flex-col justify-center items-center w-[30%] h-full">
                    <img src="/public/images/Button-icon/dashboard-chef.png" alt="chef icon" class="w-[90px] h-[90px]" />
                </span>
            </button>

            <!-- Recipes -->
            <button @click="emit('navigate','Recipes')" class="flex items-center justify-between bg-[#D9D9D9] rounded-lg p-5 w-[380px] h-[130px] mx-5 cursor-pointer text-left hover:shadow-md transition">
                <span class="flex flex-col justify-center w-[70%]">
                    <span class="text-[16px] font-[Poppins-Bold] text-[#333] m-0">RECIPES</span>
                    <span class="text-[32px] font-[Poppins-Bold] text-[#435F77] my-1">{{ props.getTotalRecipeCounts.recipeCounts }}</span>
                    <span class="flex items-center gap-2">
                        <img src="/public/images/Button-icon/approved.png" alt="approved" class="inline w-3 h-auto ml-1"/>
                        <span class="text-[#6c757d] text-[11px] font-[Poppins-Italic]">For more information</span>
                    </span>
                </span>
                <span class="flex flex-col justify-center items-center w-[30%] h-full">
                    <img src="/public/images/Button-icon/dashboard-recipes.png" alt="recipes icon" class="w-[90px] h-[90px]" />
                </span>
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
        <!-- LeaderBoard -->
        <div class="h-auto w-[100%] py-5 border border-black-500 mt-10 flex flex-col">
            <!-- Label -->
            <h2 class="capitalize mt-1 ml-5 text-[35px] font-[Poppins-Bold]">leaderboards</h2>
            <!-- Card container-->
            <div class="w-full h-auto p-10 flex flex-col">
                <div
                    v-for="(chef, index) in leaderboards"
                    :key="chef.id"
                    class="p-4 border-b flex items-center justify-between"
                >
                    <!-- RANK -->
                    <span class="text-xl font-bold">
                        #{{ index + 1 }}
                    </span>

                                <!-- USERNAME -->
                                <span class="text-lg">
                        {{ chef.user_info?.userName || 'No Name' }}
                    </span>

                                <!-- TOTAL REVENUE -->
                                <span class="text-lg font-semibold text-green-600">
                        ₱{{ chef.total_revenue ?? 0 }}
                    </span>
                </div>
            </div>

        </div>


        <Footer />
    </div>
</template>


<script setup>
import {ref, computed, onMounted} from 'vue'
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
    import Footer from "@/Component/Footer.vue"
    import axios from "axios";

    ChartJS.register(Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement, Filler)

    const props = defineProps({
        adminTotalIncome: Object,
        totalCountsUsers: Object,
        getTotalRecipeCounts: Object
    })

    const emit = defineEmits(['navigate'])
    const LineChart = Line
    const currentView = ref('month')
    const dropdownOpen = ref(false)
    const leaderboards = ref([])

    const monthNames = [
        "January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ]

    // ✅ Gradient Fill Generator
    function createGradient(ctx, chartArea, color1, color2) {
        const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top)
        gradient.addColorStop(0, color1)
        gradient.addColorStop(1, color2)
        return gradient
    }

    // ✅ Monthly Data
    const monthlyData = computed(() => ({
        labels: monthNames,
        datasets: [
            {
                label: 'Monthly Earnings',
                data: Array.from({ length: 12 }, (_, i) => {
                    const record = props.adminTotalIncome?.monthly?.find(r => r.month === i + 1)
                    return record ? record.total : 0
                }),
                borderColor: '#435F77',
                backgroundColor: (context) => {
                    const { ctx, chartArea } = context.chart
                    if (!chartArea) return null
                    return createGradient(ctx, chartArea, 'rgba(67, 95, 119, 0.1)', 'rgba(67, 95, 119, 0.4)')
                },
                fill: true,
                tension: 0.4
            }
        ]
    }))

    // ✅ Yearly Data (with min–max range +2 years)
    const yearlyData = computed(() => {
        const yearly = props.adminTotalIncome?.yearly || []

        if (!yearly.length) {
            return { labels: [], datasets: [] }
        }

        // Extract years
        const yearlyMap = {}
        yearly.forEach(item => yearlyMap[item.year] = item.total)

        const years = Object.keys(yearlyMap).map(Number)
        const minYear = Math.min(...years)
        const maxYear = Math.max(...years)

        const labels = []
        const data = []

        // Generate continuous range from min to max +2
        for (let year = minYear; year <= maxYear + 2; year++) {
            labels.push(year)
            data.push(yearlyMap[year] || 0)
        }

        return {
            labels,
            datasets: [
                {
                    label: 'Yearly Earnings',
                    data,
                    borderColor: 'green',
                    backgroundColor: (context) => {
                        const { ctx, chartArea } = context.chart
                        if (!chartArea) return null
                        return createGradient(ctx, chartArea, 'rgba(144, 238, 144, 0.1)', 'rgba(144, 238, 144, 0.4)')
                    },
                    fill: true,
                    tension: 0.4
                }
            ]
        }
    })

    // ✅ Unified Chart Options (Poppins Font + ₱ prefix)
    const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                labels: {
                    font: {
                        family: 'Poppins-BoldItalic',
                        size: 15
                    },
                    color: '#374151'
                }
            },
            tooltip: {
                bodyFont: { family: 'Poppins-Regular' },
                titleFont: { family: 'Poppins-Regular' }
            }
        },
        scales: {
            x: {
                ticks: {
                    font: { family: 'Poppins-Italic' },
                    color: '#333'
                }
            },
            y: {
                beginAtZero: true,
                ticks: {
                    font: { family: 'Poppins-SemiBold', style: 'italic' },
                    color: '#333',
                    callback: value => '₱' + value
                }
            }
        }
    }

    // ✅ Dynamically switch between month/year
    const chartData = computed(() =>
        currentView.value === 'month' ? monthlyData.value : yearlyData.value
    )

    function toggleDropdown() {
        dropdownOpen.value = !dropdownOpen.value
    }

    function setView(view) {
        currentView.value = view
        dropdownOpen.value = false
    }

    const fetchChefLeaderboards = async () => {
        try {
            const response = await axios.get('/chefs/leaderboards')
            leaderboards.value = response.data  // <-- store data here
        } catch (error) {
            console.error(error)
        }
    }

    onMounted(() => {
        fetchChefLeaderboards()
    })
</script>
