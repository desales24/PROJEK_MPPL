@extends('components.layouts.app')

@push('styles')
<style>
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

@section('order')
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