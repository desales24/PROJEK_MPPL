@extends('components.layouts.app')

@push('styles')
<style>
/* Banner Section */
.banner_section {
   background: url("{{ asset('front/images/BG-Home.jpg') }}") no-repeat center center;
   background-size: cover;
   padding: 100px 0;
   color: white;
   position: relative;
   z-index: 1;
}

.banner_text {
   color: white;
   text-align: justify;
}

.banner_taital {
   color: white;
   text-align: justify;
}

.banner_taital_main {
   background-color: rgba(0, 0, 0, 0.7); /* untuk kontras teks */
   padding: 30px;
   border-radius: 10px;
}

/* About Section */
.about_section {
   /* background image moved to inline style in HTML */
   background-size: cover;
   padding: 80px 0;
   color: white;
}

.about_taital_box {
   background-color: rgba(0, 0, 0, 0.7);
   padding: 30px 40px;
   border-radius: 10px;
}

.about_text {
   color: white;
   text-align: justify;
}

.about_taital {
   color: white;
   white-space: nowrap;
}

.about_taital span {
   color: #fe5b29;
}

/* Order Section */
.gallery_section {
   background: url("{{ asset('front/images/BG-Menu-2.jpg') }}") no-repeat center center;
   background-size: cover;
   padding: 80px 0;
   color: white;
}

.gallery_taital {
   color: white;
   text-align: center;
   margin-bottom: 40px;
}
</style>
@endpush

@section('home')
   {{-- Banner Section --}}
   <div class="banner_section layout_padding">
      <div class="container">
         <div class="row">
            <div class="col-md-6">
               <div id="banner_slider" class="carousel slide" data-ride="carousel">
                  <div class="carousel-inner">
                     <div class="carousel-item active">
                        <div class="banner_taital_main">
                           <h1 class="banner_taital">Recomendation <br><span style="color: #fe5b29;">For You</span></h1>
                           <p class="banner_text">Sate Ayam yang dihidangkan menggunakan ayam yang dirawat dengan hati-hati sehingga membuat daging menjadi lebih segar dan nikmat.</p>
                           <div class="btn_main">
                              <div class="contact_bt"><a href="http://localhost/order">Pesan</a></div>
                           </div>
                        </div>
                     </div>
                     <div class="carousel-item">
                        <div class="banner_taital_main">
                           <h1 class="banner_taital">Recomendation <br><span style="color: #fe5b29;">For You</span></h1>
                           <p class="banner_text">Ayam Panggang yang disajikan memiliki tingkat kematangan yang pas, dan bumbu yang digunakan menyerap hingga keseluruh bagian</p>
                           <div class="btn_main">
                              <div class="contact_bt"><a href="http://localhost/order">Pesan</a></div>
                           </div>
                        </div>
                     </div>
                     <div class="carousel-item">
                        <div class="banner_taital_main">
                           <h1 class="banner_taital">Recomendation <br><span style="color: #fe5b29;">For You</span></h1>
                           <p class="banner_text">Steak daging dihidangkan dengan hati-hati, dan bumbu yang digunakan pun dibuat menyerap keseluruh bagian daging.</p>
                           <div class="btn_main">
                              <div class="contact_bt"><a href="http://localhost/order">Pesan</a></div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <a class="carousel-control-prev" href="#banner_slider" role="button" data-slide="prev">
                     <i class="fa fa-angle-left"></i>
                  </a>
                  <a class="carousel-control-next" href="#banner_slider" role="button" data-slide="next">
                     <i class="fa fa-angle-right"></i>
                  </a>
               </div>
            </div>
            <div class="col-md-6">
               <div class="banner_img"><img src="{{ asset('front/images/banner-img.png') }}"></div>
            </div>
         </div>
      </div>
   </div>

   {{-- About Section --}}
<div class="about_section layout_padding" style="background: url('/front/images/BG-About-1.jpg') no-repeat center center; background-size: cover;">
   <div class="container">
      <div class="about_section_2">
         <div class="row">
            <div class="col-md-12"> 
               <div class="about_taital_box">
                  <h1 class="about_taital">About <span style="color: #fe5b29;">Us</span></h1>
                  <p class="about_text">
                     Sistem dari website AHHH Restaurant ini menyediakan beberapa informasi, yaitu informasi terkait website ini sendiri dan juga menampilkan beberapa menu yang direkomendasikan.
                  </p>
                  <div class="readmore_btn"><a href="#">Read More</a></div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
   
{{-- Order Section --}}
   <div class="gallery_section layout_padding">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <h1 class="gallery_taital">Our best offers</h1>
               </div>
            </div>
            <div class="gallery_section_2">
               <div class="row">
                  <div class="col-md-4">
                     <div class="gallery_box">
                        <div class="gallery_img"><img src="{{ asset('front/images/Menu-4.jpg') }}"></div>
                        <h3 class="types_text">Sate Ayam</h3>
                          <p class="looking_text">Rp. 30.000 / porsi</p>
                        <div class="read_bt"><a href="http://localhost/admin/login">Order Now</a></div>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="gallery_box">
                        <div class="gallery_img"><img src="{{ asset('front/images/Menu-2.jpg') }}"></div>
                        <h3 class="types_text">Ayam Panggang 1 Ekor</h3>
                          <p class="looking_text">Rp. 69.000 / porsi</p>
                        <div class="read_bt"><a href="http://localhost/admin/login">Order Now</a></div>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="gallery_box">
                        <div class="gallery_img"><img src="{{ asset('front/images/Menu-3.jpg') }}"></div>
                        <h3 class="types_text">Steak Sapi</h3>
                          <p class="looking_text">Rp. 35.000 / 150gram</p>
                        <div class="read_bt"><a href="http://localhost/admin/login">Order Now</a></div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
   </div>
@endsection