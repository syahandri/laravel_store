import {rupiah, request, alert, loading} from "./home.js"

// get detail invoice dari select invoice
const select_invoice = document.querySelector('#invoice')
select_invoice.addEventListener('change', async function () {
   const url = `/checkout/${this.value}`
   const method = 'GET'

   try {
      const invoice = await request(url, method)
      showDetail(invoice)
   } catch (error) {
      alert('error', 'Oops', 'Gagal memuat data, silahkan coba lagi.')
   }
})

// tampil detail invoice ke tabel
const showDetail = (data) => {
   const tbody = document.querySelector('#table-detail tbody')
   const cost = document.querySelector('#cost')
   const total = document.querySelector('#total')
   const inpInv = document.querySelector('input[name="inv"]')
   inpInv.value = data.result.invoice

   tbody.innerHTML = ''
   data.result.products.forEach((product, i) => {
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

   cost.innerHTML = `Ongkir: ${rupiah(data.result.cost)}`
   total.innerHTML = `Total: ${rupiah(data.result.total)}`
}

// submit form konfirmasi
const form_confirm = document.querySelector('#form-confirm')
const btn_submit = document.querySelector('#form-confirm button[type="submit"]')
const invoice = document.querySelector('#invoice')
const banks = document.querySelectorAll('input[name="bank"]')
const name = document.querySelector('#name')
const image = document.querySelector('#image')
form_confirm.addEventListener('submit', async function (e) {
   e.preventDefault()
   const url = '/payment-confirm/confirm'
   const method = 'POST'
   const token = document.querySelector('input[name="_token"]').value
   const formData = new FormData(this)

   try {
      // hilangkan class invalid dalam input
      const forms = document.querySelectorAll('.form')
      forms.forEach(form => form.classList.remove('is-invalid'))

      btn_submit.innerHTML = `${loading} Loading...`
      btn_submit.disabled = true

      // submit form
      const data = await request(url, method, token, formData)
      alert('success', 'Sukses', data.message, ()=> location.reload())
   } catch (error) {
      // error validasi
      if (error.code == 422) {
         const inv_invoice = document.querySelector('.invalid-invoice')
         const inv_bank = document.querySelector('.invalid-bank')
         const inv_name = document.querySelector('.invalid-name')
         const inv_image = document.querySelector('.invalid-image')

         if (error.invalid.invoice) {
            invoice.classList.add('is-invalid')
            inv_invoice.innerHTML = error.invalid.invoice
         } else {
            invoice.classList.remove('is-invalid')
         }

         if (error.invalid.bank) {
            banks.forEach(bank => bank.classList.add('is-invalid'))
            inv_bank.innerHTML = error.invalid.bank
         } else {
            banks.forEach(bank => bank.classList.remove('is-invalid'))
         }

         if (error.invalid.name) {
            name.classList.add('is-invalid')
            inv_name.innerHTML = error.invalid.name
         } else {
            name.classList.remove('is-invalid')
         }

         if (error.invalid.image) {
            image.classList.add('is-invalid')
            inv_image.innerHTML = error.invalid.image
         } else {
            image.classList.remove('is-invalid')
         }
      } else {
         // error selain validasi
         alert('error', 'Oops', error.message, ()=> location.reload())
      }

      restateButton()
   }
})

// kembalikan button submit ke kondisi awal
const restateButton = () => {
   btn_submit.innerHTML = '<i class="fa-solid fa-check"></i> Konfirmasi'
   btn_submit.disabled = false
}