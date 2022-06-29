import {onlyNumber, request, alert, countCart} from "./home.js"

// Tampilkan foto produk lainnya ke foto utama saat diklik
const main_img = document.querySelector('.main-img')
const other_imgs = document.querySelectorAll('.other-img')
other_imgs.forEach(img => img.addEventListener('click', function () {
   main_img.src = this.src
}))

// Membatasi input quantity
const stock = document.querySelector('input[name="stock"]')
const quantity = document.querySelector('#quantity')
quantity.addEventListener('input', function () {
   onlyNumber(this)
   this.value = parseInt(this.value) > parseInt(stock.value) ? stock.value : this.value
})

// Form tambah ke keranjang
const form_add_cart = document.querySelector('#form-add-cart')
form_add_cart.addEventListener('submit', async function (e) {
   e.preventDefault()
   const url = '/cart/add'
   const method = 'POST'
   const token = document.querySelector('input[name="_token"]').value
   const body = new FormData(this)
   quantity.classList.remove('is-invalid')

   try {
      const res = await request(url, method, token, body)
      alert('success', 'Berhasil', res.message)
      countCart()
   } catch (error) {
      // Error validasi quantity
      if (error.code == 422) {
         const invalid_quantity = document.querySelector('.invalid-quantity')
         quantity.classList.add('is-invalid')
         invalid_quantity.innerHTML = error.invalid.quantity
         return false
      }

      alert('error', 'Oops', error.message)
   }
})