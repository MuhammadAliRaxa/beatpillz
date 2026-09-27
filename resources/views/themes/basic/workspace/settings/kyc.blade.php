@extends('themes.basic.workspace.layouts.app')
@section('title', translate('Settings'))
@section('breadcrumbs', Breadcrumbs::render('workspace.settings.kyc'))
@section('content')
    @include('themes.basic.workspace.settings.includes.tabs')
    @if (authUser()->isKycVerified())
        <div class="dashboard-card card-v mb-3">
            <div class="dashboard-card-empty pd">
                <div class="mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="200px" height="200px" viewBox="0 0 819.07045 584"
                        xmlns:xlink="http://www.w3.org/1999/xlink">
                        <path
                            d="M938.36645,683.35934c7.18382,12.69813,1.0921,55.58546,1.0921,55.58546s-39.89068-16.88557-47.07316-29.57842a26.41318,26.41318,0,0,1,45.98106-26.007Z"
                            transform="translate(-190.46477 -158)" fill="#f1f1f1" />
                        <path
                            d="M940.037,738.8895l-.84744.17853c-8.16221-38.77834-36.66552-65.075-36.95246-65.33607l.58274-.64064C903.1088,673.35408,931.81545,699.82958,940.037,738.8895Z"
                            transform="translate(-190.46477 -158)" fill="#fff" />
                        <path
                            d="M1003.63788,697.81816c-9.74789,17.68309-64.70648,41.63828-64.70648,41.63828s-9.06086-59.2631.68177-76.94077a36.55622,36.55622,0,1,1,64.02471,35.30249Z"
                            transform="translate(-190.46477 -158)" fill="#f1f1f1" />
                        <path
                            d="M939.41646,740.09789l-.82555-.869c39.76932-37.7685,50.06448-90.44509,50.16385-90.97274l1.17793.2216C989.83286,649.009,979.47454,702.05508,939.41646,740.09789Z"
                            transform="translate(-190.46477 -158)" fill="#fff" />
                        <path
                            d="M383.03037,563.91909a75.18955,75.18955,0,0,1-18.63955-2.41115l-1.19992-.332-1.11309-.55768c-40.242-20.17656-74.192-46.827-100.90712-79.21137a299.86458,299.86458,0,0,1-50.94916-90.47014,348.20978,348.20978,0,0,1-19.69086-122.66453c.017-.87611.03139-1.55256.03139-2.01861,0-20.28912,11.262-38.0913,28.69121-45.35357,13.33947-5.55813,134.45539-55.30526,143.20632-58.89963,16.48038-8.25772,34.062-1.36535,36.87554-.16006,6.31094,2.58025,118.2752,48.375,142.47062,59.89621,24.93578,11.87415,31.5889,33.20566,31.5889,43.93787,0,48.58822-8.415,93.99778-25.01129,134.9674a312.51684,312.51684,0,0,1-56.16213,90.51087c-45.84677,51.59381-91.7057,69.8841-92.14828,70.0453A50.11,50.11,0,0,1,383.03037,563.91909Zm-10.78453-26.71374c3.97586.89138,13.12949,2.22845,19.0957.052,7.57929-2.76408,45.96243-22.668,81.83036-63.03189,49.55709-55.769,74.70242-125.87542,74.73919-208.37177-.08852-1.67134-1.27542-13.59188-17.06153-21.10867C507.12331,233.44669,390.746,185.86014,389.5732,185.38052l-.32154-.13631c-2.43886-1.022-10.20055-3.1747-15.55082-.371l-1.07124.49943c-1.2972.53279-129.86317,53.33754-143.57481,59.05064-9.59168,3.99651-13.00917,13.89729-13.00917,21.83037,0,.57973-.015,1.423-.03619,2.51294C214.91358,325.21375,227.97577,464.11262,372.24584,537.20535Z"
                            transform="translate(-190.46477 -158)" fill="#3f3d56" />
                        <path
                            d="M367.78865,173.58611S238.05415,226.86992,224.154,232.66164s-20.85019,19.69184-20.85019,33.592S192.87875,461.53177,367.78865,549.22768c0,0,15.87478,4.39241,27.91882,0s164.9454-78.52642,164.9454-283.55325c0,0,0-20.85018-24.32522-32.43362s-141.93358-59.6547-141.93358-59.6547S379.95125,167.21522,367.78865,173.58611Z"
                            transform="translate(-190.46477 -158)" fill="{{ $themeSettings->colors->primary_color }}" />
                        <path d="M381.68877,215.28648V499.53673S250.79593,436.53013,251.95428,270.887Z"
                            transform="translate(-190.46477 -158)" opacity="0.1" />
                        <polygon
                            points="192.931 261.581 151.235 207.969 175.483 189.11 195.226 214.494 261.921 144.088 284.224 165.219 192.931 261.581"
                            fill="#fff" />
                        <path d="M1008.53523,742h-381a1,1,0,0,1,0-2h381a1,1,0,0,1,0,2Z"
                            transform="translate(-190.46477 -158)" fill="#cacaca" />
                        <polygon points="547.206 568.237 562.671 568.236 570.029 508.583 547.203 508.584 547.206 568.237"
                            fill="#ffb8b8" />
                        <path
                            d="M733.72532,721.18754l30.45762-.00123h.00123a19.411,19.411,0,0,1,19.41,19.40966v.63075l-49.86791.00185Z"
                            transform="translate(-190.46477 -158)" fill="#2f2e41" />
                        <polygon points="599.206 568.237 614.671 568.236 622.029 508.583 599.203 508.584 599.206 568.237"
                            fill="#ffb8b8" />
                        <path
                            d="M785.72532,721.18754l30.45762-.00123h.00123a19.411,19.411,0,0,1,19.41,19.40966v.63075l-49.86791.00185Z"
                            transform="translate(-190.46477 -158)" fill="#2f2e41" />
                        <polygon
                            points="571.514 358.75 575.224 548 545.213 546.139 524.393 425.597 517.817 343.408 571.514 358.75"
                            fill="#2f2e41" />
                        <path
                            d="M813.48315,484.9709,817.68877,709l-35-1-7.56-133.17025-13.15012-48.21688-53.6962-25.20436,8.76674-60.27119,78.90049-1.09584Z"
                            transform="translate(-190.46477 -158)" fill="#2f2e41" />
                        <circle cx="562.67565" cy="99.59389" r="26.83826" fill="#ffb8b8" />
                        <polygon
                            points="584.936 137.738 589.047 143.966 600.006 174.649 591.239 294.095 539.734 295.192 533.16 158.211 546.933 140.995 584.936 137.738"
                            fill="#ccc" />
                        <path
                            d="M702.80325,319.499l-8.76674-1.09584s-2.19169,1.09584-3.2875,8.76671-14.24592,75.613-14.24592,75.613l17.53342,83.28385,19.7251-26.30016-12.05417-46.02526,12.05424-46.0253Z"
                            transform="translate(-190.46477 -158)" fill="#2f2e41" />
                        <polygon
                            points="624.114 160.404 630.689 160.404 647.127 249.166 631.785 318.204 616.443 293 620.826 265.604 618.635 241.496 610.964 227.249 624.114 160.404"
                            fill="#2f2e41" />
                        <path
                            d="M768.99945,257.59388l-4.87969-1.21993s-3.65974-20.73866-12.19924-18.29882-30.498,4.8797-30.498-4.87969,20.73867-18.29882,32.93783-17.0789,27.77947,5.267,31.71794,23.17848c6.31357,28.713-13.02638,35.96549-13.02638,35.96549l.32185-1.04544a16.28235,16.28235,0,0,0-4.37432-16.62119Z"
                            transform="translate(-190.46477 -158)" fill="#2f2e41" />
                        <path
                            d="M695.13238,318.40319l35.06691-14.24592,8.2188-6.02712,24.65642,109.03608,11.5063-112.32365,45.47733,23.56058L804.7164,392.92027l-2.19168,28.49185,6.57505,23.01263s23.0126,16.43761,15.34174,33.971-16.43761,18.6293-16.43761,18.6293-37.25859-35.06688-39.45021-43.83362-5.47918-24.10847-5.47918-24.10847-18.6293,70.13377-40.546,69.0379-21.91679-24.10848-21.91679-24.10848l5.47918-24.10848,8.76674-25.20432-4.38337-41.64192Z"
                            transform="translate(-190.46477 -158)" fill="#2f2e41" />
                    </svg>
                </div>
                <h4>{{ translate('KYC Verification Completed') }}</h4>
                <div class="col-lg-6 m-auto">
                    <p class="mb-0">
                        {{ translate('Congratulations! Your KYC verification has been successfully completed. You can now fully access our platform without any restrictions.') }}
                    </p>
                </div>
            </div>
        </div>
    @elseif (authUser()->isKycPending())
        <div class="dashboard-card card-v mb-3">
            <div class="dashboard-card-empty pd">
                <div class="mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="240px" height="240px" viewBox="0 0 892.6139 540.80203"
                        xmlns:xlink="http://www.w3.org/1999/xlink">
                        <circle cx="73.89615" cy="71.32961" r="46" fill="#ff6584" />
                        <ellipse cx="444.00318" cy="522.80203" rx="444.00318" ry="18" fill="#e6e6e6" />
                        <circle cx="113" cy="184.2194" r="113" fill="#ffc009" />
                        <polygon points="123.892 524.581 100.747 524.581 111.298 167.201 123.892 524.581" fill="#3f3d56" />
                        <polygon points="113.34 234.933 138.187 200.557 113 243.442 110.277 238.677 113.34 234.933"
                            fill="#3f3d56" />
                        <polygon points="110.617 269.65 85.771 235.274 110.958 278.159 113.681 273.394 110.617 269.65"
                            fill="#3f3d56" />
                        <polygon points="827.505 524.957 804.361 524.957 814.912 167.577 827.505 524.957" fill="#3f3d56" />
                        <polygon points="816.954 235.309 841.801 200.933 816.614 243.818 813.891 239.053 816.954 235.309"
                            fill="#3f3d56" />
                        <polygon points="814.231 270.026 789.385 235.65 814.572 278.535 817.295 273.77 814.231 270.026"
                            fill="#3f3d56" />
                        <circle cx="459.05561" cy="314.39327" r="210.50358" fill="#3f3d56" />
                        <circle cx="459.05561" cy="314.39327" r="193.07779" fill="#fff" />
                        <circle cx="458.35857" cy="315.0903" r="25.79017" fill="#3f3d56" />
                        <rect x="452.78232" y="131.07392" width="11.15251" height="20.91095" fill="#3f3d56" />
                        <rect x="452.78232" y="478.19572" width="11.15251" height="20.91095" fill="#3f3d56" />
                        <rect x="432.91447" y="484.23381" width="11.15251" height="20.91095"
                            transform="translate(779.48696 -123.40042) rotate(90)" fill="#3f3d56" />
                        <rect x="780.03627" y="484.23381" width="11.15251" height="20.91095"
                            transform="translate(1126.60876 -470.52223) rotate(90)" fill="#3f3d56" />
                        <polygon points="450.924 326.286 460.455 304.493 579.88 370.635 450.924 326.286" fill="#3f3d56" />
                        <rect x="431.87137" y="53.00636" width="54.36848" height="62.73286" fill="#3f3d56" />
                        <ellipse cx="459.05561" cy="53.00636" rx="62.73286" ry="17.42579" fill="#3f3d56" />
                        <path
                            d="M730.1985,531.28345l-3.48516,4.87922s-16.03173,18.81986-16.72876,9.75845,11.84954-15.3347,11.84954-15.3347l6.97032-4.18219Z"
                            transform="translate(-153.69305 -179.59899)" fill="#ffb8b8" />
                        <path
                            d="M829.41778,547.74522l2.91833,5.238s10.14843,22.54357,1.66044,19.29564-8.78836-17.27221-8.78836-17.27221l-.79685-8.08957Z"
                            transform="translate(-153.69305 -179.59899)" fill="#ffb8b8" />
                        <circle cx="660.03348" cy="254.04245" r="21.22425" fill="#2f2e41" />
                        <path
                            d="M811.75122,607.95694l2.78812,18.12283V631.656h16.03173V624.6857s.697-12.54657-2.09109-16.03173S811.75122,607.95694,811.75122,607.95694Z"
                            transform="translate(-153.69305 -179.59899)" fill="#ffb8b8" />
                        <path
                            d="M783.17291,607.95694l2.78813,18.12283V631.656h16.03173V624.6857s.697-12.54657-2.09109-16.03173S783.17291,607.95694,783.17291,607.95694Z"
                            transform="translate(-153.69305 -179.59899)" fill="#ffb8b8" />
                        <path
                            d="M793.96129,462.38371s-6.16626,3.25-1.9546,10.64285,28.92249,83.8766,28.92249,83.8766l14.25582-4.04685-12.57235-48.37053-7.1988-30.40158Z"
                            transform="translate(-153.69305 -179.59899)" fill="#ffc009" />
                        <circle cx="657.70965" cy="260.02479" r="16.03173" fill="#ffb8b8" />
                        <path d="M818.72153,442.06339l-2.09109,32.76049L801.99277,464.3684s4.87922-19.51689,3.48516-20.911Z"
                            transform="translate(-153.69305 -179.59899)" fill="#ffb8b8" />
                        <path
                            d="M818.72153,472.03575l-15.769-11.67706s-7.23305-2.9606-9.32415.52455-12.54657,55.06551-6.97032,64.824c0,0,29.97237,6.27328,36.24565,2.78812l2.78813-18.12282s3.48516-11.15251-2.0911-18.12283Z"
                            transform="translate(-153.69305 -179.59899)" fill="#ffc009" />
                        <path
                            d="M797.81058,461.58028s-5.57625-4.18219-10.45548,2.78812-63.42988,62.03583-63.42988,62.03583l9.75844,11.15251,38.33675-32.06346,24.39611-19.51689Z"
                            transform="translate(-153.69305 -179.59899)" fill="#ffc009" />
                        <path
                            d="M823.25224,528.14681s-34.50307-6.6218-37.98823-1.74258l-.697,7.66735s-7.66735,14.63766-3.48516,38.33674,1.39406,38.33675,1.39406,38.33675,18.81986-2.0911,32.76049,0,16.03173,0,16.03173,0Z"
                            transform="translate(-153.69305 -179.59899)" fill="#2f2e41" />
                        <path
                            d="M831.96514,628.86789l-20.21392.697,6.97031,52.27739s-5.57625,22.305.697,23.69907a70.7125,70.7125,0,0,0,13.24361,1.39407s6.27328,6.27328,13.2436,5.57625a39.034,39.034,0,0,0,13.2436-4.18219s1.39407-4.18219-3.48516-5.57625-18.12282-5.57626-20.911-20.21392Z"
                            transform="translate(-153.69305 -179.59899)" fill="#2f2e41" />
                        <path
                            d="M803.38683,628.86789l-20.21392.697,6.97032,52.27739s-5.57625,22.305.697,23.69907a70.7125,70.7125,0,0,0,13.24361,1.39407s6.27328,6.27328,13.2436,5.57625a39.034,39.034,0,0,0,13.2436-4.18219s1.39407-4.18219-3.48516-5.57625S808.96309,697.177,806.175,682.53934Z"
                            transform="translate(-153.69305 -179.59899)" fill="#2f2e41" />
                        <circle cx="667.45803" cy="231.7041" r="8.78245" fill="#2f2e41" />
                        <path
                            d="M832.861,406.54593a8.78336,8.78336,0,0,0-7.86761-8.735,8.881,8.881,0,0,1,.91484-.04748,8.78244,8.78244,0,0,1,0,17.56489,8.881,8.881,0,0,1-.91484-.04748A8.78336,8.78336,0,0,0,832.861,406.54593Z"
                            transform="translate(-153.69305 -179.59899)" fill="#2f2e41" />
                        <ellipse cx="657.56451" cy="249.54235" rx="14.63741" ry="8.78245" fill="#2f2e41" />
                        <path
                            d="M1041.81277,221.75021a44.67678,44.67678,0,0,0-87.65662-9.41942c-.54125-.01942-1.082-.04117-1.628-.04117a44.68754,44.68754,0,0,0-42.86662,32.08892,31.5744,31.5744,0,0,0-37.46546,9.31921h139.36225a30.21954,30.21954,0,0,0,30.28607-31.28863Q1041.831,222.08017,1041.81277,221.75021Z"
                            transform="translate(-153.69305 -179.59899)" fill="#e6e6e6" />
                        <circle cx="771.6139" cy="124.178" r="12" fill="#e6e6e6" />
                        <circle cx="807.84695" cy="95.88566" r="9.9918" fill="#e6e6e6" />
                        <circle cx="856.6139" cy="150.178" r="12" fill="#e6e6e6" />
                        <circle cx="765.6139" cy="205.178" r="12" fill="#e6e6e6" />
                        <circle cx="856.6139" cy="274.178" r="12" fill="#e6e6e6" />
                        <path
                            d="M991.28736,685.16529c.00415-.13007.01959-.2572.01959-.38831a11.98223,11.98223,0,0,0-16.98706-10.90472,11.96811,11.96811,0,0,0-20.8316,9.91388c-.061-.00092-.12012-.00916-.18134-.00916a12,12,0,1,0,7.33429,21.48322,11.99355,11.99355,0,0,0,20.62177,1.21716,11.99014,11.99014,0,1,0,10.02435-21.31207Z"
                            transform="translate(-153.69305 -179.59899)" fill="#e6e6e6" />
                        <circle cx="867.6139" cy="226.178" r="5" fill="#e6e6e6" />
                        <circle cx="835.6139" cy="106.178" r="5" fill="#e6e6e6" />
                        <circle cx="818.6139" cy="230.178" r="5" fill="#e6e6e6" />
                        <circle cx="822.6139" cy="225.178" r="5" fill="#e6e6e6" />
                        <circle cx="825.6139" cy="220.178" r="5" fill="#e6e6e6" />
                        <circle cx="813.6139" cy="262.178" r="5" fill="#e6e6e6" />
                        <circle cx="887.6139" cy="318.178" r="5" fill="#e6e6e6" />
                        <circle cx="780.6139" cy="321.178" r="5" fill="#e6e6e6" />
                        <circle cx="786.6139" cy="167.178" r="5" fill="#e6e6e6" />
                        <circle cx="809.6139" cy="257.178" r="5" fill="#e6e6e6" />
                        <circle cx="804.6139" cy="249.178" r="5" fill="#e6e6e6" />
                    </svg>
                </div>
                <h4>{{ translate('KYC Verification Pending') }}</h4>
                <div class="col-lg-6 m-auto">
                    <p class="mb-0">
                        {{ translate('Your KYC verification is currently pending. We are processing your information, and you will be notified once the verification is complete.') }}
                    </p>
                </div>
            </div>
        </div>
    @else
        <form action="{{ route('workspace.settings.kyc.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="dashboard-card card-v mb-3">
                <div class="form-section">
                    <h4 class="mb-0">{{ translate('ID Verification') }}</h4>
                </div>
                <div class="row g-4">
                    <div class="col-12 col-lg-12 col-xxl-5">
                        <div class="mb-4">
                            <p>
                                {{ translate('Upload a clear, legible image and Ensure that all relevant details, such as your name, photo, and ID number, are visible. the image must be type of .JPG or .PNG') }}
                            </p>
                        </div>
                        <div>
                            <label class="form-label">{{ translate('Document type') }}</label>
                            <select id="kycDocument" name="document_type" class="form-select form-select-md rounded-3">
                                <option value="national_id">{{ translate('National ID') }}</option>
                                <option value="passport">{{ translate('Passport') }}</option>
                            </select>
                        </div>
                        <div id="nationalIDNumber" class="mt-4">
                            <label class="form-label">{{ translate('National ID Number') }}</label>
                            <input type="text" name="national_id_number" class="form-control form-control-md"
                                autofocus>
                        </div>
                        <div id="passportNumber" class="mt-4 d-none">
                            <label class="form-label">{{ translate('Passport Number') }}</label>
                            <input type="text" name="passport_number" class="form-control form-control-md" autofocus>
                        </div>
                    </div>
                    <div class="col-12 col-lg-12 col-xxl-7">
                        <div id="nationalId" class="row g-3">
                            <div class="col-md-6 col-lg-6">
                                <div class="border rounded-3 p-3">
                                    <h6 class="mb-3">{{ translate('Front Of ID') }}</h6>
                                    <div class="image-preview-box mb-3">
                                        <img id="image-preview-1" src="{{ asset(@$settings->kyc->id_front_image) }}"
                                            height="205px">
                                    </div>
                                    <input type="file" name="front_of_id"
                                        class="form-control form-control-md image-input rounded-3" data-id="1">
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6">
                                <div class="border rounded-3 p-3">
                                    <h6 class="mb-3">{{ translate('Back Of ID') }}</h6>
                                    <div class="image-preview-box mb-3">
                                        <div class="mb-3">
                                            <img id="image-preview-2" src="{{ asset(@$settings->kyc->id_back_image) }}"
                                                height="205px">
                                        </div>
                                    </div>
                                    <input type="file" name="back_of_id"
                                        class="form-control form-control-md image-input rounded-3" data-id="2">
                                </div>
                            </div>
                        </div>
                        <div id="passport" class="row g-3 justify-content-lg-center d-none">
                            <div class="col-lg-7">
                                <div class="border rounded-3 p-3">
                                    <h6 class="mb-3">{{ translate('Passport') }}</h6>
                                    <div class="image-preview-box mb-3">
                                        <div class="mb-3">
                                            <img id="image-preview-4" src="{{ asset(@$settings->kyc->passport_image) }}"
                                                height="400px">
                                        </div>
                                    </div>
                                    <input type="file" name="passport"
                                        class="form-control form-control-md image-input rounded-3" data-id="4">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if (@$settings->kyc->selfie_verification)
                <div class="dashboard-card card-v mb-3">
                    <div class="form-section">
                        <h4 class="mb-0">{{ translate('Selfie Verification') }}</h4>
                    </div>
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <p>
                                {{ translate("Upload a clear selfie and Ensure it's well-lit and visible. the image must be type of.JPG or .PNG") }}
                            </p>
                        </div>
                        <div class="col-lg-6">
                            <div class="d-flex justify-content-lg-center">
                                <div class="border rounded-3 p-3">
                                    <div class="image-preview-box mb-3">
                                        <img id="image-preview-3" src="{{ asset(@$settings->kyc->selfie_image) }}"
                                            height="400px">
                                    </div>
                                    <input type="file" id="selfie" name="selfie"
                                        class="form-control form-control-md image-input rounded-3" data-id="3"
                                        accept=".jpg,.jpeg,.png">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="dashboard-card card-v">
                <button class="btn btn-primary btn-md ">{{ translate('Submit') }}</button>
            </div>
        </form>
    @endif
@endsection
