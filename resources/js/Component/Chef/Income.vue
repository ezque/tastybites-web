<template>
    <div class="income-container">
        <div class="income-card">
            <div class="card-label">
                <h2>Recent Transactions</h2>
            </div>
            <div class="card-body">
                <div class="table">
                    <div class="table-head">
                        <div class="head-row-1">
                            <h1>ID</h1>
                        </div>
                        <div class="head-row-2">
                            <h1>Date</h1>
                        </div>
                        <div class="head-row-3">
                            <h1>Recipe</h1>
                        </div>
                        <div class="head-row-4">
                            <h1>Email</h1>
                        </div>
                        <div class="head-row-5">
                            <h1>Action</h1>
                        </div>
                    </div>
                    <div class="table-body">
                        <div class="body-row" v-for="i in 15" :key="i">
                            <div class="row-1">

                            </div>
                            <div class="row-2">

                            </div>
                            <div class="row-3">

                            </div>
                            <div class="row-4">

                            </div>
                            <div class="row-5">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">

</script>

<style scoped>
    .income-container {
        width: 100%;
        height: 100vh;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .income-card {
        width: 85%;
        height: 650px;
        display: flex;
        flex-direction: column;
        margin-bottom: 6%;
        background-color: #B5BFDE;
        border-radius: 20px;
        padding: 20px;
    }
    .card-label {
        width: 100%;
        height: auto;
        display: flex;
        align-items: center;
        justify-content: center;
        padding-top: 2px;
        padding-bottom: 2px;
    }
    .card-label h2 {
        font-weight: bold;
        margin: 0;
        padding: 0;
        color: #31485B;
        font-family: 'Poppins', sans-serif;
    }
    .card-body {
        height: 100%;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .table {
        width: 90%;
        height: 90%;
        border-radius: 20px;
        background-color: #E0E7FF;
        display: flex;
        flex-direction: column;
    }
    .table-head {
        width: 100%;
        height: 10%;
        background-color: #7592AB;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        display: flex;
        flex-direction: row;
    }
    .head-row-1,.head-row-2,.head-row-3,.head-row-4,.head-row-5 {
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .head-row-1 h1,.head-row-2 h1,.head-row-3 h1,.head-row-4 h1,.head-row-5 h1{
        color: black;
        font-size: 1em;
        font-family: 'Poppins', sans-serif;
        margin-top: 20px;
    }
    .head-row-1{
        width: 10%;
    }
    .head-row-2{
        width: 25%;
    }
    .head-row-3{
        width: 25%;
    }
    .head-row-4{
        width: 25%;
    }
    .head-row-5 {
        width: 15%;
    }
    .table-body {
        overflow-y: auto;
        overflow-x: hidden;
        max-height: 90%;
        width: 100%;
    }
    .body-row {
        width: 100%;
        height: 50px;
        border: 1px solid black;
    }




</style>
