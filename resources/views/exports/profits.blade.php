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
            <th scope="col">Date</th>
            <th scope="col">Invoice</th>
            <th scope="col">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($profits as $profit)
        <tr>
            <td>{{ $profit->created_at }}</td>
            <td>{{ $profit->transaction->invoice }}</td>
            <td class="text-end">
                {{ formatPrice($profit->total) }}
            </td>
        </tr>
        @endforeach
        <tr>
            <td colspan="2" class="text-end font-weight-bold"
            style="background-color: #e6e6e7">Total</td>
            <td class="text-end fw-bold" style="background-color:#e6e6e7">
                {{ formatPrice($total) }}
            </td>
        </tr>
    </tbody>
</table>
