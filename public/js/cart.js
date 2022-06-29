import {onlyNumber, rupiah, request, alert, countCart, loading} from "./home.js"

// element input produk
const id = document.querySelectorAll('input[name="product_id"]')
const stocks = document.querySelectorAll('input[name="stock"]')
const weights = document.querySelectorAll('input[name="weight"]')
const prices = document.querySelectorAll('input[name="price"]')
const colors = document.querySelectorAll('input[name="color"]')
const sizes = document.querySelectorAll('input[name="size"]')
const quantities = document.querySelectorAll('input[name="quantity"]')
const notes = document.querySelectorAll('input[name="note"]')

// tampung produk yang dipilih, total berat, total harga, jumlah select items, dan jumlah select items yang aktif
const select_items = document.querySelectorAll('.select-item')
const select_products = {}
let total_weight = 0
let total_price = 0
let count_select_items = select_items.length
let checked_select_items = 0

// disable select item dan quantity jika stok habis
stocks.forEach((stock, i) => {
   if (stock.value == 0) {
      select_items[i].disabled = true
      select_items[i].checked = false
      quantities[i].disabled = true
   }
})

const courier = document.querySelector('#courier')
const service = document.querySelector('#service')
const btn_checkout = document.querySelector('#btn-checkout')
const label_service = document.querySelector('label[for="service"]')
// select item
select_items.forEach((select, i) => select.addEventListener('change', function () {
   if (this.checked) {
      // aktifkan select kurir
      courier.disabled = false

      // tambahkan jumlah select item aktif
      checked_select_items++

      // masukan item yang dipilih ke object select_products
      select_products[i] = {
         product_id: id[i].value,
         color: colors[i].value,
         size: sizes[i].value,
         price: prices[i].value,
         quantity: quantities[i].value,
         note: notes[i].value,
         weight: weights[i].value * quantities[i].value,
         sub_total: prices[i].value * quantities[i].value
      }

      // hitung berat total & total harga
      total_weight += select_products[i].weight
      total_price += select_products[i].sub_total
   } else {
      // kurangi jumlah select item aktif
      checked_select_items--

      // hitung ulang berat total & total harga
      total_weight -= select_products[i].weight
      total_price -= select_products[i].sub_total

      // hapus item dari object select_products
      delete select_products[i]

      // Jika select_products == 0, hapus detail ongkir, matikan select kurir, service dan tombol checkout
      if (Object.keys(select_products).length < 1) {
         courier.selectedIndex = 0
         courier.disabled = true
         service.disabled = true
         clearService()
         clearCost()
         refreshButtonCheckout()
      }
   }

   // hitung ulang ongkir jika select product bertambah
   if (service.value) {
      recountCosts()
   }
}))

// tombol hapus item
const remove_items = document.querySelectorAll('.remove-item')
remove_items.forEach((remove, i) => remove.addEventListener('click', async function () {
   const url = `/cart/remove?product_id=${id[i].value}&color=${colors[i].value}&size=${sizes[i].value}`
   const method = 'DELETE'
   const token = document.querySelector('input[name="_token"]').value

   try {
      const item = await request(url, method, token)
      deleteItem(item, i)
      countCart()

      // hitung ulang ongkir
      if (service.value) {
         recountCosts()
      }

   } catch (error) {
      alert('error', 'Oops', error.message)
   }
}))

// batasi input quantity
quantities.forEach((quantity, i) => quantity.addEventListener('input', function () {
   onlyNumber(this)
   this.value = Number(this.value) > Number(stocks[i].value) ? stocks[i].value : this.value
   this.value = Number(this.value) == 0 || !this.value ? 1 : this.value

   if (select_items[i].checked) {
      // hitung ulang select product jika quantity berubah saat select items aktif
      total_weight -= select_products[i].weight
      total_price -= select_products[i].sub_total

      select_products[i].quantity = this.value
      select_products[i].weight = weights[i].value * this.value
      select_products[i].sub_total = prices[i].value * this.value

      total_weight += select_products[i].weight
      total_price += select_products[i].sub_total

      // hitung ulang ongkir
      if (this.value && service.value) {
         recountCosts()
      }
   }
}))

notes.forEach((note, i) => note.addEventListener('input', function () {
   if (select_items[i].checked) select_products[i].note = this.value
}))

