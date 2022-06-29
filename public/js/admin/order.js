import {request, rupiah, alert, confirm} from "../home.js"
const main_loading = document.querySelector('#main-loading')

// Pesanan pending
const table_pending = $('#table-pending').DataTable({
   serverSide: true,
   ajax: '/store-admin/order/pending',
   columns: [{
      data: 'DT_RowIndex',
      name: 'id',
      orderable: false,
      searchable: false
   },
   {
      data: 'invoice',
      name: 'invoice'
   },
   {
      data: 'order_date',
      name: 'order_date'
   },
   {
      data: 'deadline_date',
      name: 'deadline_date'
   },
   {
      data: 'status',
      name: 'status'
   },
   {
      data: 'opsi',
      name: 'opsi',
      orderable: false,
      searchable: false
   }
   ],
   drawCallback: () => {
      // tombol detail order pending
      const btn_details = document.querySelectorAll('.btn-order-detail')
      btn_details.forEach(detail => detail.addEventListener('click', async function () {
         const order_id = this.dataset.id
         const url = `/store-admin/order/detail/${order_id}`
         const method = 'GET'

         const order = await request(url, method)
         showModalPending(order.result)
      }))
   }
})

// modal order pending
const showModalPending = (order) => {
   const modal_pending = new bootstrap.Modal('#modal-pending')
   const modal_title = document.querySelector('.modal-title')
   const modal_body = document.querySelector('.modal-body')
   const modal_footer = document.querySelector('.modal-footer')

   modal_title.innerHTML = 'Detail Order'
   modal_body.innerHTML = detailPending(order)
   modal_footer.innerHTML = '<button class="btn btn-primary btn-sm px-2" data-bs-dismiss="modal"><i class="fa-solid fa-check"></i> Oke</button>'
   modal_pending.show()
}

// detail order pending
const detailPending = (order) => {
   let content = `
   <div class="row justify-content-start justify-content-md-between align-items-end small">
      <div class="col-12 col-md-6">
         <div class="mb-3">
            <p class="mt-0 mb-1">Invoice: ${order.invoice}</p>
            <p class="mt-0 mb-1">Dikirim Ke:</p>
            <ul class="list-unstyled mt-0 mb-1">
               <li>${order.user.name}</li>
               <li>${order.address}, ${order.sub_district}, ${order.city}, ${order.province}, ${order.postal_code}</li>
            </ul>
         </div>
      </div>
      <div class="col-12 col-md-6">
         <div class="text-start text-md-end mb-3">
            <p class="mt-0 mb-1">Tanggal Pesanan: ${order.order_date}</p>
            <p class="mt-0 mb-1">Batas Waktu Pembayaran: ${order.deadline_date}</p>
            <p class="mt-0 mb-1">Kurir: ${order.courier}</p>
            <p class="mt-0 mb-1">Jenis Layanan: ${order.service}</p>
            <p class="mt-0 mb-1">Status: <span class="text-danger fw-semibold">Belum melakukan pembayaran</span></p>
         </div>
      </div>
   </div>
   <hr class="bg-dark my-0">
   <div class="small table-responsive">
      <table class="table table-borderless">
         <thead>
            <th scope="col">#</th>
            <th scope="col">Produk</th>
            <th scope="col">Warna</th>
            <th scope="col">Ukuran</th>
            <th scope="col">Harga</th>
            <th scope="col">Jumlah</th>
            <th scope="col">Sub Total</th>
            <th scope="col">Catatan</th>
         </thead>`
   order.products.map((product, i) => {
      content += `
         <tbody>
            <tr>
               <td>${i + 1}</td>
               <td>${product.name}</td>
               <td>${product.pivot.color}</td>
               <td>${product.pivot.size}</td>
               <td>${rupiah(product.pivot.price)}</td>
               <td>${product.pivot.quantity}</td>
               <td>${rupiah(product.pivot.sub_total)}</td>
               <td>${product.pivot.note ?? '-'}</td>
            </tr>
         </tbody>`
   })
   content += `
      </table>
      <div class="text-end mt-3 small">
         <h6>Ongkir: ${rupiah(order.cost)}</h6>
         <h5>Total: ${rupiah(order.total)}</h5>
      </div>
   </div>`

   return content
}

