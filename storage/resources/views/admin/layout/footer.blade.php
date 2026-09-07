                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6">
                                <script>document.write(new Date().getFullYear())</script> © JPLoft.
                            </div>
                            <div class="col-sm-6">
                                <div class="text-sm-end d-none d-sm-block">
                                    Design & Develop by JPLoft
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->

        <!-- JAVASCRIPT -->
        <script src="{{url('/public/admin/')}}/assets/libs/jquery/jquery.min.js"></script>
        <script src="{{url('/public/admin/')}}/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="{{url('/public/admin/')}}/assets/libs/metismenu/metisMenu.min.js"></script>
        <script src="{{url('/public/admin/')}}/assets/libs/simplebar/simplebar.min.js"></script>
        <script src="{{url('/public/admin/')}}/assets/libs/node-waves/waves.min.js"></script>
        <script src="{{url('/public/admin/')}}/assets/libs/select2/js/select2.min.js"></script>
        <!-- Required datatable js -->
        <script src="{{url('/public/admin/')}}/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="{{url('/public/admin/')}}/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
        <!-- Responsive examples -->
        <script src="{{url('/public/admin/')}}/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
        <script src="{{url('/public/admin/')}}/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>

        <!-- Buttons examples -->
        <script src="{{url('/public/admin/')}}/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
        <script src="{{url('/public/admin/')}}/assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
        <script src="{{url('/public/admin/')}}/assets/libs/jszip/jszip.min.js"></script>
        <script src="{{url('/public/admin/')}}/assets/libs/pdfmake/build/pdfmake.min.js"></script>
        <script src="{{url('/public/admin/')}}/assets/libs/pdfmake/build/vfs_fonts.js"></script>
        <script src="{{url('/public/admin/')}}/assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
        <script src="{{url('/public/admin/')}}/assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
        <script src="{{url('/public/admin/')}}/assets/libs/datatables.net-buttons/js/buttons.colVis.min.js"></script>
        <!-- apexcharts -->
        <script src="{{url('/public/admin/')}}/assets/libs/apexcharts/apexcharts.min.js"></script>

        <!-- dashboard init -->
        <script src="{{url('/public/admin/')}}/assets/js/pages/dashboard.init.js"></script>
        <!-- Alerts Live Demo js -->
        <!-- <script src="{{url('/public/admin/')}}/assets/js/pages/alerts.init.js"></script> -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/js/intlTelInput-jquery.min.js"></script>
        <!-- App js -->
        <script src="{{url('/public/admin/')}}/assets/js/pages/datatables.init.js"></script>
        <script src="{{url('/public/admin/')}}/assets/js/pages/form-advanced.init.js"></script>
        <script src="{{url('/public/admin/')}}/assets/js/app.js"></script>
        <script src="{{asset('public/admin/')}}/assets/dist/js/dropify.min.js"></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script>
            // -----Country Code Selection
            $("#mobile_code").intlTelInput({
             initialCountry: "in",
             separateDialCode: true,
           });
         </script>
        <script type="text/javascript">
            $(document).ready(function() {
              $(".alert-dismissible").hide();
                $(".alert-dismissible").fadeTo(2000, 500).slideUp(500, function() {
                  $(".alert-dismissible").slideUp(2000);
                });

            });

               
        </script>
        <script>
            $("#mobile_code").intlTelInput({
             initialCountry: "in",
             separateDialCode: true,
           });
        </script>
        <script>
            $(document).ready(function(){
                // Basic
                $('.dropify').dropify();

                // Translated
                $('.dropify-fr').dropify({
                    messages: {
                        default: 'Glissez-déposez un fichier ici ou cliquez',
                        replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
                        remove:  'Supprimer',
                        error:   'Désolé, le fichier trop volumineux'
                    }
                });

                // Used events
                var drEvent = $('#input-file-events').dropify();

                drEvent.on('dropify.beforeClear', function(event, element){
                    return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
                });

                drEvent.on('dropify.afterClear', function(event, element){
                    alert('File deleted');
                });

                drEvent.on('dropify.errors', function(event, element){
                    console.log('Has Errors');
                });

                var drDestroy = $('#input-file-to-destroy').dropify();
                drDestroy = drDestroy.data('dropify')
                $('#toggleDropify').on('click', function(e){
                    e.preventDefault();
                    if (drDestroy.isDropified()) {
                        drDestroy.destroy();
                    } else {
                        drDestroy.init();
                    }
                })
            });
        </script>
    </body>

</html>