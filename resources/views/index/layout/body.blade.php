<!DOCTYPE html>
<html lang="en-US">

@include('index.layout.head')

<body class="home page-template page-template-tpl-king_composer page-template-tpl-king_composer-php page page-id-7 kingcomposer kc-css-system">

<div class="page-wrapper">


    @yield('content')


    @include('index.layout.footer')
</div>

<!--End pagewrapper-->

<!--Scroll to top-->
<div class="scroll-to-top scroll-to-target" data-target="html"><span class="fa fa-caret-up"></span></div>

<script type="text/javascript">
    function revslider_showDoubleJqueryError(sliderID) {
        var errorMessage = "Revolution Slider Error: You have some jquery.js library include that comes after the revolution files js include.";
        errorMessage += "<br> This includes make eliminates the revolution slider libraries, and make it not work.";
        errorMessage += "<br><br> To fix it you can:<br>&nbsp;&nbsp;&nbsp; 1. In the Slider Settings -> Troubleshooting set option:  <strong><b>Put JS Includes To Body</b></strong> option to true.";
        errorMessage += "<br>&nbsp;&nbsp;&nbsp; 2. Find the double jquery.js include and remove it.";
        errorMessage = "<span style='font-size:16px;color:#BC0C06;'>" + errorMessage + "</span>";
        jQuery(sliderID).show().html(errorMessage);
    }
</script>
<script type='text/javascript'>
    /* <![CDATA[ */
    var wpcf7 = {
        "apiSettings": {
            "root": "http:\/\/wp3.commonsupport.com\/newwp\/hubli\/wp-json\/contact-form-7\/v1",
            "namespace": "contact-form-7\/v1"
        }, "recaptcha": {"messages": {"empty": "Please verify that you are not a robot."}}
    };
    /* ]]> */
</script>
<script type='text/javascript' src='/wp-content/plugins/contact-form-7/includes/js/scripts.js?ver=5.0.1'></script>
<script type='text/javascript' src='/wp-includes/js/jquery/ui/core.min.js?ver=1.11.4'></script>
<script type='text/javascript' src='/wp-content/themes/hubli/js/bootstrap.min.js?ver=4.9.8'></script>
<script type='text/javascript' src='/wp-content/themes/hubli/js/jquery.fancybox.js?ver=4.9.8'></script>
<script type='text/javascript' src='/wp-content/themes/hubli/js/isotope.js?ver=4.9.8'></script>
<script type='text/javascript' src='/wp-content/themes/hubli/js/owl.js?ver=4.9.8'></script>
<script type='text/javascript' src='/wp-content/themes/hubli/js/wow.js?ver=4.9.8'></script>
<script type='text/javascript' src='/wp-content/themes/hubli/js/appear.js?ver=4.9.8'></script>
<script type='text/javascript' src='/wp-content/themes/hubli/js/mixitup.js?ver=4.9.8'></script>
<script type='text/javascript' src='/wp-content/themes/hubli/js/script.js?ver=4.9.8'></script>
<script type='text/javascript'>
    if (ajaxurl === undefined) var ajaxurl = "/wp-admin/admin-ajax.php";
</script>
<script type='text/javascript' src='/wp-content/themes/hubli/js/map-script.js?ver=4.9.8'></script>
<script type='text/javascript' src='/wp-includes/js/comment-reply.min.js?ver=4.9.8'></script>
<script type='text/javascript'
        src='/wp-content/plugins/kingcomposer/assets/frontend/js/kingcomposer.min.js?ver=2.7.2'></script>
<script type='text/javascript' src='/wp-includes/js/wp-embed.min.js?ver=4.9.8'></script>
</body>
</html>