// Pesanan konfirmasi
const table_confirm = $('#table-confirm').DataTable({
   serverSide: true,
   ajax: '/store-admin/order/confirm',
   columns: [{
      data: 'DT_RowIndex',
      name: 'id',
      orderable: false,
      searchable: false
   },
   {
      data: 'invoice',
      name: 'invoice'
   },
   {
      data: 'order_date',
      name: 'order_date'
   },
   {
      data: 'confirm.confirm_date',
      name: 'confirm.confirm_date'
   },
   {
      data: 'confirm.name',
      name: 'confirm.name'
   },
   {
      data: 'confirm.bank',
      name: 'confirm.bank'
   },
   {
      data: 'opsi',
      name: 'opsi',
      orderable: false,
      searchable: false
   }
   ],
   drawCallback: () => {
      // tombol detail order confirm
      const btn_details = document.querySelectorAll('.btn-order-detail')
      btn_details.forEach(detail => detail.addEventListener('click', async function () {
         const order_id = this.dataset.id
         const url = `/store-admin/order/detail/${order_id}`
         const method = 'GET'

         const order = await request(url, method)
         showModalConfirm(order.result)
      }))
   }
})

// modal order confirm
const showModalConfirm = (order) => {
   const modal_confirm = new bootstrap.Modal('#modal-confirm')
   const modal_title = document.querySelector('.modal-title')
   const modal_body = document.querySelector('.modal-body #form-confirm')
   const modal_footer = document.querySelector('.modal-footer')

   modal_title.innerHTML = 'Detail Order'
   modal_body.innerHTML = detailConfirm(order)
   modal_footer.innerHTML = `
      <button type="button" class="btn btn-sm" data-bs-dismiss="modal"><i class="fa-solid fa-times"></i> Batal</button>
      <button data-id="${order.id}" class="btn btn-outline-danger btn-sm px-2" id="btn-deny"><i class="fa-solid fa-ban"></i> Tolak Konfirmasi</button>
      <button data-id="${order.id}" class="btn btn-primary btn-sm px-2" id="btn-verify"><i class="fa-solid fa-check"></i> Proses</button>`
   modal_confirm.show()

   // tombol verifikasi / proses pesanan
   const btn_verify = document.querySelector('#btn-verify')
   btn_verify.addEventListener('click', async function () {
      const order_id = this.dataset.id
      const url = `/store-admin/order/verify/${order_id}`
      const method = 'POST'
      const token = document.querySelector('input[name="_token"]').value
      const formData = new FormData()
      formData.append('status', 'Proccess')

      modal_confirm.hide()
      try {
         const data = await request(url, method, token, formData)
         alert('success', 'Sukses', data.message, () => reload(table_confirm))
      } catch (error) {
         alert('error', 'Oops', error.message, () => reload(table_confirm))
      }
   })

   // tombol tolak konfirmasi pesanan
   const btn_deny = document.querySelector('#btn-deny')
   btn_deny.addEventListener('click', function () {
      const order_id = this.dataset.id
      const url = `/store-admin/order/deny/${order_id}`
      const method = 'POST'
      const token = document.querySelector('input[name="_token"]').value
      const formData = new FormData()
      formData.append('reason', 'Konfirmasi Pembayaran Tidak Valid')
      modal_confirm.hide()
      confirm('Pesanan akan dibatalkan', 'Batalkan Pesanan', () => doCancel(url, method, token, formData))
   })
}

