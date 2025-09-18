<template>
    <div class="income-container">
        <div class="container-label">
            <h2>Queue</h2>
        </div>
        <div class="income-body">
            <div class="income-card">
                <div class="card-label">
                    <h2>Pending Recipe</h2>
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
                                <h1></h1>
                            </div>
                        </div>
                        <div class="table-body">
                            <div class="body-row" v-for="(purchase, index) in getRecipeDetailsAdmin.filter(p => p.status === 'pending')"  :key="purchase.id">
                                <div class="row-1">
                                    <p>{{ purchase.id }}</p>
                                </div>
                                <div class="row-2">
                                    <p>{{ new Date(purchase.created_at).toISOString().split('T')[0] }}</p>
                                </div>
                                <div class="row-3">
                                    <p>{{purchase.recipeName}}</p>
                                </div>
                                <div class="row-4">
                                    <u>{{ purchase.user.email }}</u>
                                </div>
                                <div class="row-5">
                                    <button
                                        @click="viewPurchase(purchase)"
                                    >
                                        View
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="purchase-details-container" v-if="selectedRecipe">
                    <div class="close-container">
                        <button
                            @click="closeDetails"
                        >
                            <img alt="close" src="/public/images/Button-icon/close.png"/>
                        </button>
                    </div>
                    <div class="purchase-details-body">
                        <div class="left-purchase">
                            <div class="receipt-container">
                                <img alt="receipt" :src="`/storage/${selectedRecipe.receipt_path}`"/>
                                <p>
                                Date Paid:
                                <span class="date-paid">
                                    {{
                                    new Date(selectedRecipe.created_at).toLocaleDateString("en-GB", {
                                        day: "2-digit",
                                        month: "long",
                                        year: "numeric"
                                    })
                                    }}
                                    |
                                    {{
                                    new Date(selectedRecipe.created_at).toLocaleTimeString("en-US", {
                                        hour: "2-digit",
                                        minute: "2-digit",
                                        hour12: true
                                    }).toLowerCase()
                                    }}
                                </span>
                                </p>
                            </div>
                        </div>
                        <div class="purchase-body-line"></div>
                        <div class="right-purchase">
                            <div class="purchase-recipe-details">
                                <div class="recipe-image">
                                    <div class="recipe-image-container">
                                        <img :src="`/storage/${selectedRecipe.image_path}`" alt="recipe"/>
                                    </div>

                                </div>
                                <div class="recipe-information">
                                    <h2>{{ selectedRecipe.recipeName }}</h2>
                                    <h4>{{ selectedRecipe.cuisineType }} Cuisine</h4>
                                    <h5>₱{{selectedRecipe.price}}</h5>
                                </div>
                            </div>
                            <div class="purchase-details-con">
                                <div class="purchase-details-column">
                                    <div class="purchase-label-con">
                                        <p>Email:</p>
                                    </div>
                                    <div class="purchase-details">
                                        <p>{{selectedRecipe.user.email}}</p>
                                    </div>
                                </div>
                                <div class="purchase-details-column">
                                    <div class="purchase-label-con">
                                        <p>Full Name:</p>
                                    </div>
                                    <div class="purchase-details" style="text-transform: capitalize">
                                        <p>{{selectedRecipe.user.user_info?.fullName}}</p>
                                    </div>
                                </div>
                                <div class="purchase-details-column">
                                    <div class="purchase-label-con">
                                        <p>Amount Paid:</p>
                                    </div>
                                    <div class="purchase-details">
                                        <p>₱ {{ amountPaid(selectedRecipe) }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="buttons-container">
                                <button style="background-color: #B5BFDE">
                                    DECLINE
                                </button>
                                <button style="background-color: #E0E7FF">
                                    ACCEPT
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>

    import {ref} from "vue";

    const props = defineProps({
        getRecipeDetailsAdmin: Array,
    })

    const selectedRecipe = ref(null)

    const viewPurchase = (purchase) => {
        selectedRecipe.value = purchase;
    };
    const closeDetails = () => {
        selectedRecipe.value = null;
    };

    const toNumber = (val) => {
        if (typeof val === "number") return val;
        return parseFloat(String(val).replace(/[^\d.]/g, "")) || 0;
    };

    const amountPaid = (recipe) => {
        if (!recipe) return 0;
        const price = toNumber(recipe.price);
        return (price * 0.02).toFixed(2);
    };



</script>
<style scoped>
    .income-container {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .container-label {
        width: 100%;
        height: 10%;
        display: flex;
        align-items: center;
    }
    .container-label h2 {
        font-size: 35px;
        margin-left: 20px;
        font-family: 'Poppins-Bold';
    }
    .income-body {
        width: 100%;
        height: 90%;
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
        padding: 10px;
    }
    .card-label {
        width: 100%;
        height: auto;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 15px;
        margin-bottom: -10px;
    }
    .card-label h2 {
        margin: 0;
        padding: 0;
        color: #31485B;
        font-family: 'Poppins-Bold';
        font-size: 25px;
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
        height: 9%;
        background-color: #7592AB;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        display: flex;
        flex-direction: row;
        border: 1px solid #B5BFDE;
    }
    .head-row-1,.head-row-2,.head-row-3,.head-row-4,.head-row-5,.head-row-6 {
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .head-row-1 h1,.head-row-2 h1,.head-row-3 h1,.head-row-4 h1,.head-row-5,.head-row-6 h1{
        color: black;
        font-size: 14px;
        font-family: 'Poppins-Bold';
    }
    .head-row-1, .row-1{
        width: 8%;
    }
    .head-row-2, .row-2{
        width: 15%;
    }
    .head-row-3, .row-3{
        width: 30%;
    }
    .head-row-4, .row-4{
        width: 30%;
    }
    .head-row-5, .row-5 {
        width: 18%;
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
        display: flex;
        flex-direction: row;
    }
    .row-1,.row-2,.row-3,.row-4,.row-5,.row-6 {
        display: flex;
        align-items: center;
        justify-content: center;
        border: .1em solid #B5BFDE;
        font-family: 'Poppins-Regular';
        font-size: 13px;
    }
    .row-5 button{
        width: 60%;
        height: 60%;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 20px;
        border-right: 2px solid #31485B;
        border-bottom: 2px solid #31485B;
        border-top: none;
        border-left: none;
        background-color: #435F77;
        color: white;
        font-family: 'Poppins-Bold';
        font-size: 11px;
        cursor: pointer;
    }
    .purchase-details-container {
        width: 60%;
        height: 650px;
        top: 19%;
        left: 23%;
        position: absolute;
        background-color: #435F77;
        border-radius: 20px;
        display: flex;
        flex-direction: column;
        padding-left: 10px;
        padding-right: 10px;
    }
    .close-container {
        width: 100%;
        height: 100px;
        padding: 10px;
        display: flex;
        justify-content: end;
        position: absolute;
    }
    .close-container button {
        height: 35px;
        width: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        border: none;
        padding: 10px;
        cursor: pointer;
        position: absolute;
        margin-right: 10px;
        border: 1px solid black;
    }
    .close-container button img {
        width: 100%;
        height: auto;
    }
    .purchase-details-body {
        margin-top: 30px;
        height: 90%;
        width: 100%;
        display: flex;
        flex-direction: row;
        align-items: center;
    }
    .left-purchase {
        width: 40%;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .receipt-container {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: start;
        flex-direction: column;
    }
    .receipt-container img {
        width: 70%;
        height: 100%;
    }
    .receipt-container p {
        color: white;
        font-family: 'Poppins-Regular';
        font-size: 11px;
        text-align: start;
        width: 70%;
    }
    .date-paid {
        font-family: 'Poppins-BoldItalic';
        font-size: 13px;
    }
    .purchase-body-line {
        width: 4px;
        height: 90%;
        background-color: #A2B0BC;
        border-radius: 20px;
        border: 1px solid black;
    }
    .right-purchase {
        width: 60%;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .purchase-recipe-details {
        width: 100%;
        height: 32%;
        display: flex;
        flex-direction: row;
    }
    .recipe-image {
        width: 40%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .recipe-image-container {
        width: 79%;
        height: 100%;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: 40px;
    }
    .recipe-image-container img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
    }
    .recipe-information {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        width: 60%;
    }
    .recipe-information h2 {
        color: white;
        font-family: 'Poppins', sans-serif;
        font-weight: bold;
        margin: 2px;
        font-size: 2.1em;
        text-align: center;
    }
    .recipe-information h4 {
        margin: 0;
        font-size: 1.5em;
        color: #D9D9D9;
        font-style: italic;
    }
    .recipe-information h5 {
        margin: 5px;
        padding: 5px 20px 5px 20px;
        background-color: #E0E7FF;
        border-radius: 20px;
        color: #435F77;
        font-size: 1em;
        font-weight: bold;
        font-family: 'Poppins', sans-serif;
    }
    .purchase-details-con {
        width: 100%;
        height: 50%;
        display: flex;
        justify-content: center;
        flex-direction: column;
    }
    .purchase-details-column {
        width: 100%;
        height: 16%;
        display: flex;
        flex-direction: row;
    }
    .purchase-label-con {
        width: 40%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: end;
    }
    .purchase-label-con p {
        margin: 0;
        color: #D9D9D9;
    }
    .purchase-details {
        width: 60%;
        height: 100%;
        display: flex;
        align-items: center;
    }
    .purchase-details p{
        color: white;
        margin-left: 10px;
        font-size: 1em;
        font-family: 'Poppins', sans-serif;
        font-weight: bold;
    }
    .buttons-container {
        width: 100%;
        height: 10%;
        display: flex;
        align-items: start;
        justify-content: center;
        gap: 20px;
    }
    .buttons-container button {
        width: 100px;
        padding: 10px;
        border-radius: 20px;
        border: none;
        color: #435F77;
        font-weight: bold;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
    }

</style>
