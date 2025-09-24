<template>
    <div class="admin-home-main-body">
        <h2>Dashboard</h2>
        <div class="admin-button-container">
            <button>
                total
            </button>
            <button
                @click="emit('navigate','Users')">
                user
            </button>
            <button
                @click="emit('navigate','AdminChefs')">
                chef
            </button>
            <button
                @click="emit('navigate', 'Recipes')">
                recipes
            </button>
        </div>
        <div class="graph-container">
            <div class="graph-header">
                <div class="admin-select-container">
                    <label for="viewSelect">Select View:</label>
                    <select id="viewSelect" v-model="currentView" @change="updateChart">
                        <option value="month">Per Month</option>
                        <option value="year">Per Year</option>
                    </select>
                </div>
            </div>

            <div class="chart-container">
                <LineChart :data="chartData" :options="chartOptions" class="chart"/>
            </div>

        </div>
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

    ChartJS.register(Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement, Filler)

    const props = defineProps({
        adminTotalIncome: Object
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
            borderColor: 'rgba(54, 162, 235, 1)',
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            fill: true,
            tension: 0.4
        }]
    }

    const yearlyData = {
        labels: props.adminTotalIncome?.yearly?.map(r => r.year) || [],
        datasets: [{
            label: 'Revenue per Year',
            data: props.adminTotalIncome?.yearly?.map(r => r.total) || [],
            borderColor: 'rgba(75, 192, 192, 1)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            fill: true,
            tension: 0.4
        }]
    }

    // Reactive chart
    const chartData = ref(monthlyData)

    // Chart options
    const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'top' },
        }
    }

    function updateChart() {
        chartData.value = currentView.value === 'month' ? monthlyData : yearlyData
    }
</script>


<style scoped>
    .admin-home-main-body{
        width: 100%;
        height: 100%;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }
    .admin-button-container {
        width: 100%;
        display: flex;
        flex-direction: row;
        padding-top: 20px;
        padding-bottom: 20px;
        align-items: center;
        justify-content: space-between;
    }
    .admin-button-container button{
        width: 23%;
        height: 140px;
        margin-left: 20px;
        margin-right: 20px;
        cursor: pointer;
    }
    .graph-container {
        width: 100%;
        height: 65%;
        display: flex;
        align-items: center;
        flex-direction: column;
    }
    .graph-header {
        width: 100%;
        height: 10%;
        border: 1px solid black;
    }
    .chart-container {
        width: 90%;
        height: 90%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .chart {
        width: 100%;
        height: 80%;
    }
</style>
