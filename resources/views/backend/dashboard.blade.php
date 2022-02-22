@extends('master::layouts.master')
@php
    /** @var \App\Models\User $authUser */
    $authUser = \App\Helpers\Classes\AuthHelper::getAuthUser();
@endphp
@section('content')
    <div class="container-fluid">
        <div class="row my-3">
            <div class="col-md-3">
                <div class="p-3 text-center rounded mb-2 text-white"
                     style="background: rgb(188,97,235);
                            background: linear-gradient(55deg,rgba(188,97,235,1) 24%, rgba(215,106,225,1) 71%);">
                    <h1><b>{{ $stickerCount['total_course']? $stickerCount['total_course']:'0' }}</b></h1>
                    <p>{{__('generic.total_course')}}</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 text-center rounded mb-2 text-white"
                     style="background: rgb(123,142,207);
                            background: linear-gradient(55deg, rgba(123,142,207,1) 24%, rgba(94,127,241,1) 71%);">
                    <h1><b>{{ $stickerCount['total_trainee']? $stickerCount['total_trainee']:'0' }}</b></h1>
                    <p>{{__('generic.total_enroll')}}</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="p-3 text-center rounded mb-2 text-white"
                     style="background: rgb(75,255,243);
                            background: linear-gradient(1deg,rgba(75,255,243,1) 22%, rgba(53,217,206,1) 71%);">
                    <h1><b>{{ $stickerCount['total_course']? $stickerCount['total_course']:'0' }}</b></h1>
                    <p>{{__('generic.certificate_issuer')}}</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="p-3 text-center rounded mb-2 text-white"
                     style="background: rgb(253,134,71);background: linear-gradient(146deg, rgba(253,134,71,1) 22%, rgba(252,159,110,1) 71%);">
                    <h1><b>{{ $stickerCount['total_course']? $stickerCount['total_course']:'0' }}</b></h1>
                    <p>{{__('generic.trending_course')}}</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="p-3 text-center rounded mb-2 text-white"
                     style="background: rgb(51,192,128);background: linear-gradient(233deg, rgba(51,192,128,1) 22%, rgba(89,205,153,1) 71%);">
                    <h1><b>{{ $stickerCount['total_course']? $stickerCount['total_course']:'0' }}</b></h1>
                    <p>{{__('generic.industry_demand')}}</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="p-3 text-center rounded mb-2 text-white"
                     style="background: rgb(248,114,177);background: linear-gradient(75deg, rgba(248,114,177,1) 22%, rgba(222,32,122,1) 71%);">
                    <h1><b>{{ $stickerCount['totalBatch']? $stickerCount['totalBatch']:'0' }}</b></h1>
                    <p>{{__('generic.batch_number')}}</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="p-3 text-center rounded mb-2 text-white"
                     style="background: rgb(237,65,65);background: linear-gradient(180deg, rgba(237,65,65,1) 22%, rgba(240,107,107,1) 71%);">
                    <h1><b>{{ $stickerCount['total_course']? $stickerCount['total_course']:'0' }}</b></h1>
                    <p>{{__('generic.running_student')}}</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="p-3 text-center rounded mb-2 text-white"
                     style="background: rgb(222,19,193);background: linear-gradient(113deg, rgba(222,19,193,1) 22%, rgba(243,104,224,1) 71%);">
                    <h1><b>{{ $stickerCount['total_course']? $stickerCount['total_course']:'0' }}</b></h1>
                    <p>{{__('generic.trainers_number')}}</p>
                </div>
            </div>

        </div>

        <div class="row mb-3">
            <div class="col-md-8">
                <div class="card" style="border-radius: 10px; height: 100%">
                    <div style="margin: 10px">
                        <h3 class="card-title font-weight-bold" style="margin-top: 20px">{{__('generic.most_demanding_course')}}</h3>
                    </div>
                    <div class="card-body">
                        <div id="my_data"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card" style="border-radius: 10px; height: 100%">
                    <div class="card-body">
                        <label>{{__('generic.institute_calender')}}</label>
                        <div id='calendar'></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card" style="border-radius: 10px; height: 100%">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold" style="margin-top: 20px">{{__('generic.trading_work')}}</h3>
                        <div class="card-tools">
                            <select class="form-control" style="">
                                <option value="1" selected>2021</option>
                                <option value="2">2020</option>
                                <option value="3">2019</option>
                                <option value="4">2018</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="my_dataviz" style="background-color: #ffffff; margin-left: -3px"></div>
                        <div style="height: 1px; margin-top: 5px; background-color: #f4f4f4"></div>
                        <div class="row" style="margin-left: 30px; margin-bottom: -20px" id="graphRadio">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card" style=" border-radius: 10px; height: 100%">
                    <div class="card-body">
                        <label>{{__('generic.map')}}</label>
                        <div id="bd_map_d3"></div>
                        <div class="map_info" style="display: none">
                            <div class="map_content_top">
                                <p><b><span id="district"></span></b></p>
                            </div>
                            <hr>
                            <div class="map_content_body">
                                <div class="mb-2">
                                    <p class="mb-0"><i class="fa fa-circle text-red" aria-hidden="true"></i> Running
                                        Courses</p>
                                    <strong id="running_courses" class="map_count_numbers">10</strong>
                                </div>
                                <div class="mb-2">
                                    <p class="mb-0"><i class="fa fa-circle text-green" aria-hidden="true"></i> Total
                                        Enrollment</p>
                                    <b id="total_enrollment" class="map_count_numbers">20</b>
                                </div>
                                <div class="mb-2">
                                    <p class="mb-0"><i class="fa fa-circle text-blue" aria-hidden="true"></i>
                                        Running Students</p>
                                    <b id="running_students" class="map_count_numbers">100</b>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-danger fade" tabindex="-1" id="course_details_modal" role="dialog">
        <div class="modal-dialog" style="max-width: 80%;">
            <div class="modal-content modal-xlg" style="background-color: #e6eaeb">
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        class FetchUser extends HTMLElement {
            constructor() {
                super();
                const _style = document.createElement('style');
                const _template = document.createElement('template');

                _style.innerHTML = `
        h1 {
          color: tomato;
        }
        `;
                _template.innerHTML = `<ul id="users" style="list-style: none; padding: 0"></ul>`;

                this.attachShadow({mode: 'open'});
                this.shadowRoot.appendChild(_style);
                this.shadowRoot.appendChild(_template.content.cloneNode(true));

                fetch('https://jsonplaceholder.typicode.com/todos')
                    .then((response) => response.json())
                    .then((data) => {
                        data.forEach((item) => {
                            let li = document.createElement('li');
                            li.style.padding = '3px';
                            li.style.textAlign = 'left';
                            li.style.border = '1px solid gray';
                            li.appendChild(document.createTextNode(item.title));
                            this.shadowRoot.querySelector("#users").appendChild(li);
                        });
                    });
            }

            static get observedAttributes() {
                return ["theme"];
            }

            attributeChangedCallback(name, oldVal, newVal) {
                console.table({name, oldVal, newVal});
            }

            connectedCallback() {
                console.log("Element added to the dom");
            }

            disconnectedCallback() {
                console.log("Element removed from dom")
            }
        }

        window.customElements.define('fetch-user', FetchUser);
    </script>
@endpush