// detail order confirm
const detailConfirm = (order) => {
   let content = `
   <div class="row justify-content-start justify-content-md-between align-items-end small">
      <div class="col-12 col-md-6">
         <div class="mb-3">
            <p class="mt-0 mb-1">Invoice: ${order.invoice}</p>
            <p class="mt-0 mb-1">Tanggal Pesanan: ${order.order_date}</p>
            <p class="mt-0 mb-1">Tanggal Konfirmasi: ${order.confirm.confirm_date}</p>
            <p class="mt-0 mb-1">Dikirim Ke:</p>
            <ul class="list-unstyled mt-0 mb-1">
               <li>${order.user.name}</li>
               <li>${order.address}, ${order.sub_district}, ${order.city}, ${order.province}, ${order.postal_code}</li>
            </ul>
         </div>
      </div>
      <div class="col-12 col-md-6">
         <div class="text-start text-md-end mb-3">
            <p class="mt-0 mb-1">Atas Nama: ${order.confirm.name}</p>
            <p class="mt-0 mb-1">Bank: ${order.confirm.bank}</p>
            <p class="mt-0 mb-1">Kurir: ${order.courier}</p>
            <p class="mt-0 mb-1">Jenis Layanan: ${order.service}</p>
            <p class="mt-0 mb-1">Status: <span class="text-success fw-semibold">Sudah Konfirmasi Pembayaran</span></p>
         </div>
      </div>
   </div>
   <hr class="bg-dark my-0">
   <div class="small table-responsive">
      <table class="table table-borderless">
         <thead>
            <th scope="col">#</th>
            <th scope="col">Produk</th>
            <th scope="col">Warna</th>
            <th scope="col">Ukuran</th>
            <th scope="col">Harga</th>
            <th scope="col">Jumlah</th>
            <th scope="col">Sub Total</th>
            <th scope="col">Catatan</th>
         </thead>`
   order.products.map((product, i) => {
      content += `
         <tbody>
            <tr>
               <td>${i + 1}</td>
               <td>${product.name}</td>
               <td>${product.pivot.color}</td>
               <td>${product.pivot.size}</td>
               <td>${rupiah(product.pivot.price)}</td>
               <td>${product.pivot.quantity}</td>
               <td>${rupiah(product.pivot.sub_total)}</td>
               <td>${product.pivot.note ?? '-'}</td>
            </tr>
         </tbody>`
   })
   content += `
      </table>
      <div class="text-end mt-3 small">
         <h6>Ongkir: ${rupiah(order.cost)}</h6>
         <h5>Total: ${rupiah(order.total)}</h5>
      </div>
      <div class="d-flex justify-content-center align-items-center border rounded p-2 mt-3">
         <img src="/storage/img/${order.confirm.image}" alt="Bukti Transfer" class="img-fluid">
      </div>
   </div>`

   return content
}

// lakukan batal pesanan
const doCancel = async (url, method, token, formData) => {
   main_loading.classList.remove('d-none')
   try {
      const data = await request(url, method, token, formData)
      alert('success', 'Sukses', data.message, () => reload(table_confirm))
   } catch (error) {
      alert('error', 'Oops', error.message, () => reload(table_confirm))
   }
   main_loading.classList.add('d-none')
}

// Pesanan diproses
const table_proccess = $('#table-proccess').DataTable({
   serverSide: true,
   ajax: '/store-admin/order/proccess',
   columns: [{
      data: 'DT_RowIndex',
      name: 'id',
      orderable: false,
      searchable: false
   },
   {
      data: 'invoice',
      name: 'invoice'
   },
   {
      data: 'order_date',
      name: 'order_date'
   },
   {
      data: 'courier',
      name: 'courier'
   },
   {
      data: 'service',
      name: 'service'
   },
   {
      data: 'cost',
      name: 'cost'
   },
   {
      data: 'opsi',
      name: 'opsi',
      orderable: false,
      searchable: false
   }
   ],
   drawCallback: () => {
      // tombol detail order proccess
      const btn_sends = document.querySelectorAll('.btn-order-send')
      btn_sends.forEach(detail => detail.addEventListener('click', async function () {
         const order_id = this.dataset.id
         const url = `/store-admin/order/detail/${order_id}`
         const method = 'GET'

         const order = await request(url, method)
         showModalProccess(order.result)
      }))
   }
})

