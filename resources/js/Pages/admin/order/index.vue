<script>
export default {
    layout: AppLayout,
}
</script>
<script setup>
import axios from "axios";
import { notify } from "notiwind";
import { object, string } from "vue-types";
import { Head } from "@inertiajs/inertia-vue3";
import { ref, onMounted, reactive } from "vue";
import AppLayout from '@/layouts/apps.vue';
import debounce from "@/composables/debounce"
import VDropdownEditMenu from '@/components/VDropdownEditMenu/index.vue';
import VDataTable from '@/components/VDataTable/index.vue';
import VPagination from '@/components/VPagination/index.vue'
import VBreadcrumb from '@/components/VBreadcrumb/index.vue';
import VLoading from '@/components/VLoading/index.vue';
import VEmpty from '@/components/src/icons/VEmpty.vue';
import VButton from '@/components/VButton/index.vue';
import VAlert from '@/components/VAlert/index.vue';
import VEdit from '@/components/src/icons/VEdit.vue';
import VTrash from '@/components/src/icons/VTrash.vue';
import VFilter from './Filter.vue';
import VModalForm from './ModalForm.vue';
import { Inertia } from "@inertiajs/inertia";
import VOrderItem from './addOrderItem.vue'


const query = ref([])
const searchFilter = ref("");
const filter = ref({});
const breadcrumb = [
    {
        name: "Dashboard",
        active: false,
        to: route('dashboard.index')
    },
    {
        name: "Order",
        active: true,
        to: route('order.index')
    },
]
const pagination = ref({
    count: '',
    current_page: 1,
    per_page: '',
    total: 0,
    total_pages: 1
})
const alertData = reactive({
    headerLabel: '',
    contentLabel: '',
    closeLabel: '',
    submitLabel: '',
})
const updateAction = ref(false)
const itemSelected = ref({})
const openAlert = ref(false)
const openModalForm = ref(false)
const heads = ["Name", "Quantity", "Price", "Total Price", ""]
const orderItemLoading = ref(true)
const changeLoading = ref(true)

const props = defineProps({
    title: string(),
    additional: object(),
})

const getOrderItemData = debounce(async () => {
   axios.get(route('order.getorderitemdata'))
   .then((res) => {
       query.value = res.data.data
       grandTotal.value = res.data.grand_total
   }).catch((res) => {
       notify({
           type: "error",
           group: "top",
           text: res.response.data.message
       }, 2500)
   }).finally(() => orderItemLoading.value = false)
}, 500);
const successSubmit = () => {
   orderItemLoading.value = true
   getOrderItemData()
}
const handleEditQty = (data) => {
   itemSelected.value = data
   openModalForm.value = true
}
const closeModalForm = () => {
   itemSelected.value = ref({})
   openModalForm.value = false
}
const alertDelete = (data) => {
   itemSelected.value = data
   openAlert.value = true
   alertData.headerLabel = 'Are you sure to delete this?'
   alertData.contentLabel = "You won't be able to revert this!"
   alertData.closeLabel = 'Cancel'
   alertData.submitLabel = 'Delete'
}
const closeAlert = () => {
   itemSelected.value = ref({})
   openAlert.value = false
}
const deleteProductFromOrderItem = async () => {
   axios.delete(route('order.deletefromorderitem', { 'id': itemSelected.value.id })
   ).then((res) => {
       notify({
           type: "success",
           group: "top",
           text: res.data.meta.message
       }, 2500)
       openAlert.value = false
       orderItemLoading.value = true
       getOrderItemData()
   }).catch((res) => {
       notify({
           type: "error",
           group: "top",
           text: res.response.data.message
       }, 2500)
   })
};



onMounted(() => {
    getOrderItemData();
});
</script>

<template>

    <Head :title="props.title" />
    <VBreadcrumb :routes="breadcrumb" />
    <div class="mb-4 sm:mb-6 flex justify-between items-center">
        <h1 class="text-2xl md:text-3xl text-slate-800 font-bold">Order</h1>
    </div>
    <div class="bg-white shadow-lg rounded-sm border border-slate-200"
        :class="orderItemLoading && 'min-h-[40vh] sm:min-h-[50vh]'">
        <VOrderItem :additional="additional" @successSubmit="successSubmit"/>

        <header class="block justify-between items-center sm:flex py-6 px-4">
            <h2 class="font-semibold text-slate-800">
                All Orders <span class="text-slate-400 !font-medium ml">{{ pagination.total }}</span>
            </h2>


            <div class="mt-3 sm:mt-0 flex space-x-2 sm:justify-between justify-end">
                <!-- Filter -->
                <VFilter @search="searchHandle" @apply="applyFilter" @clear="clearFilter" :additional="additional"/>

                  <!--  <VButton
                    label="Create Order"
                    type="primary"
                    @click="handleCreatePage"
                    class="mt-auto"
                />

            <VButton label="Add Order" type="primary" @click="handleAddModalForm" class="mt-auto" /> -->
            </div>
        </header>

        <VDataTable :heads="heads" :isLoading="orderItemLoading">

            <tr v-if="orderItemLoading">
                <td class="h-[100%] overflow-hidden my-2" :colspan="heads.length">
                    <VLoading />
                </td>
            </tr>
            
            
            <tr v-else-if="query.length === 0 && !orderItemLoading">

                <td class="overflow-hidden my-2" :colspan="heads.length">
                    <div class="flex items-center flex-col w-full my-32">
                        <VEmpty />
                        <div class="mt-9 text-slate-500 text-xl md:text-xl font-medium">Result not found.</div>
                    </div>
                </td>
            </tr>
            <tr v-for="(data, index) in query" :key="index" v-else>
                <td class="px-4 whitespace-nowrap h-16"> {{ data.product.name }} </td>
                <td class="px-4 whitespace-nowrap h-16"> {{ data.quantity }}</td>
                <td class="px-4 whitespace-nowrap h-16">Rp {{ data.product.price_formatted }}</td>
                <td class="px-4 whitespace-nowrap h-16">Rp{{ data.total_price }}</td>
                <td class="px-4 whitespace-nowrap h-16 text-right">
                    <VDropdownEditMenu class="relative inline-flex r-0" :align="'right'"
                        :last="index === query.length - 1 ? true : false">
                        <li class="cursor-pointer hover:bg-slate-100" @click="handleEditModal(data)">
                            <div class="flex items-center space-x-2 p-3">
                                <span>
                                    <VEdit color="primary" />
                                </span>
                                <span>Edit</span>
                            </div>
                        </li>
                        <li class="cursor-pointer hover:bg-slate-100">
                            <div class="flex justify-between items-center space-x-2 p-3" @click="alertDelete(data)">
                                <span>
                                    <VTrash color="danger" />
                                </span>
                                <span>Delete</span>
                            </div>
                        </li>
                    </VDropdownEditMenu>
                </td>
            </tr>
        </VDataTable>
        <div class="px-4 py-6">
            <VPagination :pagination="pagination" @next="nextPaginate" @previous="previousPaginate" />
        </div>
    </div>
    <VAlert :open-dialog="openAlert" @closeAlert="closeAlert" @submitAlert="deleteHandle" type="danger"
        :headerLabel="alertData.headerLabel" :content-label="alertData.contentLabel" :close-label="alertData.closeLabel"
        :submit-label="alertData.submitLabel" />
    <VModalForm :data="itemSelected" :update-action="updateAction" :open-dialog="openModalForm" @close="closeModalForm"
        @successSubmit="successSubmit" :additional="additional" />
</template>