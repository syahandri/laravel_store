<!doctype html>
<html lang="en">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>

<body>
   <div class="alert alert-success" role="alert">
      <h6 class="alert-heading text-center">Anda bisa melakukan pembayaran melalui salah satu bank di bawah ini.</h6>
      <hr>
      <p class="my-0 text-center">Jika anda sudah melakukan pembayaran, segera konfirmasi di menu
         <a href="{{ env('APP_URL') . 'payment-confirm' }}" class="alert-link">Konfirmasi</a> agar pesanan segera diproses.
      </p>
   </div>
   <div class="alert alert-danger" role="alert">
      <p class="my-0 text-center">Segera lakukan pembayaran sebelum</p>
      <h6 class="fw-semibold my-0 text-center">{{ $data['deadline_date'] }}</h6>
   </div>

   <table class="table">
      <tr>
         <td>
            <div class="card card-body">
               <h6 class="card-title">BRI</h6>
               <p class="card-text mb-0">No. Rekening : 123456789</p>
               <p class="card-text mb-0">Atas Nama : Andrian Syah</p>
            </div>
         </td>
         <td>
            <div class="card card-body">
               <h6 class="card-title">BCA</h6>
               <p class="card-text mb-0">No. Rekening : 123456789</p>
               <p class="card-text mb-0">Atas Nama : Andrian Syah</p>
            </div>
         </td>
         <td>
            <div class="card card-body">
               <h6 class="card-title">Mandiri</h6>
               <p class="card-text mb-0">No. Rekening : 123456789</p>
               <p class="card-text mb-0">Atas Nama : Andrian Syah</p>
            </div>
         </td>
      </tr>
   </table>

   <table class="table">
      <tr>
         <td>
            <p class="mt-0 mb-1" id="invoice">Invoice: {{ $data['invoice'] }}</p>
            <p class="mt-0 mb-1" id="order-date">Tanggal Pesanan: {{ $data['order_date'] }}</p>
            <p class="mt-0 mb-1">Dikirim Ke:</p>
            <ul class="list-unstyled mt-0 mb-1">
               <li>{{ auth()->user()->name }}</li>
               <li>{{ $data['address'] . ', ' . $data['sub_district'] . ', ' . $data['city'] . ', ' . $data['province'] . ', ' . $data['postal_code'] }}</li>
            </ul>
         </td>
         <td class="text-right">
            <p class="mt-0 mb-1" id="courier">Kurir: {{ $data['courier'] }}</p>
            <p class="mt-0 mb-1" id="service">Jenis Layanan: {{ $data['service'] }}</p>
            <p class="mt-0 mb-1" id="estimate">Estimasi (Hari): {{ $data['estimate'] }}</p>
            <p class="mt-0 mb-1" id="deadline-date">Batas Waktu Pembayaran: {{ $data['deadline_date'] }}</p>
         </td>
      </tr>
   </table>
   <hr>

   <table class="table-sm table">
      <thead>
         <tr>
            <th>#</th>
            <th>Produk</th>
            <th>Warna</th>
            <th>Ukuran</th>
            <th>Harga</th>
            <th>Jumlah</th>
            <th>Sub Total</th>
            <th>Catatan</th>
         </tr>
      </thead>
      <tbody>
         @foreach ($data['products'] as $product)
            <tr>
               <td>{{ $loop->iteration }}</td>
               <td>{{ $product->name }}</td>
               <td>{{ $product->pivot->color }}</td>
               <td>{{ $product->pivot->size }}</td>
               <td>Rp. {{ number_format($product->pivot->price, '0', ',', '.') }}</td>
               <td>{{ $product->pivot->quantity }}</td>
               <td>Rp. {{ number_format($product->pivot->sub_total, '0', ',', '.') }}</td>
               <td>{{ $product->pivot->note ?? '-' }}</td>
            </tr>
         @endforeach
      </tbody>
   </table>

   <div class="text-right">
      <h6 class="mb-2">Ongkir: Rp. {{ number_format($data['cost'], '0', ',', '.') }}</h6>
      <h5 class="fw-bolder">Total: Rp. {{ number_format($data['total'], '0', ',', '.') }}</h5>
   </div>

</body>

</html>
