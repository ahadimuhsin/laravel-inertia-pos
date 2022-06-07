<template>
    <Head>
        <title>Report Profits - Aplikasi Kasir</title>
    </Head>
    <main class="c-main">
        <div class="container-fluid">
            <div class="fade-in">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card border-0 rounded-3 shadow border-top-purple">
                            <div class="card-header">
                                <span class="fw-bold">
                                    <i class="fa fa-chart-line"></i> Report Profits
                                </span>
                            </div>
                            <div class="card-body">
                                <form @submit.prevent="filter">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">
                                                    Start Date
                                                </label>
                                                <!-- <input type="date" v-model="start_date" class="form-control"> -->
                                                <Datepicker v-model="start_date" format="dd/MM/yyyy" placeholder="hari/bulan/tahun"></Datepicker>
                                            </div>
                                            <div v-if="errors.start_date" class="alert alert-danger">
                                                {{ errors.start_date }}
                                            </div>
                                        </div>

                                        <div class="col-md-5">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">
                                                    End Date
                                                </label>
                                                <!-- <input type="date" v-model="end_date" class="form-control"> -->
                                                <Datepicker v-model="end_date" format="dd/MM/yyyy" placeholder="hari/bulan/tahun"></Datepicker>
                                            </div>
                                            <div v-if="errors.end_date" class="alert alert-danger">
                                                {{ errors.end_date }}
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">
                                                    *
                                                </label>
                                                <button class="btn btn-md btn-purple border-0 shadow w-100">
                                                    <i class="fa fa-filter"></i> Filter
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <hr>
                                <div v-if="profits">
                                    <hr>
                                    <div class="export text-end mb-3">
                                        <a :href="`/apps/profits/export?start_date=${start_date}&end_date=${end_date}`" target="_blank"
                                        class="btn btn-success btn-md border-0
                                        shadow me-3">
                                            <i class="fa fa-file-excel"></i>
                                            Excel
                                        </a>

                                        <a :href="`/apps/profits/pdf?start_date=${start_date}&end_date=${end_date}`" target="_blank"
                                        class="btn btn-secondary btn-md border-0
                                        shadow me-3">
                                            <i class="fa fa-file-pdf"></i>
                                            PDF
                                        </a>
                                    </div>

                                    <table class="table table-bordered">
                                        <thead>
                                            <tr class="custom-background">
                                                <th scope="col">Date</th>
                                                <th scope="col">Invoice</th>
                                                <th scope="col">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="profit in profits" :key="profit.id">
                                                <td>
                                                    {{ profit.created_at }}
                                                </td>
                                                <td class="text-center">
                                                    {{ profit.transaction.invoice }}
                                                </td>
                                                <td class="text-end">
                                                    {{ formatPrice(profit.total) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" class="text-end font-weight-bold custom-background">Total</td>
                                                <td class="text-end fw-bold custom-background">
                                                    Rp. {{ formatPrice(total) }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
    import LayoutApp from '../../../Layouts/App.vue';
    import {Head, Link} from '@inertiajs/inertia-vue3';
    import {ref} from 'vue';
    import {Inertia} from '@inertiajs/inertia';
    import Datepicker from '@vuepic/vue-datepicker';
    import '@vuepic/vue-datepicker/dist/main.css'
    export default {
        layout: LayoutApp,
        components: {
            Head, Link, Datepicker
        } ,
        props: {
            errors: Object,
            profits: Array,
            total: Number
        },
        setup() {
            const start_date = ref('' || (new URL(document.location)).searchParams.get('start_date'));
            const end_date = ref('' || (new URL(document.location)).searchParams.get('end_date'));

            /**
            const start_date = ref();
            const end_date = ref();
            console.log(end_date.value);
            */


            const filter = () => {
                //Http request
                Inertia.get('/apps/profits/filter', {
                    start_date: start_date.value,
                    end_date: end_date.value
                });
            }

            return {
                start_date, end_date, filter
            }
        },
}
</script>

<style scoped>
.custom-background{
    background-color: #e6e6e7;
}
</style>
