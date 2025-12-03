<template>
    <div class="flex flex-col w-full h-[97%] overflow-auto **bg-[#F4F7FA]**">
        <h2 class="mt-5 ml-5 text-[35px] font-[Poppins-Bold] **text-gray-800**">Dashboard</h2>

        <div class="flex flex-row flex-wrap items-center justify-center mt-5 pb-2 w-full gap-5">
            <button class="flex items-center justify-between bg-[#D9D9D9] rounded-xl p-5 w-[380px] h-[130px] cursor-pointer text-left **shadow-xl hover:shadow-2xl** transition **border-b-4 border-[#28A745]**">
                <span class="flex flex-col justify-center align-center w-[70%]">
                    <span class="text-[16px] font-[Poppins-Bold] text-gray-500 m-0">TOTAL INCOME</span>
                    <span class="text-[36px] font-[Poppins-Bold] **text-[#28A745]** my-1">₱{{ props.adminTotalIncome.total }}</span>
                </span>
                <span class="flex flex-col justify-center items-center w-[30%] h-full">
                    <img src="/public/images/Button-icon/dashboard-sales.png" alt="money icon" class="w-[90px] h-[90px] opacity-80" />
                </span>
            </button>

            <button @click="emit('navigate','Users')" class="flex items-center justify-between bg-[#D9D9D9] rounded-xl p-5 w-[390px] h-[130px] cursor-pointer text-left **shadow-xl hover:shadow-2xl** transition **border-b-4 border-[#435F77]**">
                <span class="flex flex-col justify-center w-[70%]">
                    <span class="text-[16px] font-[Poppins-Bold] text-gray-500 m-0">USERS</span>
                    <span class="text-[32px] font-[Poppins-Bold] text-[#435F77] my-1">{{ props.totalCountsUsers.usersCounts }}</span>
                    <span class="flex items-center gap-2">
                        <img src="/public/images/Button-icon/approved.png" alt="approved" class="inline w-3 h-auto ml-1"/>
                        <span class="text-[#6c757d] text-[11px] font-[Poppins-Italic]">For more information</span>
                    </span>
                </span>
                <span class="flex flex-col justify-center items-center w-[30%] h-full">
                    <img src="/public/images/Button-icon/dashboars-users.png" alt="users icon" class="w-[100px] h-[75px] opacity-80" />
                </span>
            </button>

            <button @click="emit('navigate','AdminChefs')" class="flex items-center justify-between bg-[#D9D9D9] rounded-xl p-5 w-[390px] h-[130px] cursor-pointer text-left **shadow-xl hover:shadow-2xl** transition **border-b-4 border-[#435F77]**">
                <span class="flex flex-col justify-center w-[70%]">
                    <span class="text-[16px] font-[Poppins-Bold] text-gray-500 m-0">CHEFS</span>
                    <span class="text-[32px] font-[Poppins-Bold] text-[#435F77] my-1">{{ props.totalCountsUsers.chefsCounts }}</span>
                    <span class="flex items-center gap-2">
                        <img src="/public/images/Button-icon/approved.png" alt="approved" class="inline w-3 h-auto ml-1"/>
                        <span class="text-[#6c757d] text-[11px] font-[Poppins-Italic]">For more information</span>
                    </span>
                </span>
                <span class="flex flex-col justify-center items-center w-[30%] h-full">
                    <img src="/public/images/Button-icon/dashboard-chef.png" alt="chef icon" class="w-[90px] h-[90px] opacity-80" />
                </span>
            </button>

            <button @click="emit('navigate','Recipes')" class="flex items-center justify-between bg-[#D9D9D9] rounded-xl p-5 w-[380px] h-[130px] cursor-pointer text-left **shadow-xl hover:shadow-2xl** transition **border-b-4 border-[#435F77]**">
                <span class="flex flex-col justify-center w-[70%]">
                    <span class="text-[16px] font-[Poppins-Bold] text-gray-500 m-0">RECIPES</span>
                    <span class="text-[32px] font-[Poppins-Bold] text-[#435F77] my-1">{{ props.getTotalRecipeCounts.recipeCounts }}</span>
                    <span class="flex items-center gap-2">
                        <img src="/public/images/Button-icon/approved.png" alt="approved" class="inline w-3 h-auto ml-1"/>
                        <span class="text-[#6c757d] text-[11px] font-[Poppins-Italic]">For more information</span>
                    </span>
                </span>
                <span class="flex flex-col justify-center items-center w-[30%] h-full">
                    <img src="/public/images/Button-icon/dashboard-recipes.png" alt="recipes icon" class="w-[90px] h-[90px] opacity-80" />
                </span>
            </button>
        </div>

        <div class="flex flex-col bg-[#D9D9D9] rounded-xl **shadow-xl** p-6 w-[80%] mt-[60px] mx-auto">
            <div class="flex items-center justify-between px-2 pb-2 border-b border-gray-100">
                <h3 class="text-[18px] font-[Poppins-Bold] m-2 **text-gray-800**">Income Recap Report</h3>
                <div class="relative">
                    <button class="bg-[#D9D9D9] hover:bg-gray-200 text-gray-700 py-1 px-3 rounded text-sm transition" @click="toggleDropdown">
                        {{ currentView === 'month' ? 'Monthly' : 'Yearly' }} <span class="ml-1">&#9662;</span>
                    </button>
                    <div v-if="dropdownOpen" class="absolute top-[35px] right-[5px] bg-[#435F77] rounded-lg shadow-xl w-[100px] flex flex-col z-10">
                        <a href="#" @click.prevent="setView('month')" class="block text-white text-center font-[Poppins-Bold] py-2 hover:bg-[#31485B] rounded-t-lg">Month</a>
                        <a href="#" @click.prevent="setView('year')" class="block text-white text-center font-[Poppins-Bold] py-2 hover:bg-[#31485B] border-t-2 border-gray-50 rounded-b-lg">Year</a>
                    </div>
                </div>
            </div>

            <div class="w-full h-[300px] p-2 self-center bg-white">
                <LineChart :data="chartData" :options="chartOptions" class="w-full h-full"/>
            </div>
        </div>

        <div class="h-auto w-[100%] py-5 mt-10 flex flex-col ">
            <h2 class="mt-1 ml-5 text-[35px] font-[Poppins-Bold] text-[#435F77] text-center">🏅 LEADERBOARDS 🏅</h2>
            <div class="w-[80%] h-auto p-10 flex flex-col **gap-2** bg-[#B5BFDE] self-center rounded-xl mt-0 mb-10 shadow-xl">

                <div class="p-4 flex items-center justify-between font-[Poppins-Bold] text-[#435F77]">
                    <span class="w-10 text-center text-s">RANK</span>

                    <span class="w-50 text-center text-s flex-grow mx-4">CHEF NAME</span>

                    <span class="w-40 text-s text-center">INCOME</span>
                </div>
                <div
                    v-for="(chef, index) in leaderboards"
                    :key="chef.id"
                    :class="[
                        'p-4 flex items-center justify-between rounded-lg shadow-md',
                        index % 2 === 0 ? 'bg-[#E0E7FF]' : 'bg-[#435F77]', // Zebra striping
                        'hover:shadow-lg transition duration-300'
                    ]"
                >
                    <span :class="[
                        'text-xl font-[Poppins-Bold] w-8 text-center w-10',
                            index === 0 ? 'text-[#FFD700]' :  
                            index === 1 ? 'text-[#C0C0C0]' :     
                            index === 2 ? 'text-[#CD7F32]' : 'text-[#B8C2CC] text-xs'
                    ]">
                        #{{ index + 1 }}
                    </span>

                    <span class="text-lg font-[Poppins-BoldItalic] **text-gray-500** flex-grow mx-4 truncate w-50">
                        {{ chef.user_info?.userName || 'No Name' }}
                    </span>

                    <span class="text-lg font-[Poppins-Bold] **text-[#28A745]** w-40 flex justify-end">
                        ₱ {{ chef.total_revenue?.toLocaleString('en-PH', {minimumFractionDigits: 0, maximumFractionDigits: 0}) ?? 0 }}.00
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
