import {request, alert, confirm, rupiah} from "../home.js"

const table_product = $('#table-product').DataTable({
   serverSide: true,
   ajax: '/store-admin/product',
   columns: [
      {data: 'DT_RowIndex', name: 'id', orderable: false, searchable: false},
      {data: 'name', name: 'name'},
      {data: 'price', name: 'price'},
      {data: 'discount', name: 'discount'},
      {data: 'stock', name: 'stock'},
      {data: 'weight', name: 'weight'},
      {data: 'sold', name: 'sold'},
      {data: 'opsi', name: 'opsi', orderable: false, searchable: false},
   ],
   drawCallback: () => {
      // tombol detail produk
      const btn_details = document.querySelectorAll('.btn-product-detail')
      btn_details.forEach(detail => detail.addEventListener('click', async function () {
         const product_id = this.dataset.id
         const url = `/store-admin/product/${product_id}`
         const method = 'GET'

         const product = await request(url, method)
         detailProduct(product.result)
      }))

      // tombol hapus produk
      const btn_delete = document.querySelectorAll('.btn-delete-product')
      btn_delete.forEach(del => del.addEventListener('click', function () {
         const product_id = this.dataset.id
         const url = `/store-admin/product/${product_id}`
         const token = this.dataset.token
         const method = 'DELETE'
         confirm('Produk akan dihapus', 'Hapus produk', () => deleteProduct(url, method, token))
      }))
   }
})

// hitung harga setelah diskon
const price = document.querySelector('#price')
const discount = document.querySelector('#discount')
const discount_price = document.querySelector('#discount_price')
if (price) {
   price.addEventListener('input', () => countDiscountPrice())
   discount.addEventListener('input', () => countDiscountPrice())
}

// hitung harga setelah diskon
const countDiscountPrice = () => {
   let result = Number(price.value) - (Number(price.value) * Number(discount.value)) / 100
   discount_price.value = result
}

// modal detail produk
const detailProduct = (product) => {
   const modal_product = new bootstrap.Modal('#modal-product')
   const modal_title = document.querySelector('.modal-title')
   const modal_body = document.querySelector('.modal-body #form-product')
   const modal_footer = document.querySelector('.modal-footer')

   modal_title.innerHTML = 'Detail Produk'
   modal_body.innerHTML = showDetail(product)
   modal_footer.innerHTML = '<buton class="btn btn-primary btn-sm px-3" data-bs-dismiss="modal"><i class="fa-solid fa-check"></i> Oke</buton>'
   modal_product.show()
   changePreviewImg()
}

// tampilkan detail produk ke modal
const showDetail = (product) => {
   let content = `
   <div class="row">
      <div class="col-12 col-md-6">
         <div class="card overflow-hidden rounded">
            <div class="position-relative">
               <div class="${product.discount ? 'position-absolute' : 'd-none'} text-bg-success discount start-0 small py-2 px-3 shadow">Diskon ${product.discount}%</div>
               <img src="/storage/img/${product.image_1}" alt="${product.name}" class="img-fluid main-img w-100 h-100" loading="lazy" />
            </div>
         </div>
         <div class="row mt-4">
            <div class="col-4">
               <div class="card overflow-hidden rounded">
                  <img src="/storage/img/${product.image_1}" alt="${product.name}" class="img-fluid other-img" loading="lazy" />
               </div>
            </div>
            <div class="col-4">
               <div class="card overflow-hidden rounded">
                  <img src="/storage/img/${product.image_2}" alt="${product.name}" class="img-fluid other-img" loading="lazy" />
               </div>
            </div>
            <div class="col-4">
               <div class="card overflow-hidden rounded">
                  <img src="/storage/img/${product.image_3}" alt="${product.name}" class="img-fluid other-img" loading="lazy" />
               </div>
            </div>
         </div>
         <hr class="d-block d-md-none my-4 bg-dark">
      </div>
      <div class="col-12 col-md-6 py-lg-5 py-0">
         <h5 class="text-capitalize">${product.name}</h5>`

   product.categories.map(category => content += `<span class="badge text-bg-secondary rounded-pill me-1 my-1 px-2">${category.name}</span>`)

   content += `<p class="my-0">Varian Warna:</p><div class="d-flex align-items-center flex-wrap">`

   product.colors.map(color => content += `
   <span class="badge text-capitalize text-bg-light border-secondary border me-2 mb-2 px-2">${color.name} <i class="fa-solid fa-circle text-${color.name}"></i></span>`)

   content += `</div><p class="my-0">Varian Ukuran:</p>`

   product.sizes.map(size => content += `<span class="badge text-bg-light border-secondary border me-1 my-1 mb-3 px-2">${size.name}</span>`)

   content += `
   <h6>Harga: ${rupiah(product.price)} <i class="fa-solid fa-tags"></i></h6>
   <h6>Stok: ${formatNumber(product.stock)}</h6>
   <h6>Berat: ${formatNumber(product.weight)} gram</h6>
   <h6>Terjual: ${formatNumber(product.sold)} produk</h6>
   </div>
   </div>`

   content += `
   <div class="row">
      <div class="col">
      <hr class="my-4 bg-dark">
      <h5>Deskripsi Produk</h5>
         <p class="my-0">${product.detail}</p>
      </div>
   </div>`
   return content
}

