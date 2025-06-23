@extends('components.layouts.app')

@push('styles')
<style>
   .about_section {
      background: url("{{ asset('front/images/BG-About-1.jpg') }}") no-repeat center center;
      background-size: cover;
      padding: 60px 0;
      color: white;
   }

   .about_taital_box.overlay-box {
      background-color: rgba(0, 0, 0, 0.7); /* lapisan hitam transparan */
      padding: 30px;
      border-radius: 20px;
   }

   .about_text {
      color: white;
      text-align: justify; /* ini membuat teks rata kiri-kanan */
   }
   
   .about_taital {
      color: white;
   }

   .about_taital span {
      color: #fe5b29;
   }
</style>
@endpush

@section('about')
{{-- About Section --}}
   <div class="about_section layout_padding">
      <div class="container">
         <div class="about_section_2">
            <div class="row"> 
               <div class="col-md-6"> 
                     <div class="about_taital_box overlay-box">
                        <h1 class="about_taital">About <span>Us</span></h1>
                        <p class="about_text">
                           Sistem dari website AHHH Restaurant ini menyediakan beberapa informasi, yaitu informasi terkait website ini sendiri dan juga menampilkan beberapa menu yang direkomendasikan.
                        </p>
                     </div>
               </div>
            </div>
         </div>
      </div>
   </div>
@endsection