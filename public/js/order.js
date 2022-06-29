import {request, rupiah} from "./home.js"

/**
 * Order satatus Pending
 * tampilkan modal checkout / detail invoice
 * tombol bayar
 */
const btn_pays = document.querySelectorAll('.btn-pay')
btn_pays.forEach(btn => btn.addEventListener('click', async function () {
   const invoice = this.dataset.invoice
   const url = `/checkout/${invoice}`
   const method = 'GET'
   const data = await request(url, method)
   showInvoice(data)
}))


// tampilkan modal
const showInvoice = (data) => {
   const modal_pending = new bootstrap.Modal("#modal-pending")
   const modal_title = document.querySelector(".modal-title")
   const modal_footer = document.querySelector('.modal-footer')

   modal_title.innerHTML = 'Detail Invoice'
   modal_footer.innerHTML = `
      <a href="/checkout/export/${data.result.id}" class="btn btn-success btn-sm px-3"><i class="fa-solid fa-file-pdf"></i> Cetak PDF</a>
      <button type="button" class="btn btn-primary btn-sm px-3" data-bs-dismiss="modal"><i class="fa-solid fa-check-double"></i> Selesai</button>
   `
   detailInvoice(data)
   modal_pending.show()
}

// tampilkan detail invoice ke modal
const detailInvoice = (data) => {
   // element detail invoice
   const elCount_down_deadline = document.querySelector('#modal-pending #count-down-deadline')
   const elInvoice = document.querySelector('#modal-pending #invoice')
   const elOrder_date = document.querySelector('#modal-pending #order-date')
   const elDeadline_date = document.querySelector('#modal-pending #deadline-date')
   const elCourier = document.querySelector('#modal-pending #courier')
   const elService = document.querySelector('#modal-pending #service')
   const elEstimate = document.querySelector('#modal-pending #estimate')
   const elCost = document.querySelector('#modal-pending #cost')
   const elTotal = document.querySelector('#modal-pending #total')
   const elAddress = document.querySelector('#modal-pending #address')
   const tbody = document.querySelector('#modal-pending #table-product-pending tbody')

   // assign detail invoice ke element
   elInvoice.innerHTML = `Invoice: ${data.result.invoice}`
   elCourier.innerHTML = `Kurir: ${data.result.courier}`
   elService.innerHTML = `Layanan: ${data.result.service}`
   elEstimate.innerHTML = `Estimasi (Hari): ${data.result.estimate}`
   elOrder_date.innerHTML = `Tanggal Pesan: ${data.result.order_date}`
   elDeadline_date.innerHTML = `Batas Waktu Pembayaran: ${data.result.deadline_date}`
   elCost.innerHTML = `Ongkir: ${rupiah(data.result.cost)}`
   elTotal.innerHTML = `Total: ${rupiah(data.result.total)}`
   elAddress.innerHTML = `${data.result.address}, ${data.result.sub_district}, ${data.result.city}, ${data.result.province}, ${data.result.postal_code}`

   tbody.innerHTML = ''
   data.result.products.map((product, i) => {
      tbody.innerHTML += `
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
      `
   })

   const deadline_date = new Date(data.result.deadline_date).getTime()
   const count_down = setInterval(() => {
      const now = new Date().getTime()
      const distance = deadline_date - now

      let days = Math.floor(distance / (1000 * 60 * 60 * 24))
      let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))
      let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60))
      let seconds = Math.floor((distance % (1000 * 60)) / 1000)

      elCount_down_deadline.innerHTML = `${days}Hari ${hours}Jam ${minutes}Menit ${seconds}Detik`

      if (distance < 0) {
         clearInterval(count_down)
         location.reload()
      }
   }, 1000)
}