import {onlyNumber, request, alert, loading} from "./home.js"

// Modal profil
const modal_profile = new bootstrap.Modal("#modal-profile")
const modal_profile_title = document.querySelector("#modal-profile .modal-title")
const form_profile = document.querySelector("#modal-profile .modal-body #form-profile")

// Tombol edit profil
const btnEditProfile = document.querySelector("#btn-edit-profile")
btnEditProfile.addEventListener("click", async function () {
   const url = `/profile/${this.dataset.id}`
   const method = 'GET'
   modal_profile_title.innerHTML = "Edit Profil"

   try {
      const user = await request(url, method)
      modal_profile.show()
      form_profile.innerHTML = profileElements(user)
   } catch (error) {
      alert('error', 'Oops', 'Gagal memuat data user, silahkan coba lagi.')
   }
})

// submit form profile
form_profile.addEventListener('submit', async function (e) {
   e.preventDefault()
   const user_id = document.querySelector('#form-profile input[name="id"]').value
   const url = `/profile/${user_id}`
   const method = 'POST'
   const token = document.querySelector('.modal-body input[name="_token"]').value
   const spoof_method = document.querySelector('.modal-body input[name="_method"]').value
   const formData = new FormData(this)
   formData.append('_method', spoof_method)

   try {
      const data = await request(url, method, token, formData)
      modal_profile.hide()
      alert('success', 'Sukses', data.message, () => location.reload())
   } catch (error) {
      if (error.code == 422) {
         return errorValidation(error)
      }
      modal_profile.hide()
      alert('error', 'Oops', error.message, () => location.reload())
   }

})

// Elemen input edit profil
const profileElements = (data) => {
   return `
      <div class="row">
         <div class="col-sm-6">
            <div class="mb-2 small">
               <input type="hidden" name="id" id="id" value="${data.result.id}">
               <input type="hidden" name="old_image" id="old_image" value="${data.result.image}">
               <label for="name" class="form-label">Nama Lengkap</label>
               <input type="text" class="form-control text-capitalize form-control-sm form" name="name" id="name" placeholder="nama lengkap..." value="${data.result.name}" required>
               <div class="invalid-feedback invalid-name"></div>
            </div>
         </div>
         <div class="col-sm-6">
            <div class="mb-2 small">
               <label for="phone" class="form-label">Telepon / WhatsApp</label>
               <input type="text" class="form-control form-control-sm form" name="phone" id="phone" placeholder="telepon..." value="${data.result.phone ?? ""}" maxlength="13" >
               <div class="invalid-feedback invalid-phone"></div>
            </div>
         </div>
         <div class="col-sm-6">
            <div class="mb-2 small">
               <label for="email" class="form-label">Email</label>
               <input type="email" class="form-control form-control-sm form" name="email" id="email" placeholder="email..." value="${data.result.email}" disabled required>
            </div>
         </div>
         <div class="col-sm-6">
            <div class="mb-2 small">
               <label for="image" class="form-label">Foto Profil</label>
               <input class="form-control form-control-sm form" name="image" id="image" type="file" accept="image/jpg, image/jpeg, image/png">
               <span class="small">*Upload foto maks. 1 MB tipe PNG, JPG, JPEG</span>
               <div class="invalid-feedback invalid-image"></div>
            </div>
         </div>
         <div class="row">
            <div class="col">
               <img class="rounded-circle" src="/storage/img/${data.result.image}" alt="profile photo" id="preview-img">
            </div>
         </div>
      </div>
   `
}

// Modal alamat
const modal_address = new bootstrap.Modal("#modal-addresses")
const modal_address_title = document.querySelector("#modal-addresses .modal-title")
const form_address = document.querySelector("#modal-addresses .modal-body #form-addresses")

// Status dalam mode simpan / edit
let isInsert = true

// Tombol tambah alamat
const btn_add_address = document.querySelector("#add-address")
if (btn_add_address) {
   btn_add_address.addEventListener("click", () => {
      isInsert = true
      modal_address_title.innerHTML = "Tambah Alamat"
      modal_address.show()
      form_address.innerHTML = addressElements()

      getProvince()
      showCity()
   })
}

// Tombol edit alamat
const btnEditAddress = document.querySelectorAll(".btn-edit-address")
btnEditAddress.forEach((btn) =>
   btn.addEventListener("click", async function (e) {
      e.preventDefault()
      isInsert = false
      const address_id = this.dataset.id
      const url = `/address/${address_id}`
      const method = 'GET'

      const data = await request(url, method)
      const province_id = data.result.province_id

      modal_address_title.innerHTML = "Edit Alamat"
      modal_address.show()
      form_address.innerHTML = addressElements(data)    

      getProvince(data)
      showCity()
      getCity(province_id, data)
   })
)

