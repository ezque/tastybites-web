<template>
    <div class="report-container">
        <div class="button-container">
            <button>
                Total Income: ₱{{ props.chefTotalIncome?.total ?? 0 }}
            </button>
            <button @click="activeGraph = 'monthly'" :class="{ active: activeGraph === 'monthly' }">
                Monthly Earnings
            </button>
            <button @click="activeGraph = 'yearly'" :class="{ active: activeGraph === 'yearly' }">
                Yearly Earnings
            </button>
        </div>

        <div class="graph-container">
            <!-- Both monthly and yearly charts share the same container now -->
            <LineChart
                v-if="activeGraph === 'monthly'"
                :data="monthlyChartData"
                :options="chartOptions"
            />
            <LineChart
                v-if="activeGraph === 'yearly'"
                :data="yearlyChartData"
                :options="chartOptions"
            />
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
    LinearScale
} from 'chart.js'
import { Line } from 'vue-chartjs'

ChartJS.register(Title, Tooltip, Legend, LineElement, PointElement, CategoryScale, LinearScale)

const props = defineProps({
    chefTotalIncome: Object, // { total, monthly, yearly }
})

const activeGraph = ref('monthly')

const monthNames = [
    "January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December"
]

// Chart options
const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { display: true, labels: { font: { size: 13 } } }
    },
    scales: {
        y: {
            beginAtZero: true,
            ticks: {
                callback: value => '₱' + value
            }
        }
    }
}

// Monthly chart data
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
                borderColor: 'blue',
                backgroundColor: 'transparent',
                fill: false,
                tension: 0.3,
                pointBackgroundColor: 'blue',
                pointRadius: 4
            }
        ]
    }
})

// Yearly chart data
const yearlyChartData = computed(() => {
    if (!props.chefTotalIncome?.yearly) return { labels: [], datasets: [] }

    const yearlyMap = {}
    props.chefTotalIncome.yearly.forEach(item => yearlyMap[item.year] = item.total)

    const years = Object.keys(yearlyMap).map(Number)
    const minYear = Math.min(...years)
    const maxYear = Math.max(...years)

    const labels = []
    const data = []

    for (let year = minYear; year <= maxYear + 2; year++) { // include future 2 years
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
                backgroundColor: 'transparent',
                fill: false,
                tension: 0.3,
                pointBackgroundColor: 'green',
                pointRadius: 4
            }
        ]
    }
})

const LineChart = Line
</script>

<style scoped>
.report-container {
    width: 100%;
    height: 80%;
    display: flex;
    flex-direction: column;
    gap: 20px;
    padding: 20px;
    background: #f8fafc;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    overflow: hidden;
}

.button-container {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 12px;
}

.button-container button {
    padding: 10px 18px;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    background: #e5e7eb;
    color: #374151;
}

.button-container button:first-child {
    background: #2563eb;
    color: #fff;
    cursor: default;
    font-size: 15px;
    letter-spacing: 0.3px;
}

.button-container button:not(:first-child):hover {
    background: #2563eb;
    color: #fff;
    transform: translateY(-1px);
}

.button-container button.active {
    background: #1d4ed8;
    color: #fff;
}

.graph-container {
    flex: 1;
    padding: 20px;
    background: #ffffff;
    border-radius: 12px;
    box-shadow: inset 0 1px 4px rgba(0, 0, 0, 0.05);
    display: flex;
    justify-content: center;
    align-items: center;
    height: 600px;
    overflow-x: auto; /* enable horizontal scroll for yearly chart */
}
</style>