// hapus produk
const deleteProduct = async (url, method, token) => {
   try {
      const data = await request(url, method, token)
      alert('success', 'Sukses', data.message, reload)
   } catch (error) {
      alert('error', 'Oops', error.message, reload)
   }
}

// format angka
const formatNumber = (input) => new Intl.NumberFormat('id-ID').format(input)

// reload data table
const reload = () => table_product.ajax.reload(null, false)

// Tampilkan foto produk lainnya ke foto utama saat diklik
const changePreviewImg = () => {
   const main_img = document.querySelector('.main-img')
   const other_imgs = document.querySelectorAll('.other-img')
   other_imgs.forEach(img => img.addEventListener('click', function () {
      main_img.src = this.src
   }))
}

// Drag and Drop foto produk
document.querySelectorAll(".drop-zone__input").forEach((inputElement) => {
   const dropZoneElement = inputElement.closest(".drop-zone")

   dropZoneElement.addEventListener("click", (e) => {
      inputElement.click()
   })

   inputElement.addEventListener("change", (e) => {
      if (inputElement.files.length) {
         updateThumbnail(dropZoneElement, inputElement.files[0])
      }
   })

   dropZoneElement.addEventListener("dragover", (e) => {
      e.preventDefault()
      dropZoneElement.classList.add("drop-zone--over")
   });

   ["dragleave", "dragend"].forEach((type) => {
      dropZoneElement.addEventListener(type, (e) => {
         dropZoneElement.classList.remove("drop-zone--over")
      })
   })

   dropZoneElement.addEventListener("drop", (e) => {
      e.preventDefault()

      if (e.dataTransfer.files.length) {
         inputElement.files = e.dataTransfer.files
         updateThumbnail(dropZoneElement, e.dataTransfer.files[0])
      }

      dropZoneElement.classList.remove("drop-zone--over")
   })
})

/**
 * Updates the thumbnail on a drop zone element.
 *
 * @param {HTMLElement} dropZoneElement
 * @param {File} file
 */
function updateThumbnail (dropZoneElement, file) {
   let thumbnailElement = dropZoneElement.querySelector(".drop-zone__thumb")

   // First time - remove the prompt
   if (dropZoneElement.querySelector(".drop-zone__prompt")) {
      dropZoneElement.querySelector(".drop-zone__prompt").remove()
   }

   // First time - there is no thumbnail element, so lets create it
   if (!thumbnailElement) {
      thumbnailElement = document.createElement("div")
      thumbnailElement.classList.add("drop-zone__thumb")
      dropZoneElement.appendChild(thumbnailElement)
   }

   thumbnailElement.dataset.label = file.name

   // Show thumbnail for image files
   if (file.type.startsWith("image/")) {
      const reader = new FileReader()

      reader.readAsDataURL(file)
      reader.onload = () => {
         thumbnailElement.style.backgroundImage = `url('${reader.result}')`
      }
   } else {
      thumbnailElement.style.backgroundImage = `url(/storage/img/product/default.jpg)`
   }
}