<div class="modal modal-danger fade" tabindex="-1" id="loggedIn_confirm__modal" role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-header custom-bg-gradient-info">
                <button type="button" class="close btn-danger" data-dismiss="modal"
                        aria-label="{{ __('voyager::generic.close') }}">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body font-weight-bold" id="delete_model_body">
                Login to apply a course.
            </div>

            <div class="col-md-12">
                <a href="{{ route('admin.login-form') }}" class="text-primary">Login</a>
                <p>Not yet registered? <a href="{{ route('frontend.trainee-registrations.index') }}">Register</a></p>
            </div>
        </div>
    </div>
</div>

