@php
    $edit = !empty($staticPage->id);
    $edit_id = $edit ? $staticPage->id: 0;

    /** @var \App\Models\User $authUser */
    $authUser = \App\Helpers\Classes\AuthHelper::getAuthUser();
@endphp

@extends('master::layouts.master')

@section('title')
    {{ ! $edit ? __('admin.static_page.add') : __('admin.static_page.update') }}
@endsection

@section('style')
    <style>
        figure.image {
            display: inline-block;
            border: 1px solid gray;
            margin: 0 2px 0 1px;
            background: #f5f2f0;
        }

        figure.align-left {
            float: left;
        }

        figure.align-right {
            float: right;
        }

        figure.image img {
            margin: 8px 8px 0 8px;
        }

        figure.image figcaption {
            margin: 6px 8px 6px 8px;
            text-align: center;
        }

        /*
         Alignment using classes rather than inline styles
         check out the "formats" option
        */
        img.align-left {
            float: left;
        }

        img.align-right {
            float: right;
        }

        /* Basic styles for Table of Contents plugin (toc) */
        .mce-toc {
            border: 1px solid gray;
        }

        .mce-toc h2 {
            margin: 4px;
        }

        .mce-toc li {
            list-style-type: none;
        }

    </style>
@endsection

@section('content')

    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-outline">
                    <div class="card-header text-primary custom-bg-gradient-info">
                        <h3 class="card-title font-weight-bold">{{ ! $edit ? __('admin.static_page.add') : __('admin.static_page.update')}}</h3>

                        <div class="card-tools">
                            <a href="{{route('admin.static-page.index')}}"
                               class="btn btn-sm btn-outline-primary btn-rounded">
                                <i class="fas fa-backward"></i>{{__('admin.common.back')}}
                            </a>
                        </div>

                    </div>

                    <div class="card-body">
                        <form class="row edit-add-form" method="post"
                              action="{{ $edit ? route('admin.static-page.update', [$staticPage->id]) : route('admin.static-page.store')}}">
                            @csrf
                            @if($edit)
                                @method('put')
                            @endif
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="name">{{ __('admin.static_page.content_title') }}<span
                                            style="color: red"> * </span></label>
                                    <input type="text" class="form-control" id="title"
                                           name="title"
                                           value="{{ $edit ? $staticPage->title : old('title') }}"
                                           placeholder="{{ __('admin.static_page.content_title') }}">
                                </div>
                            </div>
                            @if($authUser->isUserBelongsToInstitute())
                                <input type="hidden" id="institute_id" name="institute_id"
                                       value="{{$authUser->institute_id}}">
                            @else
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="institute_id">{{ __('admin.question_answer.institute_title1') }}</label>
                                        <select class="form-control select2-ajax-wizard"
                                                name="institute_id"
                                                id="institute_id"
                                                data-model="{{base64_encode(App\Models\Institute::class)}}"
                                                data-label-fields="{title}"
                                                @if($edit && $staticPage->institute)
                                                data-preselected-option="{{json_encode(['text' =>  $staticPage->institute->title, 'id' =>  $staticPage->institute->id])}}"
                                                @endif
                                                data-placeholder="{{ __('generic.select_placeholder') }}"
                                        >
                                        </select>
                                    </div>
                                </div>
                            @endif

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="page_id">{{ __('admin.static_page.link') }}<span
                                            style="color: red"> * </span></label>
                                    <input type="text"
                                           {{--{{($edit && $staticPage->institute_id) ?'': 'disabled'}}--}} class="form-control"
                                           id="page_id"
                                           name="page_id"
                                           value="{{ $edit ? $staticPage->page_id : '' }}"
                                           placeholder="{{ __('admin.static_page.link') }}">
                                </div>
                            </div>


                            <div class="col-12">
                                <div class="form-group">
                                    <label for="page_contents">{{ __('admin.static_page.page_content') }}<span
                                            style="color: red"> * </span></label>
                                    <textarea class="form-control"
                                              id="page_contents"
                                              name="page_contents"
                                              placeholder="Page Content"
                                              style="background-color: #FFFFFF">{{ $edit ? $staticPage->page_contents : '' }}</textarea>
                                </div>
                            </div>

                            <div class="col-sm-12 text-right">
                                <button type="submit"
                                        class="btn btn-success">{{ $edit ? __('admin.static_page.update') : __('admin.static_page.add') }}</button>
                            </div>
                        </form>
                    </div><!-- /.card-body -->
                    <div class="overlay" style="display: none">
                        <i class="fas fa-2x fa-sync-alt fa-spin"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@push('js')
    <x-generic-validation-error-toastr></x-generic-validation-error-toastr>
    <script>
        const EDIT = !!'{{$edit}}';

        const editAddForm = $('.edit-add-form');

        editAddForm.validate({
            errorElement: "em",
            onkeyup: false,
            errorPlacement: function (error, element) {
                error.addClass("help-block");
                element.parents(".form-group").addClass("has-feedback");

                if (element.parents(".form-group").length) {
                    error.insertAfter(element.parents(".form-group").first().children().last());
                } else if (element.hasClass('select2') || element.hasClass('select2-ajax-custom') || element.hasClass('select2-ajax')) {
                    error.insertAfter(element.parents(".form-group").first().find('.select2-container'));
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function (element, errorClass, validClass) {
                $(element).parents(".form-group").addClass("has-error").removeClass("has-success");
                $(element).closest('.help-block').remove();
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents(".form-group").addClass("has-success").removeClass("has-error");
            },
            rules: {
                title: {
                    required: true,
                },
                page_id: {
                    required: true,
                    pattern: /^[a-zA-Z0-9-_]*$/,
                    remote: {
                        url: "{{ route('admin.check-page-id') }}",
                        type: "post",
                        data: {
                            page_id: function () {
                                return $("#page_id").val();
                            },
                            institute_id: function () {
                                return $("#institute_id").val();
                            },
                            id: function () {
                                return '{{$edit_id}}';
                            },
                        }
                    }
                },
                institute_id: {
                    required: false,
                },
                page_contents: {
                    required: function () {
                        let editorContent = tinymce.get('page_contents').getContent();
                        return !editorContent?.length;
                    }
                }
            },
            messages: {
                page_contents: {
                    required: function () {
                        let editorContent = tinymce.get('page_contents').getContent();
                        return "This field is required";
                    }
                }

            },

            submitHandler: function (htmlForm) {
                $('.overlay').show();
                htmlForm.submit();
            }
        });

        function imageUploadHandler(blobInfo, success, failure, progress) {
            let formData;
            formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());
            $.ajax({
                type: 'POST',
                url: "{{ route('admin.staticPage.imageUpload') }}",

                //url: EDIT?'image-upload':'0/image-upload',
                contentType: false,
                data: formData,
                processData: false,

                success: function (response) {
                    success('{{asset('storage/')}}' + response.location);
                },
                error: function (error) {
                    if (error.status === 403) {
                        failure('HTTP Error: ' + error.status, {remove: true});
                        return;
                    }
                    if (error.status < 200 || error.status >= 300) {
                        failure('HTTP Error: ' + error.status);
                        return;
                    }
                },
                complete: function () {
                    return;
                }
            });
        }

        tinymce.init({
            selector: 'textarea#page_contents',
            init_instance_callback : function(editor) {
                var freeTiny = document.querySelector('.tox .tox-notification--in');
                var freeTinyCompany = document.querySelector('.tox .tox-statusbar a');
                freeTiny.style.display = 'none';
                freeTinyCompany.style.display = 'none';
            },
            plugins: 'print preview paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',
            menubar: '',
            toolbar: 'undo redo | link image | code | bold italic underline | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat  | emoticons | preview | insertfile image  link | code  ',
            toolbar_sticky: true,
            image_advtab: true,
            importcss_append: true,
            height: 300,
            image_caption: true,
            quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
            noneditable_noneditable_class: 'mceNonEditable',
            toolbar_mode: 'sliding',
            contextmenu: 'link image imagetools table',
            paste_data_images: true,
            images_upload_handler: imageUploadHandler,
            skin: 'oxide',
            content_css: 'default',
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
            relative_urls: false,
            remove_script_host: false,
            convert_urls: true,
            plugins: 'code',
            plugins: 'image code',
            /* enable title field in the Image dialog*/
            image_title: true,
            /* enable automatic uploads of images represented by blob or data URIs*/
            automatic_uploads: true,
            /*
                URL of our upload handler (for more details check: https://www.tiny.cloud/docs/configure/file-image-upload/#images_upload_url)
                images_upload_url: 'postAcceptor.php',
                here we add custom filepicker only to Image dialog
            */
            file_picker_types: 'image',
        });

        $('body').on('click', function() {
            let editorContent = tinymce.get('page_contents').getContent();
            if(editorContent.length>0){
                $('#page_contents').valid(true);
            }else{
                $('#page_contents').valid(false);
            }
        });


    </script>
@endpush


