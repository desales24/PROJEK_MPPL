<!DOCTYPE html>
<html>
   <!-- head -->
   @include('components.partials.head')
   <!-- end head -->
   <body>
      <!-- header section start -->
      <!-- nav -->
      @include('components.partials.nav')
      <!-- end nav -->
      <!-- header section end -->

      {{$slot}}

      <!-- banner section start --> 
      <!-- home -->
      <!-- end home -->
      <!-- banner section end -->

      <!-- about section start -->
      <!-- about -->
      <!-- end about -->
      <!-- about section end -->

      <!-- gallery section start -->
      <!-- menu -->
      <!-- end menu -->
      <!-- gallery section end -->

      <!-- footer section start -->
      <!-- footer -->
      @include('components.partials.footer')
      <!-- end footer -->
      <!-- footer section end -->

      <!-- copyright section start -->
      <!-- copyright -->
      <!-- end copyright -->
      @include('components.partials.copyright')
      <!-- copyright section end -->

      <!-- script -->
      @include('components.partials.script')
      <!-- end script -->
   </body>
</html>