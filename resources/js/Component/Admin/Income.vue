<template>
    <div class="income-container">
        <div class="container-label">
            <h2>Queue</h2>

            <!-- Search -->
            <div class="flex items-center bg-[#F5F5F5] px-1 py-1 mr-5 rounded-full border-r border-[#B5BFDE] border-b-[3px] border-b-[#B5BFDE]">
                <input
                    placeholder="Search Queue"
                    class="border-none outline-none w-[200px] px-5 py-2 bg-[#435F77] rounded-full font-[Poppins-Italic] text-white"
                />
                <img src="/public/images/Button-icon/search.png" alt="icon" class="w-[20px] h-[20px] ml-1 cursor-pointer" />
            </div>
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
                                    <h5>₱ {{selectedRecipe.price}}.00</h5>
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
                                        <p>User Name:</p>
                                    </div>
                                    <div class="purchase-details" style="text-transform: capitalize">
                                        <p>@{{selectedRecipe.user.user_info?.userName}}</p>
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
                                <button style="background-color: #B5BFDE" @click="updateRecipeStatus(selectedRecipe.id, 'declined')">
                                    DECLINE
                                </button>
                                <button style="background-color: #E0E7FF"  @click="updateRecipeStatus(selectedRecipe.id, 'active')">
                                    ACCEPT
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <Footer/>
    </div>
</template>
<script setup>
    import axios from "axios";
    import {ref} from "vue";
    import Footer from "@/Component/Footer.vue";

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
    const updateRecipeStatus = async (id, status) => {
        try {
            const res = await axios.post(`/recipes/${id}/status`, { status });
            alert(res.data.message);

            if (selectedRecipe.value) {
                selectedRecipe.value.status = status;
                selectedRecipe.value = null;
            }

        } catch (error) {
            console.error(error);
            alert("Failed to update recipe status.");
        }
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
        justify-content: space-between;
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
        text-transform: uppercase;
    }
    .head-row-1,.head-row-2,.head-row-3,.head-row-4,.head-row-5{
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .head-row-1 h1,.head-row-2 h1,.head-row-3 h1,.head-row-4 h1,.head-row-5{
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
    .row-1,.row-2, .row-3,.row-5{
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Poppins-Bold';
        font-size: 13px;
        border-bottom: .2em solid #B5BFDE;
    }
    .row-2, .row-3, .row-4, .row-5 {
        border-left: .2em solid #B5BFDE;
    }
    .row-4 {
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Poppins-Italic';
        font-size: 13px;
        border-bottom: .2em solid #B5BFDE;
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
        font-size: 12px;
        cursor: pointer;
    }
    .row-5 button:hover {
        transform: scale(1.1);
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
        height: 30px;
        width: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        border: none;
        cursor: pointer;
        position: absolute;
        right: 20px;
        top: 10px;
        background-color: none;
    }
    .close-container button img {
        height: 30px;
        width: 30px;
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
        width: 70%;
        height: 90%;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: 40px;
        border: #31485B solid 1px;
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
        gap: 5px;
    }
    .recipe-information h2 {
        color: white;
        font-family: 'Poppins-Bold';
        margin: 2px;
        font-size: 30px;
        text-align: center;
    }
    .recipe-information h4 {
        margin: 0;
        font-size: 16px;
        color: #D9D9D9;
        font-family: 'Poppins-Italic';
    }
    .recipe-information h5 {
        margin: 5px;
        padding: 5px 25px 5px 25px;
        background-color: #E0E7FF;
        border-radius: 20px;
        color: #435F77;
        font-size: 20px;
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
        justify-content: end
    }
    .purchase-label-con p {
        margin: 0;
        color: #D9D9D9;
        font-size: 15px;
        font-family: 'Poppins-Regular';
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
        font-size: 20px;
        font-family: 'Poppins-BoldItalic';
    }
    .buttons-container {
        width: 100%;
        display: flex;
        align-items: start;
        justify-content: center;
        gap: 20px;
        margin-top: 10px;
    }
    .buttons-container button {
        padding: 10px 40px 10px 40px;
        border-radius: 30px;
        border: none;
        color: #435F77;
        font-family: 'Poppins-Bold';
        font-size: 13px;
        cursor: pointer;
        box-shadow: 4px 4px 12px rgb(0, 0, 0, 0.3);
        border-right: #AFADAD solid 1px;
    }
    .buttons-container button:hover {
        transform: scale(1.1);
    }


</style>