// modal order proccess
const showModalProccess = (order) => {
   const modal_proccess = new bootstrap.Modal('#modal-proccess')
   const modal_title = document.querySelector('.modal-title')
   const form_proccess = document.querySelector('.modal-body #form-proccess')

   modal_title.innerHTML = 'Detail Order'
   form_proccess.innerHTML = detailProccess(order)
   modal_proccess.show()

   // submit form proccess / kirim pesanan
   form_proccess.addEventListener('submit', async function (e) {
      e.preventDefault()
      const order_id = order.id
      const url = `/store-admin/order/send/${order_id}`
      const method = 'POST'
      const token = document.querySelector('input[name="_token"]').value
      const formData = new FormData(this)

      modal_proccess.hide()
      main_loading.classList.remove('d-none')
      try {
         const data = await request(url, method, token, formData)
         alert('success', 'Sukses', data.message, () => reload(table_proccess))
      } catch (error) {
         alert('error', 'Oops', error.message, () => reload(table_proccess))
      }
      main_loading.classList.add('d-none')
   })
}

// detail order proccess
const detailProccess = (order) => {
   let content = `
   <div class="row justify-content-start justify-content-md-between align-items-end small">
      <div class="col-12 col-md-6">
         <div class="mb-3">
            <p class="mt-0 mb-1">Invoice: ${order.invoice}</p>
            <p class="mt-0 mb-1">Dikirim Ke:</p>
            <ul class="list-unstyled mt-0 mb-3">
               <li>${order.user.name}</li>
               <li>${order.address}, ${order.sub_district}, ${order.city}, ${order.province}, ${order.postal_code}</li>
            </ul>
            <p class="my-0">Nomor Resi</p>
            <input type="text" class="form-control form-control-sm text-uppercase" name="resi" placeholder="Masukan nomor resi..." required>
         </div>
      </div>
      <div class="col-12 col-md-6">
         <div class="text-start text-md-end mb-3">
            <p class="mt-0 mb-1">Tanggal Pesanan: ${order.order_date}</p>
            <p class="mt-0 mb-1">Kurir: ${order.courier}</p>
            <p class="mt-0 mb-1">Jenis Layanan: ${order.service}</p>
         </div>
      </div>
   </div>
   <hr class="bg-dark my-0">
   <div class="small table-responsive">
      <table class="table table-borderless">
         <thead>
            <th scope="col">#</th>
            <th scope="col">Produk</th>
            <th scope="col">Warna</th>
            <th scope="col">Ukuran</th>
            <th scope="col">Harga</th>
            <th scope="col">Jumlah</th>
            <th scope="col">Sub Total</th>
            <th scope="col">Catatan</th>
         </thead>`
   order.products.map((product, i) => {
      content += `
         <tbody>
            <tr>
               <td>${i + 1}</td>
               <td>${product.name}</td>
               <td>${product.pivot.color}</td>
               <td>${product.pivot.size}</td>
               <td>${rupiah(product.pivot.price)}</td>
               <td>${product.pivot.quantity}</td>
               <td>${rupiah(product.pivot.sub_total)}</td>
               <td>${product.pivot.note ?? '-'}</td>
            </tr>
         </tbody>`
   })
   content += `
      </table>
      <div class="text-end mt-3 small">
         <h6>Ongkir: ${rupiah(order.cost)}</h6>
         <h5>Total: ${rupiah(order.total)}</h5>
      </div>
   </div>`

   return content
}

