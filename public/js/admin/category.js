import {request, alert, confirm} from "../home.js"

const form_category = document.querySelector('#form-category')
const input_category = document.querySelector('#name')

// tombol tambah kategori
let isInsert = true
const btn_add = document.querySelector('#btn-add')
btn_add.addEventListener('click', () => {
   isInsert = true
   showModal()
})

// tombol ubah
let category_id = ''
const btn_edit = document.querySelectorAll('.btn-edit')
btn_edit.forEach(btn => btn.addEventListener('click', async function (e) {
   e.preventDefault()
   isInsert = false
   category_id = this.dataset.id
   const url = `/store-admin/category/${category_id}`
   const method = 'GET'
   const data = await request(url, method)
   showModal(data.result)
}))

// tombol hapus
const btn_delete = document.querySelectorAll('.btn-delete')
btn_delete.forEach(btn => btn.addEventListener('click', function (e) {
   e.preventDefault()
   category_id = this.dataset.id
   const url = `/store-admin/category/${category_id}`
   const method = 'DELETE'
   const token = document.querySelector('input[name="_token"]').value
   confirm('Kategori akan dihapus', 'Hapus kategori', () => doDelete(url, method, token))
}))

// submit form kategori
form_category.addEventListener('submit', async function (e) {
   e.preventDefault()
   let url = ''
   const method = 'POST'
   const token = document.querySelector('input[name="_token"]').value
   const spoof_method = document.querySelector('input[name="_method"]').value
   const formData = new FormData(this)

   // cek apakah insert atau update
   if (isInsert) {
      url = '/store-admin/category'
   } else {
      url = `/store-admin/category/${category_id}`
      formData.append('_method', spoof_method)
   }

   // submit
   try {
      const data = await request(url, method, token, formData)
      modal_category.hide()
      alert('success', 'Sukses', data.message, () => location.reload())
   } catch (error) {

      // error validation
      if (error.code == 422) {
         const invCategory = document.querySelector('.invalid-feedback')
         input_category.classList.add('is-invalid')
         invCategory.innerHTML = error.invalid.name
         return false
      }

      modal_category.hide()
      alert('error', 'Oops', error.message)
   }
})

// modal kategori
const modal_category = new bootstrap.Modal('#modal-category')
const modal_title = document.querySelector('.modal-title')
const showModal = (category) => {
   form_category.reset()
   modal_category.show()
   input_category.classList.remove('is-invalid')

   if (isInsert) {
      modal_title.innerHTML = 'Tambah kategori'
   } else {
      input_category.value = category.name
      modal_title.innerHTML = 'Edit kategori'
   }
}

// delete kategori
const doDelete = async (url, method, token) => {
   try {
      const data = await request(url, method, token)
      alert('success', 'Sukses', data.message, () => location.reload())
   } catch (error) {
      alert('error', 'Oops', error.message)
   }
}