// isi select service dari kurir
const destination = document.querySelector('#destination')
courier.addEventListener('change', async function () {
   if (!destination.value) {
      alert('error', 'Error', 'Alamat belum diatur')
   }

   if (!this.value) {
      service.disabled = true
      clearService()
      clearCost()
      return refreshButtonCheckout()
   }

   label_service.innerHTML = `Memuat layanan ${loading}`
   service.disabled = true
   clearService()
   refreshButtonCheckout()
   getServiceOrCosts(showServices)
})

// hitung ongkir dan total
service.addEventListener('change', function () {
   if (!this.value) {
      clearCost()
      return refreshButtonCheckout()
   }

   refreshButtonCheckout()
   btn_checkout.innerHTML = `${loading} Loading...`
   const service_id = this.options[this.selectedIndex].dataset.id
   getServiceOrCosts(showCosts, service_id)
})

// tombol checkout / submit form cart
const form_cart = document.querySelector('#form-cart')
form_cart.addEventListener('submit', async function (e) {
   e.preventDefault()
   const indexObj = Object.keys(select_products)
   const url = '/checkout'
   const method = 'POST'
   const token = document.querySelector('input[name="_token"]').value
   const formData = new FormData(this)

   // isi form data dengan select products
   indexObj.map(i => {
      formData.append('product_id[]', select_products[i].product_id)
      formData.append('size[]', select_products[i].size)
      formData.append('color[]', select_products[i].color)
      formData.append('quantity[]', select_products[i].quantity)
      formData.append('price[]', select_products[i].price)
      formData.append('note[]', select_products[i].note)
      formData.append('sub_total[]', select_products[i].sub_total)
   })

   try {
      refreshButtonCheckout()
      btn_checkout.innerHTML = `${loading} Loading...`
      const checkout = await request(url, method, token, formData)
      showInvoice(checkout)
   } catch (error) {
      if (error.code == 400) {
         const stock_alerts = document.querySelectorAll('.stock-alert')
         indexObj.map(i => {
            // tampilkan error jika stok tidak cukup / habis
            if (stock_alerts[i].dataset.id == error.product_id) {
               stock_alerts[i].innerHTML = `<div class="alert alert-danger text-center shadow-sm" role="alert">${error.message}</div>`
               stocks[i].value = error.stock
            }

            // disable input produk jika stock habis
            if (stocks[i].value == 0) {
               quantities[i].value = 0
               quantities[i].disabled = true
               select_items[i].checked = false
               select_items[i].disabled = true
               checked_select_items--
               total_weight -= select_products[i].weight
               total_price -= select_products[i].sub_total
               delete select_products[i]
            }
         })

         // hitung ulang ongkir, estimasi dan total jika select items masih ada
         if (checked_select_items > 0) {
            recountCosts()
         } else {
            courier.selectedIndex = 0
            courier.disabled = true
            service.disabled = true
            clearService()
            clearCost()
            refreshButtonCheckout()
         }
      } else {
         alert('error', 'Oops', error.message, () => location.reload())
      }
   }
})

// hapus cart item
const cart_items = document.querySelectorAll('.cart-item')
const deleteItem = (item, i) => {
   count_select_items--
   if (select_items[i].checked) {
      checked_select_items--
      total_weight -= select_products[i].weight
      total_price -= select_products[i].sub_total
      delete select_products[i]

      if (checked_select_items < 1) {
         courier.selectedIndex = 0
         courier.disabled = true
         service.disabled = true
         clearService()
         clearCost()
         refreshButtonCheckout()
      }
   }

   if (count_select_items < 1) {
      location.reload()
   }

   cart_items[i].remove()
}

// ambil layanan kurir / hitung ongkir
const getServiceOrCosts = async (func, i) => {
   const url = '/cart/cost'
   const method = 'POST'
   const token = document.querySelector('input[name="_token"]').value
   const formData = new FormData()
   formData.append('destination', destination.value)
   formData.append('weight', total_weight)
   formData.append('courier', courier.value)

   clearCost()

   try {
      const data = await request(url, method, token, formData)
      func(data, i)
   } catch (error) {
      alert('error', 'Oops', 'Gagal memuat, silahkan coba lagi.')
      clearService()
      clearCost()
      refreshButtonCheckout()
      label_service.innerHTML = 'Layanan'
      courier.selectedIndex = 0
   }
}

