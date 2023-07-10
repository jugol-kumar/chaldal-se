<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="https://cdn3.devexpress.com/jslib/22.2.3/css/dx.light.css" />
{{--      @vite(['resources/sass/app.scss','resources/js/app.js'])--}}
      <link rel="icon" href="{{ Storage::url(get_setting('logo_fabs')) }}">

      <link rel="stylesheet" href="{{ mix('css/app.css') }}">
      <script src="{{ mix('js/app.js') }}" defer></script>
    @inertiaHead
  </head>
  <body>
    @inertia


    @if(env('SSLCZ_TESTMODE'))
        <script>
            (function (window, document) {
                var loader = function () {
                    var script = document.createElement("script"), tag = document.getElementsByTagName("script")[0];
                    script.src = "https://sandbox.sslcommerz.com/embed.min.js?" + Math.random().toString(36).substring(7);
                    tag.parentNode.insertBefore(script, tag);
                };
                window.addEventListener ? window.addEventListener("load", loader, false) : window.attachEvent("onload", loader);
            })(window, document);
        </script>
    @else
        <script>
            (function (window, document) {
                var loader = function () {
                    var script = document.createElement("script"), tag = document.getElementsByTagName("script")[0];
                    script.src = "https://seamless-epay.sslcommerz.com/embed.min.js?" + Math.random().toString(36).substring(7);
                    tag.parentNode.insertBefore(script, tag);
                };
                window.addEventListener ? window.addEventListener("load", loader, false) : window.attachEvent("onload", loader);
            })(window, document);
        </script>
    @endif
  </body>
</html>
