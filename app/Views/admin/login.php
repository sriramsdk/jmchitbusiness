<div class="row d-flex justify-content-center" style="width: 1200px;">
    <div class="col-md-5">
        <img src="<?= base_url('logo_name.png') ?>" width="400" alt="">
    </div>
    <div class="col-md-5 mt-5">
        <div class="card">
            <div class="card-header d-flex bg-primary mb-3"><b class="m-auto h4">Login</b></div>

                <div class="card-body">
                    <form id="login_form">

                        <div class="row mb-3">
                            <label for="username" class="col-md-3 col-form-label text-md-end">Username</label>

                            <div class="col-md-8">
                                <input id="username" type="text" class="form-control" name="username" value="" required autocomplete="username" autofocus>
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-3 col-form-label text-md-end">Password</label>

                            <div class="col-md-8">
                                <input id="password" type="password" class="form-control" name="password" required autocomplete="current-password">
                            </div>
                        </div>

                        <div class="row">
                            <label for="password" class="col-md-3 col-form-label text-md-end"></label>
                            <div class="col-md-8 mt-3 mb-3">                        
                                <span class="invalid-error alert d-none" role="alert">
                                </span>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-3">
                                <button type="submit" class="btn btn-primary">
                                    Login
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$('#login_form').on('submit', function(e){
    e.preventDefault();

    $.ajax({
        url: "<?= site_url('login'); ?>",
        method: "POST",
        data: $(this).serialize(),
        dataType: "json",
        success: function(response){
            if(response.status == 'success'){
                window.location.href = response.redirect;
            }else{
                $('.invalid-error').removeClass('d-none').addClass('alert alert-danger').text(response.message); 
            }
        },
        error: function(xhr,code,status){
            $('.invalid-error').removeClass('d-none').addClass('alert alert-danger').text(status);
        }
    });
});
</script>