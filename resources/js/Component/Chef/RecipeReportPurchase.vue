<template>
    <div class="w-full h-[80%] flex flex-col p-6 bg-gray-50 rounded-xl">

        <!-- Header -->
        <div class="mb-6">
            <h2 class="text-3xl font-['Poppins-Bold'] text-gray-800">Recipe Purchases</h2>
            <h3 class="text-xl text-gray-600 mt-5 font-['Poppins-SemiBoldItalic']">{{ recipe.recipeName }}</h3>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto rounded-lg align-center justify-center width-full">
            <table class="w-full bg-white rounded-lg overflow-hidden align-center justify-center self-center">
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
                    <td class="px-6 py-4 text-blue-600 font-['Poppins-Italic'] underline text-sm">
                        {{ p.user.email }}
                    </td>
                    <td class="px-6 py-4 text-gray-600 font-['Poppins-Regular'] text-center text-sm">
                        {{ new Date(p.purchase_at).toLocaleString() }}
                    </td>
                    <td class="px-6 py-4 rounded-lg text-xs font-['Poppins-Bold'] text-gray-800 flex justify-center">
                        <button
                            class="px-4 py-2 bg-[#435f77] text-white hover:bg-blue-600 transition-all shadow rounded-lg"
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

                    <h2 class="text-2xl font-[Poppins-Bold] mb-4 text-gray-800">Purchase Details</h2>

                    <table class="min-w-full border border-gray-200 divide-y divide-gray-200">
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td class="px-4 py-2 font-[Poppins-BoldItalic] text-sm text-gray-700">User:</td>
                                <td class="px-4 py-2 font-[Poppins-Bold] text-md">{{ selectedPurchase.user.user_info.fullName }}</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 font-[Poppins-BoldItalic] text-sm text-gray-700">Email:</td>
                                <td class="px-4 py-2 font-[Poppins-Italic] text-sm text-blue-600 underline truncate">{{ selectedPurchase.user.email }}</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 font-[Poppins-BoldItalic] text-sm text-gray-700">Phone:</td>
                                <td class="px-4 py-2 font-[Poppins-Regular] text-md">{{ selectedPurchase.phone_number || 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 font-[Poppins-BoldItalic] text-sm text-gray-700">Amount:</td>
                                <td class="px-4 py-2 font-[Poppins-Bold] text-md">₱{{ selectedPurchase.amount }}</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 font-[Poppins-BoldItalic] text-sm text-gray-700">Purchased At:</td>
                                <td class="px-4 py-2 font-[Poppins-Regular] text-sm">{{ new Date(selectedPurchase.purchase_at).toLocaleString() }}</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 font-[Poppins-BoldItalic] text-sm text-gray-700">Reference:</td>
                                <td class="px-4 py-2 font-[Poppins-Regular] text-md">{{ selectedPurchase.reference || 'N/A' }}</td>
                            </tr>
                            <tr v-if="selectedPurchase.proof_path">
                                <td class="px-4 py-2 font-[Poppins-BoldItalic] text-sm text-gray-700">Proof:</td>
                                <td class="px-4 py-2">
                                    <a
                                    :href="`/storage/${selectedPurchase.proof_path}`"
                                    target="_blank"
                                    class="text-[#435f77] underline hover:text-[#B5BFDE] font-[Poppins-Italic] text-sm"
                                    >
                                    View Proof
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>


                    <div class="mt-6 flex justify-end">
                        <button
                            class="px-5 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 shadow transition-all font-[Poppins-Bold] text-xs"
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