form_address.addEventListener('submit', async function (e) {
   e.preventDefault()
   const address_id = document.querySelector('#form-addresses input[name="id"]').value
   const token = document.querySelector('#modal-addresses .modal-body input[name="_token"]').value
   const select_province = document.querySelector("#province")
   const select_city = document.querySelector("#city")
   
   const formData = new FormData(this)
   formData.append('province_id', select_province.options[select_province.selectedIndex].dataset.id)
   formData.append('city_id', select_city.options[select_city.selectedIndex].dataset.id)
   
   let url = ''
   const method = 'POST'
   if (isInsert) {
      url = `/address`
   } else {
      url = `/address/${address_id}`
      const spoof_method = document.querySelector('#modal-addresses .modal-body input[name="_method"]').value
      formData.append('_method', spoof_method)
   }

   try {
      const data = await request(url, method, token, formData)
      modal_address.hide()
      alert('success', 'Sukses', data.message, () => location.reload())
   } catch (error) {
      if (error.code == 422) {
         return errorValidation(error)
      }
      modal_address.hide()
      alert('error', 'Oops', error.message, () => location.reload())
   }
})

// Element input tambah / edit alamat
const addressElements = (data) => {
   return `
   <div class="row">
      <div class="col-12">
         <div class="mb-2 small">
            <input type="hidden" name="id" id="id" value="${data ? data.result.id : ""}">
            <label for="address" class="form-label">Alamat</label>
            <textarea class="form-control text-capitalize form-control-sm form" name="address" id="address" rows="3" placeholder="alamat..." required>${data ? data.result.address : ""}</textarea>
            <div class="invalid-feedback invalid-address"></div>
         </div>
      </div>
      <div class="col-sm-6">
         <div class="mb-2 small">
            <label for="province" class="form-label">Provinsi</label>
            <select class="form-select form-select-sm form" name="province" id="province" disabled required>
            <option value="">-- Pilih Provinsi --</option>
            </select>
            <div class="invalid-feedback invalid-province"></div>
         </div>
      </div>
      <div class="col-sm-6">
         <div class="mb-2 small">
            <label for="city" class="form-label">Kabupaten / Kota</label>
            <select class="form-select form-select-sm form" name="city" id="city" required>
               <option value="">-- Pilih Kabupaten --</option>
            </select>
            <div class="invalid-feedback invalid-city"></div>
         </div>
      </div>
      <div class="col-sm-6">
         <div class="mb-2 small">
            <label for="sub_district" class="form-label">Kecamatan</label>
            <input type="text" class="form-control text-capitalize form-control-sm form" name="sub_district" id="sub_district" placeholder="kecamatan..." value="${data ? data.result.sub_district : ""}" required>
            <div class="invalid-feedback invalid-sub_district"></div>
            </div>
      </div>
      <div class="col-sm-6">
         <div class="mb-2 small">
            <label for="post_code" class="form-label">Kode Pos</label>
            <input type="text" class="form-control text-capitalize form-control-sm form" name="postal_code" id="postal_code" placeholder="kode pos..." value="${data ? data.result.postal_code : ""}" required>
            <div class="invalid-feedback invalid-postal_code"></div>
         </div>
      </div>
      <div class="col-12">
         <div class="form-check small">
            <input class="form-check-input" type="checkbox" name="flags" id="flags" value="1" ${data ? (data.result.flags == 1 ? 'checked' : '') : ""}>
            <label class="form-check-label" for="flags">Jadikan sebagai alamat utama</label>
         </div>
      </div>
   </div>
   `
}

// Isi provinsi dari API raja ongkir
const getProvince = async (data) => {
   const url = '/province'
   const method = 'GET'

   const select_province = document.querySelector("#province")
   const label_province = document.querySelector('label[for="province"]')
   label_province.innerHTML = `Memuat Provinsi ${loading}`

   try {
      const province = await request(url, method)

      select_province.innerHTML = '<option value="">-- Pilih Provinsi --</option>'
      province.result.forEach((prov) => {
         select_province.innerHTML += `
            <option data-id="${prov.province_id}" value="${prov.province}" ${data ? (prov.province_id == data.result.province_id ? "selected" : "") : ""}>
               ${prov.province}
            </option>`
      })
      select_province.disabled = false
      label_province.innerHTML = 'Provinsi'
   } catch (error) {
      modal_address.hide()
      alert('error', 'Oops', 'Gagal memuat provinsi, silahkan coba lagi.', () => location.reload())
   }
}


