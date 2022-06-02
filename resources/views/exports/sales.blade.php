<div class="title" style="padding-bottom: 13px">
    <div style="text-align: center; text-transform: uppercase; font-size: 15px">
        Santri Koding
    </div>
    <div style="text-align: center">
        Alamat: Rawa Buaya
    </div>
    <div style="text-align: center">
        Telp: 08123456789
    </div>
</div>
<table style="width: 100%">
    <thead>
        <tr style="background-color: #e6e6e7">

        </tr>
        <th scope="col">Date</th>
        <th scope="col">Invoice</th>
        <th scope="col">Cashier</th>
        <th scope="col">Customer</th>
        <th scope="col">Total</th>
    </thead>
    <tbody>
        @foreach ($sales as $sale)
        <tr>
            <td>{{ $sale->created_at }}</td>
            <td>{{ $sale->invoice }}</td>
            <td>{{ $sale->cashier->name ?? '' }}</td>
            <td>{{ $sale->customer->name ?? 'Umum' }}</td>
            <td class="text-end">
                {{ formatPrice($sale->grand_total) }}
            </td>
        </tr>
        @endforeach
        <tr>
            <td colspan="4" class="text-end font-weight-bold" style="background-color: : #e6e6e7">Total</td>
            <td class="text-end fw-bold" style="background-color: : #e6e6e7">
                {{ formatPrice($total) }}
            </td>
        </tr>
    </tbody>
</table>