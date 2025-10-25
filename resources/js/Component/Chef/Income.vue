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
                        <div class="body-row" v-for="(purchase, index) in purchases.filter(p => p.status === 'pending')"  :key="purchase.id">
                            <div class="row-1">
                                <p>{{ purchase.id }}</p>
                            </div>
                            <div class="row-2">
                                <p>{{ new Date(purchase.created_at).toISOString().split('T')[0] }}</p>
                            </div>
                            <div class="row-3">
                                <p>{{purchase.recipe.recipeName}}</p>
                            </div>
                            <div class="row-4">
                                <p>{{ purchase.user.email }}</p>
                            </div>
                            <div class="row-5">
                                <button
                                    @click="viewPurchase(purchase)"
                                >
                                    VIEW
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
                        <img alt="receipt" :src="`/storage/${selectedRecipe.proof_path}`" />

                        <div class="date-paid">
                            Date Paid:
                            <span class="date-info">
                            {{
                                new Date(selectedRecipe.created_at).toLocaleDateString("en-US", {
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
                                })
                            }}
                            </span>
                        </div>
                    </div>

                    </div>
                   <div class="purchase-body-line"></div>
                    <div class="right-purchase">
                        <div class="purchase-recipe-details">
                            <div class="recipe-image">
                                <div class="recipe-image-container">
                                    <img :src="`/storage/${selectedRecipe.recipe.image_path}`" alt="recipe"/>
                                </div>

                            </div>
                            <div class="recipe-information">
                                <h2>{{selectedRecipe.recipe.recipeName}}</h2>
                                <h4>{{selectedRecipe.recipe.cuisineType}} Cuisine</h4>
                                <h5>₱  {{selectedRecipe.recipe.price}}.00</h5>
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
                                    <p>Phone Number:</p>
                                </div>
                                <div class="purchase-details">
                                    <p>{{selectedRecipe.phone_number}}</p>
                                </div>
                            </div>
                            <div class="purchase-details-column">
                                <div class="purchase-label-con">
                                    <p>Reference Number:</p>
                                </div>
                                <div class="purchase-details">
                                    <p>{{selectedRecipe.reference}}</p>
                                </div>
                            </div>
                            <div class="purchase-details-column">
                                <div class="purchase-label-con">
                                    <p>Amount Paid:</p>
                                </div>
                                <div class="purchase-details">
                                    <p>₱  {{selectedRecipe.amount}}.00</p>
                                </div>
                            </div>
                        </div>
                        <div class="buttons-container">
                            <button style="background-color: #B5BFDE"  @click="updatePurchaseStatus(selectedRecipe.id, 'denied')">
                                DECLINE
                            </button>
                            <button style="background-color: #E0E7FF" @click="updatePurchaseStatus(selectedRecipe.id, 'confirmed')">
                                ACCEPT
                            </button>
                        </div>
                    </div>
               </div>
            </div>
        </div>
        <Footer class="ml-35" />
    </div>

</template>

<script setup>
    import { ref } from "vue";
    import axios from "axios";
    import Footer from "@/Component/Footer.vue";

    const props = defineProps({
        purchases: Array
    });

    const selectedRecipe = ref(null);

    const viewPurchase = (purchase) => {
        selectedRecipe.value = purchase;
    };

    const closeDetails = () => {
        selectedRecipe.value = null;
    };

    const updatePurchaseStatus = async (id, status) => {
        try {
            const response = await axios.post(`/purchase-status/${id}`, {
                status: status
            });
            selectedRecipe.value.status = status;
            alert(response.data.message);
            selectedRecipe.value = null;
        } catch (error) {
            console.error(error);
            alert(`Failed to ${status} purchase.`);
        }
    };

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
    }
    .card-label h2 {
        margin: 0;
        padding: 0;
        color: #31485B;
        font-size: 28px;
        font-family: 'Poppins-Bold';
    }
    .card-body {
        height: 100%;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .table {
        width: 95%;
        height: 95%;
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
        font-size: .9em;
        font-family: 'Poppins-Bold';
    }
    .head-row-1, .row-1{
        width: 5%;
    }
    .head-row-2, .row-2{
        width: 15%;
    }
    .head-row-3, .row-3{
        width: 35%;
    }
    .head-row-4, .row-4{
        width: 30%;
    }
    .head-row-5, .row-5 {
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
        display: flex;
        flex-direction: row;
    }
    .row-1,.row-2,.row-3,.row-4,.row-5 {
        border-bottom: 2px solid #B5BFDE;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .row-1,.row-2,.row-3,.row-4 {
        border-right: 2px solid #B5BFDE;
        font-size: .9em;
    }
    .row-1,.row-2 {
        font-family: 'Poppins-Regular';
    }
    .row-3 {
        font-family: 'Poppins-BoldItalic';
    }
    .row-4 {
        font-family: 'Poppins-Italic';
    }
    .row-5 button{
        width: 50%;
        height: 60%;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 20px;
        border: none;
        background-color: #435F77;
        color: white;
        font-family: 'Poppins-Bold';
        font-size: .7em;
        cursor: pointer;
        box-shadow: #AFADAD 1px 2px 3px;
    }
    .row-5 button:hover {
        background-color: #31485B;
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
        position: relative;
    }
    .close-container button {
        height: 60px;
        width: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        border: none;
        padding: 10px;
        cursor: pointer;
        position: absolute;
        margin-right: -25px;
        margin-top: -15px;
    }
    .close-container button img {
        width: 100%;
        height: auto;
    }
    .purchase-details-body {
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
        height: 90%;
        display: flex;
        align-items: center;
        justify-content: start;
        flex-direction: column;
    }
    .receipt-container img {
        width: 80%;
        height: 95%;
        object-fit: contain; 
    }
    .date-paid {
        margin: 5px 0 0 -70px;
        color: white;
        font-family: 'Poppins-Regular';
        font-size: .8em;
    }
    .date-info {
        margin: 5px 40px 0 0;
        color: white;
        font-family: 'Poppins-BoldItalic';
        margin-left: 6px;
    }
    .purchase-body-line {
        width: 4px;
        height: 95%;
        background-color: #A2B0BC;
        border-radius: 20px;
        margin-bottom: 90px;
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
        font-family: 'Poppins-Bold';
        margin: 2px;
        font-size: 2em;
        text-align: center;
    }
    .recipe-information h4 {
        margin: 0;
        font-size: 1em;
        color: #D9D9D9;
        font-family: 'Poppins-Italic';
    }
    .recipe-information h5 {
        margin: 5px;
        margin-top: 10px;
        padding: 5px 25px 5px 25px;
        background-color: #E0E7FF;
        border-radius: 20px;
        color: #435F77;
        font-size: 1em;
        font-family: 'Poppins-Bold';
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
        font-family: 'Poppins-Regular';
        font-size: .8em;
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
        font-family: 'Poppins-BoldItalic';
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
        padding: 5px 25px 5px 25px;
        border-radius: 20px;
        border: none;
        color: #435F77;
        font-weight: bold;
        font-family: 'Poppins-Bold';
        cursor: pointer;
        box-shadow: #31485B 1px 4px 6px;
        font-size: .8em;
    }






</style>