// tampilkan kabupaten / kota dari provinsi
const showCity = (data) => {
   const select_province = document.querySelector("#province")
   select_province.addEventListener('change', async function () {
      const province_id = this.options[this.selectedIndex].dataset.id
      getCity(province_id, data)
   })
}

// ambil kabupaten / kota dari provinsi
const getCity = async (province_id, data) => {
   const url = `/city?province=${province_id}`
   const method = 'GET'

   const select_city = document.querySelector('#city')
   const label_city = document.querySelector('label[for="city"]')
   select_city.disabled = true
   label_city.innerHTML = `Memuat Kabupaten ${loading}`

   try {
      const city = await request(url, method)

      select_city.innerHTML = '<option value="">-- Pilih Kabupaten --</option>'
      city.result.forEach((city) => {
         select_city.innerHTML += `
               <option data-id="${city.city_id}" value="${city.city_name}" ${data ? (city.city_id == data.result.city_id ? "selected" : "") : ""}>
                  ${city.city_name + ' - ' + city.type}
               </option>`
      })
      select_city.disabled = false
      label_city.innerHTML = 'Kabupaten / Kota'
   } catch (error) {
      province.selectedIndex = 0
      label_city.innerHTML = 'Kabupaten / Kota'
      alert('error', 'Oops', 'Gagal memuat kabupaten, silahkan coba lagi.')
   }
}

// Membatasi hanya angka untuk input telepon
document.addEventListener("input", (e) => {
   const element = e.target
   if (element.id == "phone") {
      onlyNumber(element)
   }
})

// Menampilkan preview saat foto dipilih
document.addEventListener("change", (e) => {
   const element = e.target
   if (element.id == "image") {
      const preview = document.querySelector("#preview-img")
      const fileImg = new FileReader()
      if (element.files[0]) {
         fileImg.readAsDataURL(element.files[0])
         fileImg.onload = (e) => {
            preview.src = e.target.result
         }
      } else {
         preview.src = "/storage/img/profile/default.jpg"
      }
   }
})

const errorValidation = (error) => {
   // element profil
   const name = document.querySelector('#name')
   const phone = document.querySelector('#phone')
   const image = document.querySelector('#image')
   const inv_name = document.querySelector('.invalid-name')
   const inv_phone = document.querySelector('.invalid-phone')
   const inv_image = document.querySelector('.invalid-image')

   // element alamat
   const address = document.querySelector('#address')
   const province = document.querySelector('#province')
   const city = document.querySelector('#city')
   const sub_district = document.querySelector('#sub_district')
   const postal_code = document.querySelector('#postal_code')
   const inv_address = document.querySelector('.invalid-address')
   const inv_province = document.querySelector('.invalid-province')
   const inv_city = document.querySelector('.invalid-city')
   const inv_sub_district = document.querySelector('.invalid-sub_district')
   const inv_postal_code = document.querySelector('.invalid-postal_code')

   // hilangkan class invalid dalam input
   const forms = document.querySelectorAll('.form')
   forms.forEach(form => form.classList.remove('is-invalid'))

   // error profil
   if (error.invalid.name) {
      name.classList.add('is-invalid')
      inv_name.innerHTML = error.invalid.name
   }

   if (error.invalid.phone) {
      phone.classList.add('is-invalid')
      inv_phone.innerHTML = error.invalid.phone
   }

   if (error.invalid.image) {
      image.classList.add('is-invalid')
      inv_image.innerHTML = error.invalid.image
   }

   // error alamat
   if (error.invalid.address) {
      address.classList.add('is-invalid')
      inv_address.innerHTML = error.invalid.address
   }

   if (error.invalid.province) {
      province.classList.add('is-invalid')
      inv_province.innerHTML = error.invalid.province
   }

   if (error.invalid.city) {
      city.classList.add('is-invalid')
      inv_city.innerHTML = error.invalid.city
   }

   if (error.invalid.sub_district) {
      sub_district.classList.add('is-invalid')
      inv_sub_district.innerHTML = error.invalid.sub_district
   }

   if (error.invalid.postal_code) {
      postal_code.classList.add('is-invalid')
      inv_postal_code.innerHTML = error.invalid.postal_code
   }
}