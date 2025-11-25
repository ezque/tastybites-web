<template>
    <div class="w-full h-[80%] flex flex-col p-6 bg-gray-50 rounded-xl">

        <!-- Header -->
        <div class="mb-6">
            <h2 class="text-3xl font-['Poppins-Bold'] text-gray-800">Recipe Purchases</h2>
            <h3 class="text-xl text-gray-600 mt-5 font-['Poppins-SemiBoldItalic']">{{ recipe.recipeName }}</h3>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto rounded-lg align-center justify-center width-full">
            <table class="w-[80%] bg-white rounded-lg overflow-hidden align-center justify-center">
                <thead class="bg-[#B5BFDE] border-b border-gray-200 align-center">
                <tr>
                    <th class="px-6 py-3 text-sm font-['Poppins-Bold'] text-gray-700">User</th>
                    <th class="px-6 py-3 text-sm font-['Poppins-Bold'] text-gray-700">Email</th>
                    <th class="px-6 py-3 text-sm font-['Poppins-Bold'] text-gray-700">Purchased At</th>
                    <th class="px-6 py-3 text-sm font-['Poppins-Bold'] text-gray-700">Action</th>
                </tr>
                </thead>
                <tbody>
                <tr
                    v-for="p in purchasers"
                    :key="p.id"
                    class="border-b border-gray-200 hover:bg-gray-50 transition-colors"
                >
                    <td class="px-6 py-4 font-['Poppins-Bold'] text-gray-800 border-left border-#afadad-20">
                        {{ p.user.user_info.fullName || 'N/A' }}
                    </td>
                    <td class="px-6 py-4 text-gray-600 text-sm">
                        {{ p.user.email }}
                    </td>
                    <td class="px-6 py-4 text-gray-600 text-sm">
                        {{ new Date(p.purchase_at).toLocaleString() }}
                    </td>
                    <td class="px-6 py-4">
                        <button
                            class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-all shadow"
                            @click="openModal(p)"
                        >
                            View
                        </button>
                    </td>
                </tr>

                <!-- Empty state -->
                <tr v-if="!loading && purchasers.length === 0">
                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                        No purchases yet.
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <!-- Loading & Error -->
        <div v-if="loading" class="text-center py-4 text-gray-500">Loading...</div>
        <div v-if="error" class="text-center py-4 text-red-500">{{ error }}</div>

        <!-- Modal -->
        <transition name="fade-scale">
            <div
                v-if="showModal"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
            >
                <div
                    class="bg-white rounded-xl shadow-xl w-[90%] max-w-lg p-6 relative overflow-y-auto max-h-[80vh] transform transition-transform duration-300 scale-95"
                >
                    <button
                        class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 text-lg"
                        @click="closeModal"
                    >
                        ✖
                    </button>

                    <h2 class="text-2xl font-bold mb-4 text-gray-800">Purchase Details</h2>

                    <div class="flex flex-col gap-3">
                        <p>
                            <span class="font-semibold text-gray-700">User:</span>
                            {{ selectedPurchase.user.user_info.fullName }}
                        </p>
                        <p>
                            <span class="font-semibold text-gray-700">Email:</span>
                            {{ selectedPurchase.user.email }}
                        </p>
                        <p>
                            <span class="font-semibold text-gray-700">Phone:</span>
                            {{ selectedPurchase.phone_number || 'N/A' }}
                        </p>
                        <p>
                            <span class="font-semibold text-gray-700">Amount:</span>
                            ₱{{ selectedPurchase.amount }}
                        </p>
                        <p>
                            <span class="font-semibold text-gray-700">Purchased At:</span>
                            {{ new Date(selectedPurchase.purchase_at).toLocaleString() }}
                        </p>
                        <p>
                            <span class="font-semibold text-gray-700">Reference:</span>
                            {{ selectedPurchase.reference || 'N/A' }}
                        </p>
                        <p v-if="selectedPurchase.proof_path">
                            <span class="font-semibold text-gray-700">Proof:</span>
                            <a
                                :href="`/storage/${selectedPurchase.proof_path}`"
                                target="_blank"
                                class="text-blue-500 underline hover:text-blue-700"
                            >
                                View Proof
                            </a>
                        </p>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button
                            class="px-5 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 shadow transition-all"
                            @click="closeModal"
                        >
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </transition>

    </div>
</template>

<script setup>
    import { ref, onMounted } from "vue";
    import axios from "axios";

    const props = defineProps({ recipe: Object });

    const purchasers = ref([]);
    const loading = ref(true);
    const error = ref(null);

    const showModal = ref(false);
    const selectedPurchase = ref(null);

    const fetchWhoPurchase = async () => {
        try {
            const response = await axios.get(`/who-purchase/${props.recipe.id}`);
            purchasers.value = response.data.purchases;
        } catch (err) {
            console.error(err);
            error.value = "Failed to load data";
        } finally {
            loading.value = false;
        }
    };

    onMounted(fetchWhoPurchase);

    const openModal = (purchase) => {
        selectedPurchase.value = purchase;
        showModal.value = true;
    };

    const closeModal = () => {
        showModal.value = false;
        selectedPurchase.value = null;
    };
</script>

<style>
    /* Modal transition */
    .fade-scale-enter-active,
    .fade-scale-leave-active {
        transition: all 0.3s ease;
    }
    .fade-scale-enter-from,
    .fade-scale-leave-to {
        opacity: 0;
        transform: scale(0.95);
    }
</style>