// tampilkan layanan kurir ke select service
const showServices = data => {
   service.disabled = false
   data.result.map(result => {
      result.costs.map((data, i) => {
         service.innerHTML += `<option data-id=${i} value="${data.description}">${data.service}</option>`
      })
   })
   label_service.innerHTML = 'Layanan'
}

// element ongkir, estimasi, dan total
const elCost = document.querySelector('#view-cost')
const elEtd = document.querySelector('#view-etd')
const elTotal = document.querySelector('#view-total')

// element input ongkit, estimasi, dan total
const inpCost = document.querySelector('#cost')
const inpEtd = document.querySelector('#etd')
const inpTotal = document.querySelector('#total')

// tampilkan ongkir, estimasi dan total
const showCosts = (data, i) => {
   data.result.map(res => {
      let cost = res.costs[i].cost[0].value
      let etd = res.costs[i].cost[0].etd
      let total = total_price + cost

      elCost.innerHTML = `Ongkir: ${rupiah(cost)}`
      elEtd.innerHTML = `Estimasi (Hari): ${etd}`
      elTotal.innerHTML = `Total: ${rupiah(total)}`

      inpCost.value = cost
      inpEtd.value = etd
      inpTotal.value = total
   })

   btn_checkout.classList.replace('btn-secondary', 'btn-primary')
   btn_checkout.disabled = false
   btn_checkout.innerHTML = '<i class="fa-solid fa-bag-shopping"></i> Checkout'
}

// tampilkan modal checkout
const showInvoice = (data) => {
   const modal_checkout = new bootstrap.Modal("#modal-checkout")
   const modal_title = document.querySelector(".modal-title")
   const modal_footer = document.querySelector('.modal-footer')

   modal_title.innerHTML = 'Detail Invoice'
   modal_footer.innerHTML = `
      <a href="/checkout/export/${data.result.id}" class="btn btn-success btn-sm px-3"><i class="fa-solid fa-file-pdf"></i> Cetak PDF</a>
      <a href="/cart" class="btn btn-primary btn-sm px-3"><i class="fa-solid fa-check-double"></i> Selesai</a>
   `
   detailInvoice(data)
   modal_checkout.show()
}

// tampilkan detail invoice ke modal checkout
const detailInvoice = (data) => {
   // element detail invoice
   const elCount_down_deadline = document.querySelector('#modal-checkout #count-down-deadline')
   const elInvoice = document.querySelector('#modal-checkout #invoice')
   const elOrder_date = document.querySelector('#modal-checkout #order-date')
   const elDeadline_date = document.querySelector('#modal-checkout #deadline-date')
   const elCourier = document.querySelector('#modal-checkout #courier')
   const elService = document.querySelector('#modal-checkout #service')
   const elEstimate = document.querySelector('#modal-checkout #estimate')
   const elCost = document.querySelector('#modal-checkout #cost')
   const elTotal = document.querySelector('#modal-checkout #total')
   const elAddress = document.querySelector('#modal-checkout #address')
   const tbody = document.querySelector('#modal-checkout #table-product-checkout tbody')

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

// hitung ulang ongkir
const recountCosts = () => {
   refreshButtonCheckout()
   btn_checkout.innerHTML = `${loading} Loading...`
   const service_id = service.options[service.selectedIndex].dataset.id
   getServiceOrCosts(showCosts, service_id)
}

// bersihkan select service
const clearService = () => {
   service.innerHTML = ''
   service.innerHTML = `<option value=""> -- Pilih Service -- </option>`
   service.selectedIndex = 0
}

// bersihkan ongkir
const clearCost = () => {
   elCost.innerHTML = `Ongkir: -`
   elEtd.innerHTML = `Estimasi (Hari): -`
   elTotal.innerHTML = `Total: -`

   inpCost.value = ''
   inpEtd.value = ''
   inpTotal.value = ''
}

// kembalikan tombol checkout ke kondisi awal
const refreshButtonCheckout = () => {
   btn_checkout.disabled = true
   btn_checkout.classList.replace('btn-primary', 'btn-secondary')
}