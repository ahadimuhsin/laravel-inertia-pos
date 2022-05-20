<template>
    <Head>
        <title>Produk - Aplikasi Kasir</title>
    </Head>
    <main class="c-main">
        <div class="container-fluid">
            <div class="fade-in">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card border-0 rounded-3 shadow border-top-purple">
                            <div class="card-header">
                                <span class="font-weight-bold">
                                    <i class="fa fa-shield-alt text-uppercase"></i>
                                    Products
                                </span>
                            </div>
                            <div class="card-body">
                                <form @submit.prevent="handleSearch">
                                    <div class="input-group mb-3">
                                        <Link href="/apps/products/create" v-if="hasAnyPermission(['products.create'])"
                                        class="btn btn-primary input-group-text">
                                        <i class="fa fa-plus-circle me-2"></i> NEW
                                        </Link>
                                        <input type="text" class="form-control" v-model="search" placeholder="Search By Product's name">

                                        <button class="btn btn-primary input-group-text" type="submit">
                                            <i class="fa fa-search me-2"></i> Search
                                        </button>
                                    </div>
                                </form>

                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">Barcode</th>
                                            <th scope="col">Title</th>
                                            <th scope="col">Buy Price</th>
                                            <th scope="col">Sell Price</th>
                                            <th scope="col">Stock</th>
                                            <th scope="col" style="width:20%">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="products.data.length">
                                        <!-- {{ products.data.length }} -->
                                        <tr v-for="(product, index) in products.data" :key="index">
                                            <td>
                                                <barcode
                                                :options="{ displayValue: false, value:product.barcode, format: 'CODE39' }"></barcode>
                                            </td>
                                            <td>
                                                {{product.title}}
                                            </td>
                                            <td>
                                                Rp. {{ formatPrice(product.buy_price) }}
                                            </td>
                                            <td>
                                                Rp. {{ formatPrice(product.sell_price) }}
                                            </td>
                                            <td>
                                                {{ product.stock }}
                                            </td>
                                            <td class="text-center">
                                                <Link :href="`/apps/products/${product.id}/edit`" v-if="hasAnyPermission(['products.edit'])"
                                                class="btn btn-success btn-sm me-1">
                                                    <i class="fas fa-pencil-alt"></i> EDIT
                                                </Link>
                                                <button @click.prevent="destroy(product.name, product.id)" v-if="hasAnyPermission(['products.delete'])"
                                                class="btn btn-danger btn-sm">
                                                    <i class="fa fa-trash"></i> DELETE
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td class="text-center" colspan="6">Data Kosong</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <Pagination :links="products.links" align="end"></Pagination>
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
import Pagination from '../../../Components/Pagination.vue';
import {Head, Link} from '@inertiajs/inertia-vue3';
import {ref} from 'vue';
import {Inertia} from '@inertiajs/inertia';
import Swal from 'sweetalert2'
// import VueBarcode from 'vue-barcode';
import VueBarcode from '@chenfengyuan/vue-barcode';


export default {
    layout: LayoutApp,
    components: {
        Head, Link, Pagination,
        'barcode': VueBarcode
    },
    props: {
        products: Object
    },

    setup()
    {
        const search = ref ('' || (new URL(document.location)).searchParams.get('q'));

        //define method search
        const handleSearch = () => {
            Inertia.get('/apps/products', {
                //kirim parameter q
                q: search.value
            })
        }

        const destroy = (name, id) => {
            Swal.fire({
                title: `Are you sure want to delete ${name} product ?`,
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#dd3',
                confirmButtonText: 'Yes, delete it!'
            })
            .then((result) => {
                if(result.isConfirmed)
                {
                    Inertia.delete(`/apps/products/${id}`);

                    Swal.fire({
                        title: 'Deleted',
                        text: 'product deleted succesfully',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    })
                }
            })
        }

        return {
            search,
            handleSearch,
            destroy
        }
    }
}
</script>
