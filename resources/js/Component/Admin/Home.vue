<template>
    <div class="admin-home-main-body">
        <h2>Dashboard</h2>
        <div class="admin-button-container">
            <button class="card">
                <div class="card-left">
                    <p class="label">TOTAL INCOME</p>
                    <p class="amount">₱{{props.adminTotalIncome.total}}</p>
                    <p class="growth">↑ 3.48% <span> since last month</span></p>
                </div>
                <div class="card-right">
                    <img src="/public/images/Button-icon/dashboard-sales.png" alt="money icon">
                </div>
            </button>
            <button
                @click="emit('navigate','Users')" class="card">
                <div class="card-left">
                    <p class="label">USERS</p>
                    <p class="amount">{{ props.totalCountsUsers.usersCounts }}</p>
                    <p class="growth">↑ 3.48% <span> since last month</span></p>
                </div>
                <div class="card-right-users">
                    <img src="/public/images/Button-icon/dashboars-users.png" alt="users icon">
                </div>
            </button>
            <button
                @click="emit('navigate','AdminChefs')" class="card">
                <div class="card-left">
                    <p class="label">CHEFS</p>
                    <p class="amount">{{ props.totalCountsUsers.chefsCounts }}</p>
                    <p class="growth">↑ 3.48% <span> since last month</span></p>
                </div>
                <div class="card-right">
                    <img src="/public/images/Button-icon/dashboard-chef.png" alt="chef icon">
                </div>
            </button>
            <button
                @click="emit('navigate', 'Recipes')" class="card">
                <div class="card-left">
                    <p class="label">RECIPES</p>
                    <p class="amount">{{ props.getTotalRecipeCounts.recipeCounts }}</p>
                    <p class="growth">↑ 3.48% <span> since last month</span></p>
                </div>
                <div class="card-right">
                    <img src="/public/images/Button-icon/dashboard-recipes.png" alt="recipes icon">
                </div>
            </button>
        </div>
        <div class="graph-container">
            <div class="graph-header">
                <h3 class="graph-title">Income Recap Report</h3>
                <div class="dropdown">
                    <button class="menu-btn" @click="toggleDropdown">
                        <img src="/public/images/Button-icon/option.png" alt="dropdown"></img>
                    </button>
                    <div class="dropdown-content" v-if="dropdownOpen">
                        <a href="#" @click.prevent="setView('month')">Month</a>
                        <a href="#" @click.prevent="setView('year')">Year</a>
                    </div>
                </div>
            </div>

            <div class="chart-container">
                <LineChart :data="chartData" :options="chartOptions" class="chart"/>
            </div>
        </div>

        <Footer/>
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


<style scoped>
    .admin-home-main-body{
        width: 100%;
        height: 100%;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }
    .admin-home-main-body h2{
        margin-top: 20px;
        font-size: 35px;
        margin-left: 20px;
        font-family: 'Poppins-Bold';
    }
    .admin-button-container {
        width: 100%;
        margin-top: -10px;
        display: flex;
        flex-direction: row;
        padding-bottom: 10px;
        align-items: center;
        justify-content: space-between;
    }
    .card {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #D9D9D9;
        padding: 20px;
        border-radius: 10px;
        width: 350px;
        border: none;
        cursor: pointer;
        text-align: left;
    }
    .card-left {
        display: flex;
        flex-direction: column;
        justify-content: center;
        width: 70%;
    }
    .label {
        font-size: 16px;
        font-family: 'Poppins-Bold';
        color: #333;
        margin: 0;
    }
    .amount {
        font-size: 32px;
        font-family: 'Poppins-Bold';
        color: #435F77;
        margin: 5px 0;
    }
    .growth {
        font-size: 12px;
        color: #28a745;
        margin: 0;
        font-family: 'Poppins-Bold';
    }
    .growth span {
        color: #6c757d;
        font-size: 11px;
        font-family: 'Poppins-Italic';
    }
    .card-right {
        display: flex;
        flex-direction: column;
        justify-content: center;
        width: 30%;
        height: 100%;
        align-items: center;
    }
    .card-right img {
        width: 90px;
        height: 90px;
    }
    .card-right-users img {
        width: 100px;
        height: 75px;
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
        background: #D9D9D9;
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        padding: 10px;
        display: flex;
        flex-direction: column;
        width: 80%;
        margin: 60px auto;
    }
    .graph-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 5px 10px;
    }
    .graph-title {
        font-size: 18px;
        font-family: 'Poppins-Bold';
        margin: 10px;
    }
    .dropdown {
        position: relative;
    }
    .menu-btn {
        background: none;
        border: none;
        cursor: pointer;
    }
    .menu-btn img {
        width: 8px;
        height: auto;
    }
    .dropdown-content {
        position: absolute;
        top: 35px;
        right: 5px;
        background: #435F77;
        border-radius: 10px 0 10px 10px;
        overflow: hidden;
        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        width: 100px;
        display: flex;
        flex-direction: column;
    }

    .dropdown-content a {
        display: block;
        width: 100%;
        text-align: center;
        color: white;
        text-decoration: none;
        cursor: pointer;
        font-family: 'Poppins-Bold';
        padding: 8px 0;
    }

    .dropdown-content a + a {
        border-top: 2px solid white;
        width: 80%;
        align-self: center;
    }

    .dropdown-content a:hover {
        background: #31485B;
        width: 100%;
    }
    .chart-container {
        width: 95%;
        height: 300px;
        padding: 10px;
        align-self: center;
        background-color: white;
    }
    .chart {
        width: 100%;
        height: 100%;
    }

</style>
