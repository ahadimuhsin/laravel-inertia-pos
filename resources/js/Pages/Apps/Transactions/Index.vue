<template>
    <Head>
        <title>Transactions - Aplikasi Kasir</title>
    </Head>
    <main class="c-main">
        <div class="container-fluid">
            <div class="fade-in">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card border-0 rounded-3 shadow">
                            <div class="card-body">
                                <div class="input-group mb-3">
                                    <span class="input-group-text">
                                        <i class="fa fa-barcode"></i>
                                    </span>
                                    <input type="text" class="form-control" @keyup="searchProduct"
                                    placeholder="Scan Or Input Barcode" v-model="barcode">
                                </div>
                                <div class="mb-3">
                                    <label for="" class="form-label fw-bold">Product Name</label>
                                    <input type="text" class="form-control" :value="product.title" placeholder="Product Name" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="" class="form-label fw-bold">Qty</label>
                                    <input type="number" class="form-control" v-model="qty" placeholder="Qty" readonly min="1">
                                </div>
                                <div class="text-end">
                                    <button @click.prevent="clearSearch" class="btn btn-warning btn-md border-0 shadow text-uppercase mt-3 me-2"
                                    :disabled="!product.id">
                                        Clear
                                    </button>
                                    <button class="btn btn-success btn-md border-0 shadow text-uppercase mt-3 me-2"
                                    :disabled="!product.id" @click.prevent="addToCart">
                                        Add Item
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="alert alert-danger" v-if="session.error">
                            {{ session.error }}
                        </div>

                        <div class="alert alert-success" v-if="session.success">
                            {{ session.success }}
                        </div>
                        <div class="card border-0 rounded-3 shadow border-top-success">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 col-4">
                                        <h4 class="fw-bold">Grand Total</h4>
                                    </div>
                                    <div class="col-md-8 col-8 text-end">
                                        <h4 class="fw-bold">Rp. {{ formatPrice(grandTotal)}}</h4>
                                        <div v-if="change>0">
                                            <hr>
                                            <h5 class="text-success">
                                                Change: <strong> Rp. {{ formatPrice(change)}}</strong>
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 rounded-3 shadow border-top-success">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="" class="fw-bold">Cashier</label>
                                        <input type="text" class="form-control" readonly :value="auth.user.name">
                                    </div>
                                    <div class="col-md-6 float-end">
                                        <label for="" class="fw-bold">Customer</label>
                                        <VueMultiSelect v-model="customer_id" label="name" track-by="name"
                                        :options="customers"></VueMultiSelect>
                                    </div>
                                </div>
                                <hr>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr style="background-color: #e6e6e7">
                                            <th scope="col">#</th>
                                            <th scope="col">Product Name</th>
                                            <th scope="col">Price</th>
                                            <th scope="col">Qty</th>
                                            <th scope="col">Sub Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="cart in carts" :key="cart.id">
                                            <td class="text-center">
                                                <button class="btn btn-danger btn-sm rounded-pill" @click.prevent="destroyCart(cart.id)">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                            <td>
                                                {{cart.product.title}}
                                            </td>
                                            <td>
                                                Rp. {{ formatPrice(cart.product.sell_price) }}
                                            </td>
                                            <td class="text-center">
                                                {{ cart.qty }}
                                            </td>
                                            <td class="text-end">
                                                Rp. {{ formatPrice(cart.price) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="text-end fw-bold" style="background-color: #e6e6e7">
                                                Total
                                            </td>
                                            <td class="text-end fw-bold" style="background-colo: #e6e6e7">
                                                Rp. {{ formatPrice(carts_total) }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <hr>
                                <div class="d-flex align-items-end flex-column bd-highlight mb-3">
                                    <div class="mt-auto bd-highlight">
                                        <label for="">Discount (Rp)</label>
                                        <input type="number" class="form-control"
                                        placeholder="Discount (Rp.)" v-model="discount"
                                        @keyup="setDiscount">
                                    </div>
                                    <div class="mt-4 bd-highlight">
                                        <label for="">Pay (Rp)</label>
                                        <input type="number" class="form-control" placeholder="Pay (Rp.)"
                                        v-model="cash" @keyup="setChange">
                                    </div>
                                </div>
                                <div class="text-end mt-4">
                                    <button class="btn btn-warning btn-md border-0 shadow text-uppercase mt-3 me-2">
                                        Cancel
                                    </button>
                                    <button class="btn btn-purple btn-md border-0 shadow text-uppercase mt-3 me-2"
                                    :disabled="cash < grandTotal || grandTotal == 0" @click.prevent="storeTransaction">
                                        Pay Order & Print
                                    </button>
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
import {Head} from '@inertiajs/inertia-vue3';
import VueMultiSelect from 'vue-multiselect';
import 'vue-multiselect/dist/vue-multiselect.css';

//import ref
import {ref} from 'vue';
import axios from 'axios';

import {Inertia} from '@inertiajs/inertia';
import Swal from 'sweetalert2';

export default {
    layout: LayoutApp,
    components: {
        Head, VueMultiSelect
    },
    props: {
        auth: Object,
        customers: Array,
        carts_total: Number,
        session: Object,
        carts: Array,
    },
    setup(props)
    {
        const barcode = ref('');
        const product = ref({});
        const qty = ref(1);

        //methodn searchProduct
        const searchProduct = () => {
            axios.post('/apps/transactions/search-product', {
                barcode: barcode.value
            }).then(response => {
                if(response.data.success)
                {
                    product.value = response.data.data;
                }
                else{
                    product.value = {};
                }
            });
        }

        //method clearSearch
        const clearSearch = () => {
            product.value = {};

            //set state barcode to empy
            barcode.value = '';
        }

        const grandTotal = ref(props.carts_total);

        const addToCart = () => {
            Inertia.post('/apps/transactions/add-to-cart', {
                product_id: product.value.id,
                qty: qty.value,
                sell_price: product.value.sell_price
            }, {
                onSuccess: () => {
                    console.log(product);
                    clearSearch();

                    //set qty to 1
                    qty.value = 1;

                    //update state grandTotal
                    grandTotal.value = props.carts_total;

                    //set cash to 0
                    cash.value = 0;

                    //set change to 0
                    change.value = 0;
                }
            })
        }

        const destroyCart = (cart_id) => {
            Inertia.post('/apps/transactions/destroy-cart', {
                cart_id : cart_id,
            }, {
                onSuccess: () => {
                    grandTotal.value = props.carts_total;
                    //set cash to 0
                    cash.value = 0;

                    //set change to 0
                    change.value = 0;
                }
            })
        }

        const cash = ref(0);
        const change = ref(0);
        const discount = ref(0);

        //method setDiscount
        const setDiscount = () => {
            grandTotal.value = props.carts_total - discount.value;
            //set cash to 0
            cash.value = 0;

            //set change to 0
            change.value = 0;
        }

        const setChange = () => {
            change.value = cash.value - grandTotal.value;
        }

        //customer_id
        const customer_id = ref('');

        //method storeTransaction
        const storeTransaction = () => {
            axios.post('/apps/transactions/store', {
                customer_id : customer_id.value ? customer_id.value.id : '',
                discount: discount.value,
                grand_total: grandTotal.value,
                cash: cash.value,
                change: change.value
            })
            .then(response => {
                clearSearch();
                qty.value = 1;
                grandTotal.value = props.carts_total;
                cash.value = 0;
                change.value = 0;

                customer_id.value = '';

                Swal.fire({
                    title: 'Success',
                    text: 'Transaksi Sukses',
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 2000
                })
                .then(() => {
                    axios.get(`/apps/transactions/print`)
                    .then(res => {
                        console.log(res);
                    })
                    setTimeout(() => {
                        window.open(`/apps/transactions/print?invoice=${response.data.data.invoice}`, '_blank');
                        location.reload();
                    }, 50)
                })
            })
        }

        return {
            barcode, product, searchProduct,
            clearSearch, qty, grandTotal,
            addToCart, destroyCart,
            setDiscount, setChange, cash, change, discount,
            customer_id, storeTransaction
        }
    }

}
</script>
