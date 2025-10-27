<template>
    <div class="report-container">
        <!-- Header and Dropdown -->
        <div class="flex items-center justify-between px-2">
            <button
                class="flex items-center justify-between bg-[#D9D9D9] rounded-lg p-5 w-[400px] h-[120px] mx-5 cursor-pointer text-left hover:shadow-md transition"
            >
                <div class="flex flex-col justify-center align-center w-[80%]">
                    <p class="text-[16px] font-[Poppins-Bold] text-[#333]">TOTAL INCOME</p>
                    <p class="text-[36px] font-[Poppins-Bold] text-[#435F77] my-1">
                        ₱ {{ props.chefTotalIncome?.total ?? 0 }}.00
                    </p>
                </div>
                <div class="flex flex-col justify-center items-center w-[20%] h-full">
                    <img
                        src="/public/images/Button-icon/dashboard-sales.png"
                        alt="money icon"
                        class="w-[70px] h-[70px]"
                    />
                </div>
            </button>

            <div class="relative">
                <button class="bg-transparent border-none cursor-pointer" @click="toggleDropdown">
                    <img src="/public/images/Button-icon/option.png" alt="dropdown" class="w-3 h-auto" />
                </button>

                <div
                    v-if="dropdownOpen"
                    class="absolute top-[45px] right-0 bg-[#435F77] rounded-lg shadow-md w-[120px] flex flex-col"
                >
                    <a
                        href="#"
                        @click.prevent="setGraph('monthly')"
                        :class="[
                            'block text-white text-center font-[Poppins-Bold] py-2 hover:bg-[#31485B] transition-colors',
                            activeGraph === 'monthly' ? 'bg-[#31485B]' : ''
                        ]"
                    >Monthly</a>

                    <a
                        href="#"
                        @click.prevent="setGraph('yearly')"
                        :class="[
                            'block text-white text-center font-[Poppins-Bold] py-2 hover:bg-[#31485B] border-white',
                            activeGraph === 'yearly' ? 'bg-[#31485B]' : ''
                        ]"
                    >Yearly</a>
                </div>
            </div>
        </div>

        <!-- Graph Wrapper -->
        <div class="graph-wrapper">
            <div class="graph-box">
                <LineChart
                    v-if="activeGraph === 'monthly'"
                    :data="monthlyChartData"
                    :options="chartOptions"
                    class="w-full h-full"
                />
                <LineChart
                    v-if="activeGraph === 'yearly'"
                    :data="yearlyChartData"
                    :options="chartOptions"
                    class="w-full h-full"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    LineElement,
    PointElement,
    CategoryScale,
    LinearScale,
    Filler
} from 'chart.js'
import { Line } from 'vue-chartjs'

ChartJS.register(Title, Tooltip, Legend, LineElement, PointElement, CategoryScale, LinearScale, Filler)

const props = defineProps({
    chefTotalIncome: Object
})

const activeGraph = ref('monthly')
const dropdownOpen = ref(false)

const toggleDropdown = () => dropdownOpen.value = !dropdownOpen.value
const setGraph = (view) => {
    activeGraph.value = view
    dropdownOpen.value = false
}

const monthNames = [
    "January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December"
]

// Chart options (with Poppins font)
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
                font: { family: 'Poppins-Italic' }
            }
        },
        y: {
            beginAtZero: true,
            ticks: {
                font: {
                    family: 'Poppins-SemiBold',
                    style: 'italic'
                },
                callback: value => '₱' + value
            }
        }
    }
}

// Monthly chart with smooth gradient fill
const monthlyChartData = computed(() => {
    if (!props.chefTotalIncome?.monthly) return { labels: monthNames, datasets: [] }

    const monthlyMap = {}
    props.chefTotalIncome.monthly.forEach(item => {
        monthlyMap[item.month] = item.total
    })

    const data = monthNames.map((_, index) => monthlyMap[index + 1] || 0)

    return {
        labels: monthNames,
        datasets: [
            {
                label: 'Monthly Earnings',
                data,
                borderColor: '#435F77',
                backgroundColor: (context) => {
                    const chart = context.chart
                    const { ctx, chartArea } = chart
                    if (!chartArea) return null
                    const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top)
                    gradient.addColorStop(0, 'rgba(67, 95, 119, 0.1)')
                    gradient.addColorStop(1, 'rgba(67, 95, 119, 0.4)')
                    return gradient
                },
                fill: true,
                tension: 0.4
            }
        ]
    }
})

// Yearly chart with the same style
const yearlyChartData = computed(() => {
    if (!props.chefTotalIncome?.yearly) return { labels: [], datasets: [] }

    const yearlyMap = {}
    props.chefTotalIncome.yearly.forEach(item => yearlyMap[item.year] = item.total)

    const years = Object.keys(yearlyMap).map(Number)
    const minYear = Math.min(...years)
    const maxYear = Math.max(...years)

    const labels = []
    const data = []

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
                    const chart = context.chart
                    const { ctx, chartArea } = chart
                    if (!chartArea) return null
                    const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top)
                    gradient.addColorStop(0, 'rgba(144, 238, 144, 0.1)')
                    gradient.addColorStop(1, 'rgba(144, 238, 144, 0.4)')
                    return gradient
                },
                fill: true,
                tension: 0.4
            }
        ]
    }
})

const LineChart = Line
</script>

<style scoped>
.report-container {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    gap: 20px;
    padding: 20px;
}

/* Graph container */
.graph-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
}

.graph-box {
    width: 95%;
    height: 450px;
    background: white;
    padding: 8px;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}
</style>
