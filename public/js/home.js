// Scroll ke section saat di home
if (window.location.pathname == '/') {
   const navHome = document.querySelector('#nav-home')
   const navNewest = document.querySelector('#nav-newest')
   const navBestSeller = document.querySelector('#nav-best-seller')
   const navCheapest = document.querySelector('#nav-cheapest')

   if (navHome || navNewest || navBestSeller || navCheapest) {
      navHome.addEventListener('click', function (e) {
         e.preventDefault()
         this.classList.add('active')
         navNewest.classList.remove('active')
         navBestSeller.classList.remove('active')
         navCheapest.classList.remove('active')

         const sectionHome = document.querySelector('#home')
         sectionHome.scrollIntoView()
      })

      navNewest.addEventListener('click', function (e) {
         e.preventDefault()
         this.classList.add('active')
         navHome.classList.remove('active')
         navBestSeller.classList.remove('active')
         navCheapest.classList.remove('active')

         const sectionNewest = document.querySelector('#terbaru')
         sectionNewest.scrollIntoView()
      })

      navBestSeller.addEventListener('click', function (e) {
         e.preventDefault()
         this.classList.add('active')
         navHome.classList.remove('active')
         navNewest.classList.remove('active')
         navCheapest.classList.remove('active')

         const sectionBestSeller = document.querySelector('#terlaris')
         sectionBestSeller.scrollIntoView()
      })

      navCheapest.addEventListener('click', function (e) {
         e.preventDefault()
         this.classList.add('active')
         navHome.classList.remove('active')
         navNewest.classList.remove('active')
         navBestSeller.classList.remove('active')

         const sectionCheapest = document.querySelector('#termurah')
         sectionCheapest.scrollIntoView()
      })
   }
}

// Back to top button
const backToTop = document.querySelector('.back-to-top')
if (backToTop) {
   window.onscroll = function () {
      if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
         backToTop.style.display = "block"
      } else {
         backToTop.style.display = "none"
      }
   }

   backToTop.addEventListener('click', () => {
      document.body.scrollTop = 0 // For Safari
      document.documentElement.scrollTop = 0 // For Chrome, Firefox, IE and Opera
   })
}

// Fungsi fetch API
const request = (url, method, token, body) => {
   return fetch(url, {
      headers: {'X-CSRF-TOKEN': token},
      method,
      body
   })
      .then(res => res.json())
      .then(data => {
         if (data.code != 200) {
            throw data
         }

         return data
      })
}

// Fungsi hanya angka untuk input
const onlyNumber = input => input.value = input.value.replace(/[^0-9]/g, "")

// format rupiah
const rupiah = (input) => {
   return `Rp. ${new Intl.NumberFormat('id-ID').format(input)}`
}


// Fungsi alert (swetalert)
const alert = (icon, title, text, func) => {
   Swal.fire({
      icon,
      title,
      text,
      allowOutsideClick: false,
      allowEscapeKey: false
   }).then(() => {
      if (func) func()
   })
}

// Fungsi confirm (swetalert)
const confirm = (text, confirmText, func) => {
   Swal.fire({
      icon: 'question',
      title: 'Yakin?',
      text,
      confirmButtonText: confirmText,
      confirmButtonColor: '#dc3545',
      showCancelButton: true,
      reverseButtons: true,
      allowOutsideClick: false,
      allowEscapeKey: false
   }).then(async result => {
      if (result.isConfirmed) {
         if (func) func()
      }
   })
}

// Hitung jumlah produk dalam keranjang
const countCart = async () => {
   const spanCount = document.querySelector('.count-cart')
   const url = '/cart/count'
   const method = 'GET'
   const data = await request(url, method)
   spanCount.innerHTML = data.count
}

const loading = `<span class="spinner-grow spinner-grow-sm" role="status"></span>`

export {request, onlyNumber, rupiah, alert, confirm, countCart, loading}