<template>
    <div class="flex flex-col w-full h-full overflow-hidden bg-[#f6f8fa]">
        <!-- Header -->
        <div class="flex justify-between items-center px-8 mt-8">
            <h2 class="text-[32px] font-[Poppins-Bold] text-gray-800 tracking-wide">
                Reports
            </h2>
            <span class="text-sm font-['Poppins-Regular'] text-gray-500">Total Reports: {{ allReports?.length || 0 }}</span>
        </div>

        <!-- Table Container -->
        <div class="flex flex-row flex-wrap justify-center mt-6 mb-10 w-full px-6">
            <div class="w-[95%] h-[100%] flex flex-col rounded-lg overflow-hidden shadow-md">
                <table class="min-w-full text-sm text-gray-700">
                    <thead class="bg-gradient-to-r bg-[#7592AB] text-black uppercase">
                    <tr>
                        <th class="py-4 px-6 text-center font-['Poppins-Bold']">ID</th>
                        <th class="py-4 px-6 text-center font-['Poppins-Bold']">Reporter</th>
                        <th class="py-4 px-6 text-center font-['Poppins-Bold']">Reported</th>
                        <th class="py-4 px-6 text-center font-['Poppins-Bold']">Reason</th>
                        <th class="py-4 px-6 text-center font-['Poppins-Bold']">Status</th>
                        <th class="py-4 px-6 text-center font-['Poppins-Bold']">Action</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr
                        v-for="report in allReports"
                        :key="report.id"
                        class="bg-[#E0E7FF] hover:bg-[white] transition duration-200 border-b-2 border-[#B5BFDE] last:border-none"
                    >
                        <!-- ID -->
                        <td class="py-4 px-6 text-center text-sm font-[Poppins-Regular] border-r-2 border-[#B5BFDE] text-black">
                            {{ report.id }}
                        </td>

                        <!-- Reporter -->
                        <td class="py-4 px-6 text-left font-[Poppins-Regular] border-r-2 border-[#B5BFDE] text-black">
                            <span>
                            {{ report.reporter?.user_info?.fullName || 'Unknown' }}
                            </span>
                        </td>

                        <!-- Reported Entity -->
                        <td class="py-4 px-6 text-center font-[Poppins-Bold] border-r-2 border-[#B5BFDE] text-black">
                            <span
                                :class="report.reportedUserID ? 'text-blue-800' : 'text-green-800'"
                            >
                            {{ reported(report) }}
                            </span>
                        </td>

                        <!-- Reason -->
                        <td class="py-4 px-6 text-left font-[Poppins-Regular] border-r-2 border-[#B5BFDE] text-black max-w-[200px] truncate">
                            {{ report.reason }}
                        </td>

                        <!-- Status -->
                        <td class="py-4 px-6 text-center font-[Poppins-BoldItalic] border-r-2 border-[#B5BFDE] text-black">
                            <span
                                class="px-5 py-2 rounded-full text-xs capitalize"
                                :class="{
                                'bg-yellow-100 text-yellow-700': report.status.toLowerCase() === 'pending',
                                'bg-green-100 text-green-700': report.status.toLowerCase() === 'reviewed',
                                'bg-red-100 text-red-700': report.status.toLowerCase() === 'dismissed'
                            }"
                            >
                            {{ report.status }}
                            </span>
                        </td>

                        <!-- Actions -->
                        <td class="py-4 px-6 text-center space-x-2">
                            <!-- Show action buttons only if pending -->
                            <template v-if="report.status.toLowerCase() === 'pending'">
                                <button
                                    @click="updateStatus(report, 'reviewed')"
                                    class="bg-green-500 hover:bg-green-600 text-white py-1.5 px-3 rounded-md text-sm font-['Poppins-Bold'] transition duration-200"
                                >
                                    Review
                                </button>
                                <button
                                    @click="updateStatus(report, 'dismissed')"
                                    class="bg-red-500 hover:bg-red-600 text-white py-1.5 px-3 rounded-md text-sm font-['Poppins-Bold'] transition duration-200"
                                >
                                    Dismiss
                                </button>
                            </template>

                            <!-- If already responded -->
                            <template v-else>
                                <button
                                    disabled
                                    class="bg-gray-400 text-white py-1.5 px-3 rounded-md text-sm font-['Poppins-Bold'] cursor-not-allowed opacity-70"
                                >
                                    Responded
                                </button>
                            </template>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <!-- Empty State -->
                <div
                    v-if="!allReports?.length"
                    class="text-center text-gray-500 py-10 text-sm italic"
                >
                    No reports available.
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import axios from "axios";
    import { ref } from "vue";

    const props = defineProps({
        allReports: Array,
    });

    const emit = defineEmits(["navigate"]);

    const allReports = ref(props.allReports || []);

    // Display the entity that was reported (user or recipe)
    const reported = (report) => {
        if (report.reportedUserID && report.reportedUser) {
            return report.reportedUser?.user_info?.fullName || "Unknown User";
        } else if (report.reportedRecipeID && report.reportedRecipe) {
            return report.reportedRecipe?.recipeName || "Unknown Recipe";
        } else {
            return "Unknown";
        }
    };

    const updateStatus = async (report, status) => {
        try {
            const response = await axios.post(`/respond/${report.id}`, { status });

            // Update the local status immediately
            report.status = status.charAt(0).toUpperCase() + status.slice(1);

            if (status === "reviewed") {
                if (report.reportedUserID && report.reportedUser) {
                    emit("navigate", "AdminChefs");
                } else if (report.reportedRecipeID && report.reportedRecipe) {
                    emit("navigate", "RecipeDetails", report.reportedRecipe);
                } else {
                    console.warn(`⚠️ Unknown report type for ID ${report.id}`, report);
                }
            }
        } catch (error) {
            console.error("❌ Error updating report:", error);
        }
    };
</script>
