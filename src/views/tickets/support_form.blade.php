
<!-- Support Button -->
@if (config('crudbooster.TICKET_SYSTEM_STATUS') == 'on')
    <div id="support-button" class="support-button" onclick="toggleSupportForm()">
        <span class="fa fa-ticket fa-2x"></span>
    </div>
@endif
<!-- Support Form -->
<div class="support-form-container" id="supportForm">
    <div class="well" style="background-color: #fff;  padding: 30px; ">
        <div class="clearfix">
            <h4 class="pull-left" style="margin-top: 0;">{{ trans('crudbooster.create_ticket') }}</h4>
            <button class="close pull-right" onclick="toggleSupportForm()" type="button">&times;</button>
        </div>
        <hr>

        <form class="form-horizontal" id="supportFormData">
            <!-- Hidden fields -->
            <input type="hidden" class="form-control" id="telephone" name="client_phone" value="0">

            <!-- Description -->
            <div class="form-group">
                <label for="description" class="control-label">{{ trans('crudbooster.description') }}</label>
                <textarea id="description" name="description" rows="3" required class="form-control" placeholder=""></textarea>
            </div>

            <!-- Attachment -->
            <div class="form-group">
                <label for="attachment" class="control-label"
                    id="attachment_label">{{ trans('crudbooster.attachment') }}</label>
                <input type="file" id="attachment" name="attachments" class="form-control"
                    accept=".gif, .png, .jpeg, .jpg">
            </div>

            <!-- Submit button -->
            <div class="form-group">
                <div class="row">
                    <div class="col-lg-6">
                        <button id="supportFormBtn" type="submit"
                            class="btn btn-primary btn-block">{{ trans('crudbooster.send_ticket') }}</button>
                    </div>
                    <div class="col-lg-6">
                        <a href="{{ url('admin/show_tickets') }}">
                            <button id="allTicketBtn" type="button"
                                class="btn btn-info btn-block">{{ trans('crudbooster.all_tickets') }}</button>
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<!------------------------------------------------------->
@push('bottom')
    <script>
        $(document).ready(function() {
            //---------------------------------------------------------//
            const $description = $('#description');
            const $supportFormBtn = $('#supportFormBtn');
            const $well = $('.well');

            // Create these once, not inside the AJAX callback
            let $successMessage = $("<span class='help-block success'></span>").hide();
            let $errorMessage = $("<span class='help-block error'></span>").hide();

            $well.prepend($successMessage);
            $well.prepend($errorMessage);
            //---------------------------------
            $supportFormBtn.prop('disabled', true);
            $description.on('input', function() {
                if ($description.val().trim() === '') {
                    $supportFormBtn.prop('disabled', true);
                } else {
                    $supportFormBtn.prop('disabled', false);
                }
            });
            //---------------------------------------------------------//
            $('#supportFormData').on('submit', function(event) {
                event.preventDefault();
                const formData = new FormData(this);
                //---------------------------------
                $supportFormBtn.append('<i class="fa fa-spinner fa-spin" aria-hidden="true"></i>');
                $supportFormBtn.attr('disabled', true);
                //---------------------------------
                $.ajax({
                    url: "{{ url('admin/add-ticket') }}",
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $errorMessage.hide();
                        $successMessage.text(response.message || 'Ticket added successfully.')
                            .show();
                        $supportFormBtn.find('i').remove();

                        $supportFormBtn.prop('disabled', false);

                        $('#attachment').val('');
                        $('#description').val('');

                        setTimeout(function() {
                            $successMessage.fadeOut();
                            toggleSupportForm();
                        }, 2000);
                    },
                    error: function(xhr) {
                        $successMessage.hide();

                        let errorMsg = 'Something went wrong.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                        $supportFormBtn.find('i').remove();
                        $supportFormBtn.prop('disabled', false);
                        $errorMessage.text(errorMsg).show();
                        setTimeout(function() {
                            $errorMessage.hide();
                        }, 2000);
                    }
                });
            });
        });
        //---------------------------------------------------------//
        function toggleSupportForm() {
            const form = document.getElementById('supportForm');
            form.classList.toggle('active');
        }
        //---------------------------------------------------------//
        $(document).ready(function() {
            document.getElementById('attachment').addEventListener('change', function() {
                var fileName = this.files[0] ? this.files[0].name : 'No file chosen';
                document.getElementById('attachment_label').textContent = fileName;
            });
        });
    </script>
@endpush
