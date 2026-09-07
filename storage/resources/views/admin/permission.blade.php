@extends('admin.layout.layout')
@section('content')
<style type="text/css">
    /*.card {
    margin-bottom: 24px;
    box-shadow: 0 0.75rem 1.5rem rgb(18 38 63 / 3%)!important;
    background-color: inherit;
}*/
.accent{accent-color: #f03e39!important; }
.error {
    width: auto;
}
</style>
            

    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">{{$title}}</h4>

                            <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{route('admin_dashboard')}}">Dashboard</a></li>
                                <!-- <li class="breadcrumb-item">{{$title}}</li> -->
                            </ol>
                        </div>

                        </div>
                    </div>
                </div>
                <!-- end page title -->
                <form id="perform" method="post" action="{{$saveurl}}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                
                                <div>
                                    <h5 class="mb-3">Manage :</h5>
                                    <label id="controller[]-error" class="error" for="controller[]"></label>
                                    <div class="table-responsive">
                                        <table class="table mb-0 table-bordered">
                                            <tbody class="checkdataper">
                                                <?php foreach($controllers as $controller_data){ ?>
                                                    <tr>
                                                        <th><input type="checkbox" name="controller[]" value="<?php echo $controller_data ?>" class="checkdata accent"></th>
                                                        <td><?php echo $controller_data ?></td>
                                                    </tr>
                                              <?php  } ?>
                                                
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- end Specifications -->

                                

                            </div>
                        </div>
                        <!-- end card -->
                    </div>
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                
                                <div >
                                    <h5 class="mb-3">Subadmin :</h5>
                                    <label id="subadmin_id-error" class="error" for="subadmin_id"></label>
                                    <div class="table-responsive">
                                        <table class="table mb-0 table-bordered">
                                            <tbody>
                                                @foreach($admingroup as $key=>$data)    
                                                <tr>
                                                    <th><input type="radio" name="subadmin_id" value="{{$data->id}}" onclick="check('{{$data->id}}',this.value)" class="groupcheck accent" required></th>
                                                    <td>{{$data->name}}</td>
                                                </tr>
                                                @endforeach
                                                
                                                
                                            </tbody>
                                        </table>
                                        <tr>
                                <th><button type="submit" id="submit"  class="mt-2 btn btn-primary">Get Permission</button></th> 
                            </tr>
                                    </div>
                                </div>
                                <!-- end Specifications -->

                                

                            </div>
                        </div>
                        <!-- end card -->
                    </div>
                </div>
                </form>

            </div> <!-- container-fluid -->
        </div>
        <!-- End Page-content -->

    </div>
    <!-- end main content-->

</div>
<!-- END layout-wrapper -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js"> </script>  
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"> </script>
<script>

    $("#perform").validate({

        onfocusout: function (element) {
            $(element).valid();
        },

        rules: {
            'controller[]': {required: true},
            'subadmin_id': {required: true},
            
        },
        messages: {
            'controller[]': "Please select atleast one controller.",        
            'subadmin_id': "Please select atleast one subadmin.",
            

        },
        

      submitHandler: function(form) {
        form.submit();
    },   
});

</script>

<script type="text/javascript">
        function check(group_id) {
        
        $.ajax({
            url: "{{ url('/admin/group_check') }}",
            datatType: 'json',
            type: 'POST',
            data: {
                '_token' : '<?php echo csrf_token() ?>',
                'group_id' : group_id,
                
            },
            
            success: function (res)
            { 
                $(".checkdataper").html(res);
                
            }
        });
    }

</script>
@endsection 
        