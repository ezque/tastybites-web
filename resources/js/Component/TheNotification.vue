<template>
    <div class="w-full h-full flex flex-col">
        <div class="w-full h-[50px] flex border border-black items-center">
            <h2 class="ml-5">Notification</h2>
        </div>
        <div class="w-full h-full flex items-start justify-center mt-20">
            <div
                v-if="notification"
                class="w-[40%] h-[50%] flex flex-col justify-between mt-[20px] bg-[#E0E7FF] rounded-[20px]"
            >
                <div class="w-full mt-10 flex items-center justify-center">
                    <img v-if="notification.type === 'report'" alt="image" src="/public/images/Button-icon/report.png" class="w-[50px] h-[50px]"/>
                    <img v-if="notification.type === 'addPremiumRecipe'" src="/public/images/premium-icon.png" class="w-[50px] h-[50px]"/>
                    <img v-if="notification.type === 'chefApplicant'" src="/public/images/Button-icon/chef.png" class="w-[50px] h-[50px]"/>
                    <img v-if="notification.type === 'userApplicant'" src="/public/images/Button-icon/new_user3.png" class="w-[50px] h-[50px]"/>
                    <img v-if="notification.type === 'liked'" src="/public/images/Button-icon/filled_heart.png" class="w-[50px] h-[50px]"/>
                    <img v-if="notification.type === 'disliked'" src="/public/images/Button-icon/filled_dislike.png" class="w-[50px] h-[50px]"/>
                    <img v-if="notification.type === 'recipePurchased'" src="/public/images/Button-icon/payment.png" class="w-[50px] h-[50px]"/>
                    <img v-if="notification.type === 'followed'" src="/public/images/Button-icon/follow.png" class="w-[50px] h-[50px]"/>
                    <img v-if="notification.type === 'recipeBlocked'" src="/public/images/Button-icon/block.png" class="w-[50px] h-[50px]"/>
                    <img v-if="notification.type === 'chefApproved'" src="/public/images/Button-icon/chef.png" class="w-[50px] h-[50px]"/>
                    <img v-if="notification.type === 'premiumRecipeApproved'" src="/public/images/Button-icon/approved.png" class="w-[50px] h-[50px]"/>
                    <img v-if="notification.type === 'premiumRecipeDeclined'" src="/public/images/Button-icon/approved.png" class="w-[50px] h-[50px]"/>
                    <img v-if="notification.type === 'recipePurchaseApproved'" src="/public/images/Button-icon/payment.png" class="w-[50px] h-[50px]"/>
                    <img v-if="notification.type === 'recipePurchaseDenied'" src="/public/images/Button-icon/payment.png" class="w-[50px] h-[50px]"/>
                    <img v-if="notification.type === 'newRecipeAdded'" src="/public/images/Button-icon/RecipeFooter.png" class="w-[50px] h-[50px]"/>
                </div>
                <div
                    class="w-full flex items-center justify-center mb-20"
                >
                    <p v-if="notification.type === 'report'" class="justify-center" ><strong>@{{ notification.sender.user_info.userName }} </strong> {{ notification.message }}</p>
                    <p v-else-if="notification.type === 'chefApplicant'"><strong>@{{ notification.sender.user_info.userName }}</strong> has signed up as a new chef. Review their profile.</p>
                    <p v-else-if="notification.type === 'userApplicant'">We have a new user! <strong>@{{ notification.sender.user_info.userName }}</strong> has joined the community.</p>
                    <p v-else-if="notification.type === 'addPremiumRecipe'"><strong>@{{ notification.sender.user_info.userName}}</strong> submitted a premium recipe</p>
                    <p v-else>{{ notification.message }}</p>
                </div>
                <div class="w-full flex flex-row items-center justify-end gap-2 p-2">
                    <button class="w-[140px] h-[45px] bg-red-600 rounded-[25px] text-white cursor-pointer">
                        <span>DELETE</span>
                    </button>
                    <button
                        class="w-[140px] h-[45px] mr-2 bg-blue-600 rounded-[25px] text-white cursor-pointer"
                        v-if="notification.type === 'report'"
                    >
                        <span>BLOCK</span>
                    </button>
                </div>

            </div>
            <div v-else>
                <p>No notification selected.</p>
            </div>
        </div>

    </div>
</template>

<script setup>
    import axios from "axios";

    const props = defineProps({
        notification: Object
    });


</script>