// Pesanan dikirim
const table_sent = $('#table-sent').DataTable({
   serverSide: true,
   ajax: '/store-admin/order/sent',
   columns: [{
      data: 'DT_RowIndex',
      name: 'id',
      orderable: false,
      searchable: false
   },
   {
      data: 'invoice',
      name: 'invoice'
   },
   {
      data: 'order_date',
      name: 'order_date'
   },
   {
      data: 'courier',
      name: 'courier'
   },
   {
      data: 'service',
      name: 'service'
   },
   {
      data: 'cost',
      name: 'cost'
   },
   {
      data: 'opsi',
      name: 'opsi',
      orderable: false,
      searchable: false
   }
   ],
   drawCallback: () => {
      // tombol detail order sent
      const btn_details = document.querySelectorAll('.btn-order-detail')
      btn_details.forEach(detail => detail.addEventListener('click', async function () {
         const order_id = this.dataset.id
         const url = `/store-admin/order/detail-sent/${order_id}`
         const method = 'GET'

         const order = await request(url, method)
         showModalSent(order.result)
      }))
   }
})

// modal order Sent
const showModalSent = (order) => {
   const modal_sent = new bootstrap.Modal('#modal-sent')
   const modal_title = document.querySelector('.modal-title')
   const modal_body = document.querySelector('.modal-body #form-sent')
   const modal_footer = document.querySelector('.modal-footer')

   modal_title.innerHTML = 'Detail Order'
   modal_footer.innerHTML = '<button class="btn btn-primary btn-sm px-2" data-bs-dismiss="modal"><i class="fa-solid fa-check"></i> Oke</button>'
   modal_body.innerHTML = detailSent(order)
   modal_sent.show()
}

// detail order confirm
const detailSent = (order) => {
   let content = `
   <div class="row justify-content-start justify-content-md-between align-items-end small">
      <div class="col-12 col-md-6">
         <div class="mb-3">
            <p class="mt-0 mb-1">Invoice: ${order.invoice}</p>
            <p class="mt-0 mb-1">Nomor Resi: ${order.resi}</p>
            <p class="mt-0 mb-1">Dikirim Ke:</p>
            <ul class="list-unstyled mt-0 mb-3">
               <li>${order.user.name}</li>
               <li>${order.address}, ${order.sub_district}, ${order.city}, ${order.province}, ${order.postal_code}</li>
            </ul>
         </div>
      </div>
      <div class="col-12 col-md-6">
         <div class="text-start text-md-end mb-3">
            <p class="mt-0 mb-1">Tanggal Pesanan: ${order.order_date}</p>
            <p class="mt-0 mb-1">Tanggal Dikirim: ${order.sent_date}</p>
            <p class="mt-0 mb-1">Kurir: ${order.courier}</p>
            <p class="mt-0 mb-1">Jenis Layanan: ${order.service}</p>
            <p class="mt-0 mb-1">Estimasi (Hari): ${order.estimate}</p>
         </div>
      </div>
   </div>
   <hr class="bg-dark my-0">
   <div class="small table-responsive">
      <table class="table table-borderless">
         <thead>
            <th scope="col">#</th>
            <th scope="col">Produk</th>
            <th scope="col">Warna</th>
            <th scope="col">Ukuran</th>
            <th scope="col">Harga</th>
            <th scope="col">Jumlah</th>
            <th scope="col">Sub Total</th>
            <th scope="col">Catatan</th>
         </thead>`
   order.products.map((product, i) => {
      content += `
         <tbody>
            <tr>
               <td>${i + 1}</td>
               <td>${product.name}</td>
               <td>${product.pivot.color}</td>
               <td>${product.pivot.size}</td>
               <td>${rupiah(product.pivot.price)}</td>
               <td>${product.pivot.quantity}</td>
               <td>${rupiah(product.pivot.sub_total)}</td>
               <td>${product.pivot.note ?? '-'}</td>
            </tr>
         </tbody>`
   })
   content += `
      </table>
      <div class="text-end mt-3 small">
         <h6>Ongkir: ${rupiah(order.cost)}</h6>
         <h5>Total: ${rupiah(order.total)}</h5>
      </div>
   </div>`

   return content
}

