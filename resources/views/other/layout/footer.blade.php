                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6">
                                <script>
                                    document.write(new Date().getFullYear())
                                </script> © JPLoft.
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
                <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalLabel">Laravel Crop Image Before Upload Example - ItSolutionStuff.com</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="img-container">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <img id="image" src="https://avatars0.githubusercontent.com/u/3456749">
                                        </div>
                                        <div class="col-md-4">
                                            <div class="preview"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-primary" id="crop">Crop</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- JAVASCRIPT -->
                <script src="{{ url('/public/admin/') }}/assets/libs/jquery/jquery.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
                <script src="{{ url('/public/admin/') }}/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
                <script src="{{ url('/public/admin/') }}/assets/libs/metismenu/metisMenu.min.js"></script>
                <script src="{{ url('/public/admin/') }}/assets/libs/simplebar/simplebar.min.js"></script>
                <script src="{{ url('/public/admin/') }}/assets/libs/node-waves/waves.min.js"></script>
                <script src="{{ url('/public/admin/') }}/assets/libs/select2/js/select2.min.js"></script>
                <!-- Required datatable js -->
                <script src="{{ url('/public/admin/') }}/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
                <script src="{{ url('/public/admin/') }}/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
                <!-- Responsive examples -->
                <script src="{{ url('/public/admin/') }}/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js">
                </script>
                <script src="{{ url('/public/admin/') }}/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js">
                </script>

                <!-- Buttons examples -->
                <script src="{{ url('/public/admin/') }}/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
                <script src="{{ url('/public/admin/') }}/assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
                <script src="{{ url('/public/admin/') }}/assets/libs/jszip/jszip.min.js"></script>
                <script src="{{ url('/public/admin/') }}/assets/libs/pdfmake/build/pdfmake.min.js"></script>
                <script src="{{ url('/public/admin/') }}/assets/libs/pdfmake/build/vfs_fonts.js"></script>
                <script src="{{ url('/public/admin/') }}/assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
                <script src="{{ url('/public/admin/') }}/assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
                <script src="{{ url('/public/admin/') }}/assets/libs/datatables.net-buttons/js/buttons.colVis.min.js"></script>
                <!-- apexcharts -->
                <script src="{{ url('/public/admin/') }}/assets/libs/apexcharts/apexcharts.min.js"></script>

                <!-- dashboard init -->
                <script src="{{ url('/public/admin/') }}/assets/js/pages/dashboard.init.js"></script>
                <!-- Alerts Live Demo js -->
                <!-- <script src="{{ url('/public/admin/') }}/assets/js/pages/alerts.init.js"></script> -->
                <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/js/intlTelInput-jquery.min.js"></script>
                <!-- App js -->
                <script src="{{ url('/public/admin/') }}/assets/js/pages/datatables.init.js"></script>
                <script src="{{ url('/public/admin/') }}/assets/js/pages/form-advanced.init.js"></script>
                <script src="{{ url('/public/admin/') }}/assets/js/app.js"></script>
                <script src="{{ asset('public/admin/') }}/assets/dist/js/dropify.min.js"></script>
                <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
                <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/js/bootstrap.min.js"></script> -->
                <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.js"></script>
                
                <script>
                    var $modal = $('#modal');
                    var image = document.getElementById('image');
                    var cropper;

                    /*------------------------------------------
                    --------------------------------------------
                    Image Change Event
                    --------------------------------------------
                    --------------------------------------------*/
                    $("body").on("change", ".image", function(e) {
                        var files = e.target.files;
                        var done = function(url) {
                            image.src = url;
                            $modal.modal('show');
                        };

                        var reader;
                        var file;
                        var url;

                        if (files && files.length > 0) {
                            file = files[0];

                            if (URL) {
                                done(URL.createObjectURL(file));
                            } else if (FileReader) {
                                reader = new FileReader();
                                reader.onload = function(e) {
                                    done(reader.result);
                                };
                                reader.readAsDataURL(file);
                            }
                        }
                    });

                    /*------------------------------------------
                    --------------------------------------------
                    Show Model Event
                    --------------------------------------------
                    --------------------------------------------*/
                    $modal.on('shown.bs.modal', function() {
                        cropper = new Cropper(image, {
                            aspectRatio: NaN, // Remove aspect ratio constraint
                            viewMode: 1,
                            preview: '.preview'
                        });

                        // Calculate the appropriate width and height for the displayed image
                        var originalWidth = image.naturalWidth; // Width of the original image
                        var originalHeight = image.naturalHeight; // Height of the original image
                        var targetHeight = 371.25; // Desired height for the displayed image

                        var scaleFactor = targetHeight / originalHeight;
                        var scaledWidth = originalWidth * scaleFactor;

                        // Set the style of the image to adjust its size
                        $(image).css({
                            width: scaledWidth + 'px',
                            height: targetHeight + 'px'
                        });

                        // Set the transform property
                        var translateX = 257; // Example value for translateX
                        var translateY = 356.5; // Example value for translateY
                        $(image).css({
                            transform: 'translateX(' + translateX + 'px) translateY(' + translateY + 'px)'
                        });
                    }).on('hidden.bs.modal', function() {
                        cropper.destroy();
                        cropper = null;
                    });

                    /*------------------------------------------
                    --------------------------------------------
                    Crop Button Click Event
                    --------------------------------------------
                    --------------------------------------------*/
                    $("#crop").click(function() {
                        canvas = cropper.getCroppedCanvas({
                            width: 160,
                            height: 160,
                        });

                        canvas.toBlob(function(blob) {
                            url = URL.createObjectURL(blob);
                            var reader = new FileReader();
                            reader.readAsDataURL(blob);
                            reader.onloadend = function() {
                                var base64data = reader.result;
                                $("input[name='image_base64']").val(base64data);
                                $(".show-image").show();
                                $(".show-image").attr("src", base64data);
                                $("#modal").modal('toggle');
                            }
                        });
                    });
                </script>
                <script>
                    $(document).ready(function() {

                        // Initialize select2
                        $("#selUser").select2();

                        // Read selected option
                        $('#but_read').click(function() {
                            var username = $('#selUser option:selected').text();
                            var userid = $('#selUser').val();

                            $('#result').html("id : " + userid + ", name : " + username);

                        });
                    });
                </script>


                <script>
                    // -----Country Code Selection
                    $("#mobile_code").intlTelInput({
                        initialCountry: "au",
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
                    $(document).ready(function() {
                        // Basic
                        $('.dropify').dropify();

                        // Translated
                        $('.dropify-fr').dropify({
                            messages: {
                                default: 'Glissez-déposez un fichier ici ou cliquez',
                                replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
                                remove: 'Supprimer',
                                error: 'Désolé, le fichier trop volumineux'
                            }
                        });

                        // Used events
                        var drEvent = $('#input-file-events').dropify();

                        drEvent.on('dropify.beforeClear', function(event, element) {
                            return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
                        });

                        drEvent.on('dropify.afterClear', function(event, element) {
                            alert('File deleted');
                        });

                        drEvent.on('dropify.errors', function(event, element) {
                            console.log('Has Errors');
                        });

                        var drDestroy = $('#input-file-to-destroy').dropify();
                        drDestroy = drDestroy.data('dropify')
                        $('#toggleDropify').on('click', function(e) {
                            e.preventDefault();
                            if (drDestroy.isDropified()) {
                                drDestroy.destroy();
                            } else {
                                drDestroy.init();
                            }
                        })
                    });
                </script>
                @stack('js')
                </body>

                </html>