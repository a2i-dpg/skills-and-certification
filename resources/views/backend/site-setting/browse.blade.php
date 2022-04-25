@php
    $edit = true;
@endphp
@extends('master::layouts.master')

@section('title')
    {{ __('admin.site_setting.update') }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header text-primary custom-bg-gradient-info">
                        <h3 class="card-title font-weight-bold">{{__('admin.site_setting.update')}}</h3>

                        <div class="card-tools">
                            {{-- @can('create', \App\Models\TraineeRequest::class)
                                <a href="{{route('admin.trainee-request.create')}}"
                                   class="btn btn-sm btn-outline-primary btn-rounded">
                                    <i class="fas fa-plus-circle"></i> {{__('admin.common.add')}}
                                </a>
                            @endcan --}}
                        </div>

                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <form class="row edit-add-form" method="post"
                            action="{{ $edit ? route('admin.site-setting.update', $siteSetting) : route('admin.site-setting.store')}}"
                            enctype="multipart/form-data">
                          @csrf
                          @if($edit)
                              @method('put')
                          @endif
                          <input type="hidden" name="id" value="{{$siteSetting->id}}">

                          <div class="col-sm-6">
                            <div class="form-group">
                              <label for="site_title">{{ __('admin.site_setting.site_title') }}
                                {{-- <span class="required">*</span> --}}
                              </label>
                              <input type="text" class="form-control" id="site_title"
                                    name="site_title"
                                    value="{{ $edit ? $siteSetting->site_title : old('site_title') }}"
                                    placeholder="{{ __('admin.site_setting.site_title') }}">
                            </div>
                          </div>

                          <div class="col-sm-6">
                            <div class="form-group">
                              <label for="site_email">{{ __('admin.site_setting.site_email') }}
                                {{-- <span class="required">*</span> --}}
                              </label>
                              <input type="text" class="form-control" id="site_email"
                                    name="site_email"
                                    value="{{ $edit ? $siteSetting->site_email : old('site_email') }}"
                                    placeholder="{{ __('admin.site_setting.site_email') }}">
                            </div>
                          </div>

                          <div class="col-sm-6">
                            <div class="form-group">
                              <label for="site_mobile">{{ __('admin.site_setting.site_mobile') }}
                                {{-- <span class="required">*</span> --}}
                              </label>
                              <input type="text" class="form-control" id="site_mobile"
                                    name="site_mobile"
                                    value="{{ $edit ? $siteSetting->site_mobile : old('site_mobile') }}"
                                    placeholder="{{ __('admin.site_setting.site_mobile') }}">
                            </div>
                          </div>

                          
                          <div class="col-sm-6">
                            <div class="form-group">
                              <label for="site_address">{{ __('admin.site_setting.site_address') }}
                                {{-- <span class="required">*</span> --}}
                              </label>
                              <input type="text" class="form-control" id="site_address"
                                    name="site_address"
                                    value="{{ $edit ? $siteSetting->site_address : old('site_address') }}"
                                    placeholder="{{ __('admin.site_setting.site_address') }}">
                            </div>
                          </div>


                          <div class="col-sm-6">
                            <div class="form-group">
                              <label for="site_description">{{ __('admin.site_setting.site_description') }}</label>
                              <textarea class="form-control" id="site_description"
                                        name="site_description"
                                        rows="1"
                                        placeholder="{{ __('admin.site_setting.site_description') }}">{{ $edit ? $siteSetting->site_description : old('site_description') }}</textarea>
                            </div>
                          </div>

                          <div class="col-sm-6">
                            <div class="form-group">
                              <label for="meta_tag">{{ __('admin.site_setting.meta_tag') }}</label>
                              <textarea class="form-control" id="meta_tag"
                                        name="meta_tag"
                                        rows="1"
                                        placeholder="{{ __('admin.site_setting.meta_tag') }}">{{ $edit ? $siteSetting->meta_tag : old('meta_tag') }}</textarea>
                            </div>
                          </div>

                          <div class="col-sm-6">
                            <div class="form-group">
                              <label for="meta_name">{{ __('admin.site_setting.meta_name') }}</label>
                              <textarea class="form-control" id="meta_name"
                                        name="meta_name"
                                        rows="1"
                                        placeholder="{{ __('admin.site_setting.meta_name') }}">{{ $edit ? $siteSetting->meta_name : old('meta_name') }}</textarea>
                            </div>
                          </div>

                          <div class="col-sm-6">
                            <div class="form-group">
                              <label for="meta_description">{{ __('admin.site_setting.meta_description') }}</label>
                              <textarea class="form-control" id="meta_description"
                                        name="meta_description"
                                        rows="1"
                                        placeholder="{{ __('admin.site_setting.meta_description') }}">{{ $edit ? $siteSetting->meta_description : old('meta_description') }}</textarea>
                            </div>
                          </div>





                          <div class="col-sm-6">
                            <div class="form-group">
                              <label for="locale">{{ __('admin.site_setting.locale') }} (en-US, bn-BD) 
                                <span><a target="_blank" href="https://github.com/umpirsky/locale-list/blob/master/data/rw/locales.php"> Reference Link </a></span>
                              </label>
                              <input type="text" class="form-control" id="locale"
                                    name="locale"
                                    value="{{ $edit ? $siteSetting->locale : old('locale') }}"
                                    placeholder="{{ __('admin.site_setting.locale') }}">
                            </div>
                          </div>

                          <div class="col-sm-6">
                            <div class="form-group">
                              <label for="local_currency">{{ __('admin.site_setting.local_currency') }} (USD, BDT)
                                <span><a target="_blank" href="https://www.science.co.il/international/Currency-codes.php"> Reference Link </a></span>
                              </label>
                              <input type="text" class="form-control" id="local_currency"
                                    name="local_currency"
                                    value="{{ $edit ? $siteSetting->local_currency : old('local_currency') }}"
                                    placeholder="{{ __('admin.site_setting.local_currency') }}">
                            </div>
                          </div>

                          {{-- <div class="col-sm-6">
                            <div class="form-group">
                              <label for="locale_code">{{ __('admin.site_setting.locale_code') }}
                              </label>
                              <input type="text" class="form-control" id="locale_code"
                                    name="locale_code"
                                    value="{{ $edit ? $siteSetting->locale_code : old('locale_code') }}"
                                    placeholder="{{ __('admin.site_setting.locale_code') }}">
                            </div>
                          </div>

                          <div class="col-sm-6">
                            <div class="form-group">
                              <label for="locale_symble">{{ __('admin.site_setting.locale_symble') }}
                              </label>
                              <input type="text" class="form-control" id="locale_symble"
                                    name="locale_symble"
                                    value="{{ $edit ? $siteSetting->locale_symble : old('locale_symble') }}"
                                    placeholder="{{ __('admin.site_setting.locale_symble') }}">
                            </div>
                          </div> --}}

                          <div class="col-md-12">
                            <hr>
                            <br>
                          </div>

                          <div class="col-sm-3">
                            <div class="form-group">
                              <div class="checkbox">
                                <label><input name='show_slider' type="checkbox"
                                   {{ ($edit && ($siteSetting->show_slider == 1)) ? 'checked' : '' }}>{{ __('admin.site_setting.show_slider') }}</label>
                              </div>
                            </div>
                          </div>

                          <div class="col-sm-3">
                            <div class="form-group">
                              <div class="checkbox">
                                <label><input name='show_glance' type="checkbox"
                                  {{ ($edit && ($siteSetting->show_glance == 1)) ? 'checked' : '' }}>{{ __('admin.site_setting.show_glance') }}</label>
                              </div>
                            </div>
                          </div>

                          <div class="col-sm-3">
                            <div class="form-group">
                              <div class="checkbox">
                                <label><input name='show_course' type="checkbox"
                                  {{ ($edit && ($siteSetting->show_course == 1)) ? 'checked' : '' }}> {{ __('admin.site_setting.show_course') }}</label>
                              </div>
                            </div>
                          </div>

                          <div class="col-sm-3">
                            <div class="form-group">
                              <div class="checkbox">
                                <label><input name='show_gallary' type="checkbox"
                                  {{ ($edit && ($siteSetting->show_gallary == 1)) ? 'checked' : '' }}>{{ __('admin.site_setting.show_gallary') }}</label>
                              </div>
                            </div>
                          </div>

                          <div class="col-sm-3">
                            <div class="form-group">
                              <div class="checkbox">
                                <label><input name='show_provider' type="checkbox" 
                                  {{ ($edit && ($siteSetting->show_provider == 1)) ? 'checked' : '' }}>{{ __('admin.site_setting.show_provider') }}</label>
                              </div>
                            </div>
                          </div>

                          <div class="col-sm-3">
                            <div class="form-group">
                              <div class="checkbox">
                                <label><input name='show_lang' type="checkbox"
                                  {{ ($edit && ($siteSetting->show_lang == 1)) ? 'checked' : '' }}>{{ __('admin.site_setting.show_lang') }}</label>
                              </div>
                            </div>
                          </div>

                          <div class="col-sm-3">
                            <div class="form-group">
                              <div class="checkbox">
                                <label><input name='show_logo' type="checkbox"
                                  {{ ($edit && ($siteSetting->show_logo == 1)) ? 'checked' : '' }}>{{ __('admin.site_setting.show_logo') }}</label>
                              </div>
                            </div>
                          </div>

                          <div class="col-sm-3">
                            <div class="form-group">
                              <div class="checkbox">
                                <label><input name='show_favicon' type="checkbox"
                                  {{ ($edit && ($siteSetting->show_favicon == 1)) ? 'checked' : '' }}>{{ __('admin.site_setting.show_favicon') }}</label>
                              </div>
                            </div>
                          </div>


                          <div class="col-md-12">
                            <hr>
                          </div>
                          



                          <div class="col-md-6">
                            <div class="form-group">
                                <label for="site_logo">{{ __('admin.site_setting.site_logo') }}</label>
                                <div class="input-group">
                                    <div class="cover-image-upload-section">
                                        <div class="avatar-preview text-center">
                                            <label for="site_logo">
                                                <img class="figure-img"
                                                     src={{ $edit && $siteSetting->site_logo ? asset('storage/'.$siteSetting->site_logo) : "https://via.placeholder.com/1080x300?text=Course+Cover-image"}}
                                                         height="80" width="100%"
                                                     alt="siteSetting site_logo"/>
                                                <span class="p-1 bg-gray"
                                                      style="position: relative; right: 0; bottom: 50%; /*border: 2px solid #afafaf;*/ border-radius: 50%;margin-left: -31px; overflow: hidden">
                                                    <i class="fa fa-pencil-alt text-white"></i>
                                                </span>
                                            </label>
                                        </div>
                                        <input type="file" name="site_logo" style="display: none"
                                               id="site_logo">
                                    </div>
                                </div>
                                <p class="font-italic text-secondary d-block">
                                    (Image max 100kb, size 80x80 & file type must be jpg,png or jpeg )</p>
                            </div>
                        </div>

                        <div class="col-md-6">
                          <div class="form-group">
                              <label for="site_favicon">{{ __('admin.site_setting.site_favicon') }}</label>
                              <div class="input-group">
                                  <div class="cover-image-upload-section">
                                      <div class="avatar-preview text-center">
                                          <label for="site_favicon">
                                              <img class="figure-img"
                                                   src={{ $edit && $siteSetting->site_favicon ? asset('storage/'.$siteSetting->site_favicon) : "https://via.placeholder.com/1080x300?text=Course+Cover-image"}}
                                                       height="80" width="100%"
                                                   alt="siteSetting site_favicon"/>
                                              <span class="p-1 bg-gray"
                                                    style="position: relative; right: 0; bottom: 50%; /*border: 2px solid #afafaf;*/ border-radius: 50%;margin-left: -31px; overflow: hidden">
                                                  <i class="fa fa-pencil-alt text-white"></i>
                                              </span>
                                          </label>
                                      </div>
                                      <input type="file" name="site_favicon" style="display: none"
                                             id="site_favicon">
                                  </div>
                              </div>
                              <p class="font-italic text-secondary d-block">
                                  (Image max 100kb, size 48X48 & file type must be jpg,png or jpeg )</p>
                          </div>
                      </div>

                      <div class="col-md-12">
                        <hr>
                        <p class="text-info font-weight-bold">{{__('admin.site_setting.dynamic_mail_setup_text')}}</p>
                        <hr>
                      </div>


                      <div class="col-sm-6">
                        <div class="form-group">
                          <label for="mailer">{{ __('admin.site_setting.mailer') }}
                          </label>
                          <input type="text" class="form-control" id="mailer"
                                name="mailer"
                                value="{{ $edit ? $siteSetting->mailer : old('mailer') }}"
                                placeholder="{{ __('admin.site_setting.mailer') }}">
                        </div>
                      </div>

                      <div class="col-sm-6">
                        <div class="form-group">
                          <label for="host">{{ __('admin.site_setting.host') }}
                          </label>
                          <input type="text" class="form-control" id="host"
                                name="host"
                                value="{{ $edit ? $siteSetting->host : old('host') }}"
                                placeholder="{{ __('admin.site_setting.host') }}">
                        </div>
                      </div>

                      <div class="col-sm-6">
                        <div class="form-group">
                          <label for="port">{{ __('admin.site_setting.port') }}
                          </label>
                          <input type="text" class="form-control" id="port"
                                name="port"
                                value="{{ $edit ? $siteSetting->port : old('port') }}"
                                placeholder="{{ __('admin.site_setting.port') }}">
                        </div>
                      </div>

                      <div class="col-sm-6">
                        <div class="form-group">
                          <label for="username">{{ __('admin.site_setting.username') }}
                          </label>
                          <input type="text" class="form-control" id="username"
                                name="username"
                                value="{{ $edit ? $siteSetting->username : old('username') }}"
                                placeholder="{{ __('admin.site_setting.username') }}">
                        </div>
                      </div>

                      <div class="col-sm-6">
                        <div class="form-group">
                          <label for="password">{{ __('admin.site_setting.password') }}
                          </label>
                          <input type="text" class="form-control" id="password"
                                name="password"
                                value="{{ $edit ? $siteSetting->password : old('password') }}"
                                placeholder="{{ __('admin.site_setting.password') }}">
                        </div>
                      </div>

                      <div class="col-sm-6">
                        <div class="form-group">
                          <label for="encryption">{{ __('admin.site_setting.encryption') }}
                          </label>
                          <input type="text" class="form-control" id="encryption"
                                name="encryption"
                                value="{{ $edit ? $siteSetting->encryption : old('encryption') }}"
                                placeholder="{{ __('admin.site_setting.encryption') }}">
                        </div>
                      </div>

                          



                      <div class="col-sm-12 text-right">
                          <button type="submit"
                                  class="btn btn-success">{{ $edit ? __('admin.site_setting.update') : __('admin.site_setting.add') }}</button>
                      </div>
                      </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>

@endsection
@push('css')
    <link rel="stylesheet" href="{{asset('/css/datatable-bundle.css')}}">
@endpush

@push('js')
    <script type="text/javascript" src="{{asset('/js/datatable-bundle.js')}}"></script>

    <script>
        $(function () {
          $("#example1").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
          }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
          $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
          });
        });
      </script>
      
      <script>
@endpush