// Pesanan dibatalkan
const table_cancel = $('#table-cancel').DataTable({
   serverSide: true,
   ajax: '/store-admin/order/cancel',
   columns: [{
      data: 'DT_RowIndex',
      name: 'id',
      orderable: false,
      searchable: false
   },
   {
      data: 'invoice',
      name: 'invoice'
   },
   {
      data: 'order_date',
      name: 'order_date'
   },
   {
      data: 'created_at',
      name: 'created_at'
   },
   {
      data: 'reason',
      name: 'reason'
   },
   {
      data: 'opsi',
      name: 'opsi',
      orderable: false,
      searchable: false
   }
   ],
   drawCallback: () => {
      // tombol detail order cancel
      const btn_details = document.querySelectorAll('.btn-order-detail')
      btn_details.forEach(detail => detail.addEventListener('click', async function () {
         const order_id = this.dataset.id
         const url = `/store-admin/order/detail-cancel/${order_id}`
         const method = 'GET'

         const order = await request(url, method)
         showodalCancel(order.result)
      }))
   }
})

// modal order cancel
const showodalCancel = (order) => {
   const modal_cancel = new bootstrap.Modal('#modal-cancel')
   const modal_title = document.querySelector('.modal-title')
   const modal_body = document.querySelector('.modal-body #form-cancel')
   const modal_footer = document.querySelector('.modal-footer')

   modal_title.innerHTML = 'Detail Order'
   modal_footer.innerHTML = '<button class="btn btn-primary btn-sm px-2" data-bs-dismiss="modal"><i class="fa-solid fa-check"></i> Oke</button>'
   modal_body.innerHTML = detailCancel(order)
   modal_cancel.show()
}

// detail order cancel
const detailCancel = (order) => {
   let content = `
   <div class="row justify-content-start justify-content-md-between align-items-end small">
      <div class="col-12 col-md-6">
         <div class="mb-3">
            <p class="mt-0 mb-1">Invoice: ${order.invoice}</p>
            <p class="mt-0 mb-1">Atas Nama:</p>
            <ul class="list-unstyled mt-0 mb-3">
               <li>${order.user.name}</li>
            </ul>
         </div>
      </div>
      <div class="col-12 col-md-6">
         <div class="text-start text-md-end mb-3">
            <p class="mt-0 mb-1">Tanggal Pesanan: ${order.order_date}</p>
            <p class="mt-0 mb-1">Tanggal Batal: ${order.created_at}</p>
            <p class="mt-0 mb-1">Alasan Batal: <span class="text-danger fw-semibold">${order.reason}</span></p>
         </div>
      </div>
   </div>
   <hr class="bg-dark my-0">
   <div class="small table-responsive">
      <table class="table table-borderless">
         <thead>
            <th scope="col">#</th>
            <th scope="col">Produk</th>
            <th scope="col">Warna</th>
            <th scope="col">Ukuran</th>
            <th scope="col">Harga</th>
            <th scope="col">Jumlah</th>
            <th scope="col">Sub Total</th>
            <th scope="col">Catatan</th>
         </thead>`
   order.products.map((product, i) => {
      content += `
         <tbody>
            <tr>
               <td>${i + 1}</td>
               <td>${product.name}</td>
               <td>${product.pivot.color}</td>
               <td>${product.pivot.size}</td>
               <td>${rupiah(product.pivot.price)}</td>
               <td>${product.pivot.quantity}</td>
               <td>${rupiah(product.pivot.sub_total)}</td>
               <td>${product.pivot.note ?? '-'}</td>
            </tr>
         </tbody>`
   })
   content += `
      </table>
   </div>`

   return content
}

// reload data table
const reload = (table) => table.ajax.reload(null, false)
