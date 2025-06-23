<!DOCTYPE html>
<html lang="en">
   <head>
      <!-- head -->
      @include('components.partials.head')
      
      @stack('styles')

      <!-- header section start -->
      @include('components.partials.nav')
      <!-- header section end -->
   </head>
   <body>
      
      <!-- home section start -->
      @yield('home')
      
      <!-- about section start -->
      @yield('about')
      
      <!-- about section start -->
      @yield('order')

      <!-- footer section start -->
      @include('components.partials.footer')
      @include('components.partials.copyright')
      <!-- footer section end -->

      <!-- script -->
      @include('components.partials.script')
   </body>
</